<?php

namespace App\Helpers;

use App\Entity\User;
use Doctrine\ORM\EntityManager;

class TwoFactorHelper
{
    public function sendCode(User $user): void
    {

        $code = strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 6));
        //send code
        var_dump($code);
        $user->setCode($code);
        $user->setCodeExpires(new \DateTime('+2 hours'));
    }
}