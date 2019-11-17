<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ManufacturerRepository;
use App\Repository\ModelRepository;
use App\Repository\GenerationRepository;
use App\Repository\EngineRepository;
use App\Repository\MapRepository;

use App\Entity\Map;
use App\Entity\Manufacturer;
use App\Entity\Model;
use App\Entity\Generation;
use App\Entity\Engine;

class PageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(MapRepository $mapRepository)
    {
        $lastMaps = $mapRepository->findByLatest();
        return $this->render('page/home.html.twig', [
            'controller_name' => 'PageController',
            'lastMaps' => $lastMaps
        ]);
    }
}