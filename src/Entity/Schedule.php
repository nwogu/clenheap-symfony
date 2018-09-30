<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScheduleRepository")
 */
class Schedule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cleaningDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $cleaningTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $cleaningHours;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CleanUser", inversedBy="schedule")
     */
    private $cleanuser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cleaner", inversedBy="schedule")
     */
    private $cleaner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCleaningDate(): ?\DateTimeInterface
    {
        return $this->cleaningDate;
    }

    public function setCleaningDate(\DateTimeInterface $cleaningDate): self
    {
        $this->cleaningDate = $cleaningDate;

        return $this;
    }

    public function getCleaningTime(): ?int
    {
        return $this->cleaningTime;
    }

    public function setCleaningTime(int $cleaningTime): self
    {
        $this->cleaningTime = $cleaningTime;

        return $this;
    }

    public function getCleaningHours(): ?int
    {
        return $this->cleaningHours;
    }

    public function setCleaningHours(int $cleaningHours): self
    {
        $this->cleaningHours = $cleaningHours;

        return $this;
    }

    public function getCleanuser(): ?CleanUser
    {
        return $this->cleanuser;
    }

    public function setCleanuser(?CleanUser $cleanuser): self
    {
        $this->cleanuser = $cleanuser;

        return $this;
    }

    public function getCleaner(): ?Cleaner
    {
        return $this->cleaner;
    }

    public function setCleaner(?Cleaner $cleaner): self
    {
        $this->cleaner = $cleaner;

        return $this;
    }
}
