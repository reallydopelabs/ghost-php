<?php

namespace ReallyDope\Ghost\Transporters;

use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use ReallyDope\Ghost\Contracts\Transporter;
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

        try {
            $response = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $jsonException) {
            throw new UnserializableResponseException($jsonException);
        }

        return $response;
    }
}
