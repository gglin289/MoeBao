<?php $Page_Name="紀錄編輯" ; $Page_Permission = 1 ; include("Assets/Header.php") ; 
    if (!empty($_GET['Del'])){
        $Connection
        ->prepare("DELETE FROM `Service_Record` WHERE `System_ID` LIKE ?")
        ->execute([
            $_GET['Del']
        ]);
        echo "資料已刪除，請返回上一頁。";
        exit;
    }
	$Data = $Connection->query("SELECT *,DATE(`Time`) AS 'Date' FROM `Service_Record` WHERE `System_ID` LIKE '{$_GET['ID']}'")->fetch(PDO::FETCH_ASSOC) ; 
?>
<form id="Edit">
<div class="card mb-2">
    <div class="card-header">紀錄編輯</div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-lg-4">
                <div class="form-floating">
                    <select class="form-select" id="Type" name="Type" aria-label="服務類型" required>
                        <option value="">請選擇</option>
                        <option <?php if (!(strcmp($Data['Type'], "美容"))) { echo "selected=\"selected\"" ; } ; ?> value="美容">美容</option>
                        <option <?php if (!(strcmp($Data['Type'], "住宿"))) { echo "selected=\"selected\"" ; } ; ?> value="住宿">住宿</option>
                        <option <?php if (!(strcmp($Data['Type'], "商品"))) { echo "selected=\"selected\"" ; } ; ?> value="商品">商品</option>
                    </select>
                    <label for="Type">服務類型</label>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-floating">
                    <input type="date" class="form-control" id="Time" name="Time" placeholder="服務日期" value="<?php echo $Data['Date']; ?>">
                    <label for="Time">服務日期</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Content" name="Content" placeholder="服務項目" value="<?php echo $Data['Content']; ?>">
                    <label for="Content">服務項目</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Discount" name="Discount" placeholder="優惠活動" value="<?php echo $Data['Discount']; ?>">
                    <label for="Discount">優惠活動</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="number" class="form-control is-invalid" id="Amount" name="Amount" placeholder="總計金額"  value="<?php echo $Data['Amount']; ?>">
                    <label for="Amount">總計金額</label>
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
			url : "Ajax/Record",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) {
				if(data.Status=='Ok') {
					Swal.fire({icon: 'success',title: '執行成功',showConfirmButton: false,timer: 1500})
                    .then(() => {Swal.fire({text: '頁面重整中',showConfirmButton: false,});document.location.href="Pet_Data?ID=<?php echo $Data['Pet_ID']; ?>";});
                    
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
