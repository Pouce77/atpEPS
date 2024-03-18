<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(ContactType $form, Request $request, MailerInterface $mailer): Response
    {
        $form=$this->createForm(ContactType::class);
        $form->handleRequest($request);
       

        if ($form->isSubmitted()&&$form->isValid())
        {
            $email = (new TemplatedEmail())
            ->from('julienkunze@free.fr')
            ->to('julienkunze0@gmail.com')
            ->subject('Message depuis atpEPS')
            ->text($request->get('message'))
            ->htmlTemplate('email/newContact.html.twig')
            ->context([
                'message' => $request->request->all('contact')['message'],
                'emailUser' => $request->request->all('contact')['email']
            ]);

            try {
                $mailer->send($email);
                $this->addFlash('success','Votre message a été envoyé avec succés !');
                return $this->redirectToRoute('app_contact',['form'=>$form]);
             } catch (TransportExceptionInterface $e) {
                // some error prevented the email sending; display an
                // error message or try to resend the message
                $this->addFlash('danger',"Une erreur s'est produite lors de l'envoi de votre message !");            
            }        
        }

        return $this->render('home/contact.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/mentions', name: 'app_home_mentions')]
    public function mentions(): Response
    {
        return $this->render('home/mentions.html.twig');
    }

    #[Route('/politiques-de-confidentialite', name: 'app_home_politiques')]
    public function politiques(): Response
    {
        return $this->render('home/politiques.html.twig');
    }
}
