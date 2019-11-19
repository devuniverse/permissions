<?php

namespace Devuniverse\Permissions\Models;

use Illuminate\Database\Eloquent\Model;

class Px extends Model
{
  static public function permissions(){
    if (file_exists(base_path('bootstrap/extraperms.php'))) {
      require(base_path('bootstrap/extraperms.php'));
    }
    $permissions = [
      ['access_dashboard' => 'Access dashboard'],
      ['access_profile' => 'Access profile'],

      ['access_module_posts' => 'Access post module'],
      ['access_module_pages' => 'Access page module'],
      ['access_module_projects' => 'Access Project module'],
      ['access_module_tasks' => 'Access Tasks module'],
      ['access_module_media' => 'Access media module'],
      ['access_module_users' => 'Access Users module'],
      ['access_module_profile' => 'Access profile module'],
      ['access_module_taxonomies' => 'Access Taxonomies module'],
      ['access_module_settings' => 'Access settings module'],


      ['read_own_post' => 'Read Own Post'],
      ['read_post' => 'Read Post'],
      ['list_posts'=> 'List Posts'],
      ['create_post' => 'Create Post'],
      ['update_post' => 'Edit Post'],
      ['delete_post' => 'Delete Post'],
      ['list_taxonomies' => 'List taxonomies'],
      ['create_taxonomy' => 'Create Taxonomy'],
      ['edit_taxonomy' => 'Edit Taxonomy'],
      ['delete_taxonomy' => 'Delete Taxonomy'],
      ['list_taxonomy_terms' => 'List taxonomy terms'],
      ['create_taxonomy_term' => 'Create Taxonomy term'],
      ['edit_taxonomy_term' => 'Edit Taxonomy term'],
      ['delete_taxonomy_term' => 'Delete Taxonomy term'],

      ['read_project' => 'Read Project'],
      ['list_projects'=> 'List Projects'],
      ['create_project' => 'Create Project'],
      ['update_project' => 'Edit Project'],
      ['delete_project' => 'Delete Project'],

      ['read_own_media' => 'Read Own Media'],
      ['read_media' => 'Read Media'],
      ['list_medias'=> 'List Media'],
      ['create_media' => 'Create Media'],
      ['update_media' => 'Edit Media'],
      ['delete_media' => 'Delete Media'],

      ['read_user' => 'Read User'],
      ['list_users'=> 'List Users'],
      ['create_user' => 'Create User'],
      ['update_user' => 'Edit User'],
      ['change_user_password' => 'Change User Password'],
      ['change_user_avatar' => 'Change User Avatar'],
      ['delete_user' => 'Delete User'],
      ['send_invitation'=>'Send invitation'],
      ['remove_invitation'=>'Remove invitation'],
      ['remove_user' => 'Remove User'],

      ['read_setting' => 'Read Setting'],
      ['list_settings'=> 'List Settings'],
      ['create_setting' => 'Create Setting'],
      ['update_setting' => 'Edit Setting'],
      ['delete_setting' => 'Delete Setting'],

      ['read_permission'   => 'Read Permission'],
      ['create_permission' => 'Create Permission'],
      ['update_permission' => 'Update Permission'],
      ['delete_permission' => 'Delete Permission'],

      ['system_maintenance' => 'System Maintenance'],
      ['system_shutdown'=> 'System Shutdown'],
      ['system_statistics' => 'System Statistics'],
      ['switch_theme' => 'Switch Theme'],

    ];

    $full = array_merge($extras, $permissions);

    return $full;
  }

  static public function roles(){
    if (file_exists(base_path('bootstrap/extraperms.php'))) {
      require(base_path('bootstrap/extraroles.php'));
    }
    $roles = [
      ['superadmin' => 'Super Admin'],
      ['supportadmin' => 'Support Admin'],
      ['admin'=> 'Admin'],
      ['editor' => 'Editor'],
      ['owner' => 'Owner'],
    ];
    $full = array_merge($extras, $roles);

    return $full;
  }

}
