<?php
/**
 * Created by PhpStorm.
 * User: inoub
 * Date: 07/04/2019
 * Time: 15:27
 */

namespace App\EventListener;


use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        $data['id'] = $user->getId();

        $event->setData($data);
    }
}