<?php

namespace App\Controller\Api;

use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ConferenceController extends AbstractController
{
    #[Route('/api/conference', name: 'app_api_conference')]
    public function index(ConferenceRepository $repository): JsonResponse
    {
        $conferences = $repository->findAll();

        return $this->json($conferences, context: ['groups' => ['conf:read'], 'private_api' => false]);
    }
}
