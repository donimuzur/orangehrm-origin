<?php

//  @author Muhammad Zulfi Rusdani <donimuzur@gmail.com>
//  @copyright 2023 PT Gunung Emas Putih


namespace OrangeHRM\Fingerspot\Dto;

use DateTime;
use OrangeHRM\Core\Dto\FilterParams;
use OrangeHRM\Fingerspot\Traits\Service\FingerspotAttendanceServiceTrait;

class FingerspotAttendanceSearchFilterParams extends FilterParams
{
    use FingerspotAttendanceServiceTrait;

    public const ALLOWED_SORT_FIELDS = [
        'fingerspotAttendance.pin',
        'fingerspotAttendance.scanDate',
    ];
    /**
     * @var string|null
     */
    protected ?string $pin = null;

     /**
     * @var DateTime|null
     */
    protected ?DateTime $scanDate = null;

    public function __construct()
    {
        $this->setSortField('fingerspotAttendance.pin');
       // $this->scanDate = \DateTime::createFromFormat("Y/m/d",date("Y/m/d"));
    }

    /**
     * @return string|null
     */
    public function getPin(): ?string
    {
        return $this->pin;
    }

    /**
     * @param string|null $pin
     */
    public function setPin(?string $pin): void
    {
        $this->pin = $pin;
    }

    /**
     * @return DateTime|null
     */
    public function getScanDate(): ?DateTime
    {
        return $this->scanDate;
    }

    /**
     * @param DateTime|null $pin
     */
    public function setScanDate(?DateTime $scanDate): void
    {
        $this->scanDate = $scanDate;
    }
}
