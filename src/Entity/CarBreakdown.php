<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarBreakdownRepository")
 */
class CarBreakdown
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Car
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Car", inversedBy="carBreakdowns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $car;

    /**
     * @var Breakdown
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Breakdown", inversedBy="carBreakdowns", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $breakdown;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $minPrice;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxPrice;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Car|null
     */
    public function getCar(): ?Car
    {
        return $this->car;
    }

    /**
     * @param Car|null $car
     *
     * @return CarBreakdown
     */
    public function setCar(?Car $car): CarBreakdown
    {
        $this->car = $car;

        return $this;
    }

    /**
     * @return Breakdown|null
     */
    public function getBreakdown(): ?Breakdown
    {
        return $this->breakdown;
    }

    /**
     * @param Breakdown|null $breakdown
     *
     * @return CarBreakdown
     */
    public function setBreakdown(?Breakdown $breakdown): CarBreakdown
    {
        $this->breakdown = $breakdown;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     *
     * @return CarBreakdown
     */
    public function setDescription(?string $description): CarBreakdown
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinPrice(): ?int
    {
        return $this->minPrice;
    }

    /**
     * @param int|null $minPrice
     * @return CarBreakdown
     */
    public function setMinPrice(?int $minPrice): CarBreakdown
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * @param int|null $maxPrice
     *
     * @return CarBreakdown
     */
    public function setMaxPrice(?int $maxPrice): CarBreakdown
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }
}
