<?php

namespace App\Controller;

use App\Entity\Generation;
use App\Form\GenerationType;
use App\Entity\Model;
use App\Repository\ModelRepository;
use App\Repository\GenerationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/generation")
 */
class GenerationController extends AbstractController
{
    /**
     * @Route("/", name="generation_index", methods={"GET"})
     */
    public function index(GenerationRepository $generationRepository): Response
    {
        return $this->render('generation/index.html.twig', [
            'generations' => $generationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{slug}/new", name="generation_new", methods={"GET","POST"})
     */
    public function new(Request $request, Model $model): Response
    {
        $generation = new Generation();
        $form = $this->createForm(GenerationType::class, $generation);
        $form->handleRequest($request);


        // var_dump($model->getName());
        // die;


        if ($form->isSubmitted() && $form->isValid()) {

            // $logo = $form['logo']->getData();

            // if ($logo) {
            //     $fileUploader = new FileUploader('uploads/logos/manufacturers');
            //     $logoName = $fileUploader->upload($logo);
            //     $manufacturer->setlogo($logoName);
            // }
            $generation->setSlug($model->getManufacturer()->getName() . ' ' . $model->getName() . ' ' . $generation->getName() . ' ' . $generation->getStartYear() . ' ' . $generation->getEndYear());
            $model->addGeneration($generation);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($generation);
            $entityManager->flush();

            return $this->redirectToRoute('manufacturer_show', ['slug' => $model->getManufacturer()->getSlug()]);
        }

        return $this->render('generation/new.html.twig', [
            'generation' => $generation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="generation_show", methods={"GET"})
     */
    public function show(Generation $generation): Response
    {
        return $this->render('generation/show.html.twig', [
            'generation' => $generation,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="generation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Generation $generation): Response
    {
        $form = $this->createForm(GenerationType::class, $generation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('manufacturer_show', ['slug' => $generation->getModel()->getManufacturer()->getSlug()]);
        }

        return $this->render('generation/edit.html.twig', [
            'generation' => $generation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="generation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Generation $generation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $generation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($generation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('manufacturer_show', ['slug' => $generation->getModel()->getManufacturer()->getSlug()]);
    }
}