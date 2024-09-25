<?php

namespace App\Main\Infrastructure\Service;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;

readonly class TokenDecoderService
{
    public function __construct(private JWTEncoderInterface $JWTEncoder)
    {}

    public function getUserFromToken(Request $request): ?string
    {
        try {
            $token = $request->headers->get('Authorization');
            $token = str_replace('Bearer ', '', $token);
            $decodedToken = $this->JWTEncoder->decode($token);
            return $decodedToken['username'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
