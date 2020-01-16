<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Mapping\Annotation as Gedmo;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\Table(indexes={@ORM\Index(columns={"title","text"}, flags={"fulltext"})})
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("Api")
     */
    private ?int $id = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Groups("Api")
     */
    private string $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Groups("Api")
     */
    private string $text = '';

    /**
     * @var array|ArrayCollection|ArticleCategory[]
     * @OneToMany(targetEntity="ArticleCategory", mappedBy="article", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $articleCategories;

    /**
     * @var \DateTime|null $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $created = null;

    /**
     * @var \DateTime|null $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $updated = null;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=false)
     * @Groups("Api")
     */
    private bool $active = true;

    public function __construct(string $title)
    {
        $this->setTitle($title);
        $this->articleCategories = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category): void
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('category', $category));
        if ($this->articleCategories->matching($criteria)->isEmpty()) {
            $articleCategory = new ArticleCategory($this, $category);
            $this->articleCategories->add($articleCategory);
        }
    }

    /**
     * @SWG\Property(
     *     type="array",
     *     @SWG\Items(type="array", @Model(type="array")),
     *     description="List of IDs of article categories.")
     * @SerializedName("categories")
     * @Groups("Api")
     */
    public function getCategories()
    {
        return $this->articleCategories->map(
            fn(ArticleCategory $ArticleCategory) => $ArticleCategory->getCategory()
        );
    }

    /**
     * @param Category $category
     */
    public function removeCategory(Category $category): void
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('category', $category));
        $articleCategory = $this->articleCategories->matching($criteria)->first();
        $this->articleCategories->removeElement($articleCategory);
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active = true): void
    {
        $this->active = $active;
    }
}
