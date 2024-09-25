<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\Article\UpdateArticle;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Repository\Interfaces\Article\IArticleRepository;
use App\Shared\Domain\BaseResponse;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;
use Symfony\Component\HttpFoundation\Response;

final class UpdateArticleCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly UpdateArticle $updateArticle,
        protected TranslateInterfaceCustom $translatorCustom
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(UpdateArticleCommand $command): BaseResponse
    {
        try {
            $result = $this->updateArticle->__invoke($command);
            list($status, $message, $data) = $this->resolveResponseParams($result);
            $response = new BaseResponse($data);
            $response->setStatus($status);
            $response->setMessage($message);
            return $response;
        } catch (\Exception $e) {
            throw new StoreException('Error al actualizar el artículo: ' . $e->getMessage());
        }
    }

    private function resolveResponseParams(array $result): array
    {
        if (empty($result)) {
            $message = $this->translatorCustom->translate('empty', [], 'basic');
        } else {
            $message = $this->translatorCustom->translate('success', [], 'basic');
        }
        $status = Response::HTTP_OK;
        $data = $result;
        return array($status, $message, $data);
    }
}
