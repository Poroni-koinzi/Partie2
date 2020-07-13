<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Clients;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ClientsController extends AbstractController
{
    public function index(Request $request)
  {
    // On crée un objet Clients
    $client = new Clients();

    // On crée le FormBuilder grâce au service form factory
    $form = $this->get('form.factory')->createBuilder(FormType::class, $client)
      ->add('genre', ChoiceType::class, [
        'choices' => [
          'Homme' => 'Homme',
          'Femme' => 'Femme'
        ],
        'expanded' => true
      ])
      ->add('nom',      TextType::class)
      ->add('prenom',     TextType::class)
      ->add('email',     EmailType::class)
      ->add('numero_de_telephone',   NumberType::class)
      ->add('ville',    TextType::class)
      ->add('situation', ChoiceType::class, [
        'choices' => [
          'Célibataire' => 'Célibataire',
          'Marié' => 'Marié',
          'Divorcé' => 'Divorcé'
        ]
      ])
      ->add('valider', SubmitType::class)
      ->getForm();

      //POST
      if ($request->isMethod('POST')) {
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()) {
          // On enregistre notre objet $advert dans la base de données, par exemple
          $em = $this->getDoctrine()->getManager();
          $em->persist($client);
          $em->flush();

          // On redirige vers la page de visualisation de l'annonce nouvellement créée
          return $this->redirect($request->getUri());
        }
      }

      $clients = $this->getDoctrine()
        ->getRepository(Clients::class)
        ->findAll();
    
    // pour afficher le formulaire 
    return $this->render('clients/index.html.twig', array(
        'form' => $form->createView(),
        'clients' => $clients
      ));

   
    }
 // Modifier les données d'un client
    public function show(Request $request, int $id)
    {
      $client = $this->getDoctrine()
      ->getRepository(Clients::class)
        ->find($id);
        //générer le formulaire et remplir directement
        $form = $this->get('form.factory')->createBuilder(FormType::class, $client)
        ->add('genre', ChoiceType::class, [
          'choices' => [
            'Homme' => 'Homme',
            'Femme' => 'Femme'
          ],
          'expanded' => true,
          'data' => $client->getGenre()
        ])
        ->add('nom',      TextType::class, ['data' => $client->getNom()])
        ->add('prenom',     TextType::class, ['data' => $client->getPrenom()])
        ->add('email',     EmailType::class, ['data' => $client->getEmail()])
        ->add('numero_de_telephone',   NumberType::class, ['data' => $client->getNumeroDeTelephone()])
        ->add('ville',    TextType::class, ['data' => $client->getVille()])
        ->add('situation', ChoiceType::class, [
          'choices' => [
            'Célibataire' => 'Célibataire',
            'Marié' => 'Marié',
            'Divorcé' => 'Divorcé',
          ],
          'data' => $client->getSituation()
        ])
        ->add('Modifier', SubmitType::class)
        ->getForm();

        if ($request->isMethod('POST')) {
          $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()) {
        // modifier les données
          $client->setGenre($form["genre"]->getData());
          $client->setNom($form["nom"]->getData());
          $client->setPrenom($form["prenom"]->getData());
          $client->setEmail($form["email"]->getData());
          $client->setNumeroDeTelephone($form["numero_de_telephone"]->getData());
          $client->setVille($form["ville"]->getData());
          $client->setSituation($form["situation"]->getData());
          $this->getDoctrine()->getManager()->flush();
          return $this->redirectToRoute('index');
        }
        }
    //rediriger sur la page d'accueil
      return $this->render('clients/show.html.twig', array(
        'form' => $form->createView(),
      ));

    }
// Action pour supprimer un client
    public function delete(int $id)
    {
      $client = $this->getDoctrine()
      ->getRepository(Clients::class)
        ->find($id);
      $entitymanager=$this->getDoctrine()->getManager();
      $entitymanager->remove($client);
      $entitymanager->flush();
        return $this->redirectToRoute('index');
  
    }
}
