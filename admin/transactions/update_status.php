<?php
require_once('../../config.php');
if(isset($_GET['transaction_id'])){
    $qry = $conn->query("SELECT * FROM `transaction_list` where id = '{$_GET['transaction_id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }else{
        echo "<center><small class='text-muted'>ID de transaction inconnu.</small</center>";
        exit;
    }
}else{
    echo "<center><small class='text-muted'>L'ID de transaction est requis.</small</center>";
    exit;
}
?>
<style>
	img#cimg{
		height: 17vh;
		width: 25vw;
		object-fit: scale-down;
	}
</style>
<div class="container-fluid">
    <form action="" id="status-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="form-group">
            <label for="status" class="control-label">Status</label>
            <select id="status" name ="status" class="form-control form-control-border form-control-sm" required>
                <option value="0" <?= isset($status) && $status == 0 ? 'selected' : '' ?>>En attente</option>
                <option value="1" <?= isset($status) && $status == 1 ? 'selected' : '' ?>>En Cours</option>
                <option value="2" <?= isset($status) && $status == 2 ? 'selected' : '' ?>>Fait</option>
            </select>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('#uni_modal').on('shown.bs.modal',function(){
            $('#amount').focus();
        })
        $('#uni_modal #status-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            if(_this[0].checkValidity() == false){
                _this[0].reportValidity();
                return false;
            }
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=update_transaction_status",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("Une erreur s'est produite",'error');
					end_loader();
				},
                success:function(resp){
                    if(resp.status == 'success'){
                        location.reload();
                    }else if(!!resp.msg){
                        el.addClass("alert-danger")
                        el.text(resp.msg)
                        _this.prepend(el)
                    }else{
                        el.addClass("alert-danger")
                        el.text("Une erreur s'est produite pour une raison inconnue.")
                        _this.prepend(el)
                    }
                    el.show('slow')
                    $('html,body,.modal').animate({scrollTop:0},'fast')
                    end_loader();
                }
            })
        })
    })
</script>