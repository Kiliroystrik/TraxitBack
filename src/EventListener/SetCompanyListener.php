<?php
// src/EventListener/SetCompanyListener.php

namespace App\EventListener;

use App\Entity\CompanyAwareInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class SetCompanyListener
{
    private $security;

    public function __construct(Security $security, private UserRepository $userRepository)
    {
        $this->security = $security;
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof CompanyAwareInterface) {
            return;
        }

        $user = $this->security->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $myUser = $this->userRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        if ($myUser && $myUser->getCompany()) {
            $entity->setCompany($myUser->getCompany());
        } else {
            // Handle the case where the company could not be set, possibly log an error or throw an exception
            throw new \Exception('Company could not be set for the entity.');
        }
    }
}
