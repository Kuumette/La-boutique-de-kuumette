<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    /**
     * Fonction index affiche tout mes articles, mes categories et la pagination
     */
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepo, CategoryRepository $catRepo, PaginatorInterface $paginator, Request $request): Response
    {
        $articles = $articleRepo->findAll();

        $articles = $paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1) /*page number*/,
            6 /*limit per page*/
        );

        $c = $catRepo->findAll();

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            'category' => $c,
            'pagination' => $articles
        ]);
    }
    /**
     * Fonction descId description des articles
     */
    #[Route('/desc/{name}', name: 'descId')]
    public function descId($name, ArticleRepository $repo): Response
    {
        return $this->render('home/show.html.twig', [
            'article' => $repo->findOneBy([
                'name' => $name
            ]),
            
        ]);
    }

    /**
     * Fonction categoryPosts affiche tout mes articles d'une meme categories et la pagination
     */
    #[Route('/category/{name}', name: 'categoryPosts')]
    public function categoryPosts(ArticleRepository $articleRepo, Category $category = null, CategoryRepository $catRepo, PaginatorInterface $paginator, Request $request): Response
    {
        if ($category == null) {
            return $this->redirectToRoute("app_home");
        }
        $cat = $category->getArticles();
        
        $pagination = $paginator->paginate(
            $cat, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );
        $c = $catRepo->findAll();
        
     
        return $this->render('home/index.html.twig', [
            'articles' => $cat,
            'category' => $c,
            'pagination' => $pagination
            
        ]);
    }   
}
