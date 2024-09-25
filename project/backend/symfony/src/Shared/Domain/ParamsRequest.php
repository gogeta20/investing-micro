<?php

namespace App\Shared\Domain;

use App\Shared\Domain\Interfaces\ParamsRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class ParamsRequest implements ParamsRequestInterface
{
    const PARAMETERS = 'parameters';
    const AUTH =    'Authorization';
    const GET =     'GET';
    const POST =    'POST';
    const PUT =     'PUT';
    const DELETE =  'DELETE';

    public function requestParameters(Request $request): array
    {
        $method = $request->getMethod();
        $parameters = '';
        $parameters = $this->getParameters($method, $request, $parameters);
        try {
            return json_decode($parameters, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return [];
        }
    }

    private function getParameters(string $method, Request $request, string $parameters)
    {
        if (self::GET === $method) {
            $parameters = $this->getParametersGet($request, $parameters);
        }
        if (self::POST === $method || self::PUT === $method || self::DELETE === $method) {
            $parameters = $this->getParametersPost($request, $parameters);
        }

        return $parameters;
    }

    private function getParametersGet(Request $request, $parameters)
    {
        if ($request->query->has(self::PARAMETERS)) {
            $parameters = $request->query->get(self::PARAMETERS);
        }

        return $parameters;
    }

    private function getParametersPost(Request $request, $parameters)
    {
        if (($request->getContent()) !== '' && ($content = $request->getContent()) !== '0') {
            $parameters = $content;
        }

        return $parameters;
    }

    public function idSession(Request $request): string
    {
        return $request->headers->get(self::AUTH);
    }

    public function requestParametersBody(Request $request): array|string
    {
        $parameters = '';
        if (($request->getContent()) !== '' && ($content = $request->getContent()) !== '0') {
            $parameters = $content;
        }
        try {
            return json_decode($parameters, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            return [];
        }
    }
}
