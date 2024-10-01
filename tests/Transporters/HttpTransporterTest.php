<?php

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use ReallyDope\Ghost\Enums\Transporter\ContentType;
use ReallyDope\Ghost\Exceptions\ErrorException;
use ReallyDope\Ghost\Exceptions\TransporterException;
use ReallyDope\Ghost\Exceptions\UnserializableResponse;
use ReallyDope\Ghost\Exceptions\UnserializableResponseException;
use ReallyDope\Ghost\Transporters\HttpTransporter;
use ReallyDope\Ghost\ValueObjects\ApiKey;
use ReallyDope\Ghost\ValueObjects\Transporter\BaseUri;
use ReallyDope\Ghost\ValueObjects\Transporter\Headers;
use ReallyDope\Ghost\ValueObjects\Transporter\Payload;

beforeEach(function () {
    /** @var ClientInterface */
    $this->client = Mockery::mock(ClientInterface::class);

    $apiKey = apiKey();

    $this->http = new HttpTransporter(
        $this->client,
        BaseUri::from('ghost.org'),
        Headers::withAuthorization($apiKey)->withContentType(ContentType::JSON)
    );
});

test('it can make a request', function () {
    $payload = Payload::create('members', ['name' => 'Sam Rapaport']);
    $response = new Response(200, [], json_encode([
        'foo',
    ]));

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->withArgs(function (Request $request) {
            expect($request->getMethod())->toBe('POST')
                ->and($request->getUri())
                ->getHost()->toBe('ghost.org')
                ->getScheme()->toBe('https')
                ->getPath()->toBe('/members');

            return true;
        })->andReturn($response);

    $this->http->request($payload);
});

test('it can parse a response', function () {
    $payload = Payload::create('members', ['name' => 'Sam Rapaport']);
    $response = new Response(200, [], json_encode([
        'id' => 'test_123',
    ]));

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->andReturn($response);

    $response = $this->http->request($payload);

    expect($response)
        ->toBe([
            'id' => 'test_123',
        ]);
});

test('request can handle client errors', function () {
    $payload = Payload::create('members', ['name' => 'Sam Rapaport']);

    $baseUri = BaseUri::from('ghost.org');
    $headers = Headers::withAuthorization(apiKey());

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->andThrow(new ConnectException('Could not resolve host.', $payload->toRequest($baseUri, $headers)));

    expect(fn() => $this->http->request($payload))->toThrow(function (TransporterException $exception) {
        expect($exception->getMessage())->toBe('Could not resolve host.')
            ->and($exception->getCode())->toBe(0)
            ->and($exception->getPrevious())->toBeInstanceOf(ConnectException::class);
    });
});

test('request can handle serialization errors', function () {
    $payload = Payload::create('members', ['name' => 'Sam Rapaport']);
    $response = new Response(200, [], 'err');

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->andReturn($response);

    $this->http->request($payload);
})->throws(UnserializableResponseException::class, 'Syntax error');

test('request can throw ghost errors', function () {
    $payload = Payload::create('members', ['name' => 'Sam Rapaport']);
    $response = new Response(403, [], json_encode([
        'errors' => [
            [
                'message' => 'Invalid token: jwt expired',
                'context' => null,
                'type' => 'UnauthorizedError',
                'details' => null,
                'property' => null,
                'help' => null,
                'code' => 'INVALID_JWT',
                'id' => '054625c0-8035-11ef-89a3-d7ec48a27e0b',
                'ghostErrorCode' => null,
            ]
        ]
    ]));

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->andReturn($response);

    $this->http->request($payload);
})->throws(ErrorException::class);

test('request can throw json error', function () {
    $payload = Payload::create('members', ['name' => 'Sam Rapaport']);
    $response = new Response(422, [], 'err');

    $this->client
        ->shouldReceive('sendRequest')
        ->once()
        ->andReturn($response);

    $this->http->request($payload);
})->throws(UnserializableResponseException::class, 'Syntax error');
