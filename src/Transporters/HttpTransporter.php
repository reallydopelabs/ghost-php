<?php

namespace ReallyDope\Ghost\Transporters;

use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use ReallyDope\Ghost\Contracts\Transporter;
use ReallyDope\Ghost\Exceptions\ErrorException;
use ReallyDope\Ghost\Exceptions\TransporterException;
use ReallyDope\Ghost\Exceptions\UnserializableResponseException;
use ReallyDope\Ghost\ValueObjects\Transporter\BaseUri;
use ReallyDope\Ghost\ValueObjects\Transporter\Headers;
use ReallyDope\Ghost\ValueObjects\Transporter\Payload;

class HttpTransporter implements Transporter
{
    /**
     * Create a new HTTP Transporter instance.
     */
    public function __construct(
        private readonly ClientInterface $client,
        private readonly BaseUri $baseUri,
        private readonly Headers $headers,
    ) {
        //
    }

    /**
     * Sends a request to the Ghost API.
     *
     * @return array<array-key, mixed>
     *
     * @throws ErrorException|TransporterException|UnserializableResponse
     */
    public function request(Payload $payload): array
    {
        $request = $payload->toRequest($this->baseUri, $this->headers);

        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $clientException) {
            throw new TransporterException($clientException);
        }

        $contents = $response->getBody()->getContents();

        $this->throwIfJsonError($response, $contents);

        try {
            $response = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $jsonException) {
            throw new UnserializableResponseException($jsonException);
        }

        return $response;
    }

    /**
     * Throw an exception if there is a JSON error.
     */
    protected function throwIfJsonError(ResponseInterface $response, string $contents): void
    {
        if ($response->getStatusCode() < 400) {
            return;
        }

        try {
            $response = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

            if (isset($response['errors']) && $this->isGhostError($response['errors'][0]['type'])) {
                throw new ErrorException($response['errors'][0]);
            }
        } catch (JsonException $jsonException) {
            throw new UnserializableResponseException($jsonException);
        }
    }

    /**
     * Determine if the given error name is a Ghost error.
     */
    protected function isGhostError(string $errorName): bool
    {
        $errors = [
            'NoPermissionError',
            'UnauthorizedError',
        ];

        return in_array($errorName, $errors);
    }
}
