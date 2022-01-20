<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
