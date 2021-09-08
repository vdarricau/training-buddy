<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Entity\Exercise;
use App\Entity\Variation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Training Buddy');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Client', 'fas fa-running', Client::class);
        yield MenuItem::linkToCrud('Exercise', 'fas fa-dumbbell', Exercise::class);
        yield MenuItem::linkToCrud('Variation', 'fas fa-dumbbell', Variation::class);
    }
}
