<?php

namespace App\Entity;

use App\Repository\ContenuPanierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContenuPanierRepository::class)]
class ContenuPanier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'contenuPaniers')]
    private $Produit;

    #[ORM\Column(type: 'integer')]
    private $Quantite;

    #[ORM\Column(type: 'date')]
    private $Date;

    #[ORM\ManyToOne(targetEntity: Panier::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $Panier;



    public function __construct(Produit $P, Panier $panier) {
        $this ->Date = new \DateTime();
        $this ->Produit = $P;
        $this ->Panier = $panier;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->Produit;
    }

    public function setProduit(?Produit $Produit): self
    {
        $this->Produit = $Produit;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->Quantite;
    }

    public function setQuantite(int $Quantite): self
    {
        $this->Quantite = $Quantite;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->Panier;
    }

    public function setPanier(?Panier $Panier): self
    {
        $this->Panier = $Panier;

        return $this;
    }
}
