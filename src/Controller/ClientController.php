<?php

namespace App\Controller;

use App\Entity\Component;
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
        $client = $this->getUser();

        return $this->render('client/index.html.twig', [
            'past_workouts' => $workoutRepository->findPastWorkouts($client),
            'upcoming_workouts' => $workoutRepository->findUpcomingWorkouts($client),
        ]);
    }

    #[Route('/client/workout/create', name: 'client_workout_create')]
    public function createWorkoutAction(Request $request): Response
    {
        $workout = new Workout();
        $component = new Component();
        $workout->getComponents()->add($component);

        return $this->handleWorkoutForm($workout, $request, false);
    }

    #[Route('/client/workout/edit/{id}', name: 'client_workout_edit')]
    public function editWorkoutAction(Workout $workout, Request $request): Response
    {
        $this->denyAccessUnlessGranted(WorkoutVoter::EDIT, $workout);

        return $this->handleWorkoutForm($workout, $request, true);
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
     * @param bool $isUpdate
     * @return RedirectResponse|Response
     */
    protected function handleWorkoutForm(
        Workout $workout,
        Request $request,
        bool $isUpdate
    ): Response|RedirectResponse {
        $form = $this->createForm(WorkoutFormType::class, $workout);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $this->getUser();
            $workout->setClient($client);

            $this->entityManager->persist($workout);
            foreach ($workout->getComponents() as $component) {
                $component->setWorkout($workout);
                $this->entityManager->persist($component);
            }
            $this->entityManager->flush();

            if ($isUpdate) {
                $this->flasher->addSuccess('Your workout has been updated.');
            } else {
                $this->flasher->addSuccess('Your workout has been created.');
            }

            return $this->redirectToRoute('client_workout_view', [
                'id' => $workout->getId(),
            ]);
        }

        return $this->render('workout/form.html.twig', [
            'workout_form' => $form->createView(),
        ]);
    }

    protected function getUser(): User
    {
        // TODO: add client model here, to introduce maybe when we'll have a trainer
        return parent::getUser();
    }
}
