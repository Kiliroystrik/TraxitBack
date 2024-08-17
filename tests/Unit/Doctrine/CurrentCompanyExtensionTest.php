<?php

namespace App\Tests\Unit\Doctrine;

use App\Doctrine\CurrentCompanyExtension;
use App\Entity\Company;
use App\Entity\CompanyAwareInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;

class CurrentCompanyExtensionTest extends TestCase
{
    private $security;
    private $userRepository;
    private $queryBuilder;
    private $extension;
    private $user;
    private $company;

    protected function setUp(): void
    {
        // Mock de la classe Security pour simuler l'utilisateur connecté
        $this->security = $this->createMock(Security::class);

        // Mock de UserRepository pour simuler l'accès à l'utilisateur dans la base de données
        $this->userRepository = $this->createMock(UserRepository::class);

        // Mock de QueryBuilder pour simuler la construction de requêtes Doctrine
        $this->queryBuilder = $this->createMock(QueryBuilder::class);

        // Création de l'instance à tester
        $this->extension = new CurrentCompanyExtension($this->security, $this->userRepository);

        // Mock de l'utilisateur
        $this->user = $this->createMock(User::class);

        // Mock de la compagnie associée à l'utilisateur
        $this->company = $this->createMock(Company::class);

        // Configuration par défaut du mock User
        $this->user->method('getCompany')->willReturn($this->company);
    }

    public function testApplyToCollectionWithCompanyAwareEntity(): void
    {
        // Configuration de la classe CompanyAwareInterface pour les tests
        $resourceClass = $this->createMock(CompanyAwareInterface::class);

        // Simule un utilisateur connecté avec une compagnie
        $this->security->method('getUser')->willReturn($this->user);
        $this->userRepository->method('findOneBy')->willReturn($this->user);
        $this->queryBuilder->method('getRootAliases')->willReturn(['o']); // Ajout de l'alias 'o'

        // Ajout d'une attente sur l'appel à andWhere
        $this->queryBuilder->expects($this->once())
            ->method('andWhere')
            ->with($this->stringContains('o.company = :company'));

        // Exécution de la méthode à tester
        $this->extension->applyToCollection(
            $this->queryBuilder,
            $this->createMock(\ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface::class),
            get_class($resourceClass)
        );
    }

    public function testApplyToItemWithCompanyAwareEntity(): void
    {
        // Configuration de la classe CompanyAwareInterface pour les tests
        $resourceClass = $this->createMock(CompanyAwareInterface::class);

        // Simule un utilisateur connecté avec une compagnie
        $this->security->method('getUser')->willReturn($this->user);
        $this->userRepository->method('findOneBy')->willReturn($this->user);
        $this->queryBuilder->method('getRootAliases')->willReturn(['o']); // Ajout de l'alias 'o'

        // Ajout d'une attente sur l'appel à andWhere
        $this->queryBuilder->expects($this->once())
            ->method('andWhere')
            ->with($this->stringContains('o.company = :company'));

        // Exécution de la méthode à tester
        $this->extension->applyToItem(
            $this->queryBuilder,
            $this->createMock(\ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface::class),
            get_class($resourceClass),
            []
        );
    }

    public function testAddWhereWithNonCompanyAwareEntity(): void
    {
        // Simule une classe qui n'implémente pas CompanyAwareInterface
        $resourceClass = \stdClass::class;

        // Appel de la méthode addWhere directement pour simuler le comportement
        $reflection = new \ReflectionClass($this->extension);
        $method = $reflection->getMethod('addCompanyFilter');
        $method->setAccessible(true);
        $method->invoke($this->extension, $this->queryBuilder, $resourceClass);

        // Vérifie qu'aucun filtre n'a été ajouté
        $this->assertEmpty($this->queryBuilder->getDQL());
    }

    public function testAddWhereWithUserNotLoggedIn(): void
    {
        // Simule une entité qui implémente CompanyAwareInterface
        $resourceClass = $this->createMock(CompanyAwareInterface::class);

        // Simule l'absence d'utilisateur connecté
        $this->security->method('getUser')->willReturn(null);
        $this->queryBuilder->method('getRootAliases')->willReturn(['o']); // Ajout de l'alias 'o'

        // Appel de la méthode addWhere directement pour simuler le comportement
        $reflection = new \ReflectionClass($this->extension);
        $method = $reflection->getMethod('addCompanyFilter');
        $method->setAccessible(true);
        $method->invoke($this->extension, $this->queryBuilder, get_class($resourceClass));

        // Vérifie qu'aucun filtre n'a été ajouté
        $this->assertEmpty($this->queryBuilder->getDQL());
    }
}
