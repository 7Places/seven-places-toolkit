<?php

declare(strict_types=1);

namespace SPT\Services;

use SPT\Core\Application;

final class SettingsManager
{
    private const OPTION_NAME = 'seven_places_toolkit';

    public function __construct(
        private readonly Application $app,
    ) {
    }

    /**
     * Retrieve all plugin settings.
     *
     * @return array<string, mixed>
     */
    private function options(): array
    {
        $options = get_option(self::OPTION_NAME, []);

        return is_array($options)
            ? $options
            : [];
    }

    /**
     * Retrieve all settings.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->options();
    }

    public function get(
        string $key,
        mixed $default = null,
    ): mixed {
        $options = $this->options();

        return $options[$key] ?? $default;
    }

    public function set(
        string $key,
        mixed $value,
    ): bool {
        $options = $this->options();

        $options[$key] = $value;

        return update_option(
            self::OPTION_NAME,
            $options
        );
    }

    public function forget(
        string $key,
    ): bool {
        $options = $this->options();

        unset($options[$key]);

        return update_option(
            self::OPTION_NAME,
            $options
        );
    }
}
