<?php

namespace ReallyDope\Ghost\Endpoints;

use ReallyDope\Ghost\Member;
use ReallyDope\Ghost\ValueObjects\Transporter\Payload;

class Members extends Endpoint
{
    public function browse(array $query = [])
    {
        $payload = Payload::browse('members', $query);

        $result = $this->transporter->request($payload);

        return $this->createResourceCollection('members', $result);
    }

    public function get(string $id): Member
    {
        $payload = Payload::get('members', $id);

        $result = $this->transporter->request($payload);

        return $this->createResource('members', $result);
    }

    public function create()
    {
        //
    }

    public function update()
    {
        //
    }
}
