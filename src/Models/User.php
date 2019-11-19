<?php

namespace Devuniverse\Permissions\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Config;

use Devuniverse\Permissions\Models\Permission;
use Devuniverse\Permissions\Models\Entity;
use Devuniverse\Permissions\Models\Userrole;
use Devuniverse\Permissions\Models\Role;
use Devuniverse\Permissions\Models\Role_permission;

use \Staudenmeir\EloquentHasManyDeep\HasRelationships;


class User extends Authenticatable
{
  private function __construct(){

  }
  protected $table = 'users';

  public function userRole(){

    return $this->hasOneThrough(
        '\Devuniverse\Permissions\Models\Role',
        '\Devuniverse\Permissions\Models\Userrole',
        'user_id',
        'id'
    );
  }

  public function permissions($user=null)
  {

      return $this->hasMany('\Devuniverse\Permissions\Models\Role_permission');

  }

  static public function userPermissions($entityid=null){

    $user = \Auth::user();
    if($entityid !== null){
      $userRole = \Devuniverse\Permissions\Models\Userrole::where('user_id', $user->id)->where('entity_id',$entityid)->first();
    }else{
      $userRole = \Devuniverse\Permissions\Models\Userrole::where('user_id', $user->id)->first();
    }
    $pxs = [];
    if($userRole){
      $rolePermissions = \Devuniverse\Permissions\Models\Role_permission::where('role_id', $userRole->role_id)->get();
      if($rolePermissions){
        $pxs = $rolePermissions;
      }
    }
    return $pxs;
  }
  static public function userAdmin($user){
    $superAdminRole = Role::where('slug','superadmin')->first();
    $superAdminRoleId = $superAdminRole->id;
    $userRoles = Userrole::where('user_id', $user->id)->get();
    $userRolesX= array_column($userRoles->toArray(), 'role_id');
    return in_array($superAdminRoleId, $userRolesX ) ? true : false;
  }
  static public function userCan($permission, $entityid=null){
    if(self::userAdmin(Auth::user())){
      return true;
    }else{
      $permissions = self::userPermissions($entityid);
      if( $permissions ){
        $permissionIds = array_column($permissions->toArray(), 'permission_id');
      }else{
        $permissionIds = [];
      }

      $requested = \Devuniverse\Permissions\Models\Permission::where('slug', $permission)->first();
      if($requested){
        if (in_array($requested->id, $permissionIds)) {
          return true;
        }else{
          return false;
        }
      }else{
        return false;
      }
    }

  }

}
