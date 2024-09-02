<?php
    use App\Models\Crud;
    $this->Crud = new Crud();
?>

<?=$this->extend('designs/backend');?>
<?=$this->section('title');?>
    <?=$title;?>
<?=$this->endSection();?>

<?=$this->section('content');?>
    <div class="nk-content" >
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Access CRUD</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Manage application access CRUDs</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="nk-block">
                        <div class="row g-gs">
                            <div class="col-xxl-6">
                                <div class="row g-gs">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="role_id">Role</label>
                                            <select class="form-control js-select2" id="role_id" name="role_id" style="width: 100%;" data-placeholder="Choose one.." onchange="getModule();">
                                                <option value="">Select</option>
                                                <?php if(!empty($allrole)): ?>
                                                <?php foreach($allrole as $rol): ?>
                                                    <option value="<?php echo $rol->id; ?>" <?php if(!empty($e_role_id)){if($e_role_id == $rol->id){echo 'selected';}} ?>><?php echo $rol->name; ?></option>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12 table-responsive" style="max-height:400px;">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Module</th>
                                                    <th><u>C</u><span class="hidden-xs">reate</span></th>
                                                    <th><u>R</u><span class="hidden-xs">ead</span></th>
                                                    <th><u>U</u><span class="hidden-xs">pdate</span></th>
                                                    <th><u>D</u><span class="hidden-xs">elete</span></th>
                                                </tr>
                                            </thead>
                                            <tbody id="module_list">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?=$this->endSection();?>

<?=$this->section('scripts');?>
<script>
    $(function() {
        $('.js-select2').select2();
    });
    
    function getModule() {
        var role_id = $('#role_id').val();
        $('#module_list').html('<div class="row"><div class="text-center col-lg-12"><i class="anticon anticon-loading"></i></div></div>');
        $.ajax({
            url: '<?php echo site_url('settings/get_module'); ?>',
            type: 'post',
            data: {role_id: role_id},
            success: function(data) {
                $('#module_list').html(data);
            },
            complete: function() {
                // icheck();
            }
      });
    }

    function saveModule(x) {
        var rol = $('#rol').val();
        var mod = $('#mod' + x).val();
        var c = $('#c' + x);
        var r = $('#r' + x);
        var u = $('#u' + x);
        var d = $('#d' + x);
      
        if(c.is(':checked')){c = 1;} else {c = 0;}
        if(r.is(':checked')){r = 1;} else {r = 0;}
        if(u.is(':checked')){u = 1;} else {u = 0;}
        if(d.is(':checked')){d = 1;} else {d = 0;}
      
        $.ajax({
            url: '<?php echo site_url('settings/save_module'); ?>',
            type: 'post',
            data: {rol: rol, mod: mod, c: c, r: r, u: u, d: d},
            success: function(data) {
                //$('#module_list').html(data);
            }
        });
    }

    function icheck() {
        $('input[type="checkbox"].minimal-red').iCheck({
            checkboxClass: 'icheckbox_minimal-red'
        });
    }
  </script>
  
  <?=$this->endSection();?>