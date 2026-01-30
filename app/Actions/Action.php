<?php

declare(strict_types=1);

namespace App\Actions;

abstract class Action
{
    /**
     * Execute the action.
     */
    abstract public function execute(): mixed;
}
