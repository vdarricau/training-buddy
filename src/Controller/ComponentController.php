<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Component;
use App\Entity\Workout;
use App\Form\ComponentUpdateType;
use App\Security\Voter\WorkoutVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComponentController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/client/workout/component/{id}', name: 'component_update', methods: 'POST')]
    public function updateComponentAction(Request $request, Component $component): Response
    {
        $workout = $component->getWorkout();
        $this->denyAccessUnlessGranted(WorkoutVoter::EDIT, $workout);

        $componentForm = $this->createForm(ComponentUpdateType::class, $component);

        $componentForm->handleRequest($request);

        if ($componentForm->isValid()) {
            if ($workout->getStatus() === Workout::STATUS_PENDING) {
                $workout->setStatus(Workout::STATUS_STARTED);
                $this->entityManager->persist($workout);
            }

            $this->entityManager->persist($component);
            $this->entityManager->flush();

            return $this->json(null, Response::HTTP_ACCEPTED);
        }

        return $this->json($componentForm->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
