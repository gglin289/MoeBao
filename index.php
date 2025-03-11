<?php 
$Page_Name="入口" ; $Page_Permission = 0 ; include("Assets/Header.php") ; 
$Sum = $Connection->query("SELECT COUNT(*) AS 'Count' FROM `Pet_Information`")->fetch(PDO::FETCH_ASSOC);
?>
<div id="app">
    <form v-on:submit.prevent="handleIt">
        <div class="input-group input-group-lg mb-3">
            <input type="text" class="form-control" v-model="Inquire" v-focus aria-describedby="button-addon2" placeholder="查詢 <?php echo $Sum['Count'] ; ?> 隻寵物資料">
            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">查詢</button>
        </div>
    </form>

    <div class="alert alert-success" v-if="!Datas.length" role="alert">
        <h4 class="alert-heading">查詢資料!</h4>
        <p>可輸入電話號碼、客戶姓名、寵物姓名。</p>
        <hr>
        <p class="mb-0">查詢客人寵物資訊，或是<a :href="'Pet_Add?Phone1=' + Inquire"> 新增一筆資料 </a>。</p>
    </div>

    <div v-else class="table-responsive">
        <table class="table table-striped table-hover caption-top">
			<caption>查詢結果 {{Datas.length}} 筆資料</caption>
            <thead class="table-light">
                <tr>
                    <th nowrap>客戶</th>
                    <th nowrap>寵物</th>
                    <th nowrap>年齡</th>
                    <th nowrap>品種</th>
                    <th nowrap>金額</th>
                    <th nowrap>備註</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(Item, Index) in Datas">
                    <td nowrap><a :href="'Pet_Data?ID=' + Item.System_ID" role="button" target="_blank">{{Item.Customer_Name}}</a></td>
                    <th nowrap><a :href="'Pet_Data?ID=' + Item.System_ID" role="button" target="_blank">{{Item.Pet_Name}}</a></th>
                    <td nowrap>{{Item.Pet_Age}}</td>
                    <td nowrap>{{Item.Pet_Breed}}</td>
                    <td nowrap>{{Item.Pet_Amount}}</td>
                    <td nowrap>{{Item.Pet_Remark}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
<script>
	var app = new Vue({
		el: '#app',
		data: {
			Inquire: '',
			Modal: {
				COMM_ID: '',
				COMM_Name: '',
				PROJ_ID: '',
				PROJ_Name: '',
				PROJ_All: false,
				Species: '',
				Content: ''
			},
			Datas:[]
		},
		directives: {
			focus: {
				inserted: function (el) {
					el.focus()
				}
			}
		},
		methods:{
			handleIt: function(){
				var This = this;
				This.Datas = [] ;
				if (This.Inquire != '') {
					$.ajax({
						url:'Ajax/index',
						data:{'S':This.Inquire},
						dataType:'json',
						success:function(Res){
							if (Res.Data[0]) {
								This.Datas = Res.Data
							} else {
								Swal.fire({icon: 'warning',title: '查無資料'})
							}
							
						}
					});
				}
			}
		}
	})
</script>
<? include("Assets/Footer.php"); ?>