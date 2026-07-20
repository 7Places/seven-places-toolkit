<?php

declare(strict_types=1);

namespace SPT\Core;

use SPT\Contracts\ModuleInterface;

final class ModuleRegistry
{
    /**
     * @var ModuleInterface[]
     */
    private array $modules = [];

    public function add(ModuleInterface $module): self
    {
        $this->modules[] = $module;

        return $this;
    }

    public function boot(): void
    {
        foreach ($this->modules as $module) {
            $module->register();
        }
    }
}
