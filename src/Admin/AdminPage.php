<?php

declare(strict_types=1);

namespace SPT\Admin;

use InvalidArgumentException;

final class AdminPage
{
    /**
     * @param callable $callback
     */
    public function __construct(
        public readonly string $title,
        public readonly string $menuTitle,
        public readonly string $capability,
        public readonly string $slug,
        public readonly mixed $callback,
        public readonly ?string $parentSlug = null,
        public readonly string $icon = 'dashicons-admin-generic',
        public readonly ?int $position = null,
    ) {
        if (! is_callable($callback)) {
            throw new InvalidArgumentException(
                'Admin page callback must be callable.'
            );
        }
    }

    /**
     * Determine whether this page is a submenu.
     */
    public function isSubmenu(): bool
    {
        return $this->parentSlug !== null;
    }
}
