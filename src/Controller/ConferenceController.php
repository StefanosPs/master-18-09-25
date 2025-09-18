<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Search\ConferenceSearchInterface;
use App\Search\DatabaseConferenceSearch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConferenceController extends AbstractController
{
    #[Route('/conference/new', name: 'app_conference_new', methods: ['GET', 'POST'])]
    public function newConference(Request $request, EntityManagerInterface $manager): Response
    {
        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($conference);
            $manager->flush();

            return $this->redirectToRoute('app_conference_show', ['id' => $conference->getId()]);
        }

        return $this->render('conference/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/conference', name: 'app_conference_list', methods: ['GET'])]
    public function list(Request $request, DatabaseConferenceSearch $search): Response
    {
        return $this->render('conference/list.html.twig', [
            'conferences' => $search->search($request->query->get('name')),
        ]);
    }

    #[Route('/conference/search', name: 'app_conference_search', methods: ['GET'])]
    #[Template('conference/list.html.twig')]
    public function search(Request $request, ConferenceSearchInterface $search): array
    {
        return ['conferences' => $search->search($request->query->get('name'))];
    }

    #[Route('/conference/{id}', name: 'app_conference_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Conference $conference): Response
    {
        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
        ]);
    }
}
