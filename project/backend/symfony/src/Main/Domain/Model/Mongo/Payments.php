<?php

namespace App\Main\Domain\Model\Mongo;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use App\Main\Domain\Model\Interfaces\IEntity;

#[ODM\Document(collection: 'payments')]
class Payments implements IEntity
{
    #[ODM\Id(strategy: 'NONE')]
    private string $id; // UUID generado en el frontend

    #[ODM\Field(type: 'string')]
    private string $order_id; // ID de la orden en la pasarela de pago

    #[ODM\Field(type: 'string')]
    private string $type; // Tipo de pago (one-time, subscription, refund)

    #[ODM\Field(type: 'string')]
    private string $platform; // Pasarela usada (paypal, stripe, mercadopago)

    #[ODM\Field(type: 'float')]
    private float $amount; // Monto del pago

    #[ODM\Field(type: 'string')]
    private string $currency; // Moneda (USD, EUR, etc.)

    #[ODM\Field(type: 'string')]
    private string $customer_email; // Email del cliente

    #[ODM\Field(type: 'string')]
    private string $status; // Estado del pago (CREATED, APPROVED, FAILED, REFUNDED)

    #[ODM\Field(type: 'date')]
    private \DateTime $created_at; // Fecha de creación

    #[ODM\Field(type: 'date')]
    private \DateTime $updated_at; // Última actualización

    #[ODM\Field(type: 'hash')]
    private array $metadata = []; // Información extra (ej. approval_url)

    // Constructor opcional para inicializar fechas
    public function __construct(string $id, string $order_id, string $type, string $platform, float $amount, string $currency, string $customer_email, string $status, array $metadata = [])
    {
        $this->id = $id;
        $this->order_id = $order_id;
        $this->type = $type;
        $this->platform = $platform;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->customer_email = $customer_email;
        $this->status = $status;
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
        $this->metadata = $metadata;
    }

     public static function create(
        string $id,
        string $order_id,
        string $type,
        string $platform,
        float $amount,
        string $currency,
        string $customer_email,
        string $status,
        array $metadata = []
    ): Payments
    {
        return new self($id, $order_id, $type, $platform, $amount, $currency, $customer_email, $status, $metadata);
    }

    // Métodos Getters
    public function getId(): string
    {
        return $this->id;
    }

    public function getOrderId(): string
    {
        return $this->order_id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCustomerEmail(): string
    {
        return $this->customer_email;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    // Método para actualizar estado y fecha de actualización
    public function updateStatus(string $status): void
    {
        $this->status = $status;
        $this->updated_at = new \DateTime();
    }

    // Método para agregar metadata extra
    public function addMetadata(string $key, mixed $value): void
    {
        $this->metadata[$key] = $value;
    }

    public function getEntityName(): string
    {
        return self::class;
    }
}
