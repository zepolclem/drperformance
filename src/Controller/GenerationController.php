<?php

namespace App\Controller;

use App\Entity\Generation;
use App\Form\GenerationType;
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
     * @Route("/new", name="generation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $generation = new Generation();
        $form = $this->createForm(GenerationType::class, $generation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($generation);
            $entityManager->flush();

            return $this->redirectToRoute('generation_index');
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
     * @Route("/{id}/edit", name="generation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Generation $generation): Response
    {
        $form = $this->createForm(GenerationType::class, $generation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('generation_index');
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

        return $this->redirectToRoute('generation_index');
    }
}