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

namespace OrangeHRM\Core\Traits;

use OrangeHRM\Framework\Framework;
use OrangeHRM\Framework\Http\Request;
use OrangeHRM\Framework\Http\RequestStack;
use OrangeHRM\Framework\Http\Response;
use OrangeHRM\Framework\Services;

trait ControllerTrait
{
    /**
     * Forwards the request to another controller.
     *
     * @param string $controller The controller name (a string like OrangeHRM\Controller\PostController::handle)
     */
    protected function forward(string $controller, array $path = [], array $query = []): Response
    {
        $request = $this->getCurrentRequest();
        $path['_controller'] = $controller;
        $subRequest = $request->duplicate($query, null, $path);

        /** @var Framework $kernel */
        $kernel = $this->getContainer()->get(Services::HTTP_KERNEL);
        return $kernel->handle($subRequest, Framework::SUB_REQUEST);
    }

    /**
     * @return Request|null
     */
    protected function getCurrentRequest(): ?Request
    {
        /** @var RequestStack $requestStack */
        $requestStack = $this->getContainer()->get(Services::REQUEST_STACK);
        return $requestStack->getCurrentRequest();
    }
}