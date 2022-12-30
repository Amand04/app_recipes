<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/categories", name="category_list", methods={"GET"})
     */
    public function categoriesList(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/category/create", name="category_create", methods={"GET", "POST"})
     */
    public function create(Request $request, CategoryRepository $categoryRepository, EntityManagerInterfacE $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
       $entityManager->persist($category);
       $entityManager->flush();

            return $this->redirectToRoute('category_list');
        }

        return $this->renderForm('admin/category_create.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/category/{id}/update", name="category_update", methods={"GET", "POST"})
     */
    public function update($id, Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('category_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category_create.html.twig', [
            'form' => $form->createView()
         ]);
          
    }

    /**
     * @Route("/admin/category/{id}/delete", name="category_delete")
     */
    public function delete($id, Category $category, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        $category = $categoryRepository->find($id);
      $entityManager->remove($category);
      $entityManager->flush();


      return $this->redirectToRoute("category_list");
}
}
