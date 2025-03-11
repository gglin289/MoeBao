<?php $Page_Name="寵物新增" ; $Page_Permission = 1 ; include("Assets/Header.php") ; ?>
<form id="Add">
<div class="card mb-2">
    <div class="card-header">客戶資料</div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Customer_Name" name="Customer_Name" placeholder="客戶姓名" value="<?php if(!empty($_GET['Name'])){echo$_GET['Name'];}; ?>" required>
                    <label for="Customer_Name">客戶姓名</label>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Customer_Address" name="Customer_Address" placeholder="客戶地址" value="<?php if(!empty($_GET['Address'])){echo$_GET['Address'];}; ?>">
                    <label for="Customer_Address">客戶地址</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Customer_Phone_1" name="Customer_Phone_1" placeholder="客戶電話１" value="<?php if(!empty($_GET['Phone1'])){echo$_GET['Phone1'];}; ?>">
                    <label for="Customer_Phone_1">客戶電話１</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Customer_Phone_2" name="Customer_Phone_2" placeholder="客戶電話２" value="<?php if(!empty($_GET['Phone2'])){echo$_GET['Phone2'];}; ?>">
                    <label for="Customer_Phone_2">客戶電話２</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Customer_Phone_3" name="Customer_Phone_3" placeholder="客戶電話３" value="<?php if(!empty($_GET['Phone3'])){echo$_GET['Phone3'];}; ?>">
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
                    <input type="text" class="form-control" id="Pet_Name" name="Pet_Name" placeholder="寵物姓名" required>
                    <label for="Pet_Name">寵物姓名</label>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="form-floating">
                    <select class="form-select" id="Pet_Sex" name="Pet_Sex" aria-label="寵物性別">
                        <option value="">請選擇</option>
                        <option value="公">公的</option>
                        <option value="母">母的</option>
                    </select>
                    <label for="Pet_Sex">寵物性別</label>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Pet_Age" name="Pet_Age" placeholder="寵物年齡">
                    <label for="Pet_Age">寵物年齡</label>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Pet_Breed" name="Pet_Breed" list="Pet_Breed_List" placeholder="寵物品種">
                    <label for="Pet_Breed">寵物品種</label>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Pet_Size" name="Pet_Size" list="Pet_Size_List" placeholder="寵物體型">
                    <label for="Pet_Size">寵物體型</label>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="form-floating">
                    <input type="text" class="form-control" id="Pet_Remark" name="Pet_Remark" placeholder="寵物備註">
                    <label for="Pet_SiPet_Remarkze">寵物備註</label>
                </div>
            </div>
            <div class="col-lg-2 d-grid gap-2">
                <input type="hidden" name="API" value="Add" >
                <input type="submit" name="submit" value="確認建立" class="btn btn-primary btn-lg"/>
            </div>
        </div>
    </div>
</div>
</form>
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
<script>
	$("#Add").submit(function(e){
		var postData = $(this).serializeArray();
		Swal.fire({text:'執行中...',showConfirmButton:false})
		$.ajax({
			url : "Ajax/Pet",
			type: "POST",
			data : postData,
			success:function(data) {
				if(data.Status=='Ok') {
					Swal.fire({icon: 'success',title: '執行成功',timer: 3000,timerProgressBar: true})
					.then(() => {Swal.fire({text: '頁面重整中',showConfirmButton: false,});document.location.href="Pet_Data?ID="+data['ID'];})
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