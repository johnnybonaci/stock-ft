<?php

namespace App\Domain\StockStatus\Data;

use Pilot\Component\Abstracts\AbstractEntity;

/**
 * Designed to represent an entity that allows to work with StockStatus Data.
 */
final class StockStatusData extends AbstractEntity
{
    private int $id;
    private ?string $code = null;
    private ?string $name = null;
    private ?int $visible = null;
    private ?int $deleted = null;

    /**
     * loadFromState.
     *
     * @param  array $data Entitie's data
     *
     * @return self
     */
    public function loadFromState(array $data = []): self
    {
        $reader = $this->arrayReader($data);

        $this->id = $reader->findInt('id');
        $this->code = $reader->findString('code');
        $this->name = $reader->findString('name');
        $this->visible = $reader->findInt('visible');
        $this->deleted = $reader->findInt('deleted');

        return $this;
    }

    /**
     * Get the value of id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of code.
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Get the value of name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get the value of visible.
     */
    public function getVisible(): ?int
    {
        return $this->visible;
    }

    /**
     * Get the value of deleted.
     */
    public function getDeleted(): ?int
    {
        return $this->deleted;
    }
}
