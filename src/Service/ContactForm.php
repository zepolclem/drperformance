<?php

namespace App\Service;

use App\Form\ContactFormType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\FormFactoryInterface;

class ContactForm
{

    private $form;

    private $router;

    private $formFactory;

    public function __construct(UrlGeneratorInterface $router, FormFactoryInterface $formFactory)
    {

        $this->router = $router;

        $this->formFactory = $formFactory;

        $this->form = $this->formFactory->create(
            ContactFormType::class,
            NULL,
            array(
                'attr' =>
                array(
                    'action' => $this->router->generate('send-contact-mail')
                )
            )
        );
    }

    public function getForm()
    {
        return $this->form;
    }
}