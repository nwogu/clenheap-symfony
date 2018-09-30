<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CleanerRepository")
 */
class Cleaner
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="text")
     */
    private $hobbies;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageUrl;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Schedule", mappedBy="cleaner")
     */
    private $schedule;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CleanUser", mappedBy="cleaner")
     */
    private $cleanUsers;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":true})
     */
    private $isActive;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default":0})
     */
    private $count_rating;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $rating;

    public function __construct()
    {
        $this->schedule = new ArrayCollection();
        $this->cleanUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getHobbies(): ?string
    {
        return $this->hobbies;
    }

    public function setHobbies(string $hobbies): self
    {
        $this->hobbies = $hobbies;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Schedule[]
     */
    public function getSchedule(): Collection
    {
        return $this->schedule;
    }

    public function addSchedule(Schedule $schedule): self
    {
        if (!$this->schedule->contains($schedule)) {
            $this->schedule[] = $schedule;
            $schedule->setCleaner($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedule->contains($schedule)) {
            $this->schedule->removeElement($schedule);
            // set the owning side to null (unless already changed)
            if ($schedule->getCleaner() === $this) {
                $schedule->setCleaner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Schedule[]
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    /**
     * @return Collection|CleanUser[]
     */
    public function getCleanUsers(): Collection
    {
        return $this->cleanUsers;
    }

    public function addCleanUser(CleanUser $cleanUser): self
    {
        if (!$this->cleanUsers->contains($cleanUser)) {
            $this->cleanUsers[] = $cleanUser;
            $cleanUser->setCleaner($this);
        }

        return $this;
    }

    public function removeCleanUser(CleanUser $cleanUser): self
    {
        if ($this->cleanUsers->contains($cleanUser)) {
            $this->cleanUsers->removeElement($cleanUser);
            // set the owning side to null (unless already changed)
            if ($cleanUser->getCleaner() === $this) {
                $cleanUser->setCleaner(null);
            }
        }

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCountRating(): ?int
    {
        return $this->count_rating;
    }

    public function setCountRating(?int $count_rating): self
    {
        $this->count_rating = $count_rating;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
