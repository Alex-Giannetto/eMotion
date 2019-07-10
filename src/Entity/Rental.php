<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RentalRepository")
 */
class Rental
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicle", inversedBy="rentals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vehicle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="rentals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startRentalDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $estimatedReturnDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $realReturnDate;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Rental constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getStartRentalDate(): ?DateTimeInterface
    {
        return $this->startRentalDate;
    }

    public function setStartRentalDate(DateTimeInterface $startRentalDate): self
    {
        $this->startRentalDate = $startRentalDate;

        return $this;
    }

    public function getEstimatedReturnDate(): ?DateTimeInterface
    {
        return $this->estimatedReturnDate;
    }

    public function setEstimatedReturnDate(DateTimeInterface $estimatedReturnDate): self
    {
        $this->estimatedReturnDate = $estimatedReturnDate;

        return $this;
    }

    public function getRealReturnDate(): ?DateTimeInterface
    {
        return $this->realReturnDate;
    }

    public function setRealReturnDate(DateTimeInterface $realReturnDate): self
    {
        $this->realReturnDate = $realReturnDate;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
