<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Form\ProduitType;
use App\Entity\Produit;


class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine ->getManager() ;
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form -> handleRequest($request);
        if ($form-> isSubmitted() && $form->isValid()) {

         $image = $form->get('Image')->getData();
         if ($image) {
             $newFilename = uniqid().'.'.$image->guessExtension();
             try {
                 $image->move(
                     $this->getParameter('upload_directory'),
                     $newFilename
                 );
             } catch (FileException $e) {
                 $this -> addFlash('danger', 'Chargement échoué');
                 return $this ->redirectToRoute('app_produit');
             }
             $produit->setImage($newFilename);
         }
            $em -> persist($produit);
            $em -> flush();
            $this-> addFlash('success', 'Produit ajouté');

        }
                $produits = $em -> getRepository(Produit::class)-> findAll();

        return $this->render('default/index.html.twig', [
            'produits' => $produits,
            'ajout'=> $form -> createView()
        ]);
    }

}
