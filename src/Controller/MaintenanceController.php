<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaintenanceController extends AbstractController
{
    #[Route('/', name: 'maintenance')]
    public function maintenance(string $instagramLink): Response
    {
        return $this->render('maintenance.html.twig', compact('instagramLink'));
    }
}
