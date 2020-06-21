<?php

namespace App\Entity;

use App\Repository\TableKanbanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TableKanbanRepository::class)
 */
class TableKanban implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ColumnKanban::class, mappedBy="table_kanban", cascade={"persist", "remove"})
     */
    private $columnsKanban;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tablesKanban")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * TableKanban constructor.
     */
    public function __construct()
    {
        $this->columnsKanban = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|ColumnKanban[]
     */
    public function getColumnsKanban(): Collection
    {
        return $this->columnsKanban;
    }

    /**
     * @param ColumnKanban $columnKanban
     * @return $this
     */
    public function addColumnKanban(ColumnKanban $columnKanban): self
    {
        if (!$this->columnsKanban->contains($columnKanban)) {
            $this->columnsKanban[] = $columnKanban;
            $columnKanban->setTableKanban($this);
        }

        return $this;
    }

    /**
     * @param ColumnKanban $columnKanban
     * @return $this
     */
    public function removeColumnKanban(ColumnKanban $columnKanban): self
    {
        if ($this->columnsKanban->contains($columnKanban)) {
            $this->columnsKanban->removeElement($columnKanban);
            // set the owning side to null (unless already changed)
            if ($columnKanban->getTableKanban() === $this) {
                $columnKanban->setTableKanban(null);
            }
        }

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return $this
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            "id"=>$this->getId(),
            "name"=>$this->getName(),
            "columns"=>$this->getColumnsKanban()->toArray(),
            "user"=>$this->getUser(),
            "image"=>$this->getImage()
        ];
    }

}
