<?php

use ReallyDope\Ghost\Enums\Transporter\ContentType;
use ReallyDope\Ghost\ValueObjects\ApiKey;
use ReallyDope\Ghost\ValueObjects\Transporter\BaseUri;
use ReallyDope\Ghost\ValueObjects\Transporter\Headers;
use ReallyDope\Ghost\ValueObjects\Transporter\Payload;

test('it has a method', function () {
    $payload = Payload::create('email', []);

    $baseUri = BaseUri::from('ghost.org');
    $headers = Headers::withAuthorization(apiKey())
        ->withContentType(ContentType::JSON);

    expect($payload->toRequest($baseUri, $headers)->getMethod())->toBe('POST');
});

test('it has a body when making a POST request', function () {
    $payload = Payload::create('email', [
        'to' => 'test@ghost.org',
    ]);

    $baseUri = BaseUri::from('ghost.org');
    $headers = Headers::withAuthorization(apiKey())
        ->withContentType(ContentType::JSON);

    expect($payload->toRequest($baseUri, $headers)->getBody()->getContents())->toBe(json_encode([
        'to' => 'test@ghost.org',
    ]));
});

test('it does not have a body when making a GET request', function () {
    $payload = Payload::browse('api-keys');

    $baseUri = BaseUri::from('ghost.org');
    $headers = Headers::withAuthorization(apiKey())
        ->withContentType(ContentType::JSON);

    expect($payload->toRequest($baseUri, $headers)->getBody()->getContents())->toBe('');
});
