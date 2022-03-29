<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'app_categories')]
    public function index(): Response
    {
        return $this->render('categories/index.html.twig', [
            'controller_name' => 'CategoriesController',
        ]);
    }

    #[Route('/categories/{id}', name: 'app_products_categories')]
    public function showone(Categories $categories): Response
    {
        $products = $categories->getProduits();
        return $this->render('categories/products.html.twig', [
            'products' => $products,
            'categorie'=> $categories
        ]);
    }

    #[Route('/createcategories', name: 'app_create_categorie')]
    public function create(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $categorie = new Categories;
        $form = $this->createForm(CategoriesType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManagerInterface->persist($categorie);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Catégorie enregistrée !');
        }
        return $this->render('categories/create.html.twig', [
            'categoriesForm' => $form->createView()
        ]);
    }

    #[Route('/editcategories/{id}', name: 'app_edit_categories')]
    public function edit(Request $request, EntityManagerInterface $entityManagerInterface, Categories $categories): Response
    {
        // créer le forrmulaire
        $form = $this->createForm(CategoriesType::class, $categories);
        // Traiter toutes les requêtes reçues par cette méthode 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // informer doctrine que l'on souhaiter persister notre objet
            $entityManagerInterface->persist($categories);
            // on lui demande de procéder à l'enregistrement
            $entityManagerInterface->flush();

            // message succès
            $this->addFlash('success', 'categories modifié avec succès !');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('categories/create.html.twig', [
            'categoriesForm' => $form->createView()
        ]);
    }

    #[Route('/categories/{id}', name: 'app_categories_details')]
    public function show(Categories $categories): Response
    {
        return $this->render('categories/show.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/deletecategories/{id}', name: 'app_categories_delete')]
    public function delete(EntityManagerInterface $entityManagerInterface, Categories $categories): Response
    {
        $entityManagerInterface->remove($categories);
        $entityManagerInterface->flush();
        $this->addFlash('success', 'Catégorie supprimée !');
        return $this->redirectToRoute('app_home');
    }

}
