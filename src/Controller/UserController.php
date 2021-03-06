<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/edit/{id}', name: 'app_edit_user')]
    public function edit(Request $request, EntityManagerInterface $entityManagerInterface, User $products): Response
    {
        // créer le forrmulaire
        $form = $this->createForm(UserType::class, $products);
        // Traiter toutes les requêtes reçues par cette méthode 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // informer doctrine que l'on souhaiter persister notre objet
            $entityManagerInterface->persist($products);
            // on lui demande de procéder à l'enregistrement
            $entityManagerInterface->flush();

            // message succès
            $this->addFlash('success', 'User modifié avec succès !');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('user/edit.html.twig', [
            'userForm' => $form->createView(),
            'user' => $products
        ]);
    }
    #[Route('/user/not_delete', name: 'app_user_not_delete')]
    public function notdelete(): Response
    {
        return $this->render('user/delete.html.twig');
    }

    #[Route('/user/delete/{id}', name: 'app_user_delete')]
    public function delete(EntityManagerInterface $entityManagerInterface, User $user): Response
    {
        if ($this->isGranted('ROLE_USER') && $user == $this->getUser() || $this->isGranted('ROLE_ADMIN')){
            if($user->getProducts()){
                return $this->redirectToRoute('app_user_not_delete');
            }
            else{
        $entityManagerInterface->remove($user);
        $entityManagerInterface->remove($user);
        $entityManagerInterface->flush();
        $this->addFlash('success', 'Produit supprimé !');}
        return $this->redirectToRoute('app_home');}
        else{return $this->redirectToRoute('app_home');}
    }
}
