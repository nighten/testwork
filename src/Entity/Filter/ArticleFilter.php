<?php

namespace App\Entity\Filter;

use App\Entity\Category;

class ArticleFilter
{
    /**
     * @var Category[]|null
     */
    private ?array $categories = null;

    /**
     * @var string|null
     */
    private ?string $text = null;

    /**
     * @var bool|null
     */
    private ?bool $active = null;

    /**
     * @return Category[]|null
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }

    public function initCategories(): void
    {
        $this->categories = $this->categories ?? [];
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category): void
    {
        $this->categories[] = $category;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

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
