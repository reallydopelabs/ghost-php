<?php

namespace ReallyDope\Ghost\Contracts;

use ReallyDope\Ghost\ValueObjects\Transporter\Payload;

interface Transporter
{
    /**
     * Sends a request to the Ghost API.
     *
     * @return array<array-key, mixed>
     *
     * @throws ErrorException|TransporterException|UnserializableResponse
     */
    public function request(Payload $payload): array;
}
