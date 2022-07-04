<?php

namespace App\Controller;

use App\Entity\Order;
use App\Services\Cart;
use App\Form\OrderType;
use App\Entity\OrderLine;
use App\Repository\ArticleRepository;
use App\Repository\LogementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * Fonction index push en BDD toute les info de la commande
     */
    #[Route('/order', name: 'order')]
    public function index(Request $request, Cart $cart, EntityManagerInterface $em, SessionInterface $session, ArticleRepository $articleRepo, LogementRepository $logementRepo): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);
        $prams = $session->get('cart'); 
        $total = 0;
        
        $order = new Order(); 

        if($form->handleRequest($request)->isSubmitted() && $form->isValid())
        {
            foreach ($prams as $id => $array) {
                foreach ($array as $a => $b){
                    $article = $articleRepo->find($id); 
                    $price = $article->getPrice()/ 100; 
                    $subTotal = $article->getPrice() * $b['quantity'];
                    $total += $subTotal;
                    
                    $orderLine = new OrderLine(); 
                    $orderLine
                    ->setOrders($order)
                    ->setArticle($article)
                    ->setQuantity($b['quantity'])
                    ->setSize($b['size'])
                    ->setPrice($price); 
                    
                    $em->persist($orderLine); 
                }

                $addressId = $request->request->all('order')['address'];
                $address = $logementRepo->find($addressId);
                $date = new \Datetime(); 
                $date->setTimezone(new \DateTimeZone('Europe/Paris'));
                $order
                ->setPriceTotal($total/100)
                ->setDate($date)
                ->setDeliveryAddress($address)
                ->setUser($this->getUser())
                ->setIsPaid(0)
                ;

                $em->persist($order); 
                $em->flush(); 
                $session->set('order_number', $order->getId()); 
            }
            return $this->redirectToRoute('checkout');
        }
       
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFullProduct(),
        ]);
    }

}
