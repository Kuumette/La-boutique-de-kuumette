<?php

namespace App\Controller;

use App\Services\Cart;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * fonction index recuper de la session les données de l'article
     */
    #[Route('/cart', name: 'cart')]
    public function index(SessionInterface $session, ArticleRepository $articleRepo): Response
    {
        $cart = $session->get('cart', []);
        $cartWithData = [];
        foreach ($cart as $idProduct => $cartProductsBySize) {
            foreach ($cartProductsBySize as $cartProductBySize ) {
                $cartWithData[] = [
                    'article' => $articleRepo->find($idProduct),
                    'quantity' => $cartProductBySize['quantity'],
                    'size' => $cartProductBySize['size']
                ];
                
            }
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cartWithData,
        ]);
    }

    /**
     * @param int $idProduct id du produit
     * @param int $quantity valeur de la quantiter 
     * @param int $size valeur de la taille
     */
    #[Route('/api/cart/add/{idProduct}/{quantity}/{size}', name: 'cartAdd')]
    public function cartAdd(SessionInterface $session, int $idProduct, Request $request, int $quantity, int $size): Response
    {
        $idProduct = (string) $idProduct;
        $quantity = (int) $quantity;
        $size = (int) $size;
        $cart = $session->get('cart', []);
   
        /**
         * Si une clé existe dans le tableau et quel et pas vide
         */
        if(array_key_exists($idProduct, $cart) && !empty($cart[$idProduct]))
        {
            /**
             * Alors pour chaque cart 
             */
            foreach($cart[$idProduct] as $cartProductsBySize) 
            {
                /**
                 * Si cartProductsBySize[size] et null et cartProductsBySize[size] et egal a la taile 
                 */
                if(isset($cartProductsBySize['size']) && $cartProductsBySize['size'] == $size )
                {
                    /**
                     * Alors je modifier cartProductsBySize[quantity] 
                     */
                    $cartProductsBySize['quantity'] += $quantity;
                }
                else 
                {
                    /**
                     * Sinon je push dans un tableau ma taille et ma quantité
                     */
                    $cart[$idProduct][] = ['size' => $size, 'quantity' => $quantity];
                }
            }
        }
        else
        {
            /**
             * Sinon je push dans un tableau ma taille et ma quantité
             */
            $cart[$idProduct] = [];
            $cart[$idProduct][] = ['size' => $size, 'quantity' => $quantity];
        }

        
        $session->set('cart', $cart);

        return $this->redirectToRoute("cart");
    }

    /**
     * @param int $id id du produit
     * @param int $size valeur de la taille
     * Fonction addCart qui fais appelle a mon service Cart
     */
    #[Route('/cart/addCart/{id}/{size}', name: 'addCart')]
    public function addCart(Cart $cart, int $id, int $size): Response 
    {
        $cart->add($id, $size);
        return $this->redirectToRoute("cart");
    }

    /**
     * @param int $id id du produit
     * Fonction removeOne qui fais appelle a mon service Cart
     */
    #[Route('/cart/remove/{id}', name: 'CartRemoveOne')]
    public function removeOne(Cart $cart, int $id): Response {
        $cart->removeOne($id);
        return $this->redirectToRoute("cart");
    }

    /**
     * Fonction remove qui fais appelle a mon service Cart
     */
    #[Route('/cart/remove', name: 'CartRemove')]
    public function remove(Cart $cart): Response {
        $cart->remove();
        return $this->redirectToRoute("cart");
    }

    /**
     * @param int $id id du produit
     * @param int $size valeur de la taille
     * Fonction decrease qui fais appelle a mon service Cart
     */
    #[Route('/cart/decrease/{id}/{size}', name: 'decrease')]
    public function decrease(Cart $cart, int $id, int $size): Response {
        $cart->decrease($id, $size);
        return $this->redirectToRoute("cart");
    }
}
