<?php

namespace App\Controller\Admin;

use App\Entity\Size;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Order;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Logement;
use App\Entity\OrderLine;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\ArticleCrudController;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator){

    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(ArticleCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        //$adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('EcommerceBTS');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Gestion Users');
        yield MenuItem::subMenu('User', 'fa-solid fa-user')->setSubItems([
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-user-edit', User::class),
            MenuItem::linkToCrud('Adresses', 'fas fa-map-marked-alt', Logement::class),
        ]);
        yield MenuItem::section('Gestion des Article');
        yield MenuItem::subMenu('Vente', 'fab fa-shopify')->setSubItems([
            MenuItem::linkToCrud('Articles', 'fa-solid fa-newspaper', Article::class),
            MenuItem::linkToCrud('Image', 'fa-solid fa-image', Image::class),
            MenuItem::linkToCrud('Category', 'fa-solid fa-table-list', Category::class),
            MenuItem::linkToCrud('Size', 'fa-solid fa-table-list', Size::class),
        ]);
        yield MenuItem::section('Gestion des Commandes');
        yield MenuItem::subMenu('Commandes', 'fab fa-shopify')->setSubItems([
            MenuItem::linkToCrud('Order', 'fa-solid fa-newspaper', Order::class),
            MenuItem::linkToCrud('Order Line', 'fa-solid fa-table-list', OrderLine::class),
        ]);
    }
}