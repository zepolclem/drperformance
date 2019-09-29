<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Manufacturer;
use App\Repository\ManufacturerRepository;
use Doctrine\ORM\Mapping\OrderBy;

class AdministrationController extends AbstractController
{
    /**
     * @Route("/administration/manufacturers", name="admin_manufacturers")
     */
    public function index(ManufacturerRepository $manufacturerManager)
    {
        $manufacturers = $manufacturerManager->findAll();

        return $this->render('administration/manufacturers.html.twig', [
            'manufacturers' => $manufacturers
        ]);
    }
}