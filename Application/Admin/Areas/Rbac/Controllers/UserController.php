<?php
namespace Application\Admin\Areas\Rbac\Controllers;

use Application\Admin\Models\Admin;
use Application\Admin\Models\AdminDetail;

class UserController extends ControllerBase
{
    public function indexAction()
    {
        $builder = Admin::query('a')
            ->columns('a.admin_id, a.admin_name, a.created_time, ad.email')
            ->leftJoin(AdminDetail::class, 'ad.admin_id=a.admin_id', 'ad')
            ->orderBy('a.admin_id DESC');

        $builder->paginate(15, $this->request->get('page', 'int', 1));
    }
}