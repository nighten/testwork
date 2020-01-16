<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 */
class ArticleCategory
{
    /**
     * @var Article
     *
     * @ORM\Id()
     * @ManyToOne(targetEntity="Article", inversedBy="articleCategories",)
     * @JoinColumn(name="article", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Article $article;

    /**
     * @var Category
     *
     * @ORM\Id()
     * @ManyToOne(targetEntity="Category")
     * @JoinColumn(name="category", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Category $category;

    /**
     * @var \DateTime|null $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $created = null;

    public function __construct(Article $article, Category $category)
    {
        $this->setArticle($article);
        $this->setCategory($category);
    }

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle(Article $article): void
    {
        $this->article = $article;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }
}
