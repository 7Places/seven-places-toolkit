<?php

declare(strict_types=1);

namespace SPT\Services;

final class ViewManager
{
    /**
     * Render a PHP view.
     *
     * @param array<string, mixed> $data
     */
     public function render(
         string $view,
         array $data = [],
     ): void {
         if (! is_file($view)) {
             throw new \RuntimeException(
                 sprintf(
                     'View not found: %s',
                     $view
                 )
             );
         }

         extract($data, EXTR_SKIP);

         require $view;
     }
}
