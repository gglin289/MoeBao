<?php 
    $Page_Name = "寵物資料" ; $Page_Permission = 1 ; include("Assets/Header.php") ; 
    $Data = $Connection->query("SELECT * FROM `Pet_Information` WHERE `System_ID` LIKE '{$_GET['ID']}'")->fetch(PDO::FETCH_ASSOC) ; 
    $Record = $Connection->query("SELECT *,DATE(`Time`) AS 'Date' FROM `Service_Record` WHERE `Pet_ID` LIKE '{$Data['System_ID']}' ORDER BY `Time` DESC")->fetchAll(PDO::FETCH_ASSOC) ;
?>
<form id="Edit">
<div class="card mb-2">
    <div class="card-header">客戶資料</div>
    <div class="card-body position-relative">
        <div class="position-absolute top-0 start-100 translate-middle">
            <a class="btn btn-info btn-lg text-nowrap" href="Pet_Add?Name=<?php echo $Data['Customer_Name']; ?>&Address=<?php echo $Data['Customer_Address']; ?>&Phone1=<?php echo $Data['Customer_Phone_1']; ?>&Phone2=<?php echo $Data['Customer_Phone_2']; ?>&Phone3=<?php echo $Data['Customer_Phone_3']; ?>" role="button"><i class="fa-solid fa-plus"></i></a>
        </div>
        <div class="row g-2">
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Customer_Name" name="Customer_Name" placeholder="客戶姓名" value="<?php echo $Data['Customer_Name']; ?>">
                    <label for="Customer_Name">客戶姓名</label>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Customer_Address" name="Customer_Address" placeholder="客戶地址" value="<?php echo $Data['Customer_Address']; ?>">
                    <label for="Customer_Address">客戶地址</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Customer_Phone_1" name="Customer_Phone_1" placeholder="客戶電話１" value="<?php echo $Data['Customer_Phone_1']; ?>">
                    <label for="Customer_Phone_1">客戶電話１</label>
                </div>
                <div id="emailHelp" class="form-text">姓名與電話號碼相同資料會一併更新。</div>
            </div>
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Customer_Phone_2" name="Customer_Phone_2" placeholder="客戶電話２" value="<?php echo $Data['Customer_Phone_2']; ?>">
                    <label for="Customer_Phone_2">客戶電話２</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Customer_Phone_3" name="Customer_Phone_3" placeholder="客戶電話３" value="<?php echo $Data['Customer_Phone_3']; ?>">
                    <label for="Customer_Phone_3">客戶電話３</label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card mb-2">
    <div class="card-header">寵物資料</div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Pet_Name" name="Pet_Name" placeholder="寵物姓名" value="<?php echo $Data['Pet_Name']; ?>">
                    <label for="Pet_Name">寵物姓名</label>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="form-floating">
                    <select class="form-select" id="Pet_Sex" name="Pet_Sex" aria-label="寵物性別" required>
                        <option <?php if (!(strcmp($Data['Pet_Sex'], ""))) { echo "selected=\"selected\"" ; } ; ?> value="">請選擇</option>
                        <option <?php if (!(strcmp($Data['Pet_Sex'], "公"))) { echo "selected=\"selected\"" ; } ; ?> value="公">公的</option>
                        <option <?php if (!(strcmp($Data['Pet_Sex'], "母"))) { echo "selected=\"selected\"" ; } ; ?> value="母">母的</option>
                    </select>
                    <label for="Pet_Sex">寵物性別</label>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Pet_Age" name="Pet_Age" placeholder="寵物年齡" value="<?php echo $Data['Pet_Age']; ?>">
                    <label for="Pet_Age">寵物年齡</label>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Pet_Breed" name="Pet_Breed" placeholder="寵物品種" list="Pet_Breed_List" value="<?php echo $Data['Pet_Breed']; ?>">
                    <label for="Pet_Breed">寵物品種</label>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Pet_Size" name="Pet_Size" placeholder="寵物體型" list="Pet_Size_List" value="<?php echo $Data['Pet_Size']; ?>">
                    <label for="Pet_Size">寵物體型</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Pet_Remark" name="Pet_Remark" placeholder="寵物備註" value="<?php echo $Data['Pet_Remark']; ?>">
                    <label for="Pet_SiPet_Remarkze">寵物備註</label>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Pet_Amount" name="Pet_Amount" placeholder="寵物金額" value="<?php echo $Data['Pet_Amount']; ?>">
                    <label for="Pet_Amount">寵物金額</label>
                </div>
            </div>
            <div class="col-lg-2 d-grid gap-2">
                <input type="hidden" name="API" value="Edit" >
                <input type="hidden" name="ID" value="<?php echo $Data['System_ID']; ?>">
                <input type="submit" name="submit" value="更新資料" class="btn btn-primary btn-lg"/>
            </div>
        </div>
    </div>
