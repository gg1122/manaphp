<?php
namespace Application\Admin\Areas\Rbac\Controllers;

use Application\Admin\Forms\RbacRoleCreateForm;
use Application\Admin\Models\RbacRole;

class RoleController extends ControllerBase
{
    public function indexAction()
    {
        $roles = RbacRole::query()->execute();
        $this->view->setVars(compact('roles'));
    }

    public function createAction(RbacRoleCreateForm $rbacRoleCreateForm)
    {
        if ($this->request->isAjax()) {
            if (RbacRole::exists(['role_name' => $rbacRoleCreateForm->role_name])) {
                return $this->response->setJsonContent(['code' => __LINE__, 'error' => 'role exists']);
            }

            $rbacRole = new RbacRole();
            $rbacRole->assign($rbacRoleCreateForm->toArray());
            $rbacRole->enabled = 1;
            $rbacRole->created_time = time();

            $rbacRole->create();

            return $this->response->setJsonContent(['code' => 0, 'error' => '']);
        }
    }
}