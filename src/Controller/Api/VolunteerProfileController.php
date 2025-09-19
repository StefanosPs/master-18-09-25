<?php

namespace App\Controller\Api;

use App\Repository\VolunteerProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class VolunteerProfileController extends AbstractController
{
    #[Route('/api/volunteer/profile', name: 'app_api_volunteer_profile')]
    public function index(VolunteerProfileRepository $repository): JsonResponse
    {
        $profiles = $repository->findAll();

        return $this->json($profiles, context: ['groups' => ['profile:read'], AbstractNormalizer::CALLBACKS => []]);
    }
}
