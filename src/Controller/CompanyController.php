<?php
// api/src/State/UserResetPasswordProcessor.php

namespace App\Controller;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\CompanyCreation;
use App\Entity\Company;
use App\Entity\User;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @implements ProcessorInterface<CompanyCreation, Company>
 */
final class CompanyController implements ProcessorInterface
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher, private CompanyRepository $companyRepository, private UserRepository $userRepository)
    {
    }
    /**
     * @param CompanyCreation $data
     *
     * @throws NotFoundHttpException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Company
    {
        if (!$data instanceof CompanyCreation) {
            throw new NotFoundHttpException();
        }

        $entityManager = $this->companyRepository->getEntityManager();
        $entityManager->beginTransaction();

        try {
            $company = new Company();
            $company->setName($data->name);
            $company->setEmail($data->email);

            $this->companyRepository->save($company, true);

            $user = new User();
            $user->setEmail($data->email);
            $user->setFirstname($data->name);
            $user->setLastname($data->name);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $data->password));
            $user->setCompany($company);
            $this->userRepository->save($user, true);

            $entityManager->commit();

            return $company;
        } catch (\Throwable $e) {
            $entityManager->rollback();
            throw $e;
        }
    }
}
