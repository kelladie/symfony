<?php

namespace App\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MyCustomerClassExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('defaultImg', [$this, 'defaultImage'])
        ];
    }
    public function defaultImage(string $path): string
    {
        if (strlen(trim($path)) == 0) {
            return 'm43.jpg';
        } else {
            return $path;
        }
    }
}
