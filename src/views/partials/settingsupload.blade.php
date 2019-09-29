<?php
$permsPath = Config::get('permissions.permissions_url');
?>
<form class="save-permissions" action="/{{ $permissionsUrl }}/savepermissions" method="post">
  @csrf
  <input type="hidden" name="requestedrole" value="{{ $requestedRole }}">
  @foreach($permissions as $permission)

  <div class="custom-control custom-checkbox mb-3 permission-single col col-md-6">
    <div class="permission-innner">
      <input type="checkbox" class="custom-control-input" <?php if(in_array($permission->id, $rPermissions)){ ?> checked <?php } ?> id="permission-{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}">
      <label class="custom-control-label" for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
    </div>
  </div>

  @endforeach

  <div class="cta-final">
    <button type="submit" class="btn btn-primary btn-lg">Save Role Permissions</button>
  </div>
</form>
