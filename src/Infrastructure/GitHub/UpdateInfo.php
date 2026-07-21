<?php

declare(strict_types=1);

namespace SPT\Infrastructure\GitHub;

final readonly class UpdateInfo
{
    public function __construct(
        private bool $available,
        private string $currentVersion,
        private string $latestVersion,
        private string $releaseName,
        private string $downloadUrl,
        private string $releaseUrl,
        private string $publishedAt,
        private string $releaseNotes,
    ) {
    }

    public function available(): bool
    {
        return $this->available;
    }

    public function currentVersion(): string
    {
        return $this->currentVersion;
    }

    public function latestVersion(): string
    {
        return $this->latestVersion;
    }

    public function releaseName(): string
    {
        return $this->releaseName;
    }

    public function downloadUrl(): string
    {
        return $this->downloadUrl;
    }

    public function releaseUrl(): string
    {
        return $this->releaseUrl;
    }

    public function publishedAt(): string
    {
        return $this->publishedAt;
    }

    public function releaseNotes(): string
    {
        return $this->releaseNotes;
    }
}
