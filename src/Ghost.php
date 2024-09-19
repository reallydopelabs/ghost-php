<?php

namespace ReallyDope\Ghost;

use GuzzleHttp\Client as GuzzleClient;
use ReallyDope\Ghost\Transporters\HttpTransporter;
use ReallyDope\Ghost\ValueObjects\ApiKey;
use ReallyDope\Ghost\ValueObjects\Transporter\BaseUri;
use ReallyDope\Ghost\ValueObjects\Transporter\Headers;
use Symfony\Component\HttpClient\HttpClient;

class Ghost
{
    /**
     * The current SDK version.
     */
    public const VERSION = '0.0.0';

    /**
     * Get an instance of the Ghost Admin API with the given URL and API key.
     */
    public static function admin(string $baseUrl, ApiKey|string $apiKey): Admin
    {
        $apiKey = ($apiKey instanceof ApiKey) ? $apiKey : ApiKey::from($apiKey);
        $baseUri = BaseUri::from("{$baseUrl}/ghost/api/admin/");
        $headers = Headers::withAuthorization($apiKey)
            ->withGhostApiVersion('3.0');

        $client = new GuzzleClient();
        $transporter = new HttpTransporter($client, $baseUri, $headers);

        return new Admin($transporter);
    }
}
