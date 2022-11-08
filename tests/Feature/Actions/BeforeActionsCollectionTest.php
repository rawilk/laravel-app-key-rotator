<?php

declare(strict_types=1);

use Rawilk\AppKeyRotator\Actions\BeforeActionsCollection;
use Rawilk\AppKeyRotator\Tests\Feature\Actions\TestClasses\DummyBeforeAction;

it('will instantiate all class names', function () {
    $actionsCollection = new BeforeActionsCollection([DummyBeforeAction::class]);

    expect($actionsCollection->first())->toBeInstanceOf(DummyBeforeAction::class);
});

it('can pass parameters to actions', function () {
    $actionsCollection = new BeforeActionsCollection([
        DummyBeforeAction::class => ['a' => 1, 'b' => 2],
    ]);

    $action = $actionsCollection->first();

    expect($action->a)->toBe(1)
        ->and($action->b)->toBe(2);
});

it('can handle duplicate actions with other parameters', function () {
    $actionsCollection = new BeforeActionsCollection([
        [DummyBeforeAction::class => ['a' => 1, 'b' => 2]],
        [DummyBeforeAction::class => ['a' => 3, 'b' => 4]],
    ]);

    expect($actionsCollection[0]->a)->toBe(1)
        ->and($actionsCollection[0]->b)->toBe(2)
        ->and($actionsCollection[1]->a)->toBe(3)
        ->and($actionsCollection[1]->b)->toBe(4);
});
