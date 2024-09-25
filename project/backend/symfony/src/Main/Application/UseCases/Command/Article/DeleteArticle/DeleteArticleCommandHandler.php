<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\Article\DeleteArticle;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Repository\Interfaces\Article\IArticleRepository;
use App\Shared\Domain\BaseResponse;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;
use Symfony\Component\HttpFoundation\Response;

final class DeleteArticleCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly DeleteArticle $deleteArticle,
        protected TranslateInterfaceCustom $translatorCustom
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(DeleteArticleCommand $command): BaseResponse
    {
        try {
            $this->deleteArticle->__invoke($command);
            list($status, $message, $data) = $this->resolveResponseParams();
            $response = new BaseResponse($data);
            $response->setStatus($status);
            $response->setMessage($message);
            return $response;
        } catch (\Exception $e) {
            throw new StoreException('Error al eliminar el artículo: ' . $e->getMessage());
        }
    }

    private function resolveResponseParams(): array
    {
        $message = $this->translatorCustom->translate('success', [], 'basic');
        $status = Response::HTTP_OK;
        $data = [];
        return [$status, $message, $data];
    }
}
