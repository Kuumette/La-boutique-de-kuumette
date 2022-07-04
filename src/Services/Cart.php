<?php

namespace App\Services;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class Cart {
    // création de ma session
    private $session;
    private $repo;

    public function __construct(RequestStack $requestStack, ArticleRepository $repo) {
        $this->session = $requestStack->getSession();
        $this->repo = $repo;
    }
    // fonction recup les articles du panier
    public function get() {
        return $this->session->get('cart');
    }
    // fonction recup tout mais produit avec la quantité pour l'afficher dans mon panier
    public function getFullProduct() {
        $fullProduct = [];

        if($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                $result = $this->repo->findOneById($id);
                // au cas ou lutilisateur rentre dans l'url un id qui n'existe pas 
                if(!$result) {
                    $this->removeOne($id);
                    continue;
                }
                $fullProduct[] = [
                    'product' => $result,
                    'quantity' => $quantity,
                ];
            }
        }
        return $fullProduct;
    }

    // fonction ajouter un article au panier
    public function add($id, $size) {
        
        $cart = $this->get([]);

        // si $cart[$id] n'est pas vide alors +1
        if(!empty($cart[$id])) {
            
            foreach($cart[$id] as $item)
            {
                if($item['size'] == $size)
                {
                    $key = array_search($item, $cart[$id]);
                    $itemToAdd = $item;
                }
            }

            $cart[$id][$key]['quantity']++;
        }
        // j'ai plus cas inserer ma variable $cart dans ma session cart
        $this->session->set('cart', $cart);
    }
    // fonction suprimer un article du panier
    public function removeOne($id) {
        $cart = $this->get([]);
        unset($cart[$id]);
        return $this->session->set('cart', $cart);
    }
    // fonction suprimer le panier
    public function remove() {
        return $this->session->remove('cart');
    }
    // fonction enlever une quantiter 
    public function decrease($id, $size) {
        $cart = $this->get([]);
        // au cas ou l'utilisateur decremente de 1 alors que le produit n'existe plus
        if(!isset($cart[$id])) {
            return;
        }

        if(!empty($cart[$id])) {
            foreach($cart[$id] as $item)
            {
                if($item['size'] == $size)
                {
                    $key = array_search($item, $cart[$id]);
                    $itemToDecrease = $item;
                }
            }
            $cart[$id][$key]['quantity']--;
        } 
        if($cart[$id][$key]['quantity'] == 0) {
            unset($cart[$id][$key]);
        }
        $this->session->set('cart', $cart);
    }
}