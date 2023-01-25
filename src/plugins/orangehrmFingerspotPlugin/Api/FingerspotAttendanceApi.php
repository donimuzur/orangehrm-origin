<?php

//  @author Muhammad Zulfi Rusdani <donimuzur@gmail.com>
//  @copyright 2023 PT Gunung Emas Putih


namespace OrangeHRM\Fingerspot\Api;

use OrangeHRM\Core\Api\CommonParams;
use OrangeHRM\Core\Api\V2\CrudEndpoint;
use OrangeHRM\Core\Api\V2\Endpoint;
use OrangeHRM\Core\Api\V2\EndpointCollectionResult;
use OrangeHRM\Core\Api\V2\EndpointResourceResult;
use OrangeHRM\Core\Api\V2\EndpointResult;
use OrangeHRM\Core\Api\V2\Exception\RecordNotFoundException;
use OrangeHRM\Core\Api\V2\Model\ArrayModel;
use OrangeHRM\Core\Api\V2\ParameterBag;
use OrangeHRM\Core\Api\V2\RequestParams;
use OrangeHRM\Core\Api\V2\Validator\ParamRule;
use OrangeHRM\Core\Api\V2\Validator\ParamRuleCollection;
use OrangeHRM\Core\Api\V2\Validator\Rule;
use OrangeHRM\Core\Api\V2\Validator\Rules;
use OrangeHRM\Core\Dto\Base64Attachment;
use OrangeHRM\Entity\FingerspotAttendance;
use OrangeHRM\Fingerspot\Api\Model\FingerspotAttendanceModel;
use OrangeHRM\Fingerspot\Dto\FingerspotAttendanceSearchFilterParams;
use OrangeHRM\Fingerspot\Service\FingerspotAttendanceService;
use OrangeHRM\Fingerspot\Traits\Service\FingerspotAttendanceServiceTrait;

class FingerspotAttendanceAPI extends Endpoint implements CrudEndpoint
{
    use FingerspotAttendanceServiceTrait;
    public const FILTER_PIN = 'pin';
    public const FILTER_SCAN_DATE = 'scanDate';

    public const PARAM_RULE_FILTER_PIN_MAX_LENGTH = 30;

    public const FILTER_MODEL = 'model';
    public const MODEL_DEFAULT = 'default';
    public const MODEL_MAP = [
        self::MODEL_DEFAULT => FingerspotAttendanceModel::class
    ];
    /**
     * @var null|FingerspotAttendanceService
     */
    protected ?FingerspotAttendanceService $FingerspotAttendanceService = null;



    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        return new EndpointCollectionResult(
            $this->getModelClass(),
            null
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {

        return new ParamRuleCollection(
            new ParamRule(
                CommonParams::PARAMETER_EMP_NUMBER,
                new Rule(Rules::IN_ACCESSIBLE_EMP_NUMBERS)
            ),
            $this->getModelParamRule(),
        );
    }

    /**
     * @inheritDoc
     */
    public function getAll(): EndpointCollectionResult
    {
        $fingerspotAttendanceParamHolder = new FingerspotAttendanceSearchFilterParams();
        $this->setSortingAndPaginationParams($fingerspotAttendanceParamHolder);

        $fingerspotAttendanceParamHolder->setPin( 
            $this->getRequestParams()->getStringOrNull(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_PIN
            )
        );

        $fingerspotAttendanceParamHolder->setScanDate(
            $this->getRequestParams()->getDateTimeOrNull(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_SCAN_DATE
            )
        );
        
        $fingerspotAttendanceList = $this->getFingerspotAttendanceService()->getFingerspotAttendanceList($fingerspotAttendanceParamHolder);
        $count = $this->getFingerspotAttendanceService()->getFingerspotAttendanceCount($fingerspotAttendanceParamHolder);
        return new EndpointCollectionResult(
            $this->getModelClass(),
            $fingerspotAttendanceList,
            new ParameterBag([CommonParams::PARAMETER_TOTAL => $count])
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(
                    self::FILTER_PIN,
                    new Rule(Rules::STRING_TYPE),
                    new Rule(Rules::LENGTH, [null, self::PARAM_RULE_FILTER_PIN_MAX_LENGTH]),
                ),
            ),
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(
                    self::FILTER_SCAN_DATE,
                    new Rule(Rules::DATE_TIME)
                )
            ),
            $this->getModelParamRule(),
            ...$this->getSortingAndPaginationParamsRules(FingerspotAttendanceSearchFilterParams::ALLOWED_SORT_FIELDS)
        );
    }

    /**
     * @inheritDoc
     */
    public function create(): EndpointResult
    {
        return new EndpointCollectionResult(
            $this->getModelClass(),
            null
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForCreate(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                CommonParams::PARAMETER_EMP_NUMBER,
                new Rule(Rules::IN_ACCESSIBLE_EMP_NUMBERS)
            ),
            $this->getModelParamRule(),
        );
    }

    /**
     * @inheritDoc
     */
    public function update(): EndpointResult
    {
        return new EndpointCollectionResult(
            $this->getModelClass(),
            null
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                CommonParams::PARAMETER_EMP_NUMBER,
                new Rule(Rules::IN_ACCESSIBLE_EMP_NUMBERS)
            ),
            $this->getModelParamRule(),
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(): EndpointResult
    {

        return new EndpointCollectionResult(
            $this->getModelClass(),
            null
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForDelete(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                CommonParams::PARAMETER_EMP_NUMBER,
                new Rule(Rules::IN_ACCESSIBLE_EMP_NUMBERS)
            ),
            $this->getModelParamRule(),
        );
    }

    
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        $model = $this->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_QUERY,
            self::FILTER_MODEL,
            self::MODEL_DEFAULT
        );
        return self::MODEL_MAP[$model];
    }

    protected function getModelParamRule(): ParamRule
    {
        return $this->getValidationDecorator()->notRequiredParamRule(
            new ParamRule(
                self::FILTER_MODEL,
                new Rule(Rules::IN, [array_keys(self::MODEL_MAP)])
            )
        );
    }
}
