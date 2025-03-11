<?php 
    $Page_Name = "蝦皮記帳" ; $Page_Permission = 1 ; include("Assets/Header.php") ; 
    $Record = $Connection->query("SELECT * FROM `Shopee_Record` ORDER BY `Order_Date` DESC")->fetchAll(PDO::FETCH_ASSOC) ;
?>

<div class="card mb-2 ">
    <div class="card-header">紀錄列表</div>
    <div class="card-body position-relative">
        <div class="position-absolute top-0 start-100 translate-middle">
            <button type="button" class="btn btn-info btn-lg text-nowrap" data-bs-toggle="modal" data-bs-target="#ItemModal"><i class="fa-solid fa-plus"></i></button>
        </div>
        <div class="table-responsive ">
            <table id="ItemList" class="table table-hover table-sm table-striped">
                <thead>
                    <tr>
                        <th>日期</th>
                        <th>買家</th>
                        <th>出貨商品</th>
                        <th>收入金額</th>
                        <th>成本</th>
                        <th>運費</th>
                        <th>其他支出</th>
                        <th>實賺金額</th>
                        <th>訂單狀態</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $Record as $Val ) { ?><tr>
                        <td nowrap><?php echo $Val['Order_Date'] ; ?></td>
                        <td nowrap><?php echo $Val['Buyer_Account'] ; ?></td>
                        <td nowrap><?php echo $Val['Goods'] ; ?></td>
                        <td nowrap><?php echo $Val['Amount_Income'] ; ?></td>
                        <td nowrap><?php echo $Val['Amount_Cost'] ; ?></td>
                        <td nowrap><?php echo $Val['Amount_Shipping'] ; ?></td>
                        <td nowrap><?php echo $Val['Other_Expenses'] ; ?></td>
                        <td nowrap><?php echo $Val['Amount_Actual'] ; ?></td>
                        <td nowrap><a href="Shopee_Edit?ID=<?php echo $Val['Order_Number'] ; ?>"> <i class="fa-solid fa-pen"></i> <?php echo $Val['Order_Status'] ; ?> </a></td>
                    </tr><?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="ItemModal" tabindex="-1" aria-labelledby="ItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ItemModalLabel">新增紀錄</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="Record"><div class="modal-body">
                <div class="container-fluid">
                    <div class="row g-2">
                        <div class="col-lg-4">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="Order_Date" name="Order_Date" placeholder="訂單日期" required>
                                <label for="Order_Date">訂單日期</label>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="Order_Number" name="Order_Number" placeholder="訂單編號" required>
                                <label for="Order_Number">訂單編號</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="Buyer_Account" name="Buyer_Account" placeholder="買家帳號" required>
                                <label for="Buyer_Account">買家帳號</label>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="出貨商品" id="Goods" name="Goods" style="height: 100px"></textarea>
                                <label for="Goods">出貨商品</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="Amount_Income" name="Amount_Income" placeholder="收入金額" required>
                                <label for="Amount_Income">收入金額</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="Amount_Cost" name="Amount_Cost" placeholder="商品成本" required>
                                <label for="Amount_Cost">商品成本</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="Amount_Shipping" name="Amount_Shipping" placeholder="運費" required>
                                <label for="Amount_Shipping">運費金額</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-floating">
                                <input type="text" class="form-control is-invalid" id="Amount_Actual" name="Amount_Actual" placeholder="實賺金額" disabled value="系統計算">
                                <label for="Amount_Actual">實賺金額</label>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="Other_Expenses" name="Other_Expenses" placeholder="其他支出">
                                <label for="Other_Expenses">其他支出</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating">
                                <select class="form-select" id="Order_Status" name="Order_Status" aria-label="訂單狀態" required>
                                    <option value="">請選擇</option>
                                    <option>新成立</option>
                                    <option>已出貨</option>
                                    <option>已完成</option>
                                </select>
                                <label for="Order_Status">訂單狀態</label>
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
			url : "Ajax/Shopee",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) {
				if(data.Status=='Ok') {
					Swal.fire({icon: 'success',title: '執行成功',showConfirmButton: false,timer: 1500});
                    $('table > tbody ').prepend(
						'<tr>' + 
						'<td>' + postData[0]['value'] + '</td>' +
						'<td>' + postData[2]['value'] + '</td>' +
						'<td>' + postData[3]['value'] + '</td>' +
						'<td>' + postData[4]['value'] + '</td>' +
						'<td>' + postData[5]['value'] + '</td>' +
						'<td>' + postData[6]['value'] + '</td>' +
						'<td>' + postData[7]['value'] + '</td>' +
						'<td></td>' +
						'<td>' + postData[8]['value'] + '</td>' +
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
</script>
<? include("Assets/Footer.php"); ?>
