<?php $Page_Name="儀表板" ; $Page_Permission = 1 ; include("Assets/Header.php") ;
    $SumOrderQuery2 = $Connection->query("SELECT YEAR(`Time`) AS 'Year', MONTH(`Time`) AS 'Month' FROM `Service_Record` GROUP BY YEAR(`Time`), MONTH (`Time`) ORDER BY `Time` DESC ");
    $SumOrderFetch2 = $SumOrderQuery2->fetchAll(PDO::FETCH_ASSOC);
    if(empty($_GET['Date'])){$_GET['Date'] = $SumOrderFetch2[0]['Year']."-".$SumOrderFetch2[0]['Month'];};

    $SumOrderQuery3 = $Connection->query("SELECT 
    SUM(CASE WHEN `Type` LIKE '美容' THEN `Amount` ELSE 0 END) AS '美容',
    SUM(CASE WHEN `Type` LIKE '住宿' THEN `Amount` ELSE 0 END) AS '住宿',
    SUM(CASE WHEN `Type` LIKE '商品' THEN `Amount` ELSE 0 END) AS '商品',
    SUM(CASE WHEN `Type` LIKE '加班費' THEN `Amount` ELSE 0 END) AS '加班費',
    SUM(CASE WHEN `Type` LIKE '蝦皮' THEN `Amount` ELSE 0 END) AS '蝦皮',
    SUM(CASE WHEN `Type` LIKE '進貨款項' THEN `Amount` ELSE 0 END) AS '進貨款項',
    SUM(CASE WHEN `Type` NOT IN('美容','住宿','商品','加班費','蝦皮','進貨款項') THEN `Amount` ELSE 0 END) AS '其他',
    COUNT(CASE WHEN `Type` LIKE '美容' THEN 1 END) AS 'Count', SUM(`Amount`) AS 'Amount'
    FROM `Service_Record` 
    WHERE `Time` BETWEEN '{$_GET['Date']}-01' AND last_day('{$_GET['Date']}-01')");
    $SumOrderFetch3 = $SumOrderQuery3->fetch(PDO::FETCH_ASSOC);

    $SumOrderQuery = $Connection->query("SELECT 
    COUNT(CASE WHEN `Type` LIKE '美容' THEN 1 END) AS '美容數量',
    SUM(CASE WHEN `Type` LIKE '美容' THEN `Amount` ELSE 0 END) AS '美容',
    SUM(CASE WHEN `Type` LIKE '住宿' THEN `Amount` ELSE 0 END) AS '住宿',
    SUM(CASE WHEN `Type` LIKE '商品' THEN `Amount` ELSE 0 END) AS '商品',
    SUM(CASE WHEN `Type` LIKE '加班費' THEN `Amount` ELSE 0 END) AS '加班費',
    SUM(CASE WHEN `Type` LIKE '蝦皮' THEN `Amount` ELSE 0 END) AS '蝦皮',
    SUM(CASE WHEN `Type` LIKE '進貨款項' THEN `Amount` ELSE 0 END) AS '進貨款項',
    SUM(CASE WHEN `Type` NOT IN('美容','住宿','商品','加班費','蝦皮','進貨款項') THEN `Amount` ELSE 0 END) AS '其他',
    GROUP_CONCAT(CASE WHEN `Type` NOT IN('美容','住宿','商品','加班費','蝦皮','進貨款項') THEN `Content` END) AS '其他項目',
    DAYNAME(`Time`) AS 'DayName', DATE(`Time`) AS 'Date', COUNT(*) AS 'Count', SUM(`Amount`) AS 'Amount'
    FROM `Service_Record` 
    WHERE `Time` BETWEEN '{$_GET['Date']}-01' AND last_day('{$_GET['Date']}-01')
    GROUP BY DATE(`Time`)
    ORDER BY `Time` DESC");
    $SumOrderFetch = $SumOrderQuery->fetchAll(PDO::FETCH_ASSOC);

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
<div class="row">
  <div class="col-12 col-lg-6">
    <div class="card mb-2">
      <div class="card-body">
        <form id="Inquire">
          <div class="form-group row my-0">
            <label class="col-3 col-lg-2 col-form-label">日期：</label>
            <div class="col-7 col-lg-8">
              <select class="form-control" name="Date">
                <?php foreach ($SumOrderFetch2 as $Val) {
                  echo "<option " ;
                  if (!(strcmp($_GET['Date'], $Val['Year']."-".$Val['Month']))) { echo "selected=\"selected\"" ; } ;
                  echo " value=\"{$Val['Year']}-{$Val['Month']}\">{$Val['Year']} 年度 {$Val['Month']} 月份</option>" ;
                  } ?>
              </select>
            </div>
            <div class="col-2 col-lg-2">
              <input type="submit" value="查詢" class="btn btn-primary btn-block"/>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-3">
    <div class="card mb-2">
      <div class="card-body">
        <div class="d-grid gap-2">
          <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#ItemModal">新增紀錄</button>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-3">
    <div class="card mb-2">
      <div class="card-body">
        <div class="d-grid gap-2">
          <a class="btn btn-secondary" href="Dash_Years" role="button">切換年表</a>
        </div>
      </div>
    </div>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-4 col-lg">
    <div class="alert alert-info" role="alert">
      <h4 class="alert-heading">美容</h4>
      <h1 class="mb-0"><?php echo number_format($SumOrderFetch3['美容']) ; ?></h1>
    </div>
  </div>
  <div class="col-4 col-lg">
    <div class="alert alert-info" role="alert">
      <h4 class="alert-heading">住宿</h4>
      <h1 class="mb-0"><?php echo number_format($SumOrderFetch3['住宿']) ; ?></h1>
    </div>
  </div>
  <div class="col-4 col-lg">
    <div class="alert alert-info" role="alert">
      <h4 class="alert-heading">商品</h4>
      <h1 class="mb-0"><?php echo number_format($SumOrderFetch3['商品']) ; ?></h1>
    </div>
  </div>
  <div class="col-4 col-lg">
    <div class="alert alert-secondary" role="alert">
      <h4 class="alert-heading">加班費</h4>
      <h1 class="mb-0"><?php echo number_format($SumOrderFetch3['加班費']) ; ?></h1>
    </div>
  </div>
  <div class="col-4 col-lg">
    <div class="alert alert-secondary" role="alert">
      <h4 class="alert-heading">進貨款項</h4>
      <h1 class="mb-0"><?php echo number_format($SumOrderFetch3['進貨款項']) ; ?></h1>
    </div>
  </div>
  <div class="col-4 col-lg">
    <div class="alert alert-secondary" role="alert">
      <h4 class="alert-heading">蝦皮</h4>
      <h1 class="mb-0"><?php echo number_format($SumOrderFetch3['蝦皮']) ; ?></h1>
    </div>
  </div>
  <div class="col-4 col-lg">
    <div class="alert alert-secondary" role="alert">
      <h4 class="alert-heading">其他</h4>
      <h1 class="mb-0"><?php echo number_format($SumOrderFetch3['其他']) ; ?></h1>
    </div>
  </div>
  <div class="col-4 col-lg d-none d-xl-block">
    <div class="alert alert-primary" role="alert">
      <h4 class="alert-heading">美容筆數</h4>
      <h1 class="mb-0"><?php echo $SumOrderFetch3['Count'] ; ?></h1>
    </div>
  </div>
  <div class="col-4 col-lg">
    <div class="alert alert-<?php if ( $SumOrderFetch3['Amount'] > 0 ) { echo "success" ; } ; if ( $SumOrderFetch3['Amount'] < 0 ) { echo "danger" ; } ; ?>" role="alert">
      <h4 class="alert-heading">總金額</h4>
      <h1 class="mb-0"><?php echo number_format($SumOrderFetch3['Amount']) ; ?></h1>
    </div>
  </div>
</div>
<hr>
<?php if (!empty($SumOrderFetch)) { ?>
<div class="table-responsive">
  <table class="table table-hover table-sm table-striped">
    <thead>
      <tr>
        <th scope="col">日期</th>
        <th scope="col">美容筆數</th>
        <th scope="col">美容金額</th>
        <th scope="col">住宿</th>
        <th scope="col">商品</th>
        <th scope="col">加班費</th>
        <th scope="col">進貨款項</th>
        <th scope="col">蝦皮</th>
        <th scope="col">其他</th>
        <th scope="col">項目</th>
        <th scope="col">小計</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($SumOrderFetch as $Val) { ?><tr>
        <th nowrap scope="row"><a href="Dash_Day?Date=<?php echo $Val['Date'] ; ?>"><?php echo $Val['Date'] ; ?></a> <a class="text-<?php echo $DayColors[$Val['DayName']] ; ?>"><?php echo $DayName[$Val['DayName']] ; ?></th>
        <td nowrap><a href="Dash_Pet?Date=<?php echo $Val['Date'] ; ?>"><?php echo $Val['美容數量'] ; ?></a></td>
        <td nowrap><?php echo number_format($Val['美容']) ; ?></td>
        <td nowrap><?php echo number_format($Val['住宿']) ; ?></td>
        <td nowrap><?php echo number_format($Val['商品']) ; ?></td>
        <td nowrap><?php echo number_format($Val['加班費']) ; ?></td>
        <td nowrap><?php echo number_format($Val['進貨款項']) ; ?></td>
        <td nowrap><?php echo number_format($Val['蝦皮']) ; ?></td>
        <td nowrap><?php echo number_format($Val['其他']) ; ?></td>
        <td nowrap><?php echo $Val['其他項目'] ; ?></td>
        <th nowrap class="<?php if ( $Val['Amount'] > 0 ) { echo "text-success" ; } ; if ( $Val['Amount'] < 0 ) { echo "text-danger" ; } ; ?>"><?php echo number_format($Val['Amount']) ; ?></th>
      </tr><?php }; ?>
    </tbody>
  </table>
</div>
<?php } ?>
<!-- Modal -->
<div class="modal fade" id="ItemModal" tabindex="-1" aria-labelledby="ItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ItemModalLabel">新增其他支出『金額請以（負數）輸入』</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="Record"><div class="modal-body">
                <div class="container-fluid">
                    <div class="row g-2">
                        <div class="col-lg-4">
                            <div class="form-floating">
                                <select class="form-select" id="Type" name="Type" aria-label="支出類型" required>
                                    <option value="">請選擇</option>
                                    <option value="加班費">加班費(自動負數)</option>
                                    <option value="租金">租金(自動負數)</option>
                                    <option value="水電">水電(自動負數)</option>
                                    <option value="利息">利息(自動負數)</option>
                                    <option value="分期付款">分期付款(自動負數)</option>
                                    <option value="進貨款項">進貨款項(自動負數)</option>
                                    <option value="蝦皮">蝦皮</option>
                                    <option value="其他">其他</option>
                                </select>
                                <label for="Type">支出類型</label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="Time" name="Time" placeholder="支出日期">
                                <label for="Time">支出日期</label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="Content" name="Content" placeholder="支出項目">
                                <label for="Content">支出項目</label>
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
              <input type="hidden" name="PID" value="<?php echo $UserInfo['User_ID']; ?>">
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
</script>
<? include("Assets/Footer.php"); ?>