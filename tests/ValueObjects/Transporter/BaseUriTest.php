<?php

use ReallyDope\Ghost\ValueObjects\Transporter\BaseUri;

test('it can be created from a string', function () {
    $baseUri = BaseUri::from('ghost.org');

    expect($baseUri->toString())->toBe('https://ghost.org/');
});

test('it can be created with a protocol', function () {
    $baseUri = BaseUri::from('http://ghost.org');

    expect($baseUri->toString())->toBe('http://ghost.org/');
});

test('it can be created with a trailing slash', function () {
    $baseUri = BaseUri::from('https://ghost.org/');

    expect($baseUri->toString())->toBe('https://ghost.org/');
});
