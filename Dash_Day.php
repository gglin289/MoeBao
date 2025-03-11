<?php $Page_Name="每日紀錄" ; $Page_Permission = 1 ; include("Assets/Header.php") ; 
    $Fetch = $Connection->query("SELECT * , date(`Time`) as 'Time', Service_Record.System_ID as 'ID' FROM `Service_Record` LEFT JOIN `Pet_Information` on Service_Record.Pet_ID = Pet_Information.System_ID WHERE `Time` LIKE '{$_GET['Date']}%'")->fetchAll(PDO::FETCH_ASSOC) ;
?><script src="https://ec.egnet.tw/Assets/js/clipboard.min.js"></script>
<div class="table-responsive">
    <table id="List" class="table table-hover table-sm table-striped">
        <thead>
            <tr>
                <th>類型</th>
                <th>寵物</th>
                <th>項目</th>
                <th>活動</th>
                <th>金額</th>
                <th>備註</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $Fetch as $Val ) { ?>
            <tr>
                <td nowrap><?php echo $Val['Type'] ; ?></td>
                <td nowrap><?php if(empty($Val['Pet_Name'])) { echo $Val['Pet_ID'] ; } else { echo "<a href=\"Pet_Data?ID={$Val['Pet_ID']}\">{$Val['Pet_Name']}</a>" ; } ?></td>
                <td nowrap><?php echo $Val['Content'] ; ?></td>
                <td nowrap><?php echo $Val['Discount'] ; ?></td>
                <td nowrap><?php echo $Val['Amount'] ; ?></td>
                <td nowrap><?php echo $Val['Remark'] ; ?></td>
                <td nowrap><?php if ( $UserInfo['User_Permission'] >= 7 ) { echo "<a href=\"Record_Edit?Del={$Val['ID']}\"> <i class=\"fas fa-times\"></i> 刪除 </a>" ; } ; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<? include("Assets/Footer.php"); ?>