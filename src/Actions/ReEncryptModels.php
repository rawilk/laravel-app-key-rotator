<?php

declare(strict_types=1);

namespace Rawilk\AppKeyRotator\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Rawilk\AppKeyRotator\AppKeyRotator;
use Rawilk\AppKeyRotator\Contracts\RotatorAction;

class ReEncryptModels implements RotatorAction
{
    protected array $models;

    protected int $chunkSize;

    protected AppKeyRotator $appKeyRotator;

    public function __construct(array $config, AppKeyRotator $appKeyRotator)
    {
        $this->models = $config['models'] ?? [];
        $this->chunkSize = $config['model_chunk_size'] ?? 500;
        $this->appKeyRotator = $appKeyRotator;
    }

    public function handle(): void
    {
        foreach ($this->models as $modelClass => $fields) {
            $this->reEncryptModel($modelClass, $fields);
        }
    }

    protected function reEncryptModel(string $modelClass, array $fields): void
    {
        app($modelClass)
            ->select(array_merge(['id'], $fields))
            ->chunk(
                $this->chunkSize,
                fn (Collection $models) => $models->each(fn (Model $m) => $this->reEncryptModelInstance($m, $fields))
            );
    }

    protected function reEncryptModelInstance(Model $model, array $fields): void
    {
        // We get the attributes here to prevent any accessors or mutators from trying to
        // encrypt/decrypt values with the wrong encryption keys.
        $attributes = $model->getAttributes();

        foreach ($fields as $field) {
            $attributes[$field] = $this->appKeyRotator->reEncrypt($attributes[$field]);
        }

        $model->setRawAttributes($attributes);

        $model->timestamps = false;

        $model->save();
    }
}
