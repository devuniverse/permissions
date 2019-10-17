<?php

namespace Devuniverse\Permissions\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Config;

class AssignSuperadmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:superadmin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign an existing user a super admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email');

        if(User::where('email',$email)->exists()){
          $userX  = User::where('email',$email)->first();
          $userid  = $userX->id;
          $permissions = \Devuniverse\Permissions\Models\Px::permissions();
          echo '|***********************************************|'. PHP_EOL;
          echo '|******* INSTALLING DEFAULT PERMISSIONS ********|'. PHP_EOL;
          echo '|***********************************************|'. PHP_EOL;
          foreach(\Devuniverse\Permissions\Models\Px::permissions() as $key => $value){

            foreach( $value as $k => $v ){

              $found =\Devuniverse\Permissions\Models\Permission::where('slug', $k)->first();
              if( !$found ){

                $permission = new\Devuniverse\Permissions\Models\Permission();
                $permission->slug = $k;
                $permission->name = $v;
                $permission->save();
                echo $k.'Permission added'. PHP_EOL;

              }else{

                echo $k.' permission exists.'.PHP_EOL;

              }

            }

          }
          echo '|***********************************************|'. PHP_EOL;
          echo '|******* INSTALLING DEFAULT ROLES       ********|'. PHP_EOL;
          echo '|***********************************************|'. PHP_EOL;
          foreach(\Devuniverse\Permissions\Models\Px::roles() as $key => $value){

            foreach( $value as $k => $v ){

              $found =\Devuniverse\Permissions\Models\Role::where('slug', $k)->first();
              if( !$found ){

                $role = new\Devuniverse\Permissions\Models\Role();
                $role->slug = $k;
                $role->name = $v;
                $role->save();
                echo $k.' Role added.'.PHP_EOL;

              }

            }

          }

          /*********************************************************************************
          ******* Once the role has been logged, we assign the superadmin to the user*******
          *********************************************************************************/
          $sadminRole =\Devuniverse\Permissions\Models\Role::where('slug','superadmin')->first();

          //Whether in multi or single modes, the permissions are always guaranteed

          if( ! \Devuniverse\Permissions\Models\Userrole::where('user_id', $userX->id)->where('role_id', $sadminRole->id)->first() ){
            $userRole = new \Devuniverse\Permissions\Models\Userrole();
            $userRole->user_id = $userX->id;
            $userRole->role_id = $sadminRole->id;
            $userRole->save();
            echo ' ====== '.$email.' assigned Super admin access ====== '.PHP_EOL;
          }else{
            echo ' ====== '.$email.' already has Super admin access ====== '.PHP_EOL;
          }

          /**
           * We then proceed to insert other role permissions
           */
          $allPermissions =\Devuniverse\Permissions\Models\Permission::get();
          echo '|***********************************************|'. PHP_EOL;
          echo '|*******  ADDING SUPER ADMIN PE**RMISSION   ****|'. PHP_EOL;
          echo '|***********************************************|'. PHP_EOL;

          $sadmin =\Devuniverse\Permissions\Models\Role::where('slug', 'superadmin')->first();

          if($sadmin){

            foreach ($allPermissions as $f => $p) {
              $assignSadminExists =\Devuniverse\Permissions\Models\Role_permission::where('role_id', $sadmin->id)->where('permission_id', $p->id)->first();
              if(!$assignSadminExists){
                $assignSadmin = new\Devuniverse\Permissions\Models\Role_permission();
                $assignSadmin->role_id = $sadmin->id;
                $assignSadmin->permission_id = $p->id;
                $assignSadmin->save();
                echo 'SUPERADMIN given : '.$p->slug.''. PHP_EOL;
              }
            }

          }

          echo '|***********************************************|'. PHP_EOL;
          echo '|*******  ADDING ADMIN PERMISSION   ************|'. PHP_EOL;
          echo '|***********************************************|'. PHP_EOL;

          $admin =\Devuniverse\Permissions\Models\Role::where('slug', 'admin')->first();

          $toIncludeAdmin =  ['read_own_post','read_post','list_posts','create_post','update_post','delete_post','read_project','list_projects','create_project','update_project','delete_project','read_own_media','read_media','list_medias','create_media','update_media','delete_media','read_user','list_users','create_user','update_user','delete_user','read_setting','list_settings','create_setting','update_setting','access_dashboard','access_profile','access_module_posts','access_module_pages','access_module_projects','access_module_media','access_module_users','access_module_profile'];

          if($admin){

            foreach ($toIncludeAdmin as $f => $p) {
              $perm =\Devuniverse\Permissions\Models\Permission::where('slug', $p)->first();

              $assignAdminExists =\Devuniverse\Permissions\Models\Role_permission::where('role_id', $admin->id)->where('permission_id', $perm->id)->first();
              if(!$assignAdminExists){
                $assignAdmin = new\Devuniverse\Permissions\Models\Role_permission();
                $assignAdmin->role_id = $admin->id;
                $assignAdmin->permission_id = $perm->id;
                $assignAdmin->save();
                echo 'ADMIN given : '.$p.''. PHP_EOL;
              }
            }

          }

          echo '|***********************************************|'. PHP_EOL;
          echo '|*******  ADDING EDITOR      PERMISSION   ******|'. PHP_EOL;
          echo '|***********************************************|'. PHP_EOL;

          $editor =\Devuniverse\Permissions\Models\Role::where('slug', 'editor')->first();

          $toIncludeEditor =  ['read_own_post','read_post','list_posts','create_post','update_post','delete_post','read_project','list_projects','create_project','update_project','read_own_media','read_media','list_medias','create_media','update_media','delete_media','access_dashboard','access_profile','access_module_posts','access_module_pages','access_module_projects','access_module_media','access_module_users','access_module_profile'];

          if($editor){

            foreach ($toIncludeEditor as $e => $ee) {
              $permEditor =\Devuniverse\Permissions\Models\Permission::where('slug', $ee)->first();

              $assignEditorExists =\Devuniverse\Permissions\Models\Role_permission::where('role_id', $editor->id)->where('permission_id', $permEditor->id)->first();
              if(!$assignEditorExists){
                $assignEditor = new\Devuniverse\Permissions\Models\Role_permission();
                $assignEditor->role_id = $editor->id;
                $assignEditor->permission_id = $permEditor->id;
                $assignEditor->save();
                echo 'EDITOR given : '.$ee.''. PHP_EOL;
              }
            }

          }

          echo '|***********************************************|'. PHP_EOL;
          echo '|*******  ADDING OWNER      PERMISSIONS   ******|'. PHP_EOL;
          echo '|***********************************************|'. PHP_EOL;

          $owner =\Devuniverse\Permissions\Models\Role::where('slug', 'owner')->first();

          $toIncludeOwner =  ['access_dashboard','access_profile','access_module_posts','access_module_projects','access_module_tasks','access_module_media','access_module_profile','read_own_post','read_post','list_posts','create_post','update_post','delete_post','read_project','list_projects','create_project','update_project','delete_project','read_own_media','read_media','list_medias','create_media','update_media','delete_media','read_task','list_tasks','create_task','update_task','delete_task','manage_team'];

          if($owner){

            foreach ($toIncludeOwner as $ex => $eex) {
              $permOwner =\Devuniverse\Permissions\Models\Permission::where('slug', $eex)->first();

              $assignOwnerExists =\Devuniverse\Permissions\Models\Role_permission::where('role_id', $owner->id)->where('permission_id', $permOwner->id)->first();
              if(!$assignOwnerExists){
                $assignOwner = new \Devuniverse\Permissions\Models\Role_permission();
                $assignOwner->role_id = $owner->id;
                $assignOwner->permission_id = $permOwner->id;
                $assignOwner->save();
                echo 'OWNER given : '.$eex.''. PHP_EOL;
              }
            }

          }
          echo '|***********************************************|'. PHP_EOL;
          echo '|*********  ADDING MEMBER PERMISSIONS   ********|'. PHP_EOL;
          echo '|***********************************************|'. PHP_EOL;

          $member =\Devuniverse\Permissions\Models\Role::where('slug', 'team_member')->first();

          $toIncludeMember =  ['access_dashboard','access_profile','access_module_posts','access_module_projects','access_module_tasks','access_module_media','access_module_profile','read_own_post','read_project','read_own_media','read_media','list_medias','create_media','update_media','delete_media','read_task','list_tasks','create_task','update_task'];

          if($member){

            foreach ($toIncludeMember as $exm => $eexm) {
              $permMember =\Devuniverse\Permissions\Models\Permission::where('slug', $eexm)->first();

              $assignMemberExists =\Devuniverse\Permissions\Models\Role_permission::where('role_id', $member->id)->where('permission_id', $permMember->id)->first();
              if(!$assignMemberExists){
                $assignMember = new \Devuniverse\Permissions\Models\Role_permission();
                $assignMember->role_id = $member->id;
                $assignMember->permission_id = $permMember->id;
                $assignMember->save();
                echo 'Member given : '.$eexm.''. PHP_EOL;
              }
            }

          }
          return '';
          exit;

        }else{
          echo 'User does NOT exist'. PHP_EOL;
          exit;
        }
    }
}
