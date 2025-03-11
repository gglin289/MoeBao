<?php $Page_Name="紀錄編輯" ; $Page_Permission = 1 ; include("Assets/Header.php") ; 
    if (!empty($_GET['Del'])){
        $Connection
        ->prepare("DELETE FROM `Shopee_Record` WHERE `Order_Number` LIKE ?")
        ->execute([
            $_GET['Del']
        ]);
        echo "資料已刪除，請返回上一頁。";
        exit;
    }
	$Data = $Connection->query("SELECT * FROM `Shopee_Record` WHERE `Order_Number` LIKE '{$_GET['ID']}'")->fetch(PDO::FETCH_ASSOC) ; 
?>
<form id="Edit">
<div class="card mb-2">
    <div class="card-header">紀錄編輯</div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="date" class="form-control" id="Order_Date" name="Order_Date" placeholder="訂單日期" required value="<?php echo $Data['Order_Date'] ; ?>">
                    <label for="Order_Date">訂單日期</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Buyer_Account" name="Buyer_Account" placeholder="買家帳號" required value="<?php echo $Data['Buyer_Account'] ; ?>">
                    <label for="Buyer_Account">買家帳號</label>
                </div>
            </div>
            <div class="col-lg-2 d-grid gap-2">
                <input type="hidden" name="API" value="Edit" >
                <input type="hidden" name="ID" value="<?php echo $Data['Order_Number']; ?>">
                <input type="submit" name="submit" value="更新資料" class="btn btn-primary btn-lg"/>
            </div>
            <div class="col-lg-2 d-grid gap-2">
                <a class="btn btn-danger btn-lg" href="?Del=<?php echo $Data['Order_Number']; ?>" role="button">刪除資料</a>
            </div>
            <div class="col-lg-12">
                <div class="form-floating">
                    <textarea class="form-control" placeholder="出貨商品" id="Goods" name="Goods" style="height: 100px"><?php echo $Data['Goods'] ; ?></textarea>
                    <label for="Goods">出貨商品</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="number" class="form-control" id="Amount_Income" name="Amount_Income" placeholder="收入金額" required value="<?php echo $Data['Amount_Income'] ; ?>">
                    <label for="Amount_Income">收入金額</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="number" class="form-control" id="Amount_Cost" name="Amount_Cost" placeholder="商品成本" required value="<?php echo $Data['Amount_Cost'] ; ?>">
                    <label for="Amount_Cost">商品成本</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="number" class="form-control" id="Amount_Shipping" name="Amount_Shipping" placeholder="運費" required value="<?php echo $Data['Amount_Shipping'] ; ?>">
                    <label for="Amount_Shipping">運費金額</label>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-floating">
                    <input type="number" class="form-control is-invalid" id="Amount_Actual" name="Amount_Actual" placeholder="實賺金額" required value="<?php echo $Data['Amount_Actual'] ; ?>">
                    <label for="Amount_Actual">實賺金額</label>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Other_Expenses" name="Other_Expenses" placeholder="其他支出" value="<?php echo $Data['Other_Expenses'] ; ?>">
                    <label for="Other_Expenses">其他支出</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-floating">
                    <select class="form-select" id="Order_Status" name="Order_Status" aria-label="訂單狀態" required>
                        <option value="">請選擇</option>
                        <option <?php if (!(strcmp($Data['Order_Status'], "新成立"))) { echo "selected=\"selected\"" ; } ; ?>>新成立</option>
                        <option <?php if (!(strcmp($Data['Order_Status'], "已出貨"))) { echo "selected=\"selected\"" ; } ; ?>>已出貨</option>
                        <option <?php if (!(strcmp($Data['Order_Status'], "已完成"))) { echo "selected=\"selected\"" ; } ; ?>>已完成</option>
                    </select>
                    <label for="Order_Status">訂單狀態</label>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<script type="text/JavaScript">
	$("#Edit").submit(function(e){
		var postData = $(this).serializeArray();
		$.ajax({
			url : "Ajax/Shopee",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) {
				if(data.Status=='Ok') {
					Swal.fire({icon: 'success',title: '執行成功',showConfirmButton: false,timer: 1500})
                    .then(() => {Swal.fire({text: '頁面重整中',showConfirmButton: false,});document.location.href="Shopee";});
                    
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
