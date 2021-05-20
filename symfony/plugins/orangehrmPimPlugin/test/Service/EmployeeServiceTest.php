<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

namespace OrangeHRM\Pim\Tests\Service;

use OrangeHRM\Core\Service\ConfigService;
use OrangeHRM\Pim\Dao\EmployeeDao;
use OrangeHRM\Pim\Service\EmployeeService;
use OrangeHRM\Tests\Util\TestCase;

/**
 * @group Pim
 * @group Service
 */
class EmployeeServiceTest extends TestCase
{
    /**
     * @var EmployeeService
     */
    private EmployeeService $employeeService;

    protected function setUp(): void
    {
        $this->employeeService = new EmployeeService();
    }

    public function testGetSubordinateIdListBySupervisorId(): void
    {
        $subordinateIdList = [1, 2, 3];

        // includeChain = true
        $employeeService = $this->getMockBuilder(EmployeeService::class)
            ->onlyMethods(['getConfigService'])
            ->getMock();

        $mockDao = $this->getMockBuilder(EmployeeDao::class)
            ->onlyMethods(['getSubordinateIdListBySupervisorId'])
            ->getMock();
        $mockDao->expects($this->once())
            ->method('getSubordinateIdListBySupervisorId')
            ->with(1, true)
            ->will($this->returnValue($subordinateIdList));

        $employeeService->setEmployeeDao($mockDao);
        $result = $employeeService->getSubordinateIdListBySupervisorId(1, true);
        $this->assertEquals($subordinateIdList, $result);

        // includeChain = null
        $configService = $this->getMockBuilder(ConfigService::class)
            ->onlyMethods(['isSupervisorChainSupported'])
            ->getMock();
        $configService->expects($this->once())
            ->method('isSupervisorChainSupported')
            ->will($this->returnValue(true));

        $employeeService = $this->getMockBuilder(EmployeeService::class)
            ->onlyMethods(['getConfigService'])
            ->getMock();
        $employeeService->expects($this->once())
            ->method('getConfigService')
            ->will($this->returnValue($configService));

        $mockDao = $this->getMockBuilder(EmployeeDao::class)
            ->onlyMethods(['getSubordinateIdListBySupervisorId'])
            ->getMock();
        $mockDao->expects($this->once())
            ->method('getSubordinateIdListBySupervisorId')
            ->with(1, true)
            ->will($this->returnValue($subordinateIdList));

        $employeeService->setEmployeeDao($mockDao);
        $result = $employeeService->getSubordinateIdListBySupervisorId(1);
        $this->assertEquals($subordinateIdList, $result);
    }

    public function testIsSupervisor(): void
    {
        $empNumber = 111;
        $mockDao = $this->getMockBuilder(EmployeeDao::class)
            ->onlyMethods(['isSupervisor'])
            ->getMock();
        $mockDao->expects($this->once())
            ->method('isSupervisor')
            ->with($empNumber)
            ->will($this->returnValue(true));

        $this->employeeService->setEmployeeDao($mockDao);

        $result = $this->employeeService->isSupervisor($empNumber);
        $this->assertTrue($result);
    }
}