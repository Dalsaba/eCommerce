<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\ContenuPanier;


class ContenuPanierController extends AbstractController
{
    #[Route('/contenu/panier', name: 'app_contenu_panier')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine ->getManager() ;

        $cp = $em -> getRepository(ContenuPanier::class)-> findAll();

        return $this->render('contenu_panier/index.html.twig', [
            'controller_name' => 'ContenuPanierController',
            'cp' => $cp,
        ]);
    }



    #[Route('/contenu/panierdelete/{id}', name: 'app_contenu_panier_delete')]
    public function delete(ContenuPanier $cp = null, ManagerRegistry $d): Response{
        if ($cp == null) {
            $this-> addFlash('danger', 'Produit introuvable');

            return $this -> redirectToRoute('app_contenu_panier');
        }

        $em= $d -> getManager();
        $em -> remove($cp);
        $em -> flush();
        $this->addFlash('warning', 'Article supprimé');



        return $this -> redirectToRoute('app_contenu_panier') ;
    }

}
