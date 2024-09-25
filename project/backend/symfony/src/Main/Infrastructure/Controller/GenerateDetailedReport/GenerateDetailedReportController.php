<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Controller\GenerateDetailedReport;

use App\Main\Application\UseCases\GenerateDetailedReport\GenerateDetailedReportCommand;
use App\Main\Domain\Exception\StoreException;
use App\Main\Infrastructure\Response\JsonApiResponse;
use App\Shared\Application\AppConstants;
use App\Shared\Infrastructure\Symfony\ApiController;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class GenerateDetailedReportController extends ApiController
{
    /**
     * @throws StoreException
     */
    public function __invoke(GenerateDetailedReportRequest $request): JsonResponse
    {
        $errors = $request->validate();

        if (null !== $errors) {
            return JsonApiResponse::error(errors: $errors);
        }

        try {
            $this->dispatch(GenerateDetailedReportCommand::create(['id' => $request->data()['id']]));

        } catch (Exception $exception) {
            throw new StoreException('Error al crear el Reporte: ' . $exception->getMessage());
        }

        return JsonApiResponse::created($this->translator->translate(AppConstants::SUCCESS, [], 'basic'));
    }

    protected function exceptions(): array
    {
        return [
            StoreException::class => 500,
            Exception::class => 503,
        ];
    }
}
