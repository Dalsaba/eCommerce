<?php

namespace App\Controller;

use App\Form\ProduitType;
use App\Entity\Produit;
use App\Form\ContenuPanierType;
use App\Entity\ContenuPanier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class ProduitController extends AbstractController
{
    #[Route('/produit/detail/{id}', name: 'app_produit')]
    public function index(Produit $produit = null, ManagerRegistry $doctrine, Request $request): Response
    {

        if ($produit == null) {
            $this-> addFlash('danger', 'Aucun produit disponible');

            return $this -> redirectToRoute('app_default');
        }

       $em = $doctrine ->getManager() ;
       $cp = new ContenuPanier($produit);
       $form = $this->createForm(ContenuPanierType::class, $cp);
       $form -> handleRequest($request);
       if ($form-> isSubmitted() && $form->isValid()) {
           $em -> persist($cp);
           $em -> flush();
           $this-> addFlash('success', 'Produit ajouté au panier');
       }

               //recupération de la table Categorie - a placer apres a sauvegarde
               $cp = $em -> getRepository(ContenuPanier::class)-> findAll();

        return $this->render('produit/detail.html.twig', [
            'produit' => $produit,
            'cp' => $cp,
            'ajout'=> $form -> createView() // retourne la version html du form

        ]);

    }

    #[Route('/produit/{id}', name: 'produit_edit')]
    public function edit(Produit $produit = null, ManagerRegistry $doctrine, Request $request): Response{
        if ($produit == null) {
            $this-> addFlash('danger', 'Article introuvable');

            return $this -> redirectToRoute('app_default');
        }


        $form = $this->createForm(ProduitType::class, $produit);
        $form -> handleRequest($request);
        if ($form-> isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em -> persist($produit);
            $em -> flush();

            $this-> addFlash('success', 'Article modifié!');


        }

        return $this -> render('produit/edit.html.twig', [
            'produit' => $produit,
            'edit' => $form -> createView()
        ]);
    }


    #[Route('/produit/delete/{id}', name: 'produit_delete')]
    public function delete(Produit $produit = null, ManagerRegistry $d): Response{
        if ($produit == null) {
            $this-> addFlash('danger', 'Produit introuvable');

            return $this -> redirectToRoute('app_default');
        }

        $em= $d -> getManager();
        $em -> remove($produit);
        $em -> flush();
        $this->addFlash('warning', 'Article supprimé');



        return $this -> redirectToRoute('app_default') ;
    }
}
