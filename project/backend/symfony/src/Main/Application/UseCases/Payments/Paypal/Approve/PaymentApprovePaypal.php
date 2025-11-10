<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Payments\Paypal\Approve;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Model\Interfaces\IBaseRepository;
use App\Main\Domain\Model\Interfaces\IEntity;
use App\Main\Domain\Model\Payment;
use App\Shared\Domain\Bus\Event\EventBus;
use Doctrine\ORM\EntityManagerInterface;
use App\Shared\Infrastructure\BaseRepository;


class PaymentApprovePaypal extends BaseRepository

{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        private EventBus $bus,
         private IBaseRepository $IBaseRepository,
    ) {
        parent::__construct($entityManager);
    }

    /**
     * @throws StoreException
     */
    public function __invoke(PaymentApprovePaypalQuery $query): IEntity | array
    {

      try {;
          /** @var Payment $entityPayment */
          $entityPayment = $this->repository(Payment::class)->findOneBy(['id' => $query->id()]);
          if (!$entityPayment) {
            throw new StoreException('Pago no encontrado para ID: ' . $query->id());
          }
          return $entityPayment->toArray();
        } catch (\Exception $e) {
            throw new StoreException('Error al procesar el pago: ' . $e->getMessage());
        }
    }
}
