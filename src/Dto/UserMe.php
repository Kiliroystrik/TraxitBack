<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;


final class UserMe
{
    public string $email;

    public string $firstname;

    public string $lastname;

    public string $id;
}
