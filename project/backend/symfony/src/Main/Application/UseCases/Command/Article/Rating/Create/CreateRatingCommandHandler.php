<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\Article\Rating\Create;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Repository\Interfaces\Article\IArticleRepository;
use App\Main\Domain\Repository\Interfaces\User\IUserRepository;
use App\Shared\Domain\BaseResponse;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;
use Symfony\Component\HttpFoundation\Response;

final class CreateRatingCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly CreateRating $createRating,
        private readonly IUserRepository $userRepository,
        private readonly IArticleRepository $articleRepository,
        protected TranslateInterfaceCustom $translatorCustom
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(CreateRatingCommand $command): BaseResponse
    {
        try {
            $user = $this->userRepository->findByEmail($command->email());
            if (!$user) {
                throw new StoreException('Usuario no encontrado.');
            }

            $article = $this->articleRepository->find($command->articleId());
            if (!$article) {
                throw new StoreException('Artículo no encontrado.');
            }

            $result = $this->createRating->__invoke($command, $user, $article);

            list($status, $message, $data) = $this->resolveResponseParams($result);
            $response = new BaseResponse($data);
            $response->setStatus($status);
            $response->setMessage($message);
            return $response;

        } catch (\Exception $e) {
            throw new StoreException('Error al calificar el artículo: ' . $e->getMessage());
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
