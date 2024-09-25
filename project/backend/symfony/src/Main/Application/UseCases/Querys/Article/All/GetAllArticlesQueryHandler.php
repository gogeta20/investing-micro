<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Querys\Article\All;

use App\Main\Domain\Exception\StoreException;
use App\Shared\Domain\BaseResponse;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;
use Symfony\Component\HttpFoundation\Response;

final class GetAllArticlesQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly GetAllArticles $getAllArticles, // Caso de uso que se inyecta
        protected TranslateInterfaceCustom $translatorCustom
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(GetAllArticlesQuery $query): BaseResponse
    {
        try {
            $articles = $this->getAllArticles->__invoke($query);
            list($status, $message, $data) = $this->resolveResponseParams($articles);

            $response = new BaseResponse($data);
            $response->setStatus($status);
            $response->setMessage($message);

            return $response;
        } catch (\Exception $e) {
            throw new StoreException('Error fetching articles: ' . $e->getMessage());
        }
    }

    private function resolveResponseParams(array $articles): array
    {
        if (empty($articles)) {
            $message = $this->translatorCustom->translate('empty', [], 'basic');
        } else {
            $message = $this->translatorCustom->translate('success', [], 'basic');
        }
        $status = Response::HTTP_OK;
        $data = $articles;

        return [$status, $message, $data];
    }
}
