@extends(Config::get('permissions.master_file_extend'))

@section(Config::get('permissions.yields.head'))

@endsection
@section(Config::get('permissions.yields.permissions_content'))
<div class="permissions-main">
  <!-- ============================================================== -->
  <!-- Container fluid  -->
  <!-- ============================================================== -->
  <div class="container-fluid">
    <div class="row">
      @if($pxs->userCan('list_settings'))
      <div class="col-md-6">
        <div class="card">
          @if (session('status'))
              <div class="alert alert-{{ session('status')['msgtype'] }}" role="alert">
                  {{ session('status')['message'] }}
              </div>
          @endif

          <div class="list-roles">
            <h3>Choose a Role</h3>
            <select class="selectpicker select-role" data-live-search="true">
              @foreach($roles as $role)
              <option value="{{ $role->id }}">{{ $role->name }}</option>
              @endforeach
            </select>
            <div class="">

            </div>
          </div>
          <div class="list-permissions">
            <h3>Choose permissions</h3>
            <div class="perm-container">

            </div>
          </div>
        </div>
      </div>
      @else

      <div class="alert alert-danger" role="alert">
          {{ __('You are not authorized to do that') }}
      </div>

      @endif
    </div>
  </div>
</div>
@endsection


@section(Config::get('permissions.yields.footer'))
<script type="text/javascript">
  var permXUrl = "/<?php echo $permissionsUrl; ?>";

  $('.select-role').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {

    var token = $('[name="csrf-token"]').prop('content');
    var chosen= $(this).val();
    $('.perm-container').html('<div class="full-loading"><i class="fas fa-cog fa-spin"></i></div>');
    setTimeout(function(){
      $.ajax({
        url: permXUrl+'/updatepermissions',
        type: 'POST',
        crossDomain: true,
        data: { _token: token, v: chosen },
        success: function (response) {
          $('.perm-container').html(response.html);
        },
        error: function (errors) {
          alert(JSON.stringify(errors));
        }
      });
      e.stopImmediatePropagation();
    }, 1000);
    return false;
  });
</script>
@endsection
