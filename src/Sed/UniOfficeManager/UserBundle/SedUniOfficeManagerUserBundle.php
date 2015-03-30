<?php

namespace Sed\UniOfficeManager\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SedUniOfficeManagerUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
