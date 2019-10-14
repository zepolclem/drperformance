<?php

namespace App\Controller;

use App\Entity\Generation;
use App\Form\GenerationType;
use App\Entity\Model;
use App\Entity\Image;
use App\Repository\ModelRepository;
use App\Repository\GenerationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\FileUploader;
use Symfony\Component\Validator\Constraints\File;

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

        $generation->setModel($model);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            $picture = $form['picture']->getData();

            $model = $generation->getModel();
            $manufacturer = $model->getManufacturer();

            $directory = 'uploads/' . 'pictures/generations/';
            $filesystem = new Filesystem;

            $generation->setSlug($model->getManufacturer()->getName() . ' ' . $model->getName() . ' ' . $generation->getName() . ' ' . $generation->getStartYear() . ' ' . $generation->getEndYear());

            if ($picture) {
                $fileUploader = new FileUploader($directory);
                $pictureName =  $fileUploader->upload($picture, $generation->getslug());
                $generation->setPicture($pictureName);
            }

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

        $oldPictureName = $generation->getPicture();

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form['picture']->getData();

            $model = $generation->getModel();
            $manufacturer = $model->getManufacturer();

            $generation->setSlug($model->getManufacturer()->getName() . ' ' . $model->getName() . ' ' . $generation->getName() . ' ' . $generation->getStartYear() . ' ' . $generation->getEndYear());


            $directory = 'uploads/' . 'pictures/generations/';
            $filesystem = new Filesystem;



            if ($picture) {
                $fileUploader = new FileUploader($directory);
                if ($filesystem->exists($directory . $generation->getPicture())) {
                    $filesystem->remove(['symlink', $directory . $generation->getPicture(), 'activity.log']);
                }
                $pictureName =  $fileUploader->upload($picture, $generation->getslug());
                $generation->setPicture($pictureName);
            } else {
                if ($filesystem->exists($directory . $generation->getPicture())) {
                    // En vrai les ligne dessous sont inutiles car personne ne devrait voir le nom du fichier de la picture mais bon, il s'actualise à chaque modification :) Peu etre que cel apeut servir pour le SEO
                    $extension = pathinfo($generation->getPicture(), PATHINFO_EXTENSION);
                    $generation->setPicture($generation->getSlug() . '_' . uniqid() . '.' . $extension);
                    $filesystem->rename($directory . $oldPictureName, $directory . $generation->getPicture());
                }
            }

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