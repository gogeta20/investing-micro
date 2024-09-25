<?php

namespace App\Shared\Infrastructure\Symfony;


use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method string getLocale()
 */
class TranslatorCustom implements TranslateInterfaceCustom
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected RequestStack        $requestStack
    )
    {
    }

    public function translate(?string $string, array $parameters = [], string $domain = null, string $locale = null): string
    {
        if (!$locale) {
            $locale = $this->translator->getLocale();
        }
        return $this->translator->trans($string, $parameters, $domain, $locale);
    }

    public function locale(?bool $capitalize = false): string
    {
        if ($capitalize) {
            return ucfirst($this->translator->getLocale());
        }
        return $this->translator->getLocale();
    }
}
