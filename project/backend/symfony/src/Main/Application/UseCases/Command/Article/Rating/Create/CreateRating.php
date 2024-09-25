<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\Article\Rating\Create;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Model\Article;
use App\Main\Domain\Model\Rating;
use App\Main\Domain\Model\User;
use App\Main\Domain\Repository\Interfaces\Rating\IRatingRepository;
use Ramsey\Uuid\Uuid;

readonly class CreateRating
{
    public function __construct(private IRatingRepository $ratingRepository)
    {}

    /**
     * @throws StoreException
     */
    public function __invoke(CreateRatingCommand $command, User $user, Article $article): array
    {
        try {
            $rating = new Rating(
                Uuid::uuid4()->toString(),
                $command->rating(),
                $user,
                $article,
                $command->comment()
            );

            $this->ratingRepository->save($rating);

            return ['rating' => $rating];
        } catch (\Exception $e) {
            throw new StoreException('Error al crear la calificación: ' . $e->getMessage());
        }
    }
}
