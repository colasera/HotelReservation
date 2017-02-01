<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="tel_no", type="string", length=255)
     */
    private $telNo;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var int
     * 
     * @ORM\ManyToOne(targetEntity="Room")
     */
    private $roomNo;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Customer
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
     * Set telNo
     *
     * @param string $telNo
     *
     * @return Customer
     */
    public function setTelNo($telNo)
    {
        $this->telNo = $telNo;

        return $this;
    }

    /**
     * Get telNo
     *
     * @return string
     */
    public function getTelNo()
    {
        return $this->telNo;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Customer
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set roomNo
     *
     * @param integer $roomNo
     *
     * @return Customer
     */
    public function setRoomNo($roomNo)
    {
        $this->roomNo = $roomNo;

        return $this;
    }

    /**
     * Get roomNo
     *
     * @return int
     */
    public function getRoomNo()
    {
        return $this->roomNo;
    }
}

