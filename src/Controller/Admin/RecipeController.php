<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RecipeFormType;
use Symfony\Component\HttpFoundation\Request;






class RecipeController extends AbstractController
{
   /**
    * @Route("/admin/recipe/create", name="recipe_create")
    */
   public function recipeCreate(Request $request, EntityManagerInterface $entityManager)
   {
      $recipe = new Recipe();
      $form = $this->createForm(RecipeFormType::class, $recipe);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {

       $recipe = $form->getData();
       $entityManager->persist($recipe);
       $entityManager->flush();

      return $this->redirectToRoute('recipes_list');
      }


      return $this->renderForm('admin/recipe_create.html.twig', [
         'recipe' => $recipe,
         'form' => $form
      ]);
   }

   /**
    * @Route("/admin/recipe/{id}/update", name="recipe_update")
    */
   public function recipeUpdate($id, Request $request, EntityManagerInterface $entityManager, RecipeRepository $recipeRepository)
   {
      $recipe = $recipeRepository->find($id);
      $recipes = $recipeRepository->findAll();
      $form = $this->createForm(RecipeFormType::class, $recipe);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
         $entityManager->persist($recipe);
         $entityManager->flush();

         return $this->redirectToRoute('recipes_list');
      }


      return $this->render('admin/recipe_create.html.twig', [
         'recipes' => $recipes,
         'form' => $form->createView(),
      ]);
   }




   /**
    * @Route("/admin/recipes", name="recipes_list")
    */
   public function recipesList(RecipeRepository $recipeRepository)
   {

      $recipes = $recipeRepository->findAll();

      return $this->render('admin/recipes.html.twig', [
         'recipes' => $recipes
      ]);
   }

   /**
    * @Route("/admin/recipe/{id}/delete", name="recipe_delete")
    */
   public function recipeDelete($id, RecipeRepository $recipeRepository, EntityManagerInterface $entityManager)
   {

      $recipe = $recipeRepository->find($id);
      $entityManager->remove($recipe);
      $entityManager->flush();


      return $this->redirectToRoute("recipes_list");
   }
}
