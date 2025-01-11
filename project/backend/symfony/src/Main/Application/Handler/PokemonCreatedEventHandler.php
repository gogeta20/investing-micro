<?php
declare(strict_types=1);
namespace App\Main\Application\Handler;

use App\Main\Domain\Model\Events\PokemonCreateDomainEvent;
use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;
use App\Shared\Infrastructure\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PokemonCreatedEventHandler extends DocumentRepository
{
    public function __construct(
        protected DocumentManager $documentManager,
        protected TranslateInterfaceCustom $translatorCustom,
    )  {
        parent::__construct($this->documentManager);
    }

    /**
     * @throws MongoDBException
     * @throws \Throwable
     */
    public function __invoke(PokemonCreateDomainEvent $event): void
    {
        $data = $event->toPrimitives();
        $this->persist($data);
        $this->flush();
    }
}