</div>
</form>

<div class="card mb-2 ">
    <div class="card-header">服務資料</div>
    <div class="card-body position-relative">
        <div class="position-absolute top-0 start-100 translate-middle">
            <button type="button" class="btn btn-info btn-lg text-nowrap" data-bs-toggle="modal" data-bs-target="#RecordModal"><i class="fa-solid fa-plus"></i></button>
        </div>
        <div class="table-responsive ">
            <table id="ItemList" class="table table-hover table-sm table-striped">
                <thead>
                    <tr>
                        <th>時間</th>
                        <th>類型</th>
                        <th>項目</th>
                        <th>活動</th>
                        <th>價格</th>
                        <th>其他</th>
                        <th><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ReserveModal">新增預約單</button></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $Record as $Val ) {
                        $Content_Array = explode(" ",$Val['Content']) ;
                        $Discount_Array = explode(" ",$Val['Discount']) ;
                    ?><tr>
                        <td nowrap><?php echo $Val['Type'] ; ?></td>
                        <td nowrap><?php foreach ( $Content_Array as $Value ) { echo "<span class=\"badge bg-secondary\">{$Value}</span> " ; } ; ?></td>
                        <td nowrap><?php echo $Val['Date'] ; ?></td>
                        <td nowrap><?php echo $Val['Date'] ; ?></td>
                        <td nowrap><?php foreach ( $Discount_Array as $Value ) { echo "<span class=\"badge bg-secondary\">{$Value}</span> " ; } ; echo $Val['Remark'] ?></td>
                        <td nowrap>已完成</td>
                        <td nowrap><?php echo $Val['Amount'] ; ?></td>
                        <td nowrap><a href="Record_Edit?ID=<?php echo $Val['System_ID'] ; ?>"> <i class="fa-solid fa-pen"></i> 編輯</a></td>
                    </tr><?php } ?>
                </tbody>
            </table>
        </div>
        </div>
        </div>
<!-- 服務單 -->
<div class="modal fade" id="RecordModal" tabindex="-1" aria-labelledby="RecordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="RecordModalLabel">新增服務單</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="Record"><div class="modal-body">
                <div class="container-fluid">
                    <div class="row g-2">
                        <div class="col-lg-4">
                            <div class="form-floating">
                                <select class="form-select" id="Type" name="Type" aria-label="服務類型" required>
                                    <option value="">請選擇</option>
                                    <option value="美容">美容</option>
                                    <option value="住宿">住宿</option>
                                    <option value="商品">商品</option>
                                </select>
                                <label for="Type">服務類型</label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="Time" name="Time" placeholder="服務日期">
                                <label for="Time">服務日期</label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="Content" name="Content" placeholder="服務項目" required>
                                <label for="Content">服務項目</label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="Discount" name="Discount" placeholder="優惠活動">
                                <label for="Discount">優惠活動</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating">
                                <input type="number" class="form-control is-invalid" id="Amount" name="Amount" placeholder="總計金額" required>
                                <label for="Amount">總計金額</label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="Remark" name="Remark" placeholder="其他備註">
                                <label for="Remark">其他備註</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="API" value="Add">
                <input type="hidden" name="PID" value="<?php echo $Data['System_ID']; ?>">
                <button type="submit" class="btn btn-primary">Send</button>
            </div></form>
        </div>
    </div>
