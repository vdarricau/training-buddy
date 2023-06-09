<?php

namespace App\Controller;

use App\Entity\Component;
use App\Entity\Exercise;
use App\Entity\User;
use App\Entity\Workout;
use App\Form\WorkoutFormType;
use App\Repository\WorkoutRepository;
use App\Security\Voter\WorkoutVoter;
use App\Service\Flasher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Flasher $flasher
    ) {
    }

    #[Route('/client', name: 'client')]
    public function indexAction(WorkoutRepository $workoutRepository): Response
    {
        /** @var User $client */
        $client = $this->getUser();

        return $this->render('client/index.html.twig', [
            'past_workouts' => $workoutRepository->findPastOrFinishedWorkouts($client),
            'upcoming_workouts' => $workoutRepository->findUpcomingWorkouts($client),
        ]);
    }

    #[Route('/client/workout/create', name: 'client_workout_create')]
    public function createWorkoutAction(Request $request): Response
    {
        $workout = new Workout();
        $workout->getComponents()->add(new Component());

        return $this->handleWorkoutForm($workout, $request, true);
    }

    #[Route('/client/workout/edit/{id}', name: 'client_workout_edit')]
    public function editWorkoutAction(Workout $workout, Request $request): Response
    {
        $this->denyAccessUnlessGranted(WorkoutVoter::EDIT, $workout);

        return $this->handleWorkoutForm($workout, $request, false);
    }

    #[Route('/client/workout/copy/{id}', name: 'client_workout_copy')]
    public function copyWorkoutAction(Workout $workout): Response
    {
        $copiedWorkout = clone $workout;
        $copiedWorkout->setStatus(Workout::STATUS_PENDING);

        $this->entityManager->persist($copiedWorkout);

        foreach ($workout->getComponents() as $component) {
            $clonedComponent = clone $component;
            $copiedWorkout->addComponent($clonedComponent);
            $this->entityManager->persist($clonedComponent);
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('client_workout_edit', [
            'id' => $copiedWorkout->getId(),
        ]);
    }

    #[Route('/client/workout/start/{id}', name: 'client_workout_start')]
    public function startWorkoutAction(Workout $workout): Response
    {
        $this->denyAccessUnlessGranted(WorkoutVoter::VIEW, $workout);

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

    #[Route('/client/workout/finish/{id}', name: 'client_workout_finish')]
    public function finishWorkoutAction(Workout $workout): Response
    {
        $this->denyAccessUnlessGranted(WorkoutVoter::VIEW, $workout);

        // TODO workflow
        if (in_array($workout->getStatus(), [Workout::STATUS_STARTED, Workout::STATUS_PENDING], true)) {
            $workout->setStatus(Workout::STATUS_FINISHED);

            $this->entityManager->persist($workout);
            $this->entityManager->flush();
        }

        $this->flasher->addSuccess('Well done finishing your workout! Legend!');

        return $this->redirectToRoute('client');
    }

    #[Route('/client/workout/view/{id}', name: 'client_workout_view')]
    public function viewWorkoutAction(Workout $workout): Response
    {
        $this->denyAccessUnlessGranted(WorkoutVoter::VIEW, $workout);

        return $this->render('workout/view.html.twig', [
            'workout' => $workout,
        ]);
    }

    /**
     * @param Workout $workout
     * @param Request $request
     * @param bool $isNew
     * @return RedirectResponse|Response
     */
    protected function handleWorkoutForm(
        Workout $workout,
        Request $request,
        bool $isNew
    ): Response|RedirectResponse {
        $form = $this->createForm(WorkoutFormType::class, $workout);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $client */
            $client = $this->getUser();
            $workout->setClient($client);

            $this->entityManager->persist($workout);
            foreach ($workout->getComponents() as $component) {
                $component->setWorkout($workout);
                $this->entityManager->persist($component);

                if (null === $component->getExercise()) {
                    $exercise = new Exercise();
                    $exercise->setName($component->getTitle());
                    $exercise->setClient($client);
                    $component->setExercise($exercise);
                    $this->entityManager->persist($exercise);
                }
            }
            $this->entityManager->flush();

            if ($isNew) {
                $this->flasher->addSuccess('Your workout has been created.');
            } else {
                $this->flasher->addSuccess('Your workout has been updated.');
            }

            return $this->redirectToRoute('client_workout_view', [
                'id' => $workout->getId(),
            ]);
        }

        return $this->render('workout/form.html.twig', [
            'workout_form' => $form->createView(),
            'workout' => $workout,
            'is_new' => $isNew,
        ]);
    }
}
