<?php

declare(strict_types=1);

namespace SPT\Infrastructure\GitHub;

use RuntimeException;
use SPT\Core\Application;

final readonly class GitHubClient
{
    private const API = 'https://api.github.com';

    public function __construct(
        private Application $app,
    ) {
    }

    /**
     * Returns the latest release.
     *
     * @return array<string,mixed>
     */
    public function latestRelease(): array
    {
        $repository = $this->app
            ->metadata()
            ->header('GitHubRepository');

        if ($repository === '') {
            throw new RuntimeException(
                'GitHubRepository plugin header is missing.'
            );
        }

        $response = $this->app
            ->http()
            ->get(
                self::API . '/repos/' . $repository . '/releases/latest'
            );

        if ($response->failed()) {
          throw new RuntimeException(
              sprintf(
                  'GitHub API request to "%s" failed (%d).',
                  $endpoint,
                  $response->status()
              )
          );
        }

        $json = $response->json();

        if ($json === null) {
            throw new RuntimeException(
                'GitHub returned invalid JSON.'
            );
        }

        return $json;
    }
}
