<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarRepository")
 * @ORM\Table(name="car")
 */
class Car
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $mark;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $releaseYear;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mileage;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $engineCapacity;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, columnDefinition="fuel_types")
     *
     */
    private $fuel;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, columnDefinition="transmission_types")
     *
     */
    private $transmission;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, columnDefinition="drive_unit_types")
     */
    private $driveUnit;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     */
    private $owner;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMark(): string
    {
        return $this->mark;
    }

    /**
     * @param string $mark
     *
     * @return Car
     */
    public function setMark(string $mark): Car
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param string $model
     *
     * @return Car
     */
    public function setModel(string $model): Car
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getReleaseYear(): ?\DateTimeInterface
    {
        return $this->releaseYear;
    }

    /**
     * @param \DateTimeInterface|null $release_year
     *
     * @return Car
     */
    public function setReleaseYear(?\DateTimeInterface $releaseYear): Car
    {
        $this->releaseYear = $releaseYear;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    /**
     * @param int|null $mileage
     *
     * @return Car
     */
    public function setMileage(?int $mileage): Car
    {
        $this->mileage = $mileage;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getEngineCapacity(): ?int
    {
        return $this->engineCapacity;
    }

    /**
     * @param int|null $engine_capacity
     *
     * @return Car
     */
    public function setEngineCapacity(?int $engineCapacity): Car
    {
        $this->engineCapacity = $engineCapacity;

        return $this;
    }

    public function getFuel(): ?string
    {
        return $this->fuel;
    }

    public function setFuel(?string $fuel): Car
    {
        $this->fuel = $fuel;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    /**
     * @param null|string $transmission
     *
     * @return Car
     */
    public function setTransmission(?string $transmission): Car
    {
        $this->transmission = $transmission;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDriveUnit(): ?string
    {
        return $this->driveUnit;
    }

    /**
     * @param null|string $driveUnit
     *
     * @return Car
     */
    public function setDriveUnit(?string $driveUnit): Car
    {
        $this->driveUnit = $driveUnit;

        return $this;
    }

    /**
     * @return Client
     */
    public function getOwner(): Client
    {
        return $this->owner;
    }

    /**
     * @param Client $client
     *
     * @return Car
     */
    public function setOwner(Client $client): Car
    {
        $this->owner = $client;

        return $this;
    }
}
