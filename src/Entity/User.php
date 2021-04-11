<?php

namespace App\Entity;

use App\Entity\Client;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @Serializer\ExclusionPolicy("all")
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "app_user_detail",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true,
 *     ),
 *     exclusion = @Hateoas\Exclusion(groups="list")
 * )
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "app_users_delete",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups="list")
 * )
 * @Hateoas\Relation(
 *      "deleteByClient",
 *      href = @Hateoas\Route(
 *          "app_users_delete_by_client",
 *          parameters = { "id" = "expr(object.getClient().getId())", "userid" = "expr(object.getId())" },
 *          absolute = true
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups="list")
 * )
 * @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "app_users_list",
 *          absolute = true
 *      ),
 *     exclusion = @Hateoas\Exclusion(groups="list")
 * )
 * @Hateoas\Relation(
 *     "client",
 *     embedded = @Hateoas\Embedded(
 *      "expr(object.getClient())",
 *      exclusion = @Hateoas\Exclusion(groups="list")
 *     )
 * )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list"})
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     * @OA\Property(description="The unique identifier of the user.", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champs firstname est requis", groups={"CREATE", "CREATEUSER"})
     * @Serializer\Groups({"public","list"})
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     * @OA\Property(description="This is user's firstname.", type="string")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champs lastname est requis", groups={"CREATE", "CREATEUSER"})
     * @Serializer\Groups({"public","list"})
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     * @OA\Property(description="This is user's lastname.", type="string")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champs address est requis", groups={"CREATE", "CREATEUSER"})
     * @Serializer\Groups({"public","list"})
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     * @OA\Property(description="This is user's address.", type="string")
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le champs zipcode est requis", groups={"CREATE", "CREATEUSER"})
     * @Assert\Length(min = 5,max = 5,minMessage="Le champs phone_number doit faire 5 caractères",maxMessage="Le champs phone_number doit faire 5 caractères", groups={"CREATE", "CREATEUSER"})
     * @Serializer\Groups({"public","list"})
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     * @OA\Property(description="This is user's zipcode.", type="integer")
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champs city est requis", groups={"CREATE", "CREATEUSER"})
     * @Serializer\Groups({"public","list"})
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     * @OA\Property(description="This is user's city.", type="string")
     */
    private $city;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\Length(min = 12,max = 12,minMessage="Le champs phone_number doit faire 12 caractères",maxMessage="Le champs phone_number doit faire 5 caractères", groups={"CREATE", "CREATEUSER"})
     * @Serializer\Groups({"public","list"})
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     * @OA\Property(description="This is user's phoneNumber.", type="bigint")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champs email est requis", groups={"CREATE", "CREATEUSER"})
     * @Assert\Email(message="Le champs email n'est pas valide", groups={"CREATE", "CREATEUSER"})
     * @Serializer\Groups({"public","list"})
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     * @OA\Property(description="This is user's email.", type="string")
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="users", cascade={"all"})
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"public"})
     * @Serializer\Expose()
     * @Serializer\Since("1.0")
     * @OA\Property(description="This is user's client.", type="integer")
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
