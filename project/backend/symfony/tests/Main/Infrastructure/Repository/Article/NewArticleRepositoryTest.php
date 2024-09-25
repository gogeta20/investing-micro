<?php

declare(strict_types=1);

namespace App\Tests\Main\Infrastructure\Repository\Article;

use App\Main\Domain\Model\TblArticulo;
use App\Main\Domain\Model\TblAutor;
use App\Main\Domain\Model\TblImagen;
use App\Main\Infrastructure\Repository\Article\NewArticleRepository;
use App\Tests\Shared\Domain\UuidWrapper;
use App\Tests\Shared\Infrastructure\Symfony\BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;

class NewArticleRepositoryTest extends BaseTestCase
{
    /**
     * @throws Exception
     */
    public function testCreatePost(): void
    {
        $mockTblAutor = $this->createMock(TblAutor::class);
        $mockTblImagen = $this->createMock(TblImagen::class);
        $mockEntityRepository = $this->createMock(EntityRepository::class);
        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $newArticleRepository = $this->getMockBuilder(NewArticleRepository::class)
            ->onlyMethods(['repository', 'persist'])
            ->setConstructorArgs([$mockEntityManager, $mockEntityRepository])
            ->getMock();
        $newArticleRepository->method('repository')->willReturn($mockEntityRepository);
        $newArticleRepository->method('persist');
        $newArticleRepository->method('repository')
            ->willReturnOnConsecutiveCalls( $mockTblAutor,  $mockTblImagen);

        //aqui es la comprobacion
        $newArticleRepository->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(TblArticulo::class));

        $newArticleRepository->createPost(['title' => 'Test Article', 'content' => 'Test content']);
        $this->tearDown();
    }
}