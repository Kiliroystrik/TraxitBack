<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource()]
final class CompanyCreation
{

    #[Assert\NotBlank(message: 'Please enter a name.')]
    public $name;

    #[Assert\Email(message: 'The email {{ value }} is not a valid email.')]
    #[Assert\NotBlank(message: 'Please enter an email.')]
    public string $email;

    #[Assert\PasswordStrength(minScore: 2, message: 'Please enter a very strong password with at least 12 characters, including a mix of upper and lower case letters, numbers, and special characters.')]
    public $password;
}
