<?php

//  @author Muhammad Zulfi Rusdani <donimuzur@gmail.com>
//  @copyright 2023 PT Gunung Emas Putih


namespace OrangeHRM\Fingerspot\Dao;

use Doctrine\ORM\Query\Expr;
use Exception;
use OrangeHRM\Core\Dao\BaseDao;
use OrangeHRM\Core\Exception\DaoException;
use OrangeHRM\Entity\FingerspotAttendance;
use OrangeHRM\ORM\Paginator;
use OrangeHRM\Fingerspot\Dto\FingerspotAttendanceSearchFilterParams;

class FingerspotAttendanceDao extends BaseDao
{
    /**
     * @param FingerspotAttendance $FingerspotAttendance
     * @return FingerspotAttendance
     * @throws DaoException
     */
    public function saveFingerspotAttendance(FingerspotAttendance $FingerspotAttendance): FingerspotAttendance
    {
        try {
            $this->persist($FingerspotAttendance);
            return $FingerspotAttendance;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $pin
     * @return FingerspotAttendance|null
     * @throws DaoException
     */
    public function getFingerspotAttendanceByPin(string $pin): ?FingerspotAttendance
    {
        try {
            $FingerspotAttendance = $this->getRepository(FingerspotAttendance::class)->findOneBy(
                [
                    'pin' => $pin,
                ]
            );
            if ($FingerspotAttendance instanceof FingerspotAttendance) {
                return $FingerspotAttendance;
            }
            return null;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Search FingerspotAttendance
     *
     * @param FingerspotAttendanceSearchFilterParams $FingerspotAttendanceSearchParams
     * @return FingerspotAttendance[]
     * @throws DaoException
    */
    public function searchFingerspotAttendance(FingerspotAttendanceSearchFilterParams $FingerspotAttendanceSearchParams): array
    {
        try {
            $paginator = $this->getSearchFingerspotAttendancePaginator($FingerspotAttendanceSearchParams);
            $tmp = $paginator->getQuery()->getSQL();
            return $paginator->getQuery()->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param FingerspotAttendanceSearchFilterParams $FingerspotAttendanceSearchParams
     * @return Paginator
     */
    private function getSearchFingerspotAttendancePaginator(
        FingerspotAttendanceSearchFilterParams $FingerspotAttendanceSearchParams
    ): Paginator {
        $q = $this->createQueryBuilder(FingerspotAttendance::class, 'fingerspotAttendance');
        $this->setSortingAndPaginationParams($q, $FingerspotAttendanceSearchParams);
        if(!is_null($FingerspotAttendanceSearchParams->getPin()) && !empty($FingerspotAttendanceSearchParams->getPin())){
            $q->andWhere('fingerspotAttendance.pin= :pin')
            ->setParameter('pin', $FingerspotAttendanceSearchParams->getPin());
        }
        if(!is_null($FingerspotAttendanceSearchParams->getScanDate())){
            $q->andWhere('fingerspotAttendance.scanDate >= :scanDate')
                ->setParameter('scanDate',$FingerspotAttendanceSearchParams->getScanDate()->format('Y-m-d') );
        }
        return $this->getPaginator($q);
    }

    /**
     * Get Count of Search Query
     *
     * @param FingerspotAttendanceSearchFilterParams $FingerspotAttendanceSearchParams
     * @return int
     * @throws DaoException
     */
    public function getSearchFingerspotAttendancesCount(FingerspotAttendanceSearchFilterParams $FingerspotAttendanceSearchParams): int
    {
        try {
            $paginator = $this->getSearchFingerspotAttendancePaginator($FingerspotAttendanceSearchParams);
            return $paginator->count();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
