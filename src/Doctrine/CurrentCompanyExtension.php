<?php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\CompanyAwareInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Class CurrentCompanyExtension
 *
 * Cette classe limite les entités récupérées par un utilisateur à celles appartenant à sa compagnie.
 * Elle s'applique aux collections d'entités (listes) et aux entités individuelles.
 */
class CurrentCompanyExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    /**
     * Constructeur de la classe.
     *
     * @param Security $security Permet d'accéder à l'utilisateur actuellement connecté.
     * @param UserRepository $userRepository Permet de récupérer les informations supplémentaires sur l'utilisateur, en accédant au repository de l'utilisateur.
     */
    public function __construct(private Security $security, private UserRepository $userRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    /**
     * Filtre les entités lors de la récupération d'une collection d'objets.
     *
     * @param QueryBuilder $queryBuilder Le constructeur de requêtes Doctrine.
     * @param QueryNameGeneratorInterface $queryNameGenerator Générateur de noms pour les alias SQL.
     * @param string $resourceClass La classe de l'entité à filtrer.
     * @param Operation|null $operation L'opération en cours.
     * @param array $context Contexte de la requête (non utilisé ici).
     */
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        $this->addCompanyFilter($queryBuilder, $resourceClass);
    }

    /**
     * Filtre les entités lors de la récupération d'un élément unique.
     *
     * @param QueryBuilder $queryBuilder Le constructeur de requêtes Doctrine.
     * @param QueryNameGeneratorInterface $queryNameGenerator Générateur de noms pour les alias SQL.
     * @param string $resourceClass La classe de l'entité à filtrer.
     * @param array $identifiers Les identifiants de l'entité (non utilisés ici).
     * @param Operation|null $operation L'opération en cours.
     * @param array $context Contexte de la requête (non utilisé ici).
     */
    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []): void
    {
        $this->addCompanyFilter($queryBuilder, $resourceClass);
    }

    /**
     * Ajoute une condition WHERE pour limiter les résultats aux entités de la compagnie de l'utilisateur.
     *
     * @param QueryBuilder $queryBuilder Le constructeur de requêtes Doctrine.
     * @param string $resourceClass La classe de l'entité à filtrer.
     */
    private function addCompanyFilter(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (!is_subclass_of($resourceClass, CompanyAwareInterface::class)) {
            return;
        }

        $rootAliases = $queryBuilder->getRootAliases();
        if (empty($rootAliases)) {
            // Si aucun alias racine n'est trouvé, on arrête
            return;
        }

        $rootAlias = $rootAliases[0];
        $user = $this->security->getUser();

        $myUser = $this->userRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        if (!$myUser instanceof User) {
            return;
        }

        $company = $myUser->getCompany();

        if ($company) {
            $queryBuilder->andWhere(sprintf('%s.company = :company', $rootAlias))
                ->setParameter('company', $company);
        }
    }
}
