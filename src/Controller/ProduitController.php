<?php

namespace App\Controller;

use App\Form\ProduitType;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {

       $em = $doctrine ->getManager() ;
       $produit = new Produit();
       $form = $this->createForm(ProduitType::class, $produit);
       $form -> handleRequest($request);
       if ($form-> isSubmitted() && $form->isValid()) {
           $em -> persist($produit);
           $em -> flush();
           $this-> addFlash('sucess', 'Catégorie ajoutée');


       }
               $produits = $em -> getRepository(Produit::class)-> findAll();

       return $this->render('produit/index.html.twig', [
           'produits' => $produits,
           'ajout'=> $form -> createView()
       ]);



        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
}
