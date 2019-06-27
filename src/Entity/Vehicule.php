<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\VehiculeRepository")
 */
class Vehicule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */

    private $brand;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */

    private $model;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */

    private $serial;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */

    private $color;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */

    private $autonomy;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */

    private $distance;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */


    private $immatriculation;


    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */

    private $kilometers;


    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */

    private $date;


    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */

    private $price;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */

    private $price_per_day;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */

    private $rentability;

    /**
     * @ORM\Column(type="boolean")
     */

    private $state;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param mixed $brand
     */
    public function setBrand($brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model): void
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * @param mixed $serial
     */
    public function setSerial($serial): void
    {
        $this->serial = $serial;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color): void
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getAutonomy()
    {
        return $this->autonomy;
    }

    /**
     * @param mixed $autonomy
     */
    public function setAutonomy($autonomy): void
    {
        $this->autonomy = $autonomy;
    }

    /**
     * @return mixed
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param mixed $distance
     */
    public function setDistance($distance): void
    {
        $this->distance = $distance;
    }

    /**
     * @return mixed
     */
    public function getImmatriculation()
    {
        return $this->immatriculation;
    }

    /**
     * @param mixed $immatriculation
     */
    public function setImmatriculation($immatriculation): void
    {
        $this->immatriculation = $immatriculation;
    }

    /**
     * @return mixed
     */
    public function getKilometers()
    {
        return $this->kilometers;
    }

    /**
     * @param mixed $kilometers
     */
    public function setKilometers($kilometers): void
    {
        $this->kilometers = $kilometers;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPricePerDay()
    {
        return $this->price_per_day;
    }

    /**
     * @param mixed $price_per_day
     */
    public function setPricePerDay($price_per_day): void
    {
        $this->price_per_day = $price_per_day;
    }

    /**
     * @return mixed
     */
    public function getRentability()
    {
        return $this->rentability;
    }

    /**
     * @param mixed $rentability
     */
    public function setRentability($rentability): void
    {
        $this->rentability = $rentability;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }


}
