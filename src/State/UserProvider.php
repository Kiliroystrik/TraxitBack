<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\UserMe;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

class UserProvider implements ProviderInterface
{
    /**
     * @implements ProviderInterface<UserMe>
     */
    public function __construct(private Security $security, private UserRepository $userRepository)
    {
    }
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $userInterface = $this->security->getUser();
        $user = $this->userRepository->findOneBy(['email' => $userInterface->getUserIdentifier()]);
        if (!$user) {
            throw new NotFoundHttpException();
        }

        $userMe = new UserMe();
        $userMe->email = $user->getEmail();
        $userMe->firstname = $user->getFirstname();
        $userMe->lastname = $user->getLastname();
        $userMe->id = $user->getId();
        return $userMe;
    }
}
