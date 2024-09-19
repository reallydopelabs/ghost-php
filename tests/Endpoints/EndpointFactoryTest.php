<?php

use ReallyDope\Ghost\Contracts\Transporter;
use ReallyDope\Ghost\Endpoints\EndpointFactory;
use ReallyDope\Ghost\Endpoints\Members;

test('it can create a new service instance', function () {
    /** @var \ReallyDope\Ghost\Contracts\Transporter */
    $transporter = Mockery::mock(Transporter::class);

    $factory = new EndpointFactory($transporter);

    $service = $factory->getEndpoint('members');

    expect($service)
        ->toBeInstanceOf(Members::class);
});

test('it can handle non existent services', function () {
    /** @var \ReallyDope\Ghost\Contracts\Transporter */
    $transporter = Mockery::mock(Transporter::class);

    $factory = new EndpointFactory($transporter);

    $service = $factory->getEndpoint('endpointNotFound');

    expect($service)
        ->toBeNull();
});
