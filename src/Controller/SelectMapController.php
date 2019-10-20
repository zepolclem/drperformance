<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ManufacturerRepository;
use App\Repository\ModelRepository;
use App\Repository\GenerationRepository;
use App\Repository\EngineRepository;

use App\Entity\Manufacturer;
use App\Entity\Model;
use App\Entity\Generation;
use App\Entity\Engine;

class SelectMapController extends AbstractController
{
    /**
     * @Route("/moteur/{slug}", name="engine_selected_show")
     */
    public function showEngine(ManufacturerRepository $manufacturerRepository, Engine $engine)
    {

        $manufacturers =  $manufacturerRepository->findByAllSortedByName();

        $generation = $engine->getGeneration();
        $model = $generation->getModel();
        $manufacturer = $model->getManufacturer();

        return $this->render('select_map/engine.html.twig', [
            'controller_name' => 'PageController',
            'manufacturers' => $manufacturers,
            'manufacturer' => $manufacturer,
            'model' => $model,
            'generation' => $generation,
            'engine' => $engine
        ]);
    }

    /**
     * @Route("/reprogrammations", name="select_manufacturer")
     */
    public function selectManufacturer(ManufacturerRepository $manufacturerRepository)
    {
        $manufacturers =  $manufacturerRepository->findByAllSortedByName();

        return $this->render('select_map/index.html.twig', [
            'controller_name' => 'PageController',
            'manufacturers' => $manufacturers
        ]);
    }

    /**
     * @Route("/reprogrammations/manufacturer/{slug}", name="select_model")
     */
    public function selectModel(ManufacturerRepository $manufacturerRepository, ModelRepository $modelRepository, GenerationRepository $generationRepository, Manufacturer $manufacturer)
    {

        $manufacturers =  $manufacturerRepository->findByAllSortedByName();

        return $this->render('select_map/index.html.twig', [
            'controller_name' => 'PageController',
            'manufacturers' => $manufacturers,
            'manufacturerSelected' => $manufacturer
        ]);
    }

    /**
     * @Route("/reprogrammations/model/{slug}", name="select_generation")
     */
    public function selectGeneration(ManufacturerRepository $manufacturerRepository, ModelRepository $modelRepository, GenerationRepository $generationRepository, Model $model)
    {
        $manufacturers =  $manufacturerRepository->findByAllSortedByName();
        $manufacturer = $model->getManufacturer();

        return $this->render('select_map/index.html.twig', [
            'controller_name' => 'PageController',
            'manufacturers' => $manufacturers,
            'manufacturerSelected' => $manufacturer,
            'modelSelected' => $model
        ]);
    }

    /**
     * @Route("/reprogrammations/generation/{slug}", name="select_engine")
     */
    public function selectEngine(ManufacturerRepository $manufacturerRepository, ModelRepository $modelRepository, GenerationRepository $generationRepository, Generation $generation)
    {

        $manufacturers =  $manufacturerRepository->findByAllSortedByName();
        $model = $generation->getModel();
        $manufacturer = $model->getManufacturer();

        return $this->render('select_map/index.html.twig', [
            'controller_name' => 'PageController',
            'manufacturers' => $manufacturers,
            'manufacturerSelected' => $manufacturer,
            'modelSelected' => $model,
            'generationSelected' => $generation
        ]);
    }
}