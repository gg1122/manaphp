<?php
namespace Application\Admin\Areas\Rbac\Controllers;

use ManaPHP\Mvc\Controller;

class ControllerBase extends Controller
{
    public function beforeExecuteRoute()
    {
        $this->view->setLayout('Default');
    }
}