<?php

namespace App\DataFixtures;

use App\Factory\AddressFactory;
use App\Factory\BrandFactory;
use App\Factory\ClientFactory;
use App\Factory\CompanyFactory;
use App\Factory\DriverFactory;
use App\Factory\FuelTypeFactory;
use App\Factory\OrderFactory;
use App\Factory\OrderStepFactory;
use App\Factory\StatusFactory;
use App\Factory\UserFactory;
use App\Factory\VehicleFactory;
use App\Factory\VehicleModelFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Dans un premier temps, on crée une seule compagnie
        $company = CompanyFactory::createOne();

        // Puis on crée un seul utilisateur pour cette compagnie
        $user = UserFactory::createOne(
            [
                'company' => $company,
            ]
        );

        // On crée une première adresse pour la compagnie
        $address = AddressFactory::createOne(
            [
                'company' => $company,
            ]
        );

        // On crée les types de fuel pour les vehicules
        // tableau des types de carburant existants
        $fuelTypes = [
            'Essence',
            'Diesel',
            'GPL',
            'Electrique',
            'Hybride',
        ];

        $fuelTypesFixtures = [];

        // pour chaque type de carburant, on crée un nouveau type de carburant
        foreach ($fuelTypes as $fuelType) {
            $fuelFixture = FuelTypeFactory::createOne(
                [
                    'name' => $fuelType,
                ]
            );

            // on stocke le type de carburant dans le tableau des types de carburant existants
            $fuelTypesFixtures[] = $fuelFixture;
        };

        // On crée plusieurs marques de véhicules pour notre compagnie
        $truckBrands = [
            'Volvo', 'Scania', 'Mercedes-Benz', 'MAN', 'DAF', 'Iveco', 'Renault Trucks', 'Freightliner', 'Peterbilt', 'Kenworth', 'Mack', 'Western Star'
        ];

        foreach ($truckBrands as $truckBrand) {
            BrandFactory::createOne(
                [
                    'name' => $truckBrand,
                    'company' => $company,
                ]
            );
        }

        // On crée plusieurs modeles de véhicules pour notre compagnie
        $truckModels = [
            'Volvo', 'Scania', 'Mercedes-Benz', 'MAN', 'DAF', 'Iveco', 'Renault', 'Freightliner', 'Peterbilt', 'Kenworth', 'Mack', 'Western Star'
        ];

        $truckModelsfixtures = [];

        foreach ($truckModels as $truckModel) {
            $modelFixture = VehicleModelFactory::createOne(
                [
                    'name' => $truckModel,
                ]
            );

            $truckModelsfixtures[] = $modelFixture;
        }

        // On créer 50 vehicules
        VehicleFactory::createMany(50, [
            'company' => $company,
        ]);

        // On créer des clients
        ClientFactory::createMany(50, [
            'company' => $company,
        ]);

        // On crée des conducteurs
        DriverFactory::createMany(50, [
            'company' => $company,
        ]);

        // On crée des status de commandes
        $statuses = [
            'Planifié',
            'En cours de livraison',
            'Livré',
            'Annulé',
            'Échoué',
        ];

        foreach ($statuses as $status) {
            StatusFactory::createOne(
                [
                    'name' => $status,
                ]
            );
        }

        // On crée des unités de mesures pour les produits
        $units = [
            'Kilogramme',
            'Litre',
        ];

        // On crée des commandes
        OrderFactory::createMany(50, [
            'company' => $company,
        ]);

        // On crée des détails de commandes
        OrderStepFactory::createMany(50);
    }
}
