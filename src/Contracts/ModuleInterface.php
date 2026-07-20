<?php

declare(strict_types=1);

namespace SPT\Contracts;

interface ModuleInterface
{
    public function register(): void;
}
