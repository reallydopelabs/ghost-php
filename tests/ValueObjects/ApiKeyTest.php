<?php

use ReallyDope\Ghost\ValueObjects\ApiKey;

test('it can be created from a string', function () {
    $apiKey = apiKey('foo');

    expect($apiKey->toString())->toBe('testKeyId:1234567890abcdef1234567890abcdef');
});
