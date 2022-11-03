<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Contracts\Encryption\Encrypter;
use Jackiedo\DotenvEditor\DotenvEditor;
use function Pest\Laravel\artisan;
use Rawilk\AppKeyRotator\AppKeyRotator;
use Rawilk\AppKeyRotator\Tests\Models\User;
use Rawilk\AppKeyRotator\Tests\Models\UserWithAccessors;
use Rawilk\AppKeyRotator\Tests\Models\UserWithMutators;

beforeEach(function () {
    $this->dotEnv = new DotenvEditor(
        $this->app,
        $this->app['config'],
    );

    $this->appKeyRotator = new AppKeyRotator;
});

it('saves the new app key in the env file', function () {
    $currentKey = config('app.key');

    artisan('app-key-rotator:rotate');

    $newKey = config('app.key');

    $this->dotEnv->load(__DIR__ . '/../../../.env');

    expect($this->dotEnv->getValue('APP_KEY'))->not()->toEqual($currentKey)
        ->and($this->dotEnv->getValue('APP_KEY'))->toBe($newKey);

    $envContent = $this->dotEnv->getContent();

    $this->assertStringContainsString(
        "APP_KEY={$newKey}",
        $envContent,
    );

    $this->assertStringContainsString(
        "OLD_APP_KEY={$currentKey}",
        $envContent,
    );
});

it('re-encrypts model values', function () {
    config([
        'app-key-rotator.models' => [
            User::class => [
                'birth_date',
                'bank_account',
            ],
        ],
    ]);

    $userValues = [];

    $this->appKeyRotator->setOldAppKey(config('app.key'))
        ->createEncrypters();

    User::all()->each(function (User $user) use (&$userValues) {
        $birthDate = $this->appKeyRotator->oldEncrypter()->decrypt($user->birth_date);
        $bankAccount = $this->appKeyRotator->oldEncrypter()->decrypt($user->bank_account);

        expect($birthDate)->not()->toBeNull()
            ->and($bankAccount)->not()->toBeNull();

        $userValues[$user->id] = array_merge($user->toArray(), [
            'birth_date' => $birthDate,
            'bank_account' => $bankAccount,
        ]);
    });

    artisan('app-key-rotator:rotate');
    $this->appKeyRotator
        ->setNewAppKey(config('app.key'))
        ->createEncrypters();

    User::all()->each(function (User $user) use ($userValues) {
        $birthDate = $this->appKeyRotator->newEncrypter()->decrypt($user->birth_date);
        $bankAccount = $this->appKeyRotator->newEncrypter()->decrypt($user->bank_account);
        $newUserValues = array_merge($user->toArray(), [
            'birth_date' => $birthDate,
            'bank_account' => $bankAccount,
        ]);

        expect($newUserValues)->toEqual($userValues[$user->id]);
    });
});

it('re-encrypts model values with accessors that decrypt automatically', function () {
    config([
        'app-key-rotator.models' => [
            UserWithAccessors::class => [
                'birth_date',
                'bank_account',
            ],
        ],
    ]);

    $userValues = [];

    $this->appKeyRotator->setOldAppKey(config('app.key'))->createEncrypters();

    UserWithAccessors::all()->each(function (UserWithAccessors $user) use (&$userValues) {
        expect($user->birth_date)->not()->toBeNull()
            ->and($user->bank_account)->not()->toBeNull();

        $userValues[$user->id] = $user->toArray();
    });

    artisan('app-key-rotator:rotate');
    $this->appKeyRotator
        ->setNewAppKey(config('app.key'))
        ->createEncrypters();

    app(Encrypter::class)->setKey(config('app.key'));

    UserWithAccessors::all()->each(function (UserWithAccessors $user) use ($userValues) {
        expect($user->toArray())->toEqual($userValues[$user->id]);
    });
});

it('does not update model timestamps when re-encrypting', function () {
    config([
        'app-key-rotator.models' => [
            UserWithAccessors::class => [
                'birth_date',
                'bank_account',
            ],
        ],
    ]);

    $timestamps = [];

    UserWithAccessors::get(['id', 'updated_at'])
        ->each(function (UserWithAccessors $user) use (&$timestamps) {
            $timestamps[$user->id] = $user->updated_at;
        });

    Carbon::setTestNow(now()->addMinute());
    artisan('app-key-rotator:rotate');

    UserWithAccessors::get(['id', 'updated_at'])
        ->each(function (UserWithAccessors $user) use ($timestamps) {
            /** @var \Carbon\Carbon $original */
            $original = $timestamps[$user->id];

            expect($original->eq($user->updated_at))->toBeTrue();
        });
});

it('re-encrypts when models have mutators that encrypt automatically', function () {
    config([
        'app-key-rotator.models' => [
            UserWithMutators::class => [
                'birth_date',
                'bank_account',
            ],
        ],
    ]);

    $userValues = [];

    $this->appKeyRotator
        ->setOldAppKey(config('app.key'))
        ->createEncrypters();

    UserWithMutators::all()->each(function (UserWithMutators $user) use (&$userValues) {
        expect($user->birth_date)->not()->toBeNull()
            ->and($user->bank_account)->not()->toBeNull();

        $userValues[$user->id] = $user->toArray();
    });

    artisan('app-key-rotator:rotate');
    $this->appKeyRotator
        ->setNewAppKey(config('app.key'))
        ->createEncrypters();

    app(Encrypter::class)->setKey(config('app.key'));

    UserWithMutators::all()->each(function (UserWithMutators $user) use ($userValues) {
        expect($user->toArray())->toEqual($userValues[$user->id]);
    });
});
