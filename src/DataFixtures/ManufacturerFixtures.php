<?php

namespace App\DataFixtures;

use App\Entity\Manufacturer;
use App\Entity\Model;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Psr\Log\LoggerInterface;

class ManufacturerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $csv_carMake = fopen('car_make.csv', 'r', __FILE__);
        // $csv = fopen(dirname(__FILE__) . './car_make.csv', 'r');
        $csv_carModel = fopen('car_model.csv', 'r', __FILE__);

        $i = 0;

        $constructeursLogos = scandir('public/uploads/logos/manufacturers/');
        // $constructeursLogos = scandir('/Users/zepol/Sites/drperformance/public/uploads/logos/manufacturers/');


        while (!feof($csv_carMake)) {

            $carMake = fgetcsv($csv_carMake);
            // dump($line);

            $manufacturer = new Manufacturer();
            $manufacturer->setName(str_replace("'", "", strval($carMake[1])));

            foreach ($constructeursLogos as $logo) {

                $name = str_replace('.png', '', $logo); // RECUPERATION NOM  DEPUIS LE FICHIER (LOGOS)
                $manufacturer->setSlug($manufacturer->getName()); // SLUGIFICATION POUR COMPARAISON

                if ($manufacturer->getSlug() == $name) { // COMPARAISON AVEC LE NOM DES LOGOS

                    $manufacturers[$i] = str_replace("'", "", strval($carMake[1])); // PERMET DE GARDER L'ID ORIGINAL DU CONSTRUCTEUR POUR IMPORTER LES MODELES
                    // dump($manufacturer->getSlug());

                    $manufacturer->setCreated(date_create());
                    $manufacturer->setUpdated(date_create());
                    $manufacturer->setLogo($manufacturer->getSlug());
                    $manufacturer->setTypeVehicle("CAR");
                    $this->addReference($manufacturers[$i], $manufacturer);
                    // $manager = $this->getDoctrine()->getManager();
                    $manager->persist($manufacturer);
                    $manager->flush();
                }
            }
            $i++;
        }



        // IMPORTATION DES DES MODELES

        dump($manufacturers);

        // // IMPORTATION DES DES MODELES

        while (!feof($csv_carModel)) {

            $line = fgetcsv($csv_carModel);
            // dump(intval($line[1]));

            foreach ($manufacturers as $key => $manufacturer) {
                if (str_replace("'", "", strval($line[1])) == $key) { // SI ID COMSTRUCTEUR MODEL = ID CONSTRUCTEUR 
                    dump($key . ' ' . $manufacturer . ' ' . str_replace("'", "", strval($line[2])));
                    dump($manufacturers[$key]);
                    $manufacturer = $this->getReference($manufacturers[$key]);
                    $model = new Model;
                    $model->setName(str_replace("'", "", strval($line[2])));
                    $model->setManufacturer($manufacturer);
                    $model->setCreated(date_create());
                    $model->setUpdated(date_create());
                    $model->setSlug($model->getManufacturer()->getName() . ' ' . $model->getName());
                    $manager->persist($model);
                    $manager->flush();
                }
                // dump($key, $manufacturer);
            }
        }
    }

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
}