<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Component;
use App\Entity\Workout;
use App\Form\WorkoutFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'client')]
    public function index(): Response
    {
        /** @var Client $client */
        $client = $this->getUser();

        /** @var Workout[] $workouts */
        $workouts = $client->getWorkouts();

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'workouts' => $workouts,
        ]);
    }

    #[Route('/client/create-workout', name: 'client_create_workout')]
    public function createWorkout(Request $request, EntityManagerInterface $entityManager): Response
    {
        $workout = new Workout();
        $component = new Component();
        $workout->getComponents()->add($component);
        $form = $this->createForm(WorkoutFormType::class, $workout);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Client $client */
            $client = $this->getUser();
            $workout->setClient($client);

            $entityManager->persist($workout);
            foreach ($workout->getComponents() as $component) {
                $component->setWorkout($workout);
                $entityManager->persist($component);
            }
            $entityManager->flush();

            return $this->redirectToRoute('client');
        }

        return $this->render('client/create_workout.html.twig', [
            'workout_form' => $form->createView(),
        ]);
    }
}
