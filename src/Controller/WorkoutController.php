<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Workout;
use App\Form\WorkoutNoteFormType;
use App\Security\Voter\WorkoutVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkoutController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/client/workout/{id}/note', name: 'workout_note_update', methods: 'POST')]
    public function updateWorkoutNoteAction(Request $request, Workout $workout): Response
    {
        $this->denyAccessUnlessGranted(WorkoutVoter::VIEW, $workout);

        $workoutNoteForm = $this->createForm(WorkoutNoteFormType::class, $workout);

        $workoutNoteForm->handleRequest($request);

        if ($workoutNoteForm->isValid()) {
            $this->entityManager->persist($workout);
            $this->entityManager->flush();

            return $this->json(null, Response::HTTP_ACCEPTED);
        }

        return $this->json($workoutNoteForm->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
