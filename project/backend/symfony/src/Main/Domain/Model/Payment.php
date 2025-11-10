<?php
namespace App\Main\Domain\Model;

use App\Shared\Domain\Aggregate\AggregateRoot;
use Ramsey\Uuid\Uuid;
use App\Main\Domain\Model\Interfaces\IEntity;

class Payment extends AggregateRoot implements IEntity
{
    private string $id;
    private string $order_id;
    private string $approve_link;
    private string $type;
    private string $platform;
    private string $status;
    private float $amount;
    private string $currency;
    private string $customer_email;
    private \DateTime $created_at;
    private \DateTime $updated_at;

    public function __construct($id, $order_id, $approve_link,$type, $platform, $amount, $currency, $customer_email, $status, $metadata = [])
    {
      $this->id = $id;
      $this->order_id = $order_id;
      $this->approve_link = $approve_link;
      $this->type = $type;
      $this->platform = $platform;
      $this->amount = $amount;
      $this->currency = $currency;
      $this->customer_email = $customer_email;
      $this->status = $status;
      $this->created_at = new \DateTime();
      $this->updated_at = new \DateTime();
    }

    public static function create(
        string $id,
        string $order_id,
        string $approve_link,
        string $type,
        string $platform,
        float $amount,
        string $currency,
        string $customer_email,
        string $status,
        // array $metadata = []
    ): Payment
    {
        return new self($id, $order_id, $approve_link, $type, $platform, $amount, $currency, $customer_email, $status);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOrderId(): string
    {
        return $this->order_id;
    }

    public function getApprove_link(): string
    {
        return $this->approve_link;
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

    public function updateStatus(string $status): void
    {
        $this->status = $status;
        $this->updated_at = new \DateTime();
    }

    public function updatePlatform(string $platform): void
    {
        $this->platform = $platform;
        $this->updated_at = new \DateTime();
    }

     public function getEntityName(): string
    {
        return self::class;
    }
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'approve_link' => $this->approve_link,
            'platform' => $this->platform,
            'status' => $this->status,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'customer_email' => $this->customer_email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

}
