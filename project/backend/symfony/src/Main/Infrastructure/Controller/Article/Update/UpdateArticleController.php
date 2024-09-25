<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\Article\Update;

use App\Main\Application\UseCases\Command\Article\UpdateArticle\UpdateArticleCommand;
use App\Main\Domain\Exception\StoreException;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Shared\Infrastructure\Symfony\ApiControllerUploads;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UpdateArticleController extends ApiControllerUploads
{
    /**
     * @throws StoreException
     */
    public function __invoke(UpdateArticleRequest $request, Request $httpRequest, string $id): JsonResponse
    {
        $errors = $request->validate();

        if (null !== $errors) {
            return JsonApiResponse::error(errors: $errors);
        }

        $email = $this->decoderService->getUserFromToken($httpRequest);

        try {
            $file = $httpRequest->files->get('files');
            $mediaUrl = null;

            if ($file) {
                $mediaUrl = $this->uploadFile($file, 'public/uploads');
            }
            $this->dispatch(UpdateArticleCommand::create([
                'email' => $email,
                'id' => $request->data()['id'],
                'title' => $request->data()['title'] ?? null,
                'body' => $request->data()['body'] ?? null,
                'mediaUrl' => $mediaUrl,
                'mediaType' => $file ? $file->getClientMimeType() : null,
            ]));
        } catch (\Exception $exception) {
            throw new StoreException('Error al actualizar el artículo: ' . $exception->getMessage());
        }

        return JsonApiResponse::updated('Artículo actualizado correctamente.');
    }

    protected function exceptions(): array
    {
        return [
            StoreException::class => 500,
            Exception::class => 503,
        ];
    }
}
