<?php

namespace App\Controller;

use App\Entity\Events;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class EventsController extends AbstractController{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/events', name: 'events_index')]
    public function getAll(EventsRepository $eventRepository): Response
    {
    // Fetch all events ordered by creation date (newest first)
    $events = $eventRepository->findBy([], ['creationDate' => 'DESC']);
    
    // Get status color classes for the template
    $statusColors = [
        'UPCOMING' => [
            'bg' => 'bg-blue-100 dark:bg-blue-900',
            'text' => 'text-blue-800 dark:text-blue-300'
        ],
        'ONGOING' => [
            'bg' => 'bg-yellow-100 dark:bg-yellow-900',
            'text' => 'text-yellow-800 dark:text-yellow-300'
        ],
        'COMPLETED' => [
            'bg' => 'bg-green-100 dark:bg-green-900',
            'text' => 'text-green-800 dark:text-green-300'
        ],
        'CANCELLED' => [
            'bg' => 'bg-red-100 dark:bg-red-900',
            'text' => 'text-red-800 dark:text-red-300'
        ],
        'DEFAULT' => [
            'bg' => 'bg-gray-100 dark:bg-gray-900',
            'text' => 'text-gray-800 dark:text-gray-300'
        ]
    ];
    
    return $this->render('events/events.html.twig', [
        'events' => $events,
        'statusColors' => $statusColors,
        
    ]);
    }




    #[Route('/events/{id}', name: 'app_event_get', methods: ['GET'])]
    public function getEvent(Events $event): JsonResponse
    {
        return $this->json([
            'id' => $event->getId(),
            'title' => $event->getTitle(),
            'description' => $event->getDescription(),
            'date' => $event->getDate()->format('Y-m-d'),
            'creationDate' => $event->getCreationDate()->format('Y-m-d H:i:s'),
            'lastModifiedDate' => $event->getLastModifiedDate()->format('Y-m-d H:i:s'),
            'location' => $event->getLocation() ? $event->getLocation()->getId() : null,
            'imagePath' => $event->getImagePath(),
        ]);
    }

    #[Route('/events/{id}/update', name: 'app_event_update', methods: ['PUT'])]
    public function updateEvent(Request $request, Events $event): JsonResponse
    {
        try {
            // Decode JSON request if necessary
            $data = json_decode($request->getContent(), true);

            // Validate title
            $title = $data['title'] ?? $request->request->get('title');

            if (empty($title)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Title is required'
                ], 400);
            }

            $event->setTitle($title);
            $event->setDescription($data['description'] ?? $request->request->get('description'));
            $event->setDate(new \DateTime($data['date'] ?? $request->request->get('date')));
            $event->setLocation($this->entityManager->getRepository(Address::class)->find($data['location'] ?? $request->request->get('location')));
            $event->setImagePath($data['imagePath'] ?? $request->request->get('imagePath'));
            $event->setLastModifiedDate(new \DateTime());

            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Event updated successfully',
                'id' => $event->getId()
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/events/{id}/delete', name: 'app_event_delete', methods: ['DELETE'])]
    public function deleteEvent(Events $event): JsonResponse
    {
        try {
            $this->entityManager->remove($event);
            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Event deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    #[Route('/events/calendar', name: 'app_events_calendar')]
    public function calendar(): Response
    {
        return $this->render('events/calendar.html.twig', [
            'controller_name' => 'EventsController',
        ]);
    }
}
