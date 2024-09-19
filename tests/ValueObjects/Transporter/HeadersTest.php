<?php

use function PHPUnit\Framework\assertArrayHasKey;
use ReallyDope\Ghost\Enums\Transporter\ContentType;
use ReallyDope\Ghost\ValueObjects\ApiKey;
use ReallyDope\Ghost\ValueObjects\Transporter\Headers;

test('it can be created from an API key', function () {
    $apiKey = apiKey();
    $headers = Headers::withAuthorization($apiKey);

    expect($headers->toArray())->toBe([
        'Authorization' => "Ghost {$apiKey->toJwt()}",
    ]);
});

test('it can have content-type', function () {
    $apiKey = apiKey();
    $headers = Headers::withAuthorization($apiKey)
        ->withContentType(ContentType::JSON);

    expect($headers->toArray())->toBe([
        'Authorization' => "Ghost {$apiKey->toJwt()}",
        'Content-Type' => 'application/json',
    ]);
});

test('it can have a Ghost API version', function () {
    $apiKey = apiKey();
    $headers = Headers::withAuthorization($apiKey)
        ->withGhostApiVersion('5.0.0');

    expect($headers->toArray())->toBe([
        'Authorization' => "Ghost {$apiKey->toJwt()}",
        'Accept-Version' => 'v5.0.0',
    ]);
});

test('it can have a user agent', function () {
    $apiKey = apiKey();
    $headers = Headers::withAuthorization($apiKey)
        ->withUserAgent('ghost-php', '1.0.0');

    expect($headers->toArray())->toBe([
        'Authorization' => "Ghost {$apiKey->toJwt()}",
        'User-Agent' => 'ghost-php/1.0.0',
    ]);
});
