<?php

namespace App\Entity\Filter;

class CategoryFilter
{
    /**
     * @var bool|null
     */
    private ?bool $active = null;

    /**
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool|null $active
     */
    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }
}
