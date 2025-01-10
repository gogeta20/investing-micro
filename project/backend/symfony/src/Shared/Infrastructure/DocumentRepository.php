<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Domain\IRepository;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\DocumentManager;


abstract class DocumentRepository implements IRepository
{
    public function __construct(
        protected DocumentManager $documentManager,
    )
    {
    }

    public function repository(string $entityClass)
    {
        return $this->documentManager->getRepository($entityClass);
    }

    public function persist($entity): void
    {
        $this->documentManager->persist($entity);
    }

    public function persistOneEntity($entity): void
    {
        $this->documentManager->persist($entity);
    }

    /**
     * @throws \Throwable
     * @throws MongoDBException
     */
    public function flush(): void
    {
        $this->documentManager->flush();
    }

    /**
     * @throws MongoDBException
     * @throws \Throwable
     */
    public function persistAndFlush($entity): void
    {
        $this->documentManager->persist($entity);
        $this->documentManager->flush($entity);
    }

    public function remove($entity): void
    {
        $this->documentManager->remove($entity);
    }

    /**
     * @throws MongoDBException
     * @throws \Throwable
     */
    public function removeAndFlush($entity): void
    {
        $this->documentManager->remove($entity);
        $this->documentManager->flush($entity);
    }
}
