<?php

namespace App\Controller;

use App\Entity\Model;
use App\Entity\Manufacturer;
use App\Form\ModelType;
use App\Repository\ManufacturerRepository;
use App\Repository\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/model")
 * @IsGranted("ROLE_ADMIN")
 */
class ModelController extends AbstractController
{
    /**
     * @Route("/", name="model_index", methods={"GET"})
     */
    public function index(ModelRepository $modelRepository): Response
    {
        return $this->render('model/index.html.twig', [
            'models' => $modelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{slug}", name="model_new", methods={"GET","POST"})
     */
    public function new(Request $request, Manufacturer $manufacturer, ManufacturerRepository $manufacturerRepository): Response
    {
        $model = new Model();

        $manufacturers = $manufacturerRepository->findByAllSortedByName();

        $form = $this->createForm(ModelType::class, $model, [
            'manufacturers' => $manufacturers,
            'manufacturer' => $manufacturer
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $logo = $form['logo']->getData();

            // if ($logo) {
            //     $fileUploader = new FileUploader('uploads/logos/manufacturers');
            //     $logoName = $fileUploader->upload($logo);
            //     $manufacturer->setlogo($logoName);
            // }
            $model->setCreated(date_create());
            $model->setUpdated(date_create());
            $model->setSlug($model->getManufacturer()->getName() . ' ' . $model->getName());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($model);
            $entityManager->flush();

            return $this->redirectToRoute('manufacturer_show', ['slug' => $manufacturer->getSlug()]);
        }

        return $this->render('model/new.html.twig', [
            'manufacturer' => $manufacturer,

            'model' => $model,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="model_show", methods={"GET"})
     */
    public function show(Model $model): Response
    {
        return $this->render('model/show.html.twig', [
            'model' => $model,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="model_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Model $model, ManufacturerRepository $manufacturerRepository): Response
    {
        $manufacturers = $manufacturerRepository->findByAllSortedByName();

        $form = $this->createForm(ModelType::class, $model, [
            'manufacturers' => $manufacturers,
            'manufacturer' => $model->getManufacturer()
        ]);
        $form->handleRequest($request);

        $manufacturers = $manufacturerRepository->findByAllSortedByName();


        if ($form->isSubmitted() && $form->isValid()) {

            // $logo = $form['logo']->getData();

            // if ($logo) {
            //     $fileUploader = new FileUploader('uploads/logos/manufacturers');
            //     $logoName = $fileUploader->upload($logo);
            //     $manufacturer->setlogo($logoName);
            // }

            $model->setSlug($model->getManufacturer()->getName() . ' ' . $model->getName());
            $model->setUpdated(date_create());
            $this->getDoctrine()->getManager()->flush();

            // return $this->redirectToRoute('model_index');
            return $this->redirectToRoute('manufacturer_show', ['slug' => $model->getManufacturer()->getSlug()]);
        }

        return $this->render('model/edit.html.twig', [
            'model' => $model,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="model_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Model $model): Response
    {
        if ($this->isCsrfTokenValid('delete' . $model->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($model);
            $entityManager->flush();
        }

        return $this->redirectToRoute('manufacturer_show', ['slug' => $model->getManufacturer()->getSlug()]);
    }
}