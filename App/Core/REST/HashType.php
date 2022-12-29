<?php

namespace App\Asmvc\Core\REST;

enum HashType: string
{
    case ES384 = 'ES384';
    case ES256 = 'ES256';
    case HS256 = 'HS256';
    case HS384 = 'HS384';
    case HS512 = 'HS512';
    case RS256 = 'RS256';
    case RS384 = 'RS384';
    case RS512 = 'RS512';
}
