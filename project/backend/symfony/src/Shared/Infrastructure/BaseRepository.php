<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Domain\Interfaces\LogInterface;
use App\Shared\Domain\IRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class BaseRepository implements IRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
//        protected LogInterface                  $logger,
    )
    {
    }

    public function repository(string $entityClass): EntityRepository
    {
//        $this->logger->startQuery();
        return $this->entityManager->getRepository($entityClass);
    }

    private function prepareEntity($entity)
    {
        $now = new \DateTime();
        $nowString = $now->format('Y-m-d H:i:s');
        if (property_exists($entity, 'created') && is_null($entity->getCreated())) {
            $entity->setCreated($nowString);
        }
        if (property_exists($entity, 'modified')) {
            $entity->setModified($nowString);
        }

        return $entity;
    }

    public function persist($entity): void
    {
//        $this->logger->startQuery();
        $entity = $this->prepareEntity($entity);
        $this->entityManager->persist($entity);
    }

    public function flush(): void
    {
//        $this->logger->startQuery();
        $this->entityManager->flush();
    }

    public function persistAndFlush($entity): void
    {
//        $this->logger->startQuery();
        $entity = $this->prepareEntity($entity);
        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);
    }

    public function remove($entity): void
    {
//        $this->logger->startQuery();
        $this->entityManager->remove($entity);
    }

    public function removeAndFlush($entity): void
    {
//        $this->logger->startQuery();
        $this->entityManager->remove($entity);
        $this->entityManager->flush($entity);
    }

    public function connection(): Connection
    {
        return $this->entityManager->getConnection();
    }

    /**
     * agrega condicion al array si el valor no esta vacio
     * @param array $conditionsArray Array de condiciones
     * @param string $field Campo [y operador]
     * @param string $value Valor
     * @param string $key
     * @return array
     */
    public function addIfNotEmpty($conditionsArray, $field, $value, $key = '')
    {
        $val = $this->getValue($value, $key);
        if (!empty($val)) {
            $conditionsArray[$field] = $val;
        }
        return $conditionsArray;
    }

    /**
     * Obtiene el valor de un parámetro
     * @param mixed $array Array o valor
     * @param string $key Clave dentro del array
     * @return Valor
     */
    public function getValue($array, $key = null)
    {
        if (empty($key)) {
            return $array;
        } else {
            return empty($array[$key]) ? '' : $array[$key];
        }
    }

    public static function escaparParametros($parametro, $full_control = false, $eliminarEspacios = true)
    {
        $valorRetorno = str_replace("'", "", $parametro);
        if ($full_control) {
            $parametro = str_replace("\"", "", $parametro);
            if ($eliminarEspacios) {
                $parametro = str_replace(" ", "", $parametro);
            }
            $valorRetorno = addslashes($parametro);
            $valorRetorno = strip_tags($valorRetorno);
            $valorRetorno = htmlspecialchars($valorRetorno);
        }

        return $valorRetorno;
    }

    /**
     * Divide el array de valores de la condicion IN / NOT IN en condiciones de maximo 1000 valores
     * para evitar ORA-01795: maximum number of expressions in a list is 1000 error
     */
    protected function andWhereIn(QueryBuilder $qb, string $column, array $values, bool $in = true): QueryBuilder
    {
        if (count($values) > 1000) {
            $chunks = array_chunk($values, 10);

            $expresionsIn = [];
            foreach ($chunks as $ch) {
                if ($in) {
                    $expresionsIn[] = $qb->expr()->in($column, $ch);
                } else {
                    $expresionsIn[] = $qb->expr()->notIn($column, $ch);
                }
            }
            $orX = $qb->expr()->orX();
            $orX->addMultiple($expresionsIn);
            return $qb->andWhere($orX);
        }
        if ($in) {
            $qb = $qb->andWhere(
                $qb->expr()->in($column, $values)
            );
        } else {
            $qb = $qb->andWhere(
                $qb->expr()->notIn($column, $values)
            );
        }
        return $qb;
    }

}
