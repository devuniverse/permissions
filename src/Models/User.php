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
  public function user_roles()
  {

      return $this->hasMany('\Devuniverse\Permissions\Models\Userrole');

  }
  static public function userPermissions($user,$entityid=0){

    $pxs = [];
    $userRole = \Devuniverse\Permissions\Models\Userrole::where('user_id', $user->id)->where('entity_id',$entityid)->first();
    if($userRole){
      $rolePermissions = \Devuniverse\Permissions\Models\Role_permission::where('role_id', $userRole->role_id)->get();
      if($rolePermissions){
        $pxs = $rolePermissions;
      }else{
        $pxs =[];
      }
    }else{
      $pxs =[];
    }
    return $pxs;
  }
  /**
   * userAdmin
   * @var object $user
   * @return binary
   */
  static public function userAdmin($user){
    $superAdminRole = Role::where('slug','superadmin')->first();
    $superAdminRoleId = $superAdminRole->id;
    $userRoles = Userrole::where('user_id', $user->id)->get();
    $userRolesX= array_column($userRoles->toArray(), 'role_id');
    return in_array($superAdminRoleId, $userRolesX ) ? true : false;
  }
  static public function userCan($permission, $entityid=0){
    $permissions = self::userPermissions(\Auth::user(), $entityid);
    if( $permissions ){
      $permissionIds = array_column($permissions->toArray(), 'permission_id');
    }else{
      $permissionIds = [];
    }
    $requested = \Devuniverse\Permissions\Models\Permission::where('slug', $permission)->first();
    if($requested){
      if (!in_array($requested->id, $permissionIds)) {
        return false;
      }else{
        return true;
      }
    }else{
      return false;
    }
  }

}
