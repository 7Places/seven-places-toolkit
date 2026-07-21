<?php

declare(strict_types=1);

namespace SPT\Infrastructure\Http;

final readonly class HttpResponse
{
    /**
     * @param array<string, string|string[]> $headers
     * @param array<mixed>|null $json
     */
    public function __construct(
        private int $status,
        private string $body,
        private array $headers = [],
        private ?array $json = null,
    ) {
    }

    public function successful(): bool
    {
        return $this->status >= 200 && $this->status < 300;
    }

    public function failed(): bool
    {
        return !$this->successful();
    }

    public function status(): int
    {
        return $this->status;
    }

    public function body(): string
    {
        return $this->body;
    }

    /**
     * @return array<string, string|string[]>
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * @return array<mixed>|null
     */
    public function json(): ?array
    {
        return $this->json;
    }

    public function hasJson(): bool
    {
        return $this->json !== null;
    }

    public function header(string $name): ?string
    {
        foreach ($this->headers as $key => $value) {
            if (strcasecmp($key, $name) !== 0) {
                continue;
            }

            return is_array($value)
                ? implode(', ', $value)
                : $value;
        }

        return null;
    }
}
