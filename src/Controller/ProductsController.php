<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $products = $produitRepository->findAll();
        return $this->render('products/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/createproducts', name: 'app_create_products')]
    public function create(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $product = new Produit;
        $form = $this->createForm(ProduitType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManagerInterface->persist($product);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Produit enregistré !');
        }
        return $this->render('products/create.html.twig', [
            'produitForm' => $form->createView()
        ]);
    }
    #[Route('/editproducts/{id}', name: 'app_edit_products')]
    public function edit(Request $request, EntityManagerInterface $entityManagerInterface, Produit $products): Response
    {
        // créer le forrmulaire
        $form = $this->createForm(ProduitType::class, $products);
        // Traiter toutes les requêtes reçues par cette méthode 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // informer doctrine que l'on souhaiter persister notre objet
            $entityManagerInterface->persist($products);
            // on lui demande de procéder à l'enregistrement
            $entityManagerInterface->flush();

            // message succès
            $this->addFlash('success', 'Produit modifié avec succès !');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('products/create.html.twig', [
            'productsForm' => $form->createView()
        ]);
    }

    #[Route('/products/{id}', name: 'app_products_details')]
    public function show(Produit $products): Response
    {
        return $this->render('products/show.html.twig', [
            'products' => $products
        ]);
    }
    #[Route('/delete-products/{id}', name: 'app_products_delete')]
    public function delete(EntityManagerInterface $entityManagerInterface, Produit $produit): Response
    {
        $entityManagerInterface->remove($produit);
        $entityManagerInterface->flush();
        $this->addFlash('success', 'Produit supprimé !');
        return $this->redirectToRoute('app_home');
    }

}