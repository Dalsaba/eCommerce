<?php

namespace App\Controller;
use App\Entity\ContenuPanier;
use App\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine ->getManager() ;

        $panier = $em -> getRepository(Panier::class)-> findOneBy(['Etat' => false]);
        $cp = $em -> getRepository(ContenuPanier::class)-> findOneBy(['Panier' => $panier]);

        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'cp'=> $cp,
            'panier'=> $panier,
        ]);
    }
    #[Route('/panier/validate', name: 'app_panier_validate')]
    public function validate(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine ->getManager() ;

        $panier = $em -> getRepository(Panier::class)-> findOneBy(['Etat' => false]);
        $panier -> setEtat(true);
        $user = $panier -> getUtilisateur();
        $panierNv = new Panier($user);
        $em -> persist($panierNv);
        $em -> flush();

        return $this->render('panier/validation.html.twig', [
            'controller_name' => 'PanierController',
            'panier'=> $panier,
        ]);
    }
}
