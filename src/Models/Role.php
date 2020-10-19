<?php

namespace Devuniverse\Permissions\Models;

use Illuminate\Database\Eloquent\Model;

use Devuniverse\Permissions\Models\Permission;
use Devuniverse\Permissions\Models\Entity;
use Devuniverse\Permissions\Models\Userrole;
use Devuniverse\Permissions\Models\Role_permission;

class Role extends Model
{
  protected $table = 'roles_px';

    public function rolePermissions(){

        return $this->hasMany('Devuniverse\Permissions\Models\Role_permission','role_id');

    }

}
