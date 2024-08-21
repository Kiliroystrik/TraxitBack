<?php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\CompanyAwareInterface;
use App\Repository\UserRepository;

class CurrentCompanyExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private Security $security;
    private UserRepository $userRepository;

    public function __construct(Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?Operation $operation = null,
        array $context = []
    ): void {
        $this->addCompanyFilter($queryBuilder, $resourceClass);
    }

    public function applyToItem(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        ?Operation $operation = null,
        array $context = []
    ): void {
        $this->addCompanyFilter($queryBuilder, $resourceClass);
    }

    private function addCompanyFilter(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (!is_subclass_of($resourceClass, CompanyAwareInterface::class)) {
            return;
        }

        $securityUser = $this->security->getUser();

        $user = $this->userRepository->findOneBy(['email' => $securityUser->getUserIdentifier()]);

        if (!$user || !$user->getCompany()) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];


        $queryBuilder->andWhere(sprintf('%s.company = :company', $rootAlias))
            ->setParameter('company', $user->getCompany());
    }
}
