<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity(repositoryClass=PhoneRepository::class)
 * @ORM\Table(name="`phone`")
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "app_phone_detail",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *     )
 * )
 * @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "app_phone_list",
 *          absolute = true
 *     )
 * )
 */
class Phone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Since("1.0")
     * @OA\Property(description="The unique identifier of the phone.", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Since("1.0")
     * @OA\Property(description="This is phone's brand",type="string", maxLength=255)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Since("1.0")
     * @OA\Property(type="string", maxLength=255, description="This is phone's name")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Since("1.0")
     * @OA\Property(type="string", maxLength=255, description="This is phone's description")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Serializer\Since("1.0")
     * @OA\Property(type="float", description="This is phone's price")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Since("1.0")
     * @OA\Property(type="integer", description="This is phone's memory")
     */
    private $memory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getMemory(): ?int
    {
        return $this->memory;
    }

    public function setMemory(int $memory): self
    {
        $this->memory = $memory;

        return $this;
    }
}