</div>
<!-- 預約單 -->
<div class="modal fade" id="ReserveModal" tabindex="-1" aria-labelledby="ReserveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ReserveModalLabel">新增預約單</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><form id="Reserve"><div class="modal-body">
            <div class="container-fluid">
                <div class="row g-2">
                    <div class="col-lg-12">
                        <div class="form-floating">
                            <select class="form-select" id="Content" name="Content" aria-label="服務項目" required>
                                <option value="">請選擇</option>
                                <option>洗澡</option>
                                <option>大美</option>
                                <option>住宿</option>
                            </select>
                            <label for="Type">服務項目</label>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control" id="Time" name="Time" placeholder="服務日期" required>
                            <label for="Time">服務日期</label>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control" id="Time_End" name="Time_End" placeholder="結束時間">
                            <label for="Time_End">結束時間</label>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="Remark" name="Remark" placeholder="其他備註">
                            <label for="Remark">其他備註</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="API" value="Add">
                <input type="hidden" name="PID" value="<?php echo $Data['System_ID']; ?>">
            <button type="submit" class="btn btn-primary">Send</button>
        </div></form>
            
        </div>
    </div>
</div>
<datalist id="Pet_Breed_List">
  <option value="Mix">
  <option value="貴賓">
  <option value="博美">
  <option value="柯基">
  <option value="西施">
  <option value="瑪爾">
  <option value="英鬥">
  <option value="法鬥">
  <option value="狐狸">
  <option value="柴犬">
  <option value="臘腸">
  <option value="吉娃娃">
  <option value="比熊">
  <option value="邊境">
  <option value="約克夏">
  <option value="貓">
  <option value="黃金">
</datalist>
<datalist id="Pet_Size_List">
  <option value="小型犬">
  <option value="中小型犬">
  <option value="中型犬">
  <option value="中大型犬">
  <option value="大型犬">
  <option value="短毛">
  <option value="長毛">
</datalist>
<script type="text/JavaScript">
    document.title = "<?php echo $Data['Pet_Name']; ?> " + document.title;
	$("#Edit").submit(function(e){
		var postData = $(this).serializeArray();
		$.ajax({
			url : "Ajax/Pet",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) {
				if(data.Status=='Ok') {
					Swal.fire({icon: 'success',title: '執行成功',showConfirmButton: false,timer: 1500});
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
    $("#Record").submit(function(e){
		var postData = $(this).serializeArray();
		$.ajax({
			url : "Ajax/Record",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) {
				if(data.Status=='Ok') {
					Swal.fire({icon: 'success',title: '執行成功',showConfirmButton: false,timer: 1500});
                    $('table > tbody ').prepend(
						'<tr>' + 
						'<td>' + postData[1]['value'] + '</td>' +
						'<td>' + postData[0]['value'] + '</td>' +
						'<td>' + postData[2]['value'] + '</td>' +
						'<td>' + postData[3]['value'] + '</td>' +
						'<td>' + postData[4]['value'] + '</td>' +
						'<td>' + postData[5]['value'] + '</td>' +
						'<td></td></tr>'
					);
                    //.then(() => {Swal.fire({text: '頁面重整中',showConfirmButton: false,});window.location.reload();});
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
    $("#Reserve").submit(function(e){
		var postData = $(this).serializeArray();
		$.ajax({
			url : "Ajax/Reserve",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) {
				if(data.Status=='Ok') {
					Swal.fire({icon: 'success',title: '執行成功',showConfirmButton: false,timer: 3000});
                    //.then(() => {Swal.fire({text: '頁面重整中',showConfirmButton: false,});window.location.reload();});
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
