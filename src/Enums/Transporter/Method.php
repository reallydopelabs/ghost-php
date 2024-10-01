<?php

namespace ReallyDope\Ghost\Enums\Transporter;

enum Method: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PATCH = 'PATCH';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
}
