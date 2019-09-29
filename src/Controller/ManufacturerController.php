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
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;


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

            $logoFile = $form['logo']->getData();

            if ($logoFile) {
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();
                // Move the file to the directory where logos are stored
                try {
                    $logoFile->move(
                        $this->getParameter('logos_manufacturers_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'logoFilename' property to store the PDF file name

                // instead of its contents
                $manufacturer->setlogo($newFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($manufacturer);
            $entityManager->flush();

            return $this->redirectToRoute('manufacturer_index');
        }

        return $this->render('manufacturer/new.html.twig', [
            'manufacturer' => $manufacturer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="manufacturer_show", methods={"GET"})
     */
    public function show(Manufacturer $manufacturer): Response
    {
        return $this->render('manufacturer/show.html.twig', [
            'manufacturer' => $manufacturer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="manufacturer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Manufacturer $manufacturer): Response
    {
        $form = $this->createForm(ManufacturerType::class, $manufacturer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $logoFile = $form['logo']->getData();

            if ($logoFile) {
                $filesystem = new Filesystem();
                if ($filesystem->exists('uploads/logos/manufacturers/' . $manufacturer->getLogo())) {
                    $filesystem->remove(['symlink', 'uploads/logos/manufacturers/' . $manufacturer->getLogo(), 'activity.log']);
                }

                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                // $newFilename = $safeFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();
                $newFilename = $manufacturer->getName() . '-' . uniqid() . '.' . $logoFile->guessExtension();
                // Move the file to the directory where logos are stored
                try {
                    $logoFile->move(
                        $this->getParameter('logos_manufacturers_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'logoFilename' property to store the PDF file name

                // instead of its contents
                $manufacturer->setlogo($newFilename);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('manufacturer_index');
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
            $entityManager->flush();
        }

        return $this->redirectToRoute('manufacturer_index');
    }
}