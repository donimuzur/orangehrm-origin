<?php

//  @author Muhammad Zulfi Rusdani <donimuzur@gmail.com>
//  @copyright 2023 PT Gunung Emas Putih


namespace OrangeHRM\Fingerspot\Traits\Service;

use OrangeHRM\Fingerspot\Service\FingerspotAttendanceService;
use OrangeHRM\Core\Traits\ServiceContainerTrait;

trait FingerspotAttendanceServiceTrait
{
    use ServiceContainerTrait;

    protected function getFingerspotAttendanceService(): FingerspotAttendanceService
    {
        return $this->getContainer()->get("fingerspot.fingerspot_attendance_service");
    }
}
