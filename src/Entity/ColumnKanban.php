<?php

namespace App\Entity;

use App\Repository\ColumnKanbanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ColumnKanbanRepository::class)
 */
class ColumnKanban implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=TableKanban::class, inversedBy="columnKanbans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $table_kanban;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="column_kanban", cascade={"persist", "remove"})
     */
    private $tasks;

    /**
     * ColumnKanban constructor.
     */
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
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
     * @return TableKanban|null
     */
    public function getTableKanban(): ?TableKanban
    {
        return $this->table_kanban;
    }

    /**
     * @param TableKanban|null $table_kanban
     * @return $this
     */
    public function setTableKanban(?TableKanban $table_kanban): self
    {
        $this->table_kanban = $table_kanban;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * @param Task $task
     * @return $this
     */
    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setColumnKanban($this);
        }

        return $this;
    }

    /**
     * @param Task $task
     * @return $this
     */
    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getColumnKanban() === $this) {
                $task->setColumnKanban(null);
            }
        }

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
            "table"=>$this->getTableKanban()->getId(),
            "tasks"=>$this->getTasks()->toArray()
        ];
    }

    public function __toString()
    {
        return $this->getName();
    }
}
