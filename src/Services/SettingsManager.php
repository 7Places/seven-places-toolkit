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
        $options = get_option(
            self::OPTION_NAME,
            []
        );

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

    /**
     * Get a setting.
     */
    public function get(
        string $key,
        mixed $default = null,
    ): mixed {
        return $this->options()[$key] ?? $default;
    }

    /**
     * Store a setting.
     */
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

    /**
     * Remove a setting.
     */
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

    /**
     * Determine whether a setting exists.
     */
    public function has(string $key): bool
    {
        return array_key_exists(
            $key,
            $this->options()
        );
    }

    /**
     * Determine whether a feature is enabled.
     */
    public function enabled(string $feature): bool
    {
        return (bool) $this->get(
            $feature,
            false
        );
    }

    /**
     * Enable a feature.
     */
    public function enable(string $feature): bool
    {
        return $this->set(
            $feature,
            true
        );
    }

    /**
     * Disable a feature.
     */
    public function disable(string $feature): bool
    {
        return $this->set(
            $feature,
            false
        );
    }
}
