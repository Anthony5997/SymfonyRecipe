<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    #[Route('/recipe', name: 'recipe.index', methods: ['GET'])]
    public function index(PaginatorInterface $paginatorInterface, Request $request, RecipeRepository $recipeRepository): Response
    {
       
        $recipes = $paginatorInterface->paginate(
            $recipeRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/recipe/index.html.twig', [
            "recipes" => $recipes,
        ]);
    }

    #[Route('/recette/create', 'recipe.create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $recipe->setUser($this->getUser());

            $manager->persist($recipe);
            $manager->flush();

            // $this->addFlash(
            //     'success',
            //     'Votre recette a été créé avec succès !'
            // );

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
