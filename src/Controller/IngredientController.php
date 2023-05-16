<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class IngredientController extends AbstractController
{

    /**
     * View all Ingredients
     *
     * @param IngredientRepository $ingredientRepository
     * @param PaginatorInterface $paginatorInterface
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {

        $ingredients = $paginatorInterface->paginate(
            $ingredientRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/ingredient/index.html.twig', [
            "ingredients" => $ingredients,
        ]);
    }

    /**
     * Create a new Ingredient
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManagerInterface
     * @return Response
     */
    #[Route("/ingredient/create", name: "ingredient.create", methods:["GET", "POST"])]
    public function create(Request $request, EntityManagerInterface $entityManagerInterface) : Response{
        
        $ingredient = new Ingredient();

        $form = $this->createForm(IngredientType::class, $ingredient);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            // ... perform some action, such as saving the data to the database

            $entityManagerInterface->persist($ingredient);
            $entityManagerInterface->flush();

            $this->addFlash(
                'success',
                'Nouvel ingrédient ajouté !'
            );

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/create.html.twig', [
            "form" => $form->createView(),
        ]);

    }

    
    #[Route("/ingredient/edit/{id}", name: "ingredient.edit", methods:["GET", "POST"])]
    public function edit(Request $request, EntityManagerInterface $entityManagerInterface, Ingredient $ingredient) : Response{
        

        $form = $this->createForm(IngredientType::class, $ingredient);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            // ... perform some action, such as saving the data to the database

            $entityManagerInterface->persist($ingredient);
            $entityManagerInterface->flush();

            $this->addFlash(
                'success',
                'Ingrédient modifié avec succès !'
            );

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            "form" => $form->createView(),
        ]);

    }

    #[Route("/ingredient/delete/{id}", name: "ingredient.delete", methods:["GET", "POST"])]
    public function delete(Request $request, EntityManagerInterface $entityManagerInterface, Ingredient $ingredient) : Response{
 
            $entityManagerInterface->remove($ingredient);
            $entityManagerInterface->flush();

            $this->addFlash(
                'success',
                'Ingrédient supprimé avec succès !'
            );

            return $this->redirectToRoute('ingredient.index');
        }
}
