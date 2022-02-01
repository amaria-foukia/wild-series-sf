<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method getDoctrine()
 */
#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepo): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepo->findAll(),
        ]);
    }

    #[Route("/new", name:"new")]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        // Create a new Category Object
        $category = new Category();

        // Create the associated Form
        $form = $this->createForm(CategoryType::class, $category);

        // Get data from HTTP request
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data

            // Persist Category Object
            $entityManager->persist($category);

            // Flush the persisted object
            $entityManager->flush();

            $this->addFlash(
                'success',
                "La catégorie a été  ajoutée avec succès !"
            );

            // Finally redirect to categories list
            return $this->redirectToRoute('category_index');
        }

        // Render the form
        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    #[Route('/{categoryName}', name: 'show', methods: ['GET'])]
    public function show(string $categoryName,  CategoryRepository $categoryRepo, ProgramRepository $programRepo): Response
    {
        $category = $categoryRepo->findOneBy(['name'=>$categoryName]);
        $program = $programRepo->findBy(['category'=>$category], ['id'=>'DESC'], 3);

        if (!$category) {
            throw $this->createNotFoundException(
                'No program with category\'s name : '.$category.' found in program\'s table.'
            );
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'program'   => $program,
        ]);

    }

}
