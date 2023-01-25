<?php

//  @author Muhammad Zulfi Rusdani <donimuzur@gmail.com>
//  @copyright 2023 PT Gunung Emas Putih

namespace OrangeHRM\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use OrangeHRM\Entity\Decorator\DecoratorTrait;

/**
 * AttLog
 *
 * @ORM\Table(name="att_log", indexes={@ORM\Index(name="sn", columns={"sn"}), @ORM\Index(name="pin", columns={"pin"})})
 * @ORM\Entity
 */
class FingerspotAttendance
{
    /**
     * @var string
     *
     * @ORM\Column(name="sn", type="string", length=30, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $sn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="scan_date", type="datetime", nullable=false)
     */
    private $scanDate;

    /**
     * @var string
     *
     * @ORM\Column(name="pin", type="string", length=32, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $pin;

    /**
     * @var int
     *
     * @ORM\Column(name="verifymode", type="integer", nullable=false)
     */
    private $verifymode;

    /**
     * @var int
     *
     * @ORM\Column(name="inoutmode", type="integer", nullable=false)
     */
    private $inoutmode = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="reserved", type="integer", nullable=false)
     */
    private $reserved = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="work_code", type="integer", nullable=false)
     */
    private $workCode = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="att_id", type="string", length=50, nullable=false)
     */
    private $attId = '0';

    /**
     * @return string
     */
    public function getSn(): string
    {
        return $this->sn;
    }

    /**
     * @param string $sn
     */
    public function setSn(string $sn): void
    {
        $this->sn = $sn;
    }

    /**
     * @return string
     */
    public function getPin(): string
    {
        return $this->pin;
    }

    /**
     * @param string $pin
     */
    public function setPin(string $pin): void
    {
        $this->pin = $pin;
    }

    /**
     * @param string $scanDate
     */
    public function setScanDate(string $scanDate): void
    {
        $this->scanDate = \DateTime::createFromFormat('Y-m-d H:m:s|', $scanDate);
    }
    
    /**
     * @return string
     */
    public function getScanDate()
    {
        return $this->scanDate->format('Y-m-d H:m:s');
    }

    /**
     * @return int
     */
    public function getVerifymode(): int
    {
        return $this->verifymode;
    }

    /**
     * @param int $verifymode
     */
    public function setVerifymode(int $verifymode): void
    {
        $this->pin = $verifymode;
    }
    
    /**
     * @return int
     */
    public function getInoutmode(): int
    {
        return $this->inoutmode;
    }

    /**
     * @param int $inoutmode
     */
    public function setInoutmode(int $inoutmode): void
    {
        $this->pin = $inoutmode;
    }
}
