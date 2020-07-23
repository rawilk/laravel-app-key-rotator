<?php

namespace Rawilk\AppKeyRotator\Tests\Feature\Commands;

use Carbon\Carbon;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Jackiedo\DotenvEditor\DotenvEditor;
use Rawilk\AppKeyRotator\AppKeyRotator;
use Rawilk\AppKeyRotator\Tests\Models\User;
use Rawilk\AppKeyRotator\Tests\Models\UserWithAccessors;
use Rawilk\AppKeyRotator\Tests\Models\UserWithMutators;
use Rawilk\AppKeyRotator\Tests\TestCase;

class RotateAppKeyCommandTest extends TestCase
{
    use RefreshDatabase;

    protected DotenvEditor $dotEnv;
    protected AppKeyRotator $appKeyRotator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dotEnv = new DotenvEditor(
            $this->app,
            $this->app['config']
        );

        $this->appKeyRotator = new AppKeyRotator;
    }

    /** @test */
    public function it_saves_the_new_app_key_in_the_env_file(): void
    {
        $currentKey = config('app.key');

        $this->artisan('app-key-rotator:rotate');

        $newKey = config('app.key');

        $this->dotEnv->load(__DIR__ . '/../../../.env');

        $this->assertNotEquals(
            $currentKey,
            $this->dotEnv->getValue('APP_KEY')
        );
        $this->assertEquals(
            $newKey,
            $this->dotEnv->getValue('APP_KEY')
        );

        $envContent = $this->dotEnv->getContent();
        $this->assertStringContainsString(
            'APP_KEY=' . $newKey,
            $envContent
        );
        $this->assertStringContainsString(
            'OLD_APP_KEY=' . $currentKey,
            $envContent
        );
    }

    /** @test */
    public function it_re_encrypts_model_values(): void
    {
        config([
            'app-key-rotator.models' => [
                User::class => [
                    'birth_date',
                    'bank_account',
                ],
            ]
        ]);

        $userValues = [];

        $this->appKeyRotator
            ->setOldAppKey(config('app.key'))
            ->createEncrypters();

        User::all()->each(function (User $user) use (&$userValues) {
            $birthDate = $this->appKeyRotator->oldEncrypter()->decrypt($user->birth_date);
            $bankAccount = $this->appKeyRotator->oldEncrypter()->decrypt($user->bank_account);

            $this->assertNotNull($birthDate);
            $this->assertNotNull($bankAccount);

            $userValues[$user->id] = array_merge($user->toArray(), [
                'birth_date' => $birthDate,
                'bank_account' => $bankAccount,
            ]);
        });

        $this->artisan('app-key-rotator:rotate');
        $this->appKeyRotator
            ->setNewAppKey(config('app.key'))
            ->createEncrypters();

        User::all()->each(function (User $user) use ($userValues) {
            $birthDate = $this->appKeyRotator->newEncrypter()->decrypt($user->birth_date);
            $bankAccount = $this->appKeyRotator->newEncrypter()->decrypt($user->bank_account);
            $newUserValues = array_merge($user->toArray(), [
                'birth_date' => $birthDate,
                'bank_account' => $bankAccount
            ]);

            $this->assertEquals($userValues[$user->id], $newUserValues);
        });
    }

    /** @test */
    public function it_re_encrypts_model_values_with_accessors_that_decrypt_automatically(): void
    {
        config([
            'app-key-rotator.models' => [
                UserWithAccessors::class => [
                    'birth_date',
                    'bank_account',
                ],
            ]
        ]);

        $userValues = [];

        $this->appKeyRotator
            ->setOldAppKey(config('app.key'))
            ->createEncrypters();

        UserWithAccessors::all()->each(function (UserWithAccessors $user) use (&$userValues) {
            $this->assertNotNull($user->birth_date);
            $this->assertNotNull($user->bank_account);

            $userValues[$user->id] = $user->toArray();
        });

        $this->artisan('app-key-rotator:rotate');
        $this->appKeyRotator
            ->setNewAppKey(config('app.key'))
            ->createEncrypters();

        app(Encrypter::class)->setKey(config('app.key'));

        UserWithAccessors::all()->each(function (UserWithAccessors $user) use ($userValues) {
            $this->assertEquals($userValues[$user->id], $user->toArray());
        });
    }

    /** @test */
    public function it_does_not_update_model_timestamps_when_re_encrypting(): void
    {
        config([
            'app-key-rotator.models' => [
                UserWithAccessors::class => [
                    'birth_date',
                    'bank_account',
                ],
            ]
        ]);

        $timestamps = [];

        UserWithAccessors::get(['id', 'updated_at'])
            ->each(static function (UserWithAccessors $user) use (&$timestamps) {
                $timestamps[$user->id] = $user->updated_at;
            });

        Carbon::setTestNow(now()->addMinutes(1));
        $this->artisan('app-key-rotator:rotate');

        UserWithAccessors::get(['id', 'updated_at'])
            ->each(function (UserWithAccessors $user) use ($timestamps) {
                /** @var \Carbon\Carbon $original */
                $original = $timestamps[$user->id];

                $this->assertTrue(
                    $original->eq($user->updated_at),
                    "Expected [{$original}], but got [{$user->updated_at}]"
                );
            });
    }

    /** @test */
    public function it_re_encrypts_when_models_have_mutators_that_encrypt_automatically(): void
    {
        config([
            'app-key-rotator.models' => [
                UserWithMutators::class => [
                    'birth_date',
                    'bank_account',
                ],
            ]
        ]);

        $userValues = [];

        $this->appKeyRotator
            ->setOldAppKey(config('app.key'))
            ->createEncrypters();

        UserWithMutators::all()->each(function (UserWithMutators $user) use (&$userValues) {
            $this->assertNotNull($user->birth_date);
            $this->assertNotNull($user->bank_account);

            $userValues[$user->id] = $user->toArray();
        });

        $this->artisan('app-key-rotator:rotate');
        $this->appKeyRotator
            ->setNewAppKey(config('app.key'))
            ->createEncrypters();

        app(Encrypter::class)->setKey(config('app.key'));

        UserWithMutators::all()->each(function (UserWithMutators $user) use ($userValues) {
            $this->assertEquals($userValues[$user->id], $user->toArray());
        });
    }
}
