<?php

namespace App\Security\Voter;

use App\Entity\CompanyAwareInterface;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CompanyVoter extends Voter
{
    const VIEW = 'VIEW';
    const EDIT = 'EDIT';
    const DELETE = 'DELETE';
    const CREATE = 'CREATE';

    private Security $security;
    private LoggerInterface $logger;

    public function __construct(Security $security, LoggerInterface $logger)
    {
        $this->security = $security;
        $this->logger = $logger;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // Vérifie si l'attribut est supporté
        $supportsAttribute = in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::CREATE]);

        // Si le sujet est un Paginator, on suppose qu'il contient des objets CompanyAwareInterface
        if ($subject instanceof \Traversable) {
            $supportsSubject = true; // Paginator ou autres collections
        } else {
            $supportsSubject = $subject instanceof CompanyAwareInterface;
        }

        return $supportsAttribute && $supportsSubject;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            $this->logger->warning('User is not authenticated');
            return false;
        }

        $this->logger->info('User authenticated', ['user' => $user->getEmail(), 'attribute' => $attribute]);

        if ($subject instanceof \Traversable) {
            foreach ($subject as $entity) {
                if (!$this->canAccess($entity, $user)) {
                    $this->logger->error('Access denied for entity in collection', [
                        'entity_id' => $entity->getId(),
                        'entity_company' => $entity->getCompany()->getId(),
                        'user_company' => $user->getCompany()->getId(),
                    ]);
                    return false; // Bloque l'accès si une entité n'est pas accessible
                }
            }
            return true; // Toutes les entités sont accessibles
        }

        switch ($attribute) {
            case self::VIEW:
            case self::EDIT:
            case self::DELETE:
                return $this->canAccess($subject, $user);
            case self::CREATE:
                // Si l'objet est null, c'est une création, on ne vérifie que les rôles
                return $this->canCreate($user);
        }

        return false;
    }

    private function canAccess(CompanyAwareInterface $entity, User $user): bool
    {
        // Log detailed comparison information
        $canAccess = $user->getCompany() === $entity->getCompany();
        $this->logger->info('Access check', [
            'canAccess' => $canAccess,
            'user_company' => $user->getCompany()->getId(),
            'entity_company' => $entity->getCompany()->getId(),
        ]);
        return $canAccess;
    }

    private function canCreate(User $user): bool
    {
        $canCreate = $this->security->isGranted('ROLE_USER') || $this->security->isGranted('ROLE_ADMIN');
        $this->logger->info('Create check', ['canCreate' => $canCreate]);
        return $canCreate;
    }
}
