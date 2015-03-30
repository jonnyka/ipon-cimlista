<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Person
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Person
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(
     *   message = "app_bundle.validate.person.name.empty"
     * )
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="PersonData", mappedBy="person", cascade={"persist"})
     * @Assert\Valid
     */
    protected $personData;

    /**
     * @ORM\Column(name="createdAt", type="date")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updatedAt", type="date")
     */
    protected $updatedAt;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Person
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Person
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Person
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->personData = new \Doctrine\Common\Collections\ArrayCollection();
        $this->addPersonDatum(new PersonData());
    }

    /**
     * Add personData
     *
     * @param PersonData $personData
     * @return Person
     */
    public function addPersonDatum(PersonData $personData)
    {
        $this->personData[] = $personData;

        return $this;
    }

    /**
     * Remove personData
     *
     * @param PersonData $personData
     */
    public function removePersonDatum(PersonData $personData)
    {
        $this->personData->removeElement($personData);
    }

    /**
     * Get personData
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersonData()
    {
        return $this->personData;
    }
}
