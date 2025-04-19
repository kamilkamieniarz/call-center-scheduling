<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Repository\AgentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api', name: 'api_')]
class AgentController extends AbstractController
{
    private $entityManager;
    private $agentRepository;

    public function __construct(EntityManagerInterface $entityManager, AgentRepository 
$agentRepository)
    {
        $this->entityManager = $entityManager;
        $this->agentRepository = $agentRepository;
    }

    #[Route('/agents', name: 'agent_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $agents = $this->agentRepository->findAll();
        $data = [];

        foreach ($agents as $agent) {
            $data[] = [
                'id' => $agent->getId(),
                'name' => $agent->getName(),
                'skills' => $agent->getSkills(),
                'availability' => $agent->getAvailability(),
                'efficiency' => $agent->getEfficiency(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/agents', name: 'agent_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $agent = new Agent();
        $agent->setName($data['name'] ?? '');
        $agent->setSkills($data['skills'] ?? []);
        $agent->setAvailability($data['availability'] ?? []);
        $agent->setEfficiency($data['efficiency'] ?? []);

        $this->entityManager->persist($agent);
        $this->entityManager->flush();

        return $this->json([
            'id' => $agent->getId(),
            'name' => $agent->getName(),
            'skills' => $agent->getSkills(),
            'availability' => $agent->getAvailability(),
            'efficiency' => $agent->getEfficiency(),
        ], 201);
    }
}
