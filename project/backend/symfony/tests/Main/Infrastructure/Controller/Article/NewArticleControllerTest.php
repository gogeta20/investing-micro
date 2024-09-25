<?php

declare(strict_types=1);

namespace App\Tests\Main\Infrastructure\Controller\Article;

use App\Main\Domain\Exception\StoreException;
use App\Main\Infrastructure\Controller\Article\Post\Create\PostArticleController;
use App\Main\Infrastructure\Controller\Article\Post\NewArticleRequest;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Interfaces\ParamsRequestInterface;
use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;
use App\Shared\Domain\Parameters;
use App\Shared\Infrastructure\Symfony\ApiExceptionsHttpStatusCodeMapping;
use App\Tests\Shared\Infrastructure\Symfony\BaseTestCase;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class NewArticleControllerTest extends BaseTestCase
{

    /**
     * @throws Exception
     * @throws StoreException
     */
    public function testInvokeReturnJsonResponse()
    {
        $newArticleRequest = $this->createMock(NewArticleRequest::class);
        $newArticleRequest->method('validate')->willReturn(null);
        $newArticleRequest->method('data')->willReturn(['data']);

        $controller = $this->getController();

        $result = $controller($newArticleRequest);
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(201, $result->getStatusCode());
        $headers = $result->headers->all();
        $this->assertArrayHasKey('content-type', $headers);
        $this->assertContains('application/json', $headers['content-type']);
        $this->tearDown();
    }

    /**
     * @throws Exception
     */
    public function testInvokeThrowsStoreException()
    {
        $this->expectException(StoreException::class);

        $newArticleRequest = $this->createMock(NewArticleRequest::class);
        $newArticleRequest->method('validate')->willReturn(null);
        $newArticleRequest->method('data')->willThrowException(new \Exception('An error occurred'));

        $controller = $this->getController('NewArticleController');

        $controller($newArticleRequest);
        $this->tearDown();
    }


    /**
     * @throws Exception
     */
    public function getController(): PostArticleController
    {
        $queryBus = $this->createMock(QueryBus::class);
        $commandBus = $this->createMock(CommandBus::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $exceptionHandler = $this->createMock(ApiExceptionsHttpStatusCodeMapping::class);
        $translator = $this->createMock(TranslateInterfaceCustom::class);
        $paramsRequest = $this->createMock(ParamsRequestInterface::class);
        $configurationParams = $this->createMock(Parameters::class);
        $projectDir = 'project-dir';

        return new PostArticleController(
            $queryBus,
            $commandBus,
            $eventDispatcher,
            $exceptionHandler,
            $translator,
            $paramsRequest,
            $configurationParams,
            $projectDir
        );
    }


}
