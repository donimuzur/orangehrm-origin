<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PerformanceReviewDao
 *
 * @author nadeera
 */
class PerformanceReviewDao extends BaseDao {

    /**
     *
     * @param sfDoctrineRecord $review
     * @return PerformanceReview      
     */
    public function saveReview(sfDoctrineRecord $review) {
        try {
            $review->save();
            $review->refresh();
            return $review;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     *
     * @param array $parameters
     * @return Doctrine_Collection
     * @throws DaoException 
     */
    public function searchReview($parameters, $orderby = null) {
        
        if($orderby == null ){
            $orderby = 'e.emp_firstname,r.group.piority';
        }
        
        if( $orderby == 'piority'){
            $orderby = "r.group.piority";
        }
        try {

            $query = Doctrine_Query:: create()->from('PerformanceReview p');
            $query->leftJoin("p.Employee e");
            $query->leftJoin("p.reviewers r");
            $query->leftJoin("r.rating rating");

            if (isset($parameters['reviewerId']) && $parameters['reviewerId'] > 0) {
                $query->andWhere('r.employeeNumber = ?', $parameters['reviewerId']);
                $query->andWhere('r.id = rating.reviewer_id');
            }

            if (!empty($parameters)) {
                if (isset($parameters['id']) && $parameters['id'] > 0) {
                    $query->andWhere('id = ?', $parameters['id']);
                    return $query->fetchOne();
                } else {
                    foreach ($parameters as $key => $parameter) {
                        if (is_array($parameter) || strlen(trim($parameter)) > 0) {
                            switch ($key) {
                                case 'employeeName':
                                    $query->andWhere("CONCAT(e.emp_firstname,IF(LENGTH(e.emp_middle_name)>0,' ',''),e.emp_middle_name,' ',e.emp_lastname) LIKE ?", "%" . $parameter . "%");
                                    break;
                                case 'jobTitleCode':
                                    $query->andWhere('jobTitleCode = ?', $parameter);
                                    break;
                                case 'departmentId':
                                    $query->andWhere('departmentId = ?', $parameter);
                                    break;
                                case 'from':
                                    $query->andWhere('dueDate >= ?', $parameter);
                                    break;
                                case 'to':
                                    $query->andWhere('dueDate <= ?', $parameter);
                                    break;
                                case 'employeeNumber':
                                    $query->andWhere('e.empNumber = ?', $parameter);
                                    break;
                                case 'status':
                                    $query->andWhereIn('p.status_id', $parameter);
                                    break;
                                case 'employeeNotIn':
                                    $query->andWhereNotIn('e.empNumber', $parameter);
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                }
            }          

            $query->orderBy($orderby); 
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
    
/**
 *
 * @param type $reviwerEmployeeId
 * @return type 
 */
    public function getReviwerEmployeeList( $reviwerEmployeeId ){
        try {

            $query = Doctrine_Query:: create()
                      ->from('PerformanceReview p');
            
            $query->leftJoin("p.Employee e");
            $query->leftJoin("p.reviewers r");

            
            $query->andWhere('r.employeeNumber = ?', $reviwerEmployeeId);
            $query->andWhere('e.empNumber != ?', $reviwerEmployeeId);

            
            $query->orderBy('e.emp_firstname');
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
    
    /**
     *
     * @param integer $ids
     * @return boolean
     * @throws DaoException 
     */
    public function deleteReview($ids) {
        try {
            if (sizeof($ids)) {
                $q = Doctrine_Query::create()
                        ->delete('PerformanceReview')
                        ->whereIn('id', $ids);
                $q->execute();
            }
            return true;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     *
     * @param integer $id
     * @return boolean
     * @throws DaoException 
     */
    public function deleteReviewersByReviewId($id) {
        try {
            $q = Doctrine_Query::create()
                    ->delete('Reviewer')
                    ->whereIn('review_id', $id);
            $q->execute();
            return true;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     *
     * @param integer $id
     * @return boolean
     * @throws DaoException 
     */
    public function searchRating($parameters = null) {
        
        
        try {
            $q = Doctrine_Query::create()->from('ReviewerRating');
            if (isset($parameters['id']) && sizeof($parameters) == 1) {               
                $q->whereIn('id', $parameters['id']);
                return $q->fetchOne();               
            } else {   
                if(is_array($parameters)){
                    foreach ($parameters as $key=>$parameter) {
                        if (strlen($parameter) > 0) {
                            switch ($key) {
                                case 'reviewId':
                                    $q->andWhere('review_id =?', $parameter);
                                    break;
                                default:
                                case 'id':
                                    $q->andWhere('id =?', $parameter);
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                }
                return $q->execute();
            }
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

}