<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Component;
use App\Entity\Workout;
use App\Form\WorkoutFormType;
use App\Repository\WorkoutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/client', name: 'client')]
    public function indexAction(WorkoutRepository $workoutRepository): Response
    {
        /** @var Client $client */
        $client = $this->getUser();

        return $this->render('client/index.html.twig', [
            'past_workouts' => $workoutRepository->findPastWorkoutForAClient($client),
            'upcoming_workouts' => $workoutRepository->findUpcomingWorkouts($client),
        ]);
    }

    #[Route('/client/workout/create', name: 'client_workout_create')]
    public function createWorkoutAction(Request $request): Response
    {
        $workout = new Workout();
        $component = new Component();
        $workout->getComponents()->add($component);

        return $this->handleWorkoutForm($workout, $request);
    }

    #[Route('/client/workout/edit/{id}', name: 'client_workout_edit')]
    public function editWorkoutAction(Workout $workout, Request $request): Response
    {
        return $this->handleWorkoutForm($workout, $request);
    }

    #[Route('/client/workout/start/{id}', name: 'client_workout_start')]
    public function startWorkoutAction(Workout $workout): Response
    {
        // TODO workflow
        if ($workout->getStatus() === Workout::STATUS_PENDING) {
            $workout->setStatus(Workout::STATUS_STARTED);

            $this->entityManager->persist($workout);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('client_workout_view', [
            'id' => $workout->getId(),
        ]);
    }

    #[Route('/client/workout/view/{id}', name: 'client_workout_view')]
    public function viewWorkoutAction(Workout $workout): Response
    {
        return $this->render('workout/view.html.twig', [
            'workout' => $workout,
        ]);
    }

    /**
     * @param Workout $workout
     * @param Request $request
     * @return RedirectResponse|Response
     */
    protected function handleWorkoutForm(
        Workout $workout,
        Request $request,
    ): Response|RedirectResponse {
        $form = $this->createForm(WorkoutFormType::class, $workout);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Client $client */
            $client = $this->getUser();
            $workout->setClient($client);

            $this->entityManager->persist($workout);
            foreach ($workout->getComponents() as $component) {
                $component->setWorkout($workout);
                $this->entityManager->persist($component);
            }
            $this->entityManager->flush();

            return $this->redirectToRoute('client');
        }

        return $this->render('workout/form.html.twig', [
            'workout_form' => $form->createView(),
        ]);
    }
}
