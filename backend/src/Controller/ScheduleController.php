<?php

namespace App\Controller;

use App\Repository\AgentRepository;
use App\Repository\DemandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ScheduleController extends AbstractController
{
    private $agentRepository;
    private $demandRepository;

    public function __construct(AgentRepository $agentRepository, DemandRepository $demandRepository)
    {
        $this->agentRepository = $agentRepository;
        $this->demandRepository = $demandRepository;
    }

    #[Route('/schedule', name: 'schedule_generate', methods: ['POST'])]
    public function generate(): JsonResponse
    {
        $demands = $this->demandRepository->findAll();
        $agents = $this->agentRepository->findAll();
        $schedule = [];

        foreach ($demands as $demand) {
            $requiredAgents = ceil($demand->getPredictedCalls() / 10);
            $availableAgents = [];

            foreach ($agents as $agent) {
                $skills = $agent->getSkills();
                $availability = $agent->getAvailability();
                $day = $demand->getDay();
                $hour = sprintf('%02d:00', $demand->getHour());

                if (
                    in_array($demand->getQueue(), $skills) &&
                    isset($availability[$day]) &&
                    in_array($hour, $availability[$day])
                ) {
                    $availableAgents[] = $agent->getName();
                }
            }

            $schedule[] = [
                'day' => $demand->getDay(),
                'hour' => $demand->getHour(),
                'queue' => $demand->getQueue(),
                'assignedAgents' => array_slice($availableAgents, 0, $requiredAgents),
            ];
        }

        return $this->json($schedule);
    }
}
