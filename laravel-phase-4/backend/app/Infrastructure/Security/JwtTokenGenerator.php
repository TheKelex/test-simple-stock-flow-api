<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

final class JwtTokenGenerator
{
    public function generate(string $subject): string
    {
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode(['sub' => $subject, 'iat' => time()]));
        $signature = hash_hmac('sha256', "$header.$payload", env('JWT_SECRET', 'fallback-secret'), true);

        return "$header.$payload." . rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');
    }
}
