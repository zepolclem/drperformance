<?php

namespace App\Controller;

use App\Entity\Map;
use App\Entity\Engine;

use App\Form\MapType;
use App\Repository\MapRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/map")
 */
class MapController extends AbstractController
{
    /**
     * @Route("/", name="map_index", methods={"GET"})
     */
    public function index(MapRepository $mapRepository): Response
    {
        return $this->render('map/index.html.twig', [
            'maps' => $mapRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{slug}/new", name="map_new", methods={"GET","POST"})
     */
    public function new(Request $request, Engine $engine): Response
    {
        $map = new Map();
        $form = $this->createForm(MapType::class, $map);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $map->setEngine($engine);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($map);
            $entityManager->flush();

            return $this->redirectToRoute('engine_show', ['slug' => $engine->getslug()]);
        }

        return $this->render('map/new.html.twig', [
            'map' => $map,
            'engine' => $engine,
            'generation' => $engine->getGeneration(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="map_show", methods={"GET"})
     */
    public function show(Map $map): Response
    {
        return $this->render('map/show.html.twig', [
            'map' => $map,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="map_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Map $map): Response
    {
        $engine = $map->getEngine();
        $generation = $engine->getGeneration();

        $form = $this->createForm(MapType::class, $map);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('engine_show', ['slug' => $engine->getslug()]);
        }

        return $this->render('map/edit.html.twig', [
            'engine' =>  $engine,
            'generation' => $generation,
            'map' => $map,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="map_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Map $map): Response
    {
        if ($this->isCsrfTokenValid('delete' . $map->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($map);
            $entityManager->flush();
        }

        return $this->redirectToRoute('engine_show', ['slug' => $map->getEngine()->getslug()]);
    }
}