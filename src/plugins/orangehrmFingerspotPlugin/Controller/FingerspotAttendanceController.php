<?php

//  @author Muhammad Zulfi Rusdani <donimuzur@gmail.com>
//  @copyright 2023 PT Gunung Emas Putih


namespace OrangeHRM\Fingerspot\Controller;

use OrangeHRM\Core\Controller\AbstractVueController;
use OrangeHRM\Core\Helper\VueControllerHelper;
use OrangeHRM\Core\Vue\Component;
use OrangeHRM\Framework\Http\Request;

class FingerspotAttendanceController extends AbstractVueController
{
    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $component = new Component('fingerspotattendance-list');
        // $component = new Component('employee-list');
        $this->setComponent($component);

        $allowedToDeleteActive = false;
        $allowedToDeleteTerminated = false;
        $permissionsArray['fingerspotattendance_list'] = [
            'canRead' => true,
            'canCreate' => false,
            'canUpdate' => true,
            'canDelete' => $allowedToDeleteActive || $allowedToDeleteTerminated,
        ];
        $this->getContext()->set(
            VueControllerHelper::PERMISSIONS,
            $permissionsArray
        );
    }
}
