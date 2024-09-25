<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony;

use App\Shared\Domain\SfFinderInterface;
use Symfony\Component\Finder\Finder;

class SymfonyFinder extends Finder implements SfFinderInterface
{

}
