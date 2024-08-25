<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Brand;
use App\Entity\Client;
use App\Entity\Company;
use App\Entity\Driver;
use App\Entity\FuelType;
use App\Entity\Order;
use App\Entity\OrderStep;
use App\Entity\Product;
use App\Entity\Status;
use App\Entity\Tour;
use App\Entity\TourStep;
use App\Entity\Unit;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Entity\VehicleModel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private array $cities = [];

    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create('fr_FR');

        $this->loadCities();

        // Créer une compagnie
        $company = new Company();
        $company->setName($faker->company());
        $company->setEmail($faker->companyEmail());
        $company->setPhone($faker->phoneNumber());
        $company->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($company);

        // Créer un utilisateur pour cette compagnie
        $user = new User();
        $user->setCompany($company);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail('borges.mathieu@gmail.com');
        $password = $this->passwordHasher->hashPassword($user, 'password');
        $user->setPassword($password);
        $user->setFirstname($faker->firstName());
        $user->setLastname($faker->lastName());
        $user->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($user);

        // Créer une adresse pour la compagnie
        $address = new Address();
        $address->setCompany($company);
        $address->setStreet($faker->streetAddress());
        $address->setCity($faker->city());
        $address->setZipCode($faker->postcode());
        $address->setStateProvince($faker->country());
        $address->setCountry($faker->country());
        $address->setLatitude($faker->latitude());
        $address->setLongitude($faker->longitude());
        $manager->persist($address);

        //fake 10 addresses
        $addresses = [];
        for ($i = 0; $i < 10; $i++) {
            $address = new Address();
            $address->setCompany($company);
            $address->setStreet($faker->streetAddress());
            $address->setCity($faker->city());
            $address->setZipCode($faker->postcode());
            $address->setStateProvince($faker->country());
            $address->setCountry($faker->country());
            $address->setLatitude($faker->latitude());
            $address->setLongitude($faker->longitude());
            $addresses[] = $address;
            $manager->persist($address);
        }

        // Créer les types de carburant
        $fuelTypes = ['Essence', 'Diesel', 'GPL', 'Electrique', 'Hybride'];
        foreach ($fuelTypes as $fuelTypeName) {
            $fuelType = new FuelType();
            $fuelType->setName($fuelTypeName);
            $manager->persist($fuelType);
        }

        // Créer des marques de véhicules
        $truckBrands = ['Volvo', 'Scania', 'Mercedes-Benz', 'MAN', 'DAF', 'Iveco', 'Renault Trucks', 'Freightliner', 'Peterbilt', 'Kenworth', 'Mack', 'Western Star'];
        // Tableau de marques de véhicules
        $brands = [];
        foreach ($truckBrands as $truckBrandName) {
            $brand = new Brand();
            $brand->setName($truckBrandName);
            $brand->setCompany($company);
            $brands[] = $brand;
            $manager->persist($brand);
        }

        // Créer des modèles de véhicules
        $truckModels = ['Volvo', 'Scania', 'Mercedes-Benz', 'MAN', 'DAF', 'Iveco', 'Renault', 'Freightliner', 'Peterbilt', 'Kenworth', 'Mack', 'Western Star'];
        foreach ($truckModels as $truckModelName) {
            $model = new VehicleModel();
            $model->setName($truckModelName);
            $model->setBrand($brands[array_rand($brands)]);
            $manager->persist($model);
        }

        // Créer des véhicules
        for ($i = 0; $i < 50; $i++) {
            $vehicle = new Vehicle();
            $vehicle->setCompany($company);
            $vehicle->setModel($model); // Utiliser le dernier modèle créé comme exemple
            $vehicle->setRegistrationNumber($faker->regexify('[A-Z]{2}-[0-9]{3}-[A-Z]{2}'));
            $vehicle->setMileage($faker->randomFloat(2, 0, 100000));
            $vehicle->setIsAvailable($faker->boolean());
            $vehicle->setRegisteredAt(new \DateTimeImmutable());
            $manager->persist($vehicle);
        }

        // Créer des clients
        $clients = [];
        for ($i = 0; $i < 50; $i++) {
            $client = new Client();
            $client->setCompany($company);
            $client->setAddress($faker->randomElement($addresses));
            $client->setName($faker->name());
            $client->setEmail($faker->email());
            $client->setPhone($faker->phoneNumber());
            $client->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($client);
            $clients[] = $client;
        }

        // Créer des conducteurs
        for ($i = 0; $i < 50; $i++) {
            $driver = new Driver();
            $driver->setCompany($company);
            $driver->setEmail($faker->email());
            $driver->setRoles(['ROLE_DRIVER']);
            $driver->setFirstname($faker->firstName());
            $driver->setLastname($faker->lastName());
            $driver->setPassword($this->passwordHasher->hashPassword($driver, 'password'));
            $driver->setLicenceNumber($faker->regexify('[A-Z]{2}-[0-9]{6}'));
            $driver->setLicenceExpiration(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('+1 years', '+5 years')));
            $driver->setIsAvailable($faker->boolean());
            $driver->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($driver);
        }

        // Créer des statuts de commandes
        $statuses = ['Planifié', 'En cours de livraison', 'Livré', 'Annulé', 'Échoué'];
        foreach ($statuses as $statusName) {
            $status = new Status();
            $status->setName($statusName);
            $status->setCompany($company);
            $manager->persist($status);
        }

        // Créer des unités de mesure
        $units = ['Kilogramme', 'Litre'];
        foreach ($units as $unitName) {
            $unit = new Unit();
            $unit->setName($unitName);
            $manager->persist($unit);
        }

        // Créer des produits (ajouté car utilisé dans OrderStep)
        $product = new Product();
        $product->setName($faker->word());
        $product->setDescription($faker->sentence());
        $manager->persist($product);

        // Créer des commandes et des tournées
        for ($i = 0; $i < 50; $i++) {
            $order = new Order();
            $order->setCompany($company);
            $order->setClient($clients[array_rand($clients)]);
            $order->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($order);

            $tour = new Tour();
            $tour->setCompany($company);
            $tour->setStartDate(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years', '+1 years')));
            $tour->setEndDate(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('+1 years', '+2 years')));
            $manager->persist($tour);

            // Créer des étapes de commandes
            for ($j = 0; $j < 5; $j++) {
                $orderStep = new OrderStep();
                $orderStep->setOrder($order);
                $orderStep->setAddress($faker->randomElement($addresses));
                $orderStep->setStatus($status); // Utiliser le dernier statut créé comme exemple
                $orderStep->setProduct($product);
                $orderStep->setUnit($unit); // Utiliser la dernière unité créée comme exemple
                $orderStep->setType($faker->word());
                $orderStep->setPosition($j);
                $orderStep->setDescription($faker->sentence());
                $orderStep->setQuantity($faker->randomFloat(2, 1, 100));
                $orderStep->setCreatedAt(new \DateTimeImmutable());
                $orderStep->setScheduledArrival(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('+1 days', '+1 weeks')));
                $orderStep->setScheduledDeparture(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('+1 weeks', '+2 weeks')));
                $manager->persist($orderStep);

                $tourStep = new TourStep();
                $tourStep->setTour($tour);
                $tourStep->setStepNumber($i);
                $tourStep->setOrderStep($orderStep);
                $tourStep->setCreatedAt(new \DateTimeImmutable());
                $tourStep->setActualArrival(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('+1 days', '+1 weeks')));
                $tourStep->setActualDeparture(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('+1 weeks', '+2 weeks')));
                $manager->persist($tourStep);
            }
        }

        $manager->flush();
    }

    private function loadCities(): void
    {
        $filePath = __DIR__ . '/french_cities.json';
        $json = file_get_contents($filePath);
        $this->cities = json_decode($json, true);
    }
}
