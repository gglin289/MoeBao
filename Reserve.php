<?php $Page_Name="預約表" ; $Page_Permission = 1 ; include("Assets/Header.php") ;
    $Fetch = $Connection->query("SELECT *,
    Reserve_Record.System_ID AS 'System_ID',
    Reserve_Record.Pet_ID AS 'Pet_ID',
    DAYNAME(`Time`) AS 'DayName'
    FROM `Reserve_Record` LEFT JOIN `Pet_Information` on Reserve_Record.Pet_ID = Pet_Information.System_ID WHERE `Time` >= CURDATE() ORDER BY `Time` ASC")->fetchAll(PDO::FETCH_ASSOC);

    $DayName = Array(
      'Monday' => '星期一',
      'Tuesday' => '星期二',
      'Wednesday' => '星期三',
      'Thursday' => '星期四',
      'Friday' => '星期五',
      'Saturday' => '星期六',
      'Sunday' => '星期日'
    );
    $DayColors = Array(
      'Monday' => 'primary',
      'Tuesday' => 'primary',
      'Wednesday' => 'primary',
      'Thursday' => 'primary',
      'Friday' => 'primary',
      'Saturday' => 'success',
      'Sunday' => 'success'
    );
?>


<div class="table-responsive">
  <table class="table table-hover table-sm table-striped">
    <thead>
      <tr>
        <th scope="col">預約日期</th>
        <th scope="col">寵物名稱</th>
        <th scope="col">預約項目</th>
        <th scope="col">其他備註</th>
        <th scope="col"><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ReserveModal">新增預約單</button></th>
      </tr>
    </thead>
    <?php if (!empty($Fetch)) { ?><tbody>
      <?php foreach ($Fetch as $Val) { ?><tr>
        <th nowrap scope="row"><?php echo $Val['Time'] ; ?> <a class="text-<?php echo $DayColors[$Val['DayName']] ; ?>"><?php echo $DayName[$Val['DayName']] ; ?></th>
        <td nowrap><?php if (empty($Val['Pet_Name'])) {
          echo $Val['Pet_ID'] ;
        } else {
          echo "<a href=\"Pet_Data?ID={$Val['Pet_ID']}\">{$Val['Pet_Name']}</a>" ;
        } ; ?></td>
        <td nowrap><?php echo $Val['Content'] ; ?></td>
        <td nowrap><?php echo $Val['Remark'] ; ?></td>
        <td nowrap><a href="Reserve_Edit?ID=<?php echo $Val['System_ID'] ; ?>"> 編輯 </a></td>
      </tr><?php }; ?>
    </tbody><?php } ?>
  </table>
</div>
<!-- 預約單 -->
<div class="modal fade" id="ReserveModal" tabindex="-1" aria-labelledby="ReserveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="ReserveModalLabel">新增預約單</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="Reserve"><div class="modal-body">
            <div class="container-fluid">
                <div class="row g-2">
                    <div class="col-lg-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="Name" name="PID" placeholder="寵物名稱" required>
                            <label for="Content">寵物名稱</label>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-floating">
                            <input type="datetime-local" class="form-control" id="Time" name="Time" placeholder="服務日期" required>
                            <label for="Time">服務日期</label>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="Content" name="Content" placeholder="服務項目" required>
                            <label for="Content">服務項目</label>
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
            <button type="submit" class="btn btn-primary">Send</button>
        </div></form>
    </div>
  </div>
</div>

<script type="text/JavaScript">
    $("#Record").submit(function(e){
		var postData = $(this).serializeArray();
		$.ajax({
			url : "Ajax/Record",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) {
				if(data.Status=='Ok') {
					Swal.fire({icon: 'success',title: '執行成功',showConfirmButton: false,timer: 1500})
          .then(() => {Swal.fire({text: '頁面重整中',showConfirmButton: false,});window.location.reload();});
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