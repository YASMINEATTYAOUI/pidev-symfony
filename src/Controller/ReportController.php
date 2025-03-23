<?php

namespace App\Controller;

use App\Entity\Report;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;

final class ReportController extends AbstractController{
    
    private EntityManagerInterface $entityManager;
    private ReportRepository $reportRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReportRepository $reportRepository
    ) {
        $this->entityManager = $entityManager;
        $this->reportRepository = $reportRepository;
    }
    
    #[Route('/reports', name: 'reports_index')]
    public function getAll(ReportRepository $reportRepository): Response
    {
        // Fetch all reports ordered by creation date (newest first)
        $reports = $reportRepository->findBy([], ['creationDate' => 'DESC']);
        
        // Get status color classes for the template
        $statusColors = [
            'PENDING' => [
                'bg' => 'bg-blue-100 dark:bg-blue-900',
                'text' => 'text-blue-800 dark:text-blue-300'
            ],
            'CLOSED' => [
                'bg' => 'bg-red-100 dark:bg-red-900',
                'text' => 'text-red-800 dark:text-red-300'
            ],
            'IN_PROGRESS' => [
                'bg' => 'bg-yellow-100 dark:bg-yellow-900',
                'text' => 'text-yellow-800 dark:text-yellow-300'
            ],
            'RESOLVED' => [
                'bg' => 'bg-green-100 dark:bg-green-900',
                'text' => 'text-green-800 dark:text-green-300'
            ],
            'DEFAULT' => [
                'bg' => 'bg-gray-100 dark:bg-gray-900',
                'text' => 'text-gray-800 dark:text-gray-300'
            ]
        ];
        
        // Get report type color classes for the template
        $typeColors = [
            'Security Concern' => [
                'bg' => 'bg-yellow-100 dark:bg-yellow-900',
                'text' => 'text-yellow-800 dark:text-yellow-300'
            ],
            'Environmental' => [ 
            'bg' => 'bg-green-100 dark:bg-green-900',
            'text' => 'text-green-800 dark:text-green-300'
            ],
            'Public Safety' => [  
            'bg' => 'bg-red-100 dark:bg-red-900',
            'text' => 'text-red-800 dark:text-red-300'
            ],
            'Community' => [
            'bg' => 'bg-teal-100 dark:bg-teal-900',
            'text' => 'text-teal-800 dark:text-teal-300'
            ],
            'Infrastructure' => [ 
            'bg' => 'bg-purple-100 dark:bg-purple-900',
            'text' => 'text-purple-800 dark:text-purple-300'
            ],
            'Other' => [
                'bg' => 'bg-gray-100 dark:bg-gray-700',
                'text' => 'text-gray-800 dark:text-gray-300'
            ],
            'DEFAULT' => [
                'bg' => 'bg-gray-100 dark:bg-gray-900',
                'text' => 'text-gray-800 dark:text-gray-300'
            ]
        ];
        
        return $this->render('reports/reports.html.twig', [
            'reports' => $reports,
            'statusColors' => $statusColors,
            'typeColors' => $typeColors,
        ]);
    }

    #[Route('/reports/kanban', name: 'reports_kanban')]
    public function kanban(ReportRepository $reportRepository): Response
    {
        // Get reports grouped by status
        $pendingReports = $reportRepository->findBy(['responseStatus' => 'PENDING']);
        $inProgressReports = $reportRepository->findBy(['responseStatus' => 'IN_PROGRESS']);
        $resolvedReports = $reportRepository->findBy(['responseStatus' => 'RESOLVED']);
        $closedReports = $reportRepository->findBy(['responseStatus' => 'CLOSED']);
        
        return $this->render('reports/kanban.html.twig', [
            'pendingReports' => $pendingReports,
            'inProgressReports' => $inProgressReports,
            'resolvedReports' => $resolvedReports,
            'closedReports' => $closedReports,
        ]);
    }


    
    #[Route('/reports/{id}', name: 'app_report_get', methods: ['GET'])]
    public function getReport(Report $report): JsonResponse
    {
        return $this->json([
            'id' => $report->getId(),
            'title' => $report->getTitle(),
            'description' => $report->getDescription(),
            'reportType' => $report->getReportType(),
            'creationDate' => $report->getCreationDate()->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('/reports/{id}/update', name:'app_report_update', methods:['PUT'])]
    public function updateReport(Request $request, Report $report): JsonResponse
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

        $report->setTitle($title);
        $report->setReportType($data['reportType'] ?? $request->request->get('reportType'));
        $report->setDescription($data['description'] ?? $request->request->get('description'));
        $report->setLastModifiedDate(new \DateTime());

        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Report updated successfully',
            'id' => $report->getId()
        ]);
    } catch (\Exception $e) {
        return $this->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 400);
    }
}


}