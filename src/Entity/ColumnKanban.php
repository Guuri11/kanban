<?php

namespace App\Entity;

use App\Repository\ColumnKanbanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ColumnKanbanRepository::class)
 */
class ColumnKanban
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
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="column_kanban")
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
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

    public function getTableKanban(): ?TableKanban
    {
        return $this->table_kanban;
    }

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

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setColumnKanban($this);
        }

        return $this;
    }

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
}
