<?php

declare(strict_types=1);

namespace App\Tests\Main\Application\UseCases\Command\Article\NewArticle;

use App\Main\Application\UseCases\Command\Article\Create\NewArticle;
use App\Main\Application\UseCases\Command\Article\Create\NewArticleCommand;
use App\Main\Domain\Repository\Interfaces\Article\NewArticleInterface;
use App\Tests\Shared\Infrastructure\Symfony\BaseTestCase;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

final class NewArticleTest extends BaseTestCase
{
    /**
     * @throws Exception
     */
    public function testInvokeCallsCreateDataOnNewArticle(): void
    {
        // Preparar un mock para NewArticleInterface
        $mockNewArticleInterface = $this->createMock(NewArticleInterface::class);
        $mockNewArticleInterface->expects($this->once())
            ->method('createData')
            ->with($this->isType('array')); // Asegurar que createData es llamado con un array

        // Crear instancia de NewArticle con la dependencia simulada
        $newArticle = new NewArticle($mockNewArticleInterface);

        // Crear instancia de NewArticleCommand con datos de prueba
        $newArticleCommand = new NewArticleCommand(['title' => 'Test Article', 'content' => 'This is a test.']);

        // Ejecutar el método __invoke
        $newArticle->__invoke($newArticleCommand);

        // El método PHPUnit "expects" se encarga de las aserciones
    }
}
