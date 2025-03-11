<?php $Page_Name="系統管理" ; $Page_Permission = 7 ; include("Assets/Header.php") ; 
	$Fetch = $Connection->query("SELECT * FROM `System_Account` WHERE `User_Permission` >= 0 ORDER BY `User_Permission` DESC")->fetchAll(PDO::FETCH_ASSOC) ;
?>
<div class="card mb-2">
    <div class="card-header">新增人員</div>
    <div class="card-body">
        <form id="Add">
			<div class="form-group row my-0">
                <label class="col-4 col-lg-2 col-form-label my-2">電話：</label>
                <div class="col-8 col-lg-3 my-2">
                    <input type="text" class="form-control" name="UID" >
                </div>
				<label class="col-4 col-lg-2 col-form-label my-2">暱稱：</label>
                <div class="col-8 col-lg-3 my-2">
                    <input type="text" class="form-control" name="NickName" >
                </div>
                <div class="col-lg-2 my-2">
                    <input type="hidden" value="Add" name="Type">
                    <input type="submit" name="submit" value="確認建立" class="btn btn-primary btn-block"/>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
	if (empty($Fetch)) {
		echo "<div class=\"alert alert-success mb-2\" role=\"alert\">
			目前沒有等待確認的項目。
		</div>" ;
	} else {
		echo "<div class=\"card mb-2\">
			<div class=\"card-body\">
				<table class=\"table table-responsive-lg\">
					<thead>
						<tr>
							<th scope=\"col\">Name</th>
							<th scope=\"col\">ID</th>
							<th scope=\"col\">最後登入時間</th>
							<th scope=\"col\">功能</th>
						</tr>
					</thead>
					<tbody>" ;
						foreach ($Fetch as $Value) {echo "<tr id=\"{$Value['User_ID']}\">
							<td nowrap=\"nowrap\">{$Value['User_Name']}</td>
							<td nowrap=\"nowrap\">{$Value['User_ID']}</td>
							<td nowrap=\"nowrap\">{$Value['Last_Time']}</td>
							<td nowrap=\"nowrap\">
								<button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"Btn('{$Value['User_ID']}', 'Cancel', '停用（{$Value['User_Name']}）的權限');\"> <i class=\"fas fa-times\"></i> 停用 </button>
								<button type=\"button\" class=\"btn btn-info btn-sm\" data-bs-toggle=\"modal\" data-bs-target=\"#ItemModal\" id=\"{$Value['User_ID']}\" data-Name=\"{$Value['User_Name']}\"> <i class=\"fa-solid fa-pen\"></i> 編輯 </button>
							</td>
						</tr>" ;}
					echo "</tbody>
				</table>
			</div>
		</div>" ;
	}
?>
<div class="modal" id="ItemModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="Change">
				<div class="modal-header">
					<h5 class="modal-title">Loading...</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label for="Permission" class="col-4 col-form-label">使用權限：</label>
						<div class="col-8">
							<select class="form-control" name="Permission">
								<option value="0">保持目前權限</option>
								<option value="1">一般帳號</option>
								<option value="7">管理帳號</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="Pin" class="col-4 col-form-label">ＰＩＮ碼：</label>
						<div class="col-8">
							<input type="text" class="form-control" id="Pin" name="Pin">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" id="UID" name="UID">
					<input type="hidden" name="Type" value="Edit">
					<button type="submit" class="btn btn-primary">Send</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$('.btn-info').click(function(){
		var UID = $(this).attr('id')
		var Name = $(this).data('name')
		
		var modal = $('#ItemModal')
		modal.find('.modal-title').text(Name)
		modal.find('#UID').val(UID)
	})
	$("#Change").submit(function(e){
		var postData = $(this).serializeArray();
		Swal.fire({text:'執行中...',showConfirmButton:false})
		$.ajax({
			url : "Ajax/Account",
			type: "POST",
			data : postData,
			success:function(data) {
				if(data.Status=='Ok') {
					$('#ItemModal').modal('hide')
					Swal.fire({icon: 'success',title: '執行成功',text: data.Msg})
					.then(() => {Swal.fire({text: '頁面重整中',showConfirmButton: false,});window.location.reload();})
				} else {
					Swal.fire({icon: 'error',title: '處理錯誤',text: data.Msg})
				}
			},
			beforeSend: function() { Swal.showLoading() },
			complete: function() { Swal.hideLoading() },
			error: function(jqXHR, textStatus, thrownError) {
				Swal.fire({icon: 'error',title: '系統錯誤',text: thrownError})
			}
		});
		e.preventDefault();
	});
	function Btn(ID, Type, Name) {
		Swal.fire({
			icon: 'question',
			title: '動作確認',
			text: Name,
			showCancelButton: true,
		}).then((result) => {
			if (result.value) {
				$.ajax({
					type: 'POST',
					url: 'Ajax/Account',
					data: {
						'Type': Type,
						'UID': ID,
					},
					success: function(response) {
						if (response.Status == 'Ok') {
							Swal.fire({icon: 'success',title: '執行成功',text:response.Msg,timer: 3000,timerProgressBar: true})
							.then(() => {$('#' + ID).remove()})
						} else {
							swal.fire({
								icon: "warning",
								title: "更新失敗",
								text: response.Msg,
								showConfirmButton: false,
								showCancelButton: true,
								cancelButtonText: "關閉"
							})
						}
					},
					error: function(xhr) {swal.fire(Type + "伺服器發生錯誤 " + xhr.status , {icon: "error",button: "關閉"});}
				});
			}
		})
	};
	$("#Add").submit(function(e){
		var postData = $(this).serializeArray();
		Swal.fire({text:'執行中...',showConfirmButton:false})
		$.ajax({
			url : "Ajax/Account",
			type: "POST",
			data : postData,
			success:function(data) {
				if(data.Status=='Ok') {
					Swal.fire({icon: 'success',title: '執行成功',timer: 3000,timerProgressBar: true})
					.then(() => {Swal.fire({text: '頁面重整中',showConfirmButton: false,});window.location.reload();})
				} else {
					Swal.fire({icon: 'error',title: '處理錯誤',text: data.Msg})
				}
			},
			beforeSend: function() { Swal.showLoading() },
			complete: function() { Swal.hideLoading() },
			error: function(jqXHR, textStatus, thrownError) {
				Swal.fire({icon: 'error',title: '系統錯誤',text: thrownError})
			}
		});
		e.preventDefault();
	});
</script>
<? include("Assets/Footer.php"); ?>
