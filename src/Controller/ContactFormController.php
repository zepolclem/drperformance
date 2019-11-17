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
            $formData = $form->getData();
            // Create the Transport
            $transport = (new Swift_SmtpTransport(APP_SMTP_HOST, APP_SMTP_PORT, 'ssl'))
                ->setUsername(APP_SMTP_USER)
                ->setPassword(APP_SMTP_PASSWORD);
            // Create the Mailer using your created Transport
            $mailer = new Swift_Mailer($transport);
            // Create a message
            $body = 'Nouveau message, <p>' . $formData['email'] . ' – ' . $formData['tel'] . ' – ' . $formData['nom'] . ' ' . $formData['prenom'] . '</p> Message : <p>' . $formData['message'] . '</p> 
            <p>Message émis depuis le formulaire de contact <span style="color:red;">www.cappecheloisirs.com</span>.</p>';
            $message = (new Swift_Message('Nouveau message'))
                ->setFrom([$formData['email'] => 'drperformance.fr'])
                ->setTo(['lopez.clmnt@gmail.com'])
                // ->setCc(['RECEPIENT_2_EMAIL_ADDRESS'])
                // ->setBcc(['RECEPIENT_3_EMAIL_ADDRESS'])
                ->setBody($body)
                ->setContentType('text/html')
                ->setReplyTo(array(
                    $formData['email'] => $formData['nom'] . ' ' . $formData['prenom']
                ));
            // Send the message
            if ($mailer->send($message) != 0) {
                $alert['form'] = true;
                return $this->twig->render('Home/index.html.twig', ['alert' => $alert]);
            }
        }

        return new Response($var_return); // Simple réponse car mon formulaire est exécute en Ajax
    }
}