<?php 

//  @author Muhammad Zulfi Rusdani <donimuzur@gmail.com>
//  @copyright 2023 PT Gunung Emas Putih

namespace OrangeHRM\Fingerspot\Service;

use DateTime;
use OrangeHRM\Admin\Traits\Service\UserServiceTrait;
use OrangeHRM\Config\Config;
use OrangeHRM\Core\Exception\CoreServiceException;
use OrangeHRM\Core\Service\IDGeneratorService;
use OrangeHRM\Core\Traits\EventDispatcherTrait;
use OrangeHRM\Core\Traits\Service\ConfigServiceTrait;
use OrangeHRM\Core\Traits\Service\NormalizerServiceTrait;
use OrangeHRM\Core\Traits\UserRoleManagerTrait;
use OrangeHRM\Entity\FingerspotAttendance;

use OrangeHRM\Fingerspot\Dao\FingerspotAttendanceDao;
use OrangeHRM\Fingerspot\Dto\FingerspotAttendanceSearchFilterParams;

class FingerspotAttendanceService
{
    /**
     * @var FingerspotAttendanceDao|null
     */
    private ?FingerspotAttendanceDao $FingerspotAttendanceDao = null;

    /**
     * @return FingerspotAttendanceDao|null
     */
    public function getFingerspotAttendanceDao(): FingerspotAttendanceDao
    {
        if (!($this->FingerspotAttendanceDao instanceof FingerspotAttendanceDao)) {
            $this->FingerspotAttendanceDao = new FingerspotAttendanceDao();
        }
        return $this->FingerspotAttendanceDao;
    }

    /**
     * @param $FingerspotAttendanceDao
     */
    public function setFingerspotAttendanceDao(FingerspotAttendanceDao $FingerspotAttendanceDao): void
    {
        $this->FingerspotAttendanceDao = $FingerspotAttendanceDao;
    }
    
    /**
    * @param FingerspotAttendanceSearchFilterParams $fingerspotAttendanceSearchParamHolder
    * @return FingerspotAttendance[]
    */
   public function getFingerspotAttendanceList(FingerspotAttendanceSearchFilterParams $fingerspotAttendanceSearchParamHolder): array
   {
       return $this->getFingerspotAttendanceDao()->searchFingerspotAttendance($fingerspotAttendanceSearchParamHolder);
   }

    /**
     * @param FingerspotAttendanceSearchFilterParams $fingerspotAttendanceSearchParamHolder
     * @return int
     */
    public function getFingerspotAttendanceCount(FingerspotAttendanceSearchFilterParams $fingerspotAttendanceSearchParamHolder): int
    {
        return $this->getFingerspotAttendanceDao()->getSearchFingerspotAttendancesCount($fingerspotAttendanceSearchParamHolder);
    }
}