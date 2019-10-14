<?php

namespace App\Controller;

use App\Entity\Manufacturer;
use App\Form\ManufacturerType;
use App\Repository\ManufacturerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\FileUploader;



/**
 * @Route("/manufacturer")
 * @IsGranted("ROLE_ADMIN")
 */
class ManufacturerController extends AbstractController
{
    /**
     * @Route("/", name="manufacturer_index", methods={"GET"})
     */
    public function index(ManufacturerRepository $manufacturerRepository): Response
    {
        return $this->render('manufacturer/index.html.twig', [
            'manufacturers' => $manufacturerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="manufacturer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $manufacturer = new Manufacturer();
        $form = $this->createForm(ManufacturerType::class, $manufacturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $logo = $form['logo']->getData();

            if ($logo) {
                $fileUploader = new FileUploader('uploads/logos/manufacturers');
                $logoName =  $fileUploader->upload($logo, $manufacturer->getslug());
                $manufacturer->setlogo($logoName);
            }

            $manufacturer->setSlug($manufacturer->getName());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($manufacturer);
            $entityManager->flush();

            return $this->redirectToRoute('manufacturer_show', ['slug' => $manufacturer->getslug()]);
        }

        return $this->render('manufacturer/new.html.twig', [
            'manufacturer' => $manufacturer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="manufacturer_show", methods={"GET"})
     */
    public function show(Manufacturer $manufacturer): Response
    {
        return $this->render('manufacturer/show.html.twig', [
            'manufacturer' => $manufacturer,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="manufacturer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Manufacturer $manufacturer): Response
    {
        $form = $this->createForm(ManufacturerType::class, $manufacturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manufacturer->setSlug($manufacturer->getName());
            $logo = $form['logo']->getData();

            if ($logo) {
                $fileUploader = new FileUploader('uploads/logos/manufacturers');
                $filesystem = new Filesystem;
                if ($filesystem->exists('uploads/logos/manufacturers/' . $manufacturer->getLogo())) {
                    $filesystem->remove(['symlink', 'uploads/logos/manufacturers/' . $manufacturer->getLogo(), 'activity.log']);
                }
                $logoName =  $fileUploader->upload($logo, $manufacturer->getslug());
                $manufacturer->setlogo($logoName);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('manufacturer_show', ['slug' => $manufacturer->getslug()]);
        }

        return $this->render('manufacturer/edit.html.twig', [
            'manufacturer' => $manufacturer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="manufacturer_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Manufacturer $manufacturer): Response
    {
        if ($this->isCsrfTokenValid('delete' . $manufacturer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($manufacturer);

            $filesystem = new Filesystem();
            if ($filesystem->exists('uploads/logos/manufacturers/' . $manufacturer->getLogo())) {
                $filesystem->remove(['symlink', 'uploads/logos/manufacturers/' . $manufacturer->getLogo(), 'activity.log']);
            }

            $entityManager->flush();
        }

        return $this->redirectToRoute('manufacturer_index');
    }
}