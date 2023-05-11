<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category")
 */
class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin_category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('admin_category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        //param converter permet de rattacher mon formulaire a mon instance
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_category_delete", methods={"POST"})
     */
    
    //La fonction accepte trois arguments :
    // un objet Request, un objet Category et un objet CategoryRepository.
     public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
    //La fonction vérifie si le jeton CSRF transmis dans la requête 
    //est valide à l'aide de la isCsrfTokenValid méthode.
    // Il s'agit d'une mesure de sécurité pour empêcher les attaques de falsification de requêtes intersites.

    //Si le jeton CSRF est valide, la fonction appelle la remove méthode 
    //sur l' CategoryRepository objet, en transmettant l' Categoryobjet 
    //pour le supprimer de la base de données. Le deuxième paramètre ( true)
    //est utilisé pour vider les modifications apportées à la base de données.
    
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
        $categoryRepository->remove($category, true);
        }
// la fonction redirige l'utilisateur vers la route 'app_admin_category_index' 
//en utilisant la redirectToRouteméthode avec un tableau vide de paramètres de route et le code d'état HTTP HTTP_SEE_OTHER.
        return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
    }
}