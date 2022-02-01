<?php

namespace App\Controller;

use App\Entity\Program;
use App\Repository\ActorRepository;
use App\Repository\ProgramRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/actor', name: 'actor_')]
class ActorController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ActorRepository $actorRepo): Response
    {
        return $this->render('actor/index.html.twig', [
            'actors' => $actorRepo->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, ActorRepository $actorRepo, ProgramRepository $programRepo): Response
    {

        $actor = $actorRepo->findOneBy(['id' => $id]);
        $programs = $actor->getPrograms();

        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
           'programs' => $programs,
        ]);
    }

}
