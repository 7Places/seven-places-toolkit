<?php

declare(strict_types=1);

namespace SPT\Infrastructure\Http;

use SPT\Core\Application;
use WP_Error;

final readonly class HttpClient
{
    private const DEFAULT_TIMEOUT = 15;

    public function __construct(
        private Application $app,
    ) {
    }

    /**
     * @param array<string,mixed> $args
     */
    public function get(string $url, array $args = []): HttpResponse
    {
        return $this->request('GET', $url, $args);
    }

    /**
     * @param array<string,mixed> $args
     */
    public function post(string $url, array $args = []): HttpResponse
    {
        return $this->request('POST', $url, $args);
    }

    /**
     * @param array<string,mixed> $args
     */
    public function request(
        string $method,
        string $url,
        array $args = []
    ): HttpResponse {

        $defaults = [
            'method'      => strtoupper($method),
            'timeout'     => self::DEFAULT_TIMEOUT,
            'user-agent'  => sprintf(
                '%s/%s; WordPress',
                $this->app->slug(),
                $this->app->version()
            ),
        ];

        $response = wp_remote_request(
            $url,
            array_replace($defaults, $args)
        );

        if ($response instanceof WP_Error) {
            throw new \RuntimeException($response->get_error_message());
        }

        $body = wp_remote_retrieve_body($response);

        $status = wp_remote_retrieve_response_code($response);

        $headers = wp_remote_retrieve_headers($response);

        $json = json_decode($body, true);

        return new HttpResponse(
            status: $status,
            body: $body,
            headers: is_object($headers)
                ? $headers->getAll()
                : (array) $headers,
            json: is_array($json)
                ? $json
                : null,
        );
    }
}
