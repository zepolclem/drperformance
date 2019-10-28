<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\ContactForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactFormController extends Controller
{
    /**
     * @Route("/contact/form", name="send-contact-mail")
     * @Method({"POST"})
     */
    public function add(ContactForm $ContactForm, Request $request)
    {
        $form = $ContactForm->getForm();

        $form->handleRequest($request);

        $var_return = 0;

        if ($form->isSubmitted() && $form->isValid()) {
            // traitement du formulaire
            //....
        }

        return new Response($var_return); // Simple réponse car mon formulaire est exécute en Ajax
    }
}