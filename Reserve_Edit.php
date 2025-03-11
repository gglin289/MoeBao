<?php $Page_Name="預約編輯" ; $Page_Permission = 1 ; include("Assets/Header.php") ; 
    if (!empty($_GET['Del'])){
        $Connection
        ->prepare("DELETE FROM `Reserve_Record` WHERE `System_ID` LIKE ?")
        ->execute([
            $_GET['Del']
        ]);
        echo "資料已刪除，請返回上一頁。";
        exit;
    }
	$Data = $Connection->query("SELECT * FROM `Reserve_Record` WHERE `System_ID` LIKE '{$_GET['ID']}'")->fetch(PDO::FETCH_ASSOC) ; 
?>
<form id="Edit">
<div class="card mb-2">
    <div class="card-header">紀錄編輯</div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Time" name="Time" placeholder="服務日期" value="<?php echo $Data['Time']; ?>">
                    <label for="Time">服務日期</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Time_End" name="Time_End" placeholder="結束時間" value="<?php echo $Data['Time_End']; ?>">
                    <label for="Time_End">結束時間</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Content" name="Content" placeholder="服務項目" value="<?php echo $Data['Content']; ?>">
                    <label for="Content">服務項目</label>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Remark" name="Remark" placeholder="其他備註" value="<?php echo $Data['Remark']; ?>">
                    <label for="Remark">其他備註</label>
                </div>
            </div>
            <div class="col-lg-2 d-grid gap-2">
                <input type="hidden" name="API" value="Edit" >
                <input type="hidden" name="ID" value="<?php echo $Data['System_ID']; ?>">
                <input type="submit" name="submit" value="更新資料" class="btn btn-primary btn-lg"/>
            </div>
            <div class="col-lg-2 d-grid gap-2">
                <a class="btn btn-danger btn-lg" href="?Del=<?php echo $Data['System_ID']; ?>" role="button">刪除資料</a>
            </div>
        </div>
    </div>
</div>
</form>

<script type="text/JavaScript">
	$("#Edit").submit(function(e){
		var postData = $(this).serializeArray();
		$.ajax({
			url : "Ajax/Reserve",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) {
				if(data.Status=='Ok') {
					Swal.fire({icon: 'success',title: '執行成功',showConfirmButton: false,timer: 1500})
                    .then(() => {Swal.fire({text: '頁面重整中',showConfirmButton: false,});document.location.href="Reserve_Dash";});
                    
				} else {
					Swal.fire({icon: 'warning',title: '處理錯誤',text: data.Info})
				}
			},
			beforeSend: function() {$('#SubmitBtn').attr('disabled', true).html('<span class="spinner-border" role="status" aria-hidden="true"></span><span class="sr-only">Loading...</span>');},
			complete: function() {$('#SubmitBtn').attr('disabled', false).text('編輯');},
			error: function(jqXHR, textStatus, thrownError) {
				Swal.fire({icon: 'error',title: '系統錯誤',text: thrownError})
				.then(() => {Swal.fire({text: '頁面重整中',showConfirmButton: false,});window.location.reload();})
			}
		});
		e.preventDefault(); //STOP default action
	});
</script>
<? include("Assets/Footer.php"); ?>
