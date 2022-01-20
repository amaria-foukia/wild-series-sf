<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\CategoryType;
use App\Form\ProgramType;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route("/program", name:"program_")]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepo): Response
    {

        return $this->render('program/index.html.twig', parameters: [
                'programs' => $programRepo->findAll()
        ]
        );
    }

    #[Route("/new", name:"new")]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        // Create a new Category Object
        $program = new Program();

        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);

        // Get data from HTTP request
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data

            // Persist Program Object
            $entityManager->persist($program);

            // Flush the persisted object
            $entityManager->flush();

            $this->addFlash(
                'success',
                "Le programme a été ajouté avec succès !"
            );

            // Finally redirect to programs list
            return $this->redirectToRoute('program_index');
        }

        // Render the form
        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    #[Route('/{id<^[0-9]+$>}', name: 'show', methods: ['GET'])]
    public function show(int $id, ProgramRepository $programRepo, SeasonRepository $seasonRepo): Response
    {
        $program = $programRepo->findOneBy(['id' => $id]);
        $seasons = $seasonRepo->findAll();

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', parameters: [
            'program' => $program,
            'seasons' =>  $seasons,
        ]);
    }

    #[Route('/{id<^[0-9]+$>}/season/{seasonId<^[0-9]+$>}', name: 'season_show', methods: ['GET'])]
    #[ParamConverter('season', options: ['mapping' => ['seasonId' => 'id']])]
    public function showSeason(Program $program, Season $season, EpisodeRepository $episodeRepo): Response
    {
        $episode = $episodeRepo->findBy(['season'=>$season]);

        return $this->render('program/season_show.html.twig', parameters: [
            'program' => $program,
            'season' =>  $season,

            'episode' => $episode,
        ]);

    }

/*  #[Route('/{id<^[0-9]+$>}/season/{seasonId<^[0-9]+$>}/episode/{episodeId<^[0-9]+$>}', name: 'season_show', methods: ['GET'])]
    #[ParamConverter('season', options: ['mapping' => ['seasonId' => 'id']])]
    #[ParamConverter('episode', options: ['mapping' => ['episodeId' => 'id']])]
    public function showEpisode(Program $program, Season $season, Episode $episode, EpisodeRepository $episodeRepo): Response
    {

        return $this->render('program/episode_show.html.twig', parameters: [
            'program' => $program,
            'season' =>  $season,

            'episode' => $episode,
        ]);

    }
*/

}
