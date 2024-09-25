<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Domain\Interfaces\BaseRepositoryInterface;
use App\Shared\Domain\Interfaces\LogInterface;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class BaseDoctrine implements BaseRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
//        protected LogInterface $logger,
    ) {
    }

    public function repository(string $entityClass): EntityRepository
    {
//        $this->logger->startQuery();
        return $this->entityManager->getRepository($entityClass);
    }

    public function connection(): Connection
    {
        return $this->entityManager->getConnection();
    }

    public function persist($entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);
    }

    public function persistOnly($entity): void
    {
        $this->entityManager->persist($entity);
    }

    public function flushOnly(): void
    {
        $this->entityManager->flush();
    }

    public function remove($entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush($entity);
    }

    protected function executeSqlWithParameters(string $sql, array $parameters)
    {
        $connection = $this->connection()->getNativeConnection();
        $statemtent = oci_parse($connection, $sql);
        $this->bindParamn($statemtent, $parameters);
        oci_execute($statemtent);

        return $statemtent;
    }

    protected function createResourceOci(string $sql)
    {
        $connection = $this->connection()->getNativeConnection();
        return oci_parse($connection, $sql);
    }

    protected function createConsultaOciCsv(string $sql,array $parameters)
    {
        $connection = $this->getNativeConnection();
        $statemtent = $this->ociParce($connection, $sql);
        return $this->bindParameters($statemtent, $parameters);
    }

    protected function createConsultaOci(string $sql,array $parameters)
    {
        $connection = $this->getNativeConnection();
        $statemtent = $this->ociParce($connection, $sql);
        $statemtent = $this->bindParameters($statemtent, $parameters);
        return $this->fetch($statemtent);
    }

    protected function createConsultaOciOutDecorate(string $sql)
    {
        $connection = $this->getNativeConnection();
        $statemtent = $this->ociParce($connection, $sql);
        oci_execute($statemtent);
        return $this->fetch($statemtent);
    }


    protected function bindParamn(&$statemtent, array $parameters)
    {
        foreach ($parameters as $parameterKey => $parameterValue) {
            oci_bind_by_name($statemtent, $parameterKey, $parameterValue);
        }

        return $statemtent;
    }

    protected function fetchAll(string $sql, array $parameters): array
    {
        $statement = $this->executeSqlWithParameters($sql, $parameters);
        $array = [];
        oci_fetch_all($statement, $array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

        return $array;
    }

    protected function fetch($resource): array
    {
        $array = [];
        oci_fetch_all($resource, $array, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
        return $array;
    }

    private function bindParameters($resource, array $parametros)
    {
        foreach ($parametros as $key => $value) {
            oci_bind_by_name($resource, $key, $parametros[$key]);
        }
        oci_execute($resource);
        return $resource;
    }

    private function getNativeConnection()
    {
//        $this->logger->startQuery();
        return $this->connection()->getNativeConnection();
    }

    private function ociParce($connection, $sql)
    {
        return oci_parse($connection, $sql);
    }
}
