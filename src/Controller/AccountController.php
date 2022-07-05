<?php

namespace App\Controller;

use App\Entity\Logement;
use App\Form\LogementType;
use App\Repository\OrderRepository;
use App\Repository\LogementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /**
     * Fonction Index retourne ma vue account
     */
    #[Route('/account', name: 'account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    /**
     * Fonction addLogement permet d'afficher et de push en bdd le form Logement
     */
    #[Route('/addLogement', name: 'addLogement')]
    public function addressAdd(Request $request, EntityManagerInterface $em): Response
    {
        // Instencie l'objet Logement
        $logement = new Logement();
        //crée le form
        $form = $this->createForm(LogementType::class, $logement);
        // recup les datas dans ma request
        $form->handleRequest($request);
        // si le form et soumit et valid alors je set mon user et je persit et flush 
        if($form->isSubmitted() && $form->isValid()) {
            $logement->setUser($this->getUser());
            $em->persist($logement);
            $em->flush();
    
            return $this->redirectToRoute('account'); 
        }

        return $this->render('logement/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * fonction logment retourne la liste de logement de l'utilisateur connecter
     */
    #[Route('/viewLogement', name: 'viewLogement')]
    public function logement(): Response
    {
        return $this->render('logement/show.html.twig');
    }

    /**
     * FOnction edit permet d'afficher et de modifier le form logement
     */
    #[Route('/{id}/edit', name: 'editLogement', methods: ['GET', 'POST'])]
    public function edit(Request $request, Logement $logement, LogementRepository $logementRepository): Response
    {
        $form = $this->createForm(LogementType::class, $logement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logementRepository->add($logement, true);

            return $this->redirectToRoute('viewLogement', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('logement/edit.html.twig', [
            'logement' => $logement,
            'form' => $form,
        ]);
    }

     /**
     * FOnction delete permet de supprimer le logement
     */
    #[Route('/{id}/delete', name: 'deleteLogement', methods: ['POST', 'GET'])]
    public function delete(Logement $logement, EntityManagerInterface $em): Response
    {

        // si address et address user et = a this user alors
        if($logement && $logement->getUser() == $this->getUser()) {
            //$em = $this->getDoctrine()->getEntityManager();
            $em->remove($logement);
            $em->flush();
            return $this->redirectToRoute('viewLogement');
        }
        return $this->redirectToRoute('viewLogement');
    }

    /**
     * Fonction order vas recupere dans mon repo orderRepository la fonction findSuccessOrder pour afficher le commande de l'utilisateur connecter
     */
    #[Route('/orderAccount', name: 'orderAccount')]
    public function order(OrderRepository $repo): Response
    {
        $orders = $repo->findSuccessOrder($this->getUser());

        return $this->render('account/order.html.twig', [
            'orders' => $orders,
            
        ]);
    }

    /**
     * @param int $id La valeur de l'id
     * Fonction order vas recupere dans mon repo orderRepository la fonction findOneById pour afficher le detail de la commande choisi de l'utilisateur connecter
     */
    #[Route('/orderAccount/detail/{id}', name: 'orderDetail')]
    public function histoDetail(int $id, OrderRepository $repo): Response
    {
        $order = $repo->findOneById($id);
        if(!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('accountHisto');
        }
        
        return $this->render('account/orderDetail.html.twig', [
            'order' => $order,
        ]);
    }

}
