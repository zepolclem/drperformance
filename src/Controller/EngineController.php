<?php

namespace App\Controller;

use App\Entity\Engine;
use App\Entity\Generation;
use App\Form\EngineType;
use App\Repository\EngineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/engine")
 */
class EngineController extends AbstractController
{
    /**
     * @Route("/", name="engine_index", methods={"GET"})
     */
    public function index(EngineRepository $engineRepository): Response
    {
        return $this->render('engine/index.html.twig', [
            'engines' => $engineRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{slug}/new", name="engine_new", methods={"GET","POST"})
     */
    public function new(Request $request, Generation $generation): Response
    {
        $engine = new Engine();
        $engine->setGeneration($generation);
        $form = $this->createForm(EngineType::class, $engine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $engine->setGeneration($generation);
            $engine->setSlug($engine->getGeneration()->getModel()->getManufacturer()->getName() . ' ' . $engine->getGeneration()->getModel()->getName() . ' ' . $engine->getGeneration()->getName() . ' ' . $engine->getGeneration()->getStartYear() . ' ' . $engine->getGeneration()->getEndYear() . ' ' . $engine->getName() . ' ' . $engine->getPower());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($engine);
            $entityManager->flush();

            return $this->redirectToRoute('manufacturer_show', ['slug' => $generation->getModel()->getManufacturer()->getSlug()]);
        }

        return $this->render('engine/new.html.twig', [
            'engine' => $engine,
            'generation' => $engine->getGeneration(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="engine_show", methods={"GET"})
     */
    public function show(Engine $engine): Response
    {
        return $this->render('engine/show.html.twig', [
            'engine' => $engine,
            'generation' => $generation = $engine->getGeneration(),
            'model' => $model = $generation->getModel(),
            'manufacturer' => $model->getManufacturer()
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="engine_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Engine $engine): Response
    {
        $form = $this->createForm(EngineType::class, $engine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $engine->setSlug($engine->getGeneration()->getModel()->getManufacturer()->getName() . ' ' . $engine->getGeneration()->getModel()->getName() . ' ' . $engine->getGeneration()->getName() . ' ' . $engine->getGeneration()->getStartYear() . ' ' . $engine->getGeneration()->getEndYear() . ' ' . $engine->getName() . ' ' . $engine->getPower());

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('engine_show', ['slug' => $engine->getSlug()]);
        }

        return $this->render('engine/edit.html.twig', [
            'engine' => $engine,
            'generation' => $engine->getGeneration(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="engine_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Engine $engine): Response
    {
        if ($this->isCsrfTokenValid('delete' . $engine->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($engine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('manufacturer_show', ['slug' => $engine->getGeneration()->getModel()->getManufacturer()->getSlug()]);
    }
}