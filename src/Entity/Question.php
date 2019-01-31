<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\PrePersist()
     */
    public function prePersist(){
        $this->setDateCreated(new \DateTime());
        $this->setSupports(0);
        $this->setStatus('deting');

    }
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Veuillez poser votre question")
     * @Assert\Length(min="15", max="255",minMessage="15 caractère svp" , maxMessage=" 255 caractère svp")
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @Assert\Length(min="15", max="10000000000
     * ",minMessage="15 caractère svp" , maxMessage=" 255 caractère svp")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="integer")
     */
    private $supports;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="question", orphanRemoval=true)
     */
    private $messages;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sujet", inversedBy="questions")
     */
    private $Sujet;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->Sujet = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getSupports(): ?int
    {
        return $this->supports;
    }

    public function setSupports(int $supports): self
    {
        $this->supports = $supports;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setQuestion($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getQuestion() === $this) {
                $message->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|sujet[]
     */
    public function getSujet(): Collection
    {
        return $this->Sujet;
    }

    public function addSujet(sujet $sujet): self
    {
        if (!$this->Sujet->contains($sujet)) {
            $this->Sujet[] = $sujet;
        }

        return $this;
    }

    public function removeSujet(sujet $sujet): self
    {
        if ($this->Sujet->contains($sujet)) {
            $this->Sujet->removeElement($sujet);
        }

        return $this;
    }




}
