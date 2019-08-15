<?php

namespace App\Entity;

use DateTime;
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
     * @ORM\Column(type="json", nullable=true)
     */
    private $pdf = [];

    /**
     * Rental constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
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

    public function getStartRentalDate(): ?DateTime
    {
        return $this->startRentalDate;
    }

    public function setStartRentalDate(DateTime $startRentalDate): self
    {
        $this->startRentalDate = $startRentalDate;

        return $this;
    }

    public function getEstimatedReturnDate(): ?DateTime
    {
        return $this->estimatedReturnDate;
    }

    public function setEstimatedReturnDate(DateTime $estimatedReturnDate): self
    {
        $this->estimatedReturnDate = $estimatedReturnDate;

        return $this;
    }

    public function getRealReturnDate(): ?DateTime
    {
        return $this->realReturnDate;
    }

    public function setRealReturnDate(DateTime $realReturnDate): self
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

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEstimatedRentalDuration()
    {
        return $this->getStartRentalDate()->diff($this->getEstimatedReturnDate())->format("%a");
    }

    public function getPdf(): ?array
    {
        return $this->pdf;
    }

    public function setPdf(?array $pdf): self
    {
        $this->pdf = $pdf;

        return $this;
    }

    public function addPdf(string $type, string $path)
    {
        $this->pdf[$type][] = [date('Y-m-d H:i:s') => $path];
    }
}
