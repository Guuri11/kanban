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

    public function __construct()
    {
        $this->columnsKanban = new ArrayCollection();
    }

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

    /**
     * @return Collection|ColumnKanban[]
     */
    public function getColumnsKanban(): Collection
    {
        return $this->columnsKanban;
    }

    public function addColumnKanban(ColumnKanban $columnKanban): self
    {
        if (!$this->columnsKanban->contains($columnKanban)) {
            $this->columnsKanban[] = $columnKanban;
            $columnKanban->setTableKanban($this);
        }

        return $this;
    }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "id"=>$this->getId(),
            "name"=>$this->getName(),
            "columns"=>$this->getColumnsKanban()->toArray(),
            "user"=>$this->getUser()
        ];
    }
}
