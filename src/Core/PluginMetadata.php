<?php

declare(strict_types=1);

namespace SPT\Core;

final class PluginMetadata
{
    /**
     * Standard and custom plugin headers.
     */
    private const HEADERS = [
        'Name'              => 'Plugin Name',
        'PluginURI'         => 'Plugin URI',
        'Version'           => 'Version',
        'Description'       => 'Description',
        'Author'            => 'Author',
        'AuthorURI'         => 'Author URI',
        'TextDomain'        => 'Text Domain',
        'RequiresPHP'       => 'Requires PHP',
        'RequiresWP'        => 'Requires at least',

        // Custom headers
        'GitHubRepository'  => 'GitHub Repository',
        'GitHubBranch'      => 'GitHub Branch',
        'ReleaseChannel'    => 'Release Channel',
    ];

    /**
     * Raw plugin header values.
     *
     * @var array<string,string>
     */
    private array $headers = [];

    public function __construct(
        private readonly string $pluginFile,
    ) {
        if (! function_exists('get_file_data')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $this->headers = get_file_data(
            $this->pluginFile,
            self::HEADERS,
            'plugin'
        );
    }

    /**
     * Retrieve any header value by its internal key.
     */
    public function header(string $key): string
    {
        return trim($this->headers[$key] ?? '');
    }

    public function name(): string
    {
        return $this->header('Name');
    }

    public function version(): string
    {
        return $this->header('Version');
    }

    public function description(): string
    {
        return $this->header('Description');
    }

    public function author(): string
    {
        return $this->header('Author');
    }

    public function authorUri(): string
    {
        return $this->header('AuthorURI');
    }

    public function pluginUri(): string
    {
        return $this->header('PluginURI');
    }

    public function textDomain(): string
    {
        return $this->header('TextDomain');
    }

    public function requiresPhp(): string
    {
        return $this->header('RequiresPHP');
    }

    public function requiresWp(): string
    {
        return $this->header('RequiresWP');
    }

    public function pluginFile(): string
    {
        return $this->pluginFile;
    }

    public function pluginBasename(): string
    {
        return plugin_basename($this->pluginFile);
    }

    public function pluginDirectory(): string
    {
        return plugin_dir_path($this->pluginFile);
    }

    public function pluginUrl(): string
    {
        return plugin_dir_url($this->pluginFile);
    }

    public function slug(): string
    {
        return dirname($this->pluginBasename());
    }
}
