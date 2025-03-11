<?php $Page_Name="預約表" ; $Page_Permission = 1 ; include("Assets/Header.php") ;
    $Fetch = $Connection->query("SELECT *,
    Reserve_Record.System_ID AS 'System_ID',
    Reserve_Record.Pet_ID AS 'Pet_ID',
    DAYNAME(`Time`) AS 'DayName'
    FROM `Reserve_Record` LEFT JOIN `Pet_Information` on Reserve_Record.Pet_ID = Pet_Information.System_ID WHERE `Time` >= DATE_ADD(now(),INTERVAL -3 month) ORDER BY `Time` ASC")->fetchAll(PDO::FETCH_ASSOC);

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

          
          <div id='calendar'></div>
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
                                      <label for="Name">寵物名稱</label>
                                  </div>
                              </div>
                              <div class="col-lg-6">
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
                      <button type="submit" class="btn btn-primary">Send</button>
                  </div></form>
              </div>
            </div>
          </div>

          <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              var calendarEl = document.getElementById('calendar');
              var calendar = new FullCalendar.Calendar(calendarEl, {
                customButtons: {
                  myCustomButton: {
                    text: '新增預約單',
                    click: function() {
                      $('#ReserveModal').modal('show')
                    }
                  }
                },
                headerToolbar: {
                  left: 'myCustomButton',
                  center: 'title',
                  right: 'prev,next today dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap5',
                locale : 'zh-tw',
                events: [
                  <?php foreach ($Fetch as $Val) { 
                    if (empty($Val['Pet_Name'])) { $Pet_Name = $Val['Pet_ID'] ; } else { $Pet_Name = $Val['Pet_Name'] ; } ; 
                    $Pet_Name .=  " " . $Val['Content'] ;
                    switch ($Val['Content']) {
                      case "洗澡":
                        $textColor = "#000" ;
                        break;
                      case "大美":
                        $textColor = "#0F0" ;
                        break;
                      case "住宿":
                        $textColor = "#00F" ;
                        break;
                      default:
                        $textColor = "#F00" ;
                  }?>{title  : '<?php echo $Pet_Name ?>',start  : '<?php echo $Val['Time'] ; ?>',end  : '<?php echo $Val['Time_End'] ; ?>',url : 'Reserve_Edit?ID=<?php echo $Val['System_ID'] ; ?>',color : '<?php echo $textColor ; ?>',display : 'auto'},
                  <?php } ?>
                ],
                eventTimeFormat: {
                  hour: '2-digit',
                  minute: '2-digit',
                  meridiem: false,
                  hour12:false
                },
              });
              calendar.render();
            });

          </script>
          <script type="text/JavaScript">
              $("#Reserve").submit(function(e){
              var postData = $(this).serializeArray();
              $.ajax({
                url : "Ajax/Reserve",
                type: "POST",
                data : postData,
                success:function(data, textStatus, jqXHR) {
                  if(data.Status=='Ok') {
                    Swal.fire({icon: 'success',title: '執行成功',showConfirmButton: false,timer: 3000})
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