<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Querys\Article\ById;

use App\Main\Domain\Exception\StoreException;
use App\Shared\Domain\BaseResponse;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;
use Symfony\Component\HttpFoundation\Response;

final class GetArticleByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly GetArticleById $getArticleById,
        protected TranslateInterfaceCustom $translatorCustom
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(GetArticleByIdQuery $query): BaseResponse
    {
        try {
            $article = $this->getArticleById->__invoke($query->id());
            list($status, $message, $data) = $this->resolveResponseParams($article);

            $response = new BaseResponse($data);
            $response->setStatus($status);
            $response->setMessage($message);

            return $response;
        } catch (\Exception $e) {
            throw new StoreException('Error al obtener el artículo: ' . $e->getMessage());
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
