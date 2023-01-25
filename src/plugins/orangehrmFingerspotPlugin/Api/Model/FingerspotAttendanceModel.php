<?php

//  @author Muhammad Zulfi Rusdani <donimuzur@gmail.com>
//  @copyright 2023 PT Gunung Emas Putih


namespace OrangeHRM\Fingerspot\Api\Model;

use OrangeHRM\Core\Api\V2\Serializer\ModelTrait;
use OrangeHRM\Core\Api\V2\Serializer\Normalizable;
use OrangeHRM\Entity\FingerspotAttendance;

class FingerspotAttendanceModel implements Normalizable
{
    use ModelTrait;

    public function __construct(FingerspotAttendance $FingerspotAttendance)
    {
        $this->setEntity($FingerspotAttendance);
        $this->setFilters(
            [
                'scanDate',
                'pin'
            ]
        );
        $this->setAttributeNames(
            [
                'scanDate',
                'pin'
            ]
        );
    }
}