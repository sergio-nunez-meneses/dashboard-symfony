<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 */
class Products
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(type="datetime")
     */
    public $purchase_date;

    /**
     * @ORM\Column(type="datetime")
     */
    public $warranty_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $receipt;

    /**
     * @ORM\Column(type="text")
     */
    private $maintenance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $manual;

    /**
     * @ORM\Column(type="datetime")
     */
    public $reservation_date;

    /**
     * @ORM\Column(type="datetime")
     */
    public $return_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $availability;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="products")
     */
    private $id_user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $purchase_place;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchase_date;
    }

    public function setPurchaseDate(\DateTimeInterface $purchase_date): self
    {
        $this->purchase_date = $purchase_date;

        return $this;
    }

    public function getWarrantyDate(): ?\DateTimeInterface
    {
        return $this->warranty_date;
    }

    public function setWarrantyDate(\DateTimeInterface $warranty_date): self
    {
        $this->warranty_date = $warranty_date;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getReceipt(): ?string
    {
        return $this->receipt;
    }

    public function setReceipt(string $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
    }

    public function getMaintenance(): ?string
    {
        return $this->maintenance;
    }

    public function setMaintenance(string $maintenance): self
    {
        $this->maintenance = $maintenance;

        return $this;
    }

    public function getManual(): ?string
    {
        return $this->manual;
    }

    public function setManual(string $manual): self
    {
        $this->manual = $manual;

        return $this;
    }

    public function getReservationDate(): ?\DateTimeInterface
    {
        return $this->reservation_date;
    }

    public function setReservationDate(\DateTimeInterface $reservation_date): self
    {
        $this->reservation_date = $reservation_date;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->return_date;
    }

    public function setReturnDate(\DateTimeInterface $return_date): self
    {
        $this->return_date = $return_date;

        return $this;
    }

    public function getAvailability(): ?bool
    {
        return $this->availability;
    }

    public function setAvailability(bool $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    public function getIdUser(): ?Users
    {
        return $this->id_user;
    }

    public function setIdUser(?Users $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getPurchasePlace(): ?string
    {
        return $this->purchase_place;
    }

    public function setPurchasePlace(string $purchase_place): self
    {
        $this->purchase_place = $purchase_place;

        return $this;
    }
}
