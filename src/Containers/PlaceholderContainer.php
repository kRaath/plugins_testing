<?php

namespace Placeholder\Containers;

use Plenty\Plugin\Templates\Twig;
    
class PlaceholderContainer
{
    public function call(Twig $twig):string
    {
        return $twig->render('Placeholder::content.Placeholder');
    }
}