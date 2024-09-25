<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Querys\User\ById;

use App\Main\Domain\Exception\StoreException;
use App\Shared\Domain\BaseResponse;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;
use Symfony\Component\HttpFoundation\Response;

final class GetUserByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly GetUserById $getUser,
        protected TranslateInterfaceCustom $translatorCustom
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(GetUserByIdQuery $query): BaseResponse
    {
        try {
            $article = $this->getUser->__invoke($query->user());
            list($status, $message, $data) = $this->resolveResponseParams($article);

            $response = new BaseResponse($data);
            $response->setStatus($status);
            $response->setMessage($message);

            return $response;
        } catch (\Exception $e) {
            throw new StoreException('Error al obtener el usuario: ' . $e->getMessage());
        }
    }

    private function resolveResponseParams(array $article): array
    {
        if (empty($article)) {
            $message = $this->translatorCustom->translate('empty', [], 'basic');
        } else {
            $message = $this->translatorCustom->translate('success', [], 'basic');
        }
        $status = Response::HTTP_OK;
        $data = $article;

        return [$status, $message, $data];
    }
}
