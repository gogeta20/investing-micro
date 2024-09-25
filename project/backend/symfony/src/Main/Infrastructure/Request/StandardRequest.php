<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StandardRequest
{
    public function __construct(
        private readonly RequestInterface   $request,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function dataRequest(): array
    {
        return array_merge(
            $this->request->dataRequest(),
            $this->request->routeParams(),
            0 === count($this->files()) ? [] : ['files' => $this->files()]
        );
    }

    public function dataFul(): array
    {
        return array_merge($this->request->data(), $this->request->routeParams());
    }

    public function data(): array
    {
        return array_merge($this->request->data(), $this->request->routeParams());
    }

    public function getHeaderParameter(string $parameter): ?string
    {
        if (array_key_exists($parameter, $this->request->data()['headers'])) {
            return $this->request->data()['headers'][$parameter][0];
        } elseif (array_key_exists($parameter, $this->request->data()['server'])) {
            return $this->request->data()['server'][$parameter];
        }

        return null;
    }

    public function getQueryParameter(string $parameter): ?string
    {
        if (array_key_exists($parameter, $this->request->data()['query'])) {
            return $this->request->data()['query'][$parameter];
        } elseif (array_key_exists($parameter, $this->request->data())) {
            return $this->request->data()[$parameter];
        }

        return null;
    }

    public function filters(): array
    {
        return $this->request->filters();
    }

    public function attributes(): array
    {
        return $this->request->routeParams();
    }

    public function locale(): string
    {
        return $this->request->locale();
    }

    public function files(): array
    {
        return $this->request->files();
    }

    public function validate(): ?array
    {
        return $this->validation(constraints: $this->constraints());
    }

    private function validation(?Assert\Collection $constraints): ?array
    {
        $validations = $this->validator()->validate($this->dataRequest(), $constraints);

        $function = static function ($errors, $error) {
            $pattern           = '/\[(.*?)\]/';
            $propPath          = $error->getPropertyPath();
            $propPath          = preg_replace($pattern, '$1', $propPath);
            $errors[$propPath] = $error->getMessage();

            return $errors;
        };
        $errors = array_reduce(iterator_to_array($validations), $function, []);

        return 0 !== count($errors) ? $errors : null;
    }

    private function validator(): ValidatorInterface
    {
        return $this->validator;
    }

    protected function constraints(): Assert\Collection
    {
        return new Assert\Collection([]);
    }
}
