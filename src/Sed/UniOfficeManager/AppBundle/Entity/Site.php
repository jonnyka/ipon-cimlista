<?php

namespace Sed\UniOfficeManager\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Site
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity("name")
 * @UniqueEntity("port")
 */
class Site
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
     * @ORM\Column(name="gitrepo", type="string", length=255, options={"default" = "unioffice-central"})
     *
     * @Assert\NotNull(
     *  message = "sed_uniofficemanager_app_bundle.site.validate.gitrepo.empty"
     * )
     */
    protected $gitrepo;

    /**
     * @var string
     *
     * @ORM\Column(name="port", type="integer", options={"default" = 9000})
     *
     * @Assert\NotNull(
     *  message = "sed_uniofficemanager_app_bundle.site.validate.port.empty"
     * )
     */
    protected $port;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotNull(
     *  message = "sed_uniofficemanager_app_bundle.site.validate.name.empty"
     * )
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotNull(
     *  message = "sed_uniofficemanager_app_bundle.site.validate.title.empty"
     * )
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="directory", type="string", length=255)
     *
     * @Assert\NotNull(
     *  message = "sed_uniofficemanager_app_bundle.site.validate.directory.empty"
     * )
     */
    protected $directory;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastUpdated", type="datetime")
     */
    protected $lastUpdated;

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
     * @return Site
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
     * Set title
     *
     * @param string $title
     * @return Site
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set directory
     *
     * @param string $directory
     * @return Site
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Get directory
     *
     * @return string 
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Set lastUpdated
     *
     * @param \DateTime $lastUpdated
     * @return Site
     */
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    /**
     * Get lastUpdated
     *
     * @return \DateTime 
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * Set gitrepo
     *
     * @param string $gitrepo
     * @return Site
     */
    public function setGitrepo($gitrepo)
    {
        $this->gitrepo = $gitrepo;

        return $this;
    }

    /**
     * Get gitrepo
     *
     * @return string 
     */
    public function getGitrepo()
    {
        return $this->gitrepo;
    }

    /**
     * Set port
     *
     * @param integer $port
     * @return Site
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return integer 
     */
    public function getPort()
    {
        return $this->port;
    }
}
