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

    /**
     * Page panier
     */
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

    /**
     * Validation du panier pour l'achat
     */
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

    /**
     * Historique du contenu du panier
     */
    #[Route('/panier/hist', name: 'app_panier_hist')]
    public function hist(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine ->getManager() ;

        $paniers = $em -> getRepository(Panier::class)-> findby(array('Etat' => true));

        return $this->render('panier/historique.htlm.twig', [
            'controller_name' => 'PanierController',
            'paniers'=> $paniers,
        ]);
    }

    #[Route('/panier/hist/{id}', name: 'app_panier_hist_detail')]
    public function histDetail(Panier $panier = null, ManagerRegistry $doctrine): Response
    {

        if ($panier == null) {
            $this-> addFlash('danger', 'Article introuvable');

            return $this -> redirectToRoute('app_panier_hist');
        }

/*         $em = $doctrine ->getManager() ;

        $panier = $em -> getRepository(Panier::class)-> findby(array('id' => true)); */

        return $this->render('panier/historiqueDetail.html.twig', [
            'controller_name' => 'PanierController',
            'panier'=> $panier,
        ]);
    }
}
