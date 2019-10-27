<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class DefaultVolter implements VolterInterface{
    public function vote(TokenInterface $token,$subject,array $attributes){
        return false;
    }
}