<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Querys\Article\Favorite\All;

use App\Main\Domain\Exception\StoreException;
use App\Shared\Domain\BaseResponse;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;
use Symfony\Component\HttpFoundation\Response;

final class GetAllFavoritesQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly GetAllFavorites $getAllFavorites,
        protected TranslateInterfaceCustom $translatorCustom
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(GetAllFavoritesQuery $query): BaseResponse
    {
        try {
            $favorites = $this->getAllFavorites->__invoke($query->userId());
            list($status, $message, $data) = $this->resolveResponseParams($favorites);

            $response = new BaseResponse($data);
            $response->setStatus($status);
            $response->setMessage($message);

            return $response;
        } catch (\Exception $e) {
            throw new StoreException('Error al obtener los favoritos: ' . $e->getMessage());
        }
    }

    private function resolveResponseParams(array $favorites): array
    {
        if (empty($favorites)) {
            $message = $this->translatorCustom->translate('empty', [], 'basic');
        } else {
            $message = $this->translatorCustom->translate('success', [], 'basic');
        }
        $status = Response::HTTP_OK;
        $data = $favorites;

        return [$status, $message, $data];
    }
}
