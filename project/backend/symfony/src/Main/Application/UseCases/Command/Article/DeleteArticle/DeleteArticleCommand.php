<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\Article\DeleteArticle;

use App\Shared\Domain\Bus\Command\Command;

final class DeleteArticleCommand implements Command
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function create(string $id): self
    {
        return new self($id);
    }

    public function id(): string
    {
        return $this->id;
    }
}
