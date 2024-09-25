<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Request;

//use Api\Shared\Domain\Aggregate\Admin\Admin\Admin;
//use Api\Shared\Domain\Aggregate\Customer\Customer\Customer;
//use Api\Shared\Domain\Aggregate\Merchant\Merchant\Merchant;
//use Symfony\Component\Security\Core\Secutity;
use App\Shared\Tools\JsonTool;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
//use Symfony\Component\Security\Core\User\UserInterface;

use  App\Shared\Tools\ArrayTool;

class SymfonyRequest implements RequestInterface
{

    public function __construct(
        private readonly RequestStack $request,
        private Security              $security,
        public $jt =  new JsonTool()
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function dataRequest(): array
    {
        return $this->jt->jsonDecode(string: $this->currentRequestContent()) ?? [];
    }

    /**
     * @throws \JsonException
     */
    public function data(): array
    {
        $data = $this->jt->jsonDecode(string: $this->currentRequestContent()) ?? [];

        return array_merge(
            $data,
            $this->routeParams(),
//            $this->userData() ?? [],
        );
    }

    /**
     * @throws \JsonException
     */
    public function dataFull(): array
    {
        $data = $this->jt->jsonDecode(string: $this->currentRequestContent()) ?? [];

        return array_merge(
            $data,
            $this->routeParams(),
//            $this->userData() ?? [],
            ['host'    => $this->getHost()],
            ['headers' => $this->getHeaders()],
            ['server'  => $this->getServerParams()],
            ['query'   => $this->getQueryParameters()],
        );
    }

//    private function userData(): array|null
//    {
//        $user = $this->getUser();
//
//        return ($user instanceof Admin || $user instanceof Customer || $user instanceof Merchant) ?
//            [
//                'userId'   => $user->id(),
//                'username' => $user->name()->value(),
//            ]
//            : null;
//    }

    /**
     * @throws \JsonException
     */
    public function filters(): array
    {
        $data = $this->data();

        if (ArrayTool::arrayKeyExists(key: 'filters', array: $data)) {
            return $this->jt->jsonDecode(string: $data['filters']) ?? [];
        }

        return [];
    }

    public function getHost(): string
    {
        return $this->request->getCurrentRequest()?->getHost() ?? '';
    }

    private function currentRequestContent(): string|bool
    {
        if (null === $this->currentRequest()) {
            throw new \UnexpectedValueException(message: 'There is no Request ');
        }

        if ('GET' === $this->currentRequestMethod() && [] !== $this->currentRequest()->query->all()) {
            return $this->jt->jsonEncode(array: $this->currentRequest()->query->all());
        }

        if (str_contains($this->currentRequest()->headers->get('content-type', ''), 'multipart/form-data')) {
            return $this->jt->jsonEncode(array: $this->currentRequest()->request->all());
        }

        return $this->currentRequest()->getContent();
    }

    protected function getQueryParameters(): array
    {
        return !is_null($this->currentRequest()) ? $this->currentRequest()->query->all() : [];
    }

    protected function getHeaders(): array
    {
        return !is_null($this->currentRequest()) ? $this->currentRequest()->headers->all() : [];
    }

    protected function getServerParams(): array
    {
        return !is_null($this->request->getMainRequest()) ? $this->request->getMainRequest()->server->all() : [];
    }

    private function currentRequestMethod(): string
    {
        return $this->currentRequest()?->getMethod() ?? throw new \UnexpectedValueException(message: 'There is no Request ');
    }

    private function currentRequest(): ?Request
    {
        return $this->request->getCurrentRequest();
    }

    public function routeParams(): array
    {
        return $this->request->getCurrentRequest()?->attributes->get('_route_params') ?? [];
    }

//    private function getUser(): ?UserInterface
//    {
//        return $this->security->getUser();
//    }

    public function locale(): string
    {
        return $this->request->getCurrentRequest()?->getLocale() ?? 'en';
    }

    public function files(): array
    {
        return $this->request->getCurrentRequest()?->files->all() ?? [];
    }
}
