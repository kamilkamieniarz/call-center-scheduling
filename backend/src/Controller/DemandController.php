<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Repository\DemandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api', name: 'api_')]
class DemandController extends AbstractController
{
    private $entityManager;
    private $demandRepository;

    public function __construct(EntityManagerInterface $entityManager, DemandRepository $demandRepository)
    {
        $this->entityManager = $entityManager;
        $this->demandRepository = $demandRepository;
    }

    #[Route('/demands', name: 'demand_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $demands = $this->demandRepository->findAll();
        $data = [];

        foreach ($demands as $demand) {
            $data[] = [
                'id' => $demand->getId(),
                'queue' => $demand->getQueue(),
                'day' => $demand->getDay(),
                'hour' => $demand->getHour(),
                'predictedCalls' => $demand->getPredictedCalls(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/demands', name: 'demand_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $demand = new Demand();
        $demand->setQueue($data['queue'] ?? '');
        $demand->setDay($data['day'] ?? '');
        $demand->setHour($data['hour'] ?? 0);
        $demand->setPredictedCalls($data['predictedCalls'] ?? 0);

        $this->entityManager->persist($demand);
        $this->entityManager->flush();

        return $this->json([
            'id' => $demand->getId(),
            'queue' => $demand->getQueue(),
            'day' => $demand->getDay(),
            'hour' => $demand->getHour(),
            'predictedCalls' => $demand->getPredictedCalls(),
        ], 201);
    }
}
