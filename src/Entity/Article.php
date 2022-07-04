<?php

namespace App\Entity;

use App\Entity\Size;
use App\Entity\Image;
use App\Entity\Category;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $name;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Image::class)]
    private $img;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'articles')]
    private $category;

    #[ORM\ManyToMany(targetEntity: Size::class, inversedBy: 'articles')]
    private $size;

    public function __construct()
    {
        $this->img = new ArrayCollection();
        $this->category = new ArrayCollection();
        $this->size = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, image>
     */
    public function getImg(): Collection
    {
        return $this->img;
    }

    public function addImg(image $img): self
    {
        if (!$this->img->contains($img)) {
            $this->img[] = $img;
            $img->setArticle($this);
        }

        return $this;
    }

    public function removeImg(image $img): self
    {
        if ($this->img->removeElement($img)) {
            // set the owning side to null (unless already changed)
            if ($img->getArticle() === $this) {
                $img->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, size>
     */
    public function getSize(): Collection
    {
        return $this->size;
    }

    public function addSize(size $size): self
    {
        if (!$this->size->contains($size)) {
            $this->size[] = $size;
        }

        return $this;
    }

    public function removeSize(size $size): self
    {
        $this->size->removeElement($size);

        return $this;
    }
    public function __toString() 
    {
        return $this->getName();
    }
}
