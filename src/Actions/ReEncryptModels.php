<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Rawilk\AppKeyRotator\AppKeyRotator;
use Rawilk\AppKeyRotator\Contracts\ReEncryptsData;
use Rawilk\AppKeyRotator\Contracts\RotatorAction;

class ReEncryptModels implements RotatorAction
{
    public function handle(AppKeyRotator $appKeyRotator, array $config): void
    {
        $models = $this->getModels($config['models'] ?? []);

        $models->each(fn (string $model) => $this->reEncryptModel($model, $appKeyRotator));
    }

    protected function reEncryptModel(string $modelClass, AppKeyRotator $appKeyRotator): void
    {
        $encryptedProperties = $modelClass::make()->encryptedProperties();

        $modelClass::query()
            ->select(['id', ...$encryptedProperties])
            ->cursor()
            ->each(function (Model $model) use ($modelClass, $appKeyRotator, $encryptedProperties) {
                // We get the attributes here to prevent any accessors or mutators from trying to
                // encrypt/decrypt values with the wrong encryption keys.
                $attributes = $model->getAttributes();

                foreach ($encryptedProperties as $field) {
                    $attributes[$field] = $appKeyRotator->reEncrypt($attributes[$field]);
                }

                $model->setRawAttributes($attributes);

                $model->timestamps = false;

                $model->saveQuietly();
            });
    }

    /**
     * @param array $models
     * @return \Illuminate\Support\Collection<int, \Rawilk\AppKeyRotator\Contracts\ReEncryptsData>
     */
    protected function getModels(array $models): Collection
    {
        return collect($models)
            ->filter(function (string $model) {
                $instance = app($model);

                return $instance instanceof ReEncryptsData;
            });
    }
}
