<?php

namespace OrangeHRM\Fingerspot\Controller;

use Exception;
use OrangeHRM\Core\Controller\AbstractModuleController;
use OrangeHRM\Framework\Http\RedirectResponse;

class FingerspotModuleController extends AbstractModuleController
{
    /**
     * @return RedirectResponse
     * @throws Exception
     */
    public function handle(): RedirectResponse
    {
        $defaultPath = $this->getPimModuleDefaultPath();
        return $this->redirect($defaultPath);
    }  
    /**
     * @return string|null
     */
    public function getPimModuleDefaultPath(): ?string
    {
        return $this->getHomePageService()->getModuleDefaultPage('fingerspot');
    }
}
