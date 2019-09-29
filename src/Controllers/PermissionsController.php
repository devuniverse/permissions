<?php

namespace Devuniverse\Permissions\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Config;
use Auth;



class PermissionsController extends Controller
{

    public function __construct()
    {

    }
    public function index(){

      return view('permissions::permissions');

    }

    public function updatePermissions(Request $req){
      $role = $req->v;
      $permissions = \Devuniverse\Permissions\Models\Permission::orderBy('id', 'desc')->get();
      $rPermissions  = \Devuniverse\Permissions\Models\Role_permission::where( 'role_id',$role )->get();
      $rP = [];
      $i = 0;
      foreach ($rPermissions as $value) {
        $rP[$i] = $value->permission_id;
        $i++;
      }
      $whereTo = Config::get('permissions.permissions_url');
      $returnHTML = view('permissions::partials.settingsupload',[ 'permissions'=>$permissions, 'rPermissions'=>$rP, 'requestedRole'=>$role ])->render();

      return response()->json(array('success' => true, 'html'=>$returnHTML));
    }
    /**
     * Updates permissions saved by a logged user in the backend
     * @return binary true when successful
     */
    public function savepermissions(Request $req){

      $userl = new \Devuniverse\Permissions\Models\User();

      if($userl->userCan('update_permission')){
        $role        = $req->requestedrole;
        $permissions = $req->permissions;
        //We delete existing ones
        $existing  = \Devuniverse\Permissions\Models\Role_permission::where('role_id', $role)->get();
        foreach ($existing as $value) {
          $value->delete();
        }

        foreach ($permissions as $k => $v) {
          $combo = new \Devuniverse\Permissions\Models\Role_permission();
          $combo->role_id       = $role;
          $combo->permission_id = $v;
          $combo->save();
        }
        if($combo){
          $message = "Updated successfully";
          $msgtype = "success";
        }else{
          $message = "There were errors";
          $msgtype = "danger";
        }
      }else{
        $message = "You are not authorized to do that";
        $msgtype = "danger";
      }
      $whereTo = Config::get('permissions.permissions_url');

      return redirect('/'.$whereTo)->with('status', ["message"=>$message, "msgtype"=>$msgtype]);
    }
}
