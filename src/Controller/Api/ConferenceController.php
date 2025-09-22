<?php

namespace App\Controller\Api;

use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use function Symfony\Component\Clock\now;

final class ConferenceController extends AbstractController
{
    #[Route('/api/conference', name: 'app_api_conference')]
    public function index(Request $request, ConferenceRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $conferences = $repository->findAll();
        $response = new JsonResponse();
        $response
            ->setEtag(md5(serialize($conferences)))
            ->setLastModified(now('-5 days'))
            ->setPublic()
            ->setMaxAge(3600)
        ;

        if ($response->isNotModified($request)) {
            return $response;
        }

        $response->setContent($serializer->serialize($conferences, 'json', ['groups' => ['conf:read'], 'private_api' => false]));

        return $response;
    }
}
