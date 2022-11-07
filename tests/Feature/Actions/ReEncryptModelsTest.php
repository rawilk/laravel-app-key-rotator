<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Contracts\Encryption\Encrypter;
use Rawilk\AppKeyRotator\Actions\ReEncryptModels;
use Rawilk\AppKeyRotator\Tests\Models\User;
use Rawilk\AppKeyRotator\Tests\Models\UserWithAccessors;
use Rawilk\AppKeyRotator\Tests\Models\UserWithMutators;
use function Pest\Laravel\artisan;

beforeEach(function () {
    setUpDatabase();
});

it('re-encrypts model values', function (User $user) {
    config([
        'app-key-rotator.actions' => [ReEncryptModels::class],
        'app-key-rotator.models' => [User::class],
    ]);

    $oldAppKey = config('app.key');

    // Our test base user model does not encrypt/decrypt automatically.
    $birthDate = decrypt($user->birth_date);
    $bankAccount = decrypt($user->bank_account);

    $encryptedBirthDate = $user->birth_date;
    $encryptedBankAccount = $user->bank_account;

    artisan('app-key-rotator:rotate');

    $newAppKey = config('app.key');
    app(Encrypter::class)->setKey($newAppKey);
    expect($newAppKey)->not()->toEqual($oldAppKey);

    $user->refresh();

    expect($user->bank_account)->not()->toEqual($encryptedBankAccount)
        ->and($user->birth_date)->not()->toEqual($encryptedBirthDate)
        ->and(decrypt($user->bank_account))->toEqual($bankAccount)
        ->and(decrypt($user->birth_date))->toEqual($birthDate);
})->with('users');

it('re-encrypts model values with accessors that decrypt automatically', function (UserWithAccessors $user) {
    config([
        'app-key-rotator.actions' => [ReEncryptModels::class],
        'app-key-rotator.models' => [UserWithAccessors::class],
    ]);

    expect($user->birth_date)->not()->toBeNull()
        ->and($user->bank_account)->not()->toBeNull();

    $birthDate = $user->birth_date;
    $bankAccount = $user->bank_account;

    artisan('app-key-rotator:rotate');

    app(Encrypter::class)->setKey(config('app.key'));

    $user->refresh();

    expect($user->birth_date)->toEqual($birthDate)
        ->and($user->bank_account)->toEqual($bankAccount);
})->with('usersWithAccessors');

it('re-encrypts when models have mutators that encrypt automatically', function (UserWithMutators $user) {
    config([
        'app-key-rotator.actions' => [ReEncryptModels::class],
        'app-key-rotator.models' => [UserWithMutators::class,],
    ]);

    $birthDate = $user->birth_date;
    $bankAccount = $user->bank_account;

    expect($birthDate)->not()->toBeNull()
        ->and($bankAccount)->not()->toBeNull();

    artisan('app-key-rotator:rotate');

    app(Encrypter::class)->setKey(config('app.key'));

    $user->refresh();

    expect($user->birth_date)->toEqual($birthDate)
        ->and($user->bank_account)->toEqual($bankAccount);
})->with('usersWithMutators');

it('does not update timestamps when re-encrypting model data', function (UserWithAccessors $user) {
    config([
        'app-key-rotator.actions' => [ReEncryptModels::class],
        'app-key-rotator.models' => [UserWithAccessors::class],
    ]);

    $updatedAt = $user->updated_at;

    $encryptedBirthDate = $user->getRawOriginal('birth_date');

    Carbon::setTestNow(now()->addMinute());
    artisan('app-key-rotator:rotate');

    $user->refresh();

    expect($user->getRawOriginal('birth_date'))->not()->toEqual($encryptedBirthDate)
        ->and($updatedAt->eq($user->updated_at))->toBeTrue();
})->with('usersWithAccessors');

dataset('users', function () {
    yield fn () => User::factory()->create();
    yield fn () => User::factory()->create();
    yield fn () => User::factory()->create();
});

dataset('usersWithAccessors', function () {
    yield fn () => UserWithAccessors::factory()->create();
    yield fn () => UserWithAccessors::factory()->create();
    yield fn () => UserWithAccessors::factory()->create();
});

dataset('usersWithMutators', function () {
    yield fn () => UserWithMutators::factory()->create();
    yield fn () => UserWithMutators::factory()->create();
    yield fn () => UserWithMutators::factory()->create();
});
