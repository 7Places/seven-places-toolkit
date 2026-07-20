<?php

declare(strict_types=1);

namespace SPT\Admin;

final class AdminPageManager
{
    /** @var AdminPage[] */
    private array $pages = [];

    public function register(): void
    {
        add_action(
            'admin_menu',
            [$this, 'registerPages']
        );
    }

    public function addPage(
        AdminPage $page,
    ): self {
        $this->pages[] = $page;

        return $this;
    }

    public function registerPages(): void
    {
        foreach ($this->pages as $page) {
            add_menu_page(
                page_title: $page->title,
                menu_title: $page->menuTitle,
                capability: $page->capability,
                menu_slug: $page->slug,
                callback: $page->callback,
                icon_url: $page->icon,
                position: $page->position,
            );
        }
    }
}
