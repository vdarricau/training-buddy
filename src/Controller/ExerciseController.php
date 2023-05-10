<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExerciseController extends AbstractController
{
    #[Route('client/exercises', name: 'client_exercises')]
    public function listAction(): Response
    {
        /** @var User $client */
        $client = $this->getUser();

        return $this->render('exercise/list.html.twig', [
            'exercises' => $client->getExercises(),
        ]);
    }

    #[Route('client/exercises/{id}', name: 'client_exercise_history')]
    public function exerciseHistoryAction(Exercise $exercise): Response
    {
        return $this->render('exercise/history.html.twig', [
            'exercise' => $exercise,
        ]);
    }
}
