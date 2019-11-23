<?php

namespace App\DataFixtures;

use App\Entity\Generation;
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
        $csv_carGeneration = fopen('car_generation.csv', 'r', __FILE__);


        $i = 0;

        $constructeursLogos = scandir('public/uploads/logos/manufacturers/');
        // $constructeursLogos = scandir('/Users/zepol/Sites/drperformance/public/uploads/logos/manufacturers/');

        $manufacturersRef = [];

        while (!feof($csv_carMake)) {

            $carMake = fgetcsv($csv_carMake);
            // dump($line);

            $manufacturer = new Manufacturer();
            $manufacturer->setName(str_replace("'", "", strval($carMake[1])));


            foreach ($constructeursLogos as $logo) {

                $name = str_replace('.png', '', $logo); // RECUPERATION NOM  DEPUIS LE FICHIER (LOGOS)
                $manufacturer->setSlug($manufacturer->getName()); // SLUGIFICATION POUR COMPARAISON

                if ($manufacturer->getSlug() == $name) { // COMPARAISON AVEC LE NOM DES LOGOS

                    $manufacturersRef[str_replace("'", "", strval($carMake[0]))] = str_replace("'", "", strval($carMake[1])); // PERMET DE GARDER L'ID ORIGINAL DU CONSTRUCTEUR POUR IMPORTER LES MODELES
                    // dump($manufacturer->getSlug());

                    $manufacturer->setCreated(date_create());
                    $manufacturer->setUpdated(date_create());
                    $manufacturer->setLogo($manufacturer->getSlug());
                    $manufacturer->setTypeVehicle("CAR");
                    $this->addReference($manufacturersRef[str_replace("'", "", strval($carMake[0]))], $manufacturer);
                    // $manager = $this->getDoctrine()->getManager();
                    $manager->persist($manufacturer);
                    $manager->flush();
                }
            }
            $i++;
        }

        // IMPORTATION DES DES MODELES

        dump($manufacturersRef);

        $modelsRef = [];


        while (!feof($csv_carModel)) {

            $carModel = fgetcsv($csv_carModel);
            // dump(intval($line[1]));

            foreach ($manufacturersRef as $key => $manufacturer) {
                if (str_replace("'", "", strval($carModel[1])) == $key) { // SI ID COMSTRUCTEUR MODEL = ID CONSTRUCTEUR 
                    // dump($key . ' ' . $manufacturer . ' ' . str_replace("'", "", strval($carModel[2])));
                    $modelsRef[str_replace("'", "", strval($carModel[0]))] = str_replace("'", "", strval($carModel[0])); // PERMET DE GARDER LES MODELS
                    $manufacturer = $this->getReference($manufacturersRef[$key]);
                    $model = new Model;
                    $model->setName(str_replace("'", "", strval($carModel[2])));
                    $model->setManufacturer($manufacturer);
                    $model->setCreated(date_create());
                    $model->setUpdated(date_create());
                    $model->setSlug($model->getManufacturer()->getName() . ' ' . $model->getName());
                    $this->addReference($modelsRef[str_replace("'", "", strval($carModel[0]))], $model);
                    $manager->persist($model);
                    $manager->flush();
                }
                // dump($key, $manufacturer);
            }
        }


        dump($modelsRef);


        while (!feof($csv_carGeneration)) {

            $carGeneration = fgetcsv($csv_carGeneration);
            // dump(intval($line[1]));
            foreach ($modelsRef as $key => $model) {
                if (str_replace("'", "", strval($carGeneration[1])) == $key) { // SI ID COMSTRUCTEUR MODEL = ID CONSTRUCTEUR 
                    // dump($key . ' ' . $model . ' ' . str_replace("'", "", strval($carGeneration[2])));
                    $modelRef = $this->getReference($modelsRef[$key]);
                    $generation = new Generation;
                    $generation->setName(str_replace("'", "", strval($carGeneration[2])));
                    $generation->setModel($modelRef);
                    if (
                        str_replace("'", "", strval($carGeneration[3])) !== ''
                        || str_replace("'", "", strval($carGeneration[3])) !== null
                        || $carGeneration[3] !== NULL // LOL en fait ça sert à rien 
                        || str_replace("'", "", strval($carGeneration[3])) !== 'NULL'
                    ) {
                        $generation->setStartYear(intval(str_replace("'", "", strval($carGeneration[3]))));
                    } else {
                        $generation->setStartYear(0000);
                    }
                    if (
                        str_replace("'", "", strval($carGeneration[4])) !== ''
                        || str_replace("'", "", strval($carGeneration[4])) !== null
                        || $carGeneration[4] !== NULL
                        || str_replace("'", "", strval($carGeneration[4])) !== 'NULL'

                    ) {
                        $generation->setEndYear(intval(str_replace("'", "", strval($carGeneration[4]))));
                    } else {
                        $generation->setEndYear(0000);
                    }
                    $generation->setSlug($generation->getModel()->getManufacturer()->getName() . ' ' . $generation->getModel()->getName() . ' ' . $generation->getName() . ' ' . $generation->getStartYear() . ' ' . $generation->getEndYear());
                    $generation->setCreated(date_create());
                    $generation->setUpdated(date_create());
                    $manager->persist($generation);
                    $manager->flush();
                    dump($generation->getModel()->getManufacturer()->getName() . ' ' . $generation->getModel()->getName() . ' ' . $generation->getName() . ' ' . $generation->getStartYear() . ' ' . $generation->getEndYear());
                }
            }
        }
    }

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
}
