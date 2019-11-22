<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(formats={"json","xml"},
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"details"}}
 *           },
 *          "patch","put","delete"
 *     },
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"list"}}
 *          },
 *          "post"
 *     })
 * @ORM\Entity(repositoryClass="App\Repository\ColorRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Color
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ApiProperty(identifier=true)
     * @Groups({"list", "details"})
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     * @Groups({"list", "details"})
     * @Serializer\Expose()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/#[0-9a-f]{3,6}/i", message="This value is not valid. Examples: '#fff', '#FFF' or '#0101DF'")
     * @Groups({"list", "details"})
     * @Serializer\Expose()
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255, name="pantone_value")
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     * @Groups({"details"})
     * @Serializer\Expose()
     * @SerializedName("pantone_value")
     */
    private $pantoneValue;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Regex(pattern="/^(19[0-9]{2}|2[0-9]{3})$/")
     * @Assert\NotBlank()
     * @Groups({"details"})
     * @Serializer\Expose()
     */
    private $year;

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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getPantoneValue(): ?string
    {
        return $this->pantoneValue;
    }

    public function setPantoneValue(string $pantoneValue): self
    {
        $this->pantoneValue = $pantoneValue;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }
}
