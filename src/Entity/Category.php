<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Swagger\Annotations as SWG;
#use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
*/
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("Api")
     * @SWG\Property(description="The unique identifier of the category.")
     */
    private ?int $id = null;

    /**
     * @var string
     *
     * @Groups("Api")
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(type="string", maxLength=255, description="The name of the category.")
     */
    private string $name;

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
     * @Groups("Api")
     * @ORM\Column(type="boolean", nullable=false)
     * @SWG\Property(type="boolean", description="The activity of the category.")
     */
    private bool $active = true;

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
