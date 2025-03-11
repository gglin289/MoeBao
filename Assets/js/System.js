const Version = "20201223-1321";
const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
const Alert = Swal.mixin({ type: 'info', confirmButtonText: '確認' });

$(function() {
    $(".System-Version").text(Version);
});

// $( document ).ajaxSend(function() { Swal.fire({text: '系統處理中',showConfirmButton: false}) });
// $( document ).ajaxStart(function() { Swal.showLoading(); });
// $( document ).ajaxStop(function() { Swal.hideLoading(); });
// $( document ).ajaxComplete(function() { Swal.close(); });
$( document ).ajaxError(function(event, request, settings) {
    console.error(event, request, settings);
    Swal.fire({
        icon: 'error',
        title: '系統錯誤',
        text: request.status + ' - ' + request.statusText,
        showConfirmButton: false,
        showCancelButton: true
    })
});
if (sessionStorage.getItem("PageRefresh")) { sessionStorage.removeItem('PageRefresh'); window.location.reload(); };

function Logout() {
    Swal.fire({
        icon: 'question',
        text: '您是否要登出管理系統?',
        showCancelButton: true,
        confirmButtonText: '確認',
        cancelButtonText: '取消'
    }).then((result) => {
        if (result.value) {
            $.ajax({url:'Ajax/Logout',cache:false,
                success:function(data){
                    if(data.Status=='success') {
                        sessionStorage.clear()
                        Swal.fire({icon: 'success',title: '頁面切換中',showConfirmButton: false});
                        window.location.reload();
                    } else {
                        Swal.fire({icon: 'warning',title: '處理錯誤',text: data.Info})
                    }
                },
                error:function(xhr, ajaxOptions, thrownError){
                    Swal.fire({icon: 'error',title: '系統錯誤',text: thrownError})
                    .then(() => {Swal.fire({text: '頁面重整中',showConfirmButton: false,});window.location.reload();})
                }
            });
        }
    })
}