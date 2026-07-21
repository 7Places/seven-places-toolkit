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
     * Returns the configured repository.
     */
    public function repository(): string
    {
        $repository = trim(
            $this->app
                ->metadata()
                ->header('GitHubRepository')
        );

        if ($repository === '') {
            throw new RuntimeException(
                'GitHubRepository plugin header is missing.'
            );
        }

        return $repository;
    }

    /**
     * Returns the latest published release.
     *
     * @return array<string,mixed>
     */
    public function latestRelease(): array
    {
        return $this->get(
            '/repos/' . $this->repository() . '/releases/latest'
        );
    }

    /**
     * Returns a release by tag.
     *
     * @return array<string,mixed>
     */
    public function releaseByTag(string $tag): array
    {
        return $this->get(
            '/repos/' . $this->repository() . '/releases/tags/' . rawurlencode($tag)
        );
    }

    /**
     * Returns repository information.
     *
     * @return array<string,mixed>
     */
    public function repositoryInfo(): array
    {
        return $this->get(
            '/repos/' . $this->repository()
        );
    }

    /**
     * Executes a GitHub API GET request.
     *
     * @return array<string,mixed>
     */
    private function get(string $endpoint): array
    {
        $response = $this->app
            ->http()
            ->get(self::API . $endpoint);

        if ($response->failed()) {
            throw new RuntimeException(
                sprintf(
                    'GitHub API request failed (%d).',
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
