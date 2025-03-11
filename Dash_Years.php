<?php $Page_Name="儀表板" ; $Page_Permission = 1 ; include("Assets/Header.php") ;
    $SumOrderQuery2 = $Connection->query("SELECT YEAR(`Time`) AS 'Year' FROM `Service_Record` GROUP BY YEAR(`Time`) ORDER BY `Time` DESC ");
    $SumOrderFetch2 = $SumOrderQuery2->fetchAll(PDO::FETCH_ASSOC);
    if(empty($_GET['Date'])){$_GET['Date'] = $SumOrderFetch2[0]['Year'];};

    $SumOrderQuery3 = $Connection->query("SELECT 
    SUM(CASE WHEN `Type` LIKE '美容' THEN `Amount` ELSE 0 END) AS '美容',
    SUM(CASE WHEN `Type` LIKE '住宿' THEN `Amount` ELSE 0 END) AS '住宿',
    SUM(CASE WHEN `Type` LIKE '商品' THEN `Amount` ELSE 0 END) AS '商品',
    SUM(CASE WHEN `Type` LIKE '加班費' THEN `Amount` ELSE 0 END) AS '加班費',
    SUM(CASE WHEN `Type` LIKE '蝦皮' THEN `Amount` ELSE 0 END) AS '蝦皮',
    SUM(CASE WHEN `Type` NOT IN('美容','住宿','商品','加班費','蝦皮') THEN `Amount` ELSE 0 END) AS '其他',
    COUNT(*) AS 'Count', SUM(`Amount`) AS 'Amount'
    FROM `Service_Record` 
    WHERE `Time` BETWEEN '{$_GET['Date']}-01-01' AND '{$_GET['Date']}-12-31'");
    $SumOrderFetch3 = $SumOrderQuery3->fetch(PDO::FETCH_ASSOC);

    $SumOrderQuery = $Connection->query("SELECT 
    COUNT(CASE WHEN `Type` LIKE '美容' THEN 1 END) AS '美容數量',
    SUM(CASE WHEN `Type` LIKE '美容' THEN `Amount` ELSE 0 END) AS '美容',
    SUM(CASE WHEN `Type` LIKE '住宿' THEN `Amount` ELSE 0 END) AS '住宿',
    SUM(CASE WHEN `Type` LIKE '商品' THEN `Amount` ELSE 0 END) AS '商品',
    SUM(CASE WHEN `Type` LIKE '加班費' THEN `Amount` ELSE 0 END) AS '加班費',
    SUM(CASE WHEN `Type` LIKE '蝦皮' THEN `Amount` ELSE 0 END) AS '蝦皮',
    SUM(CASE WHEN `Type` NOT IN('美容','住宿','商品','加班費','蝦皮') THEN `Amount` ELSE 0 END) AS '其他',
    YEAR(`Time`) AS 'Years', MONTH(`Time`) AS 'Month', COUNT(*) AS 'Count', SUM(`Amount`) AS 'Amount'
    FROM `Service_Record` 
    WHERE `Time` BETWEEN '{$_GET['Date']}-01-01' AND '{$_GET['Date']}-12-31'
    GROUP BY MONTH(`Time`)
    ORDER BY `Time` DESC");
    $SumOrderFetch = $SumOrderQuery->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="row">
  <div class="col-12 col-lg-9">
    <div class="card mb-2">
      <div class="card-body">
        <form id="Inquire">
          <div class="form-group row my-0">
            <label class="col-3 col-lg-2 col-form-label">日期：</label>
            <div class="col-7 col-lg-8">
              <select class="form-control" name="Date">
                <?php foreach ($SumOrderFetch2 as $Val) {
                  echo "<option " ;
                  if (!(strcmp($_GET['Date'], $Val['Year']))) { echo "selected=\"selected\"" ; } ;
                  echo " value=\"{$Val['Year']}\">{$Val['Year']} 年度</option>" ;
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
          <a class="btn btn-secondary" href="Dash_Month" role="button">切換月表</a>
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
      <h4 class="alert-heading">筆數</h4>
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
        <th scope="col">月份</th>
        <th scope="col">美容筆數</th>
        <th scope="col">美容金額</th>
        <th scope="col">住宿</th>
        <th scope="col">商品</th>
        <th scope="col">加班費</th>
        <th scope="col">蝦皮</th>
        <th scope="col">其他</th>
        <th scope="col">小計</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($SumOrderFetch as $Val) { ?><tr>
        <th nowrap scope="row"><a href="Dash_Month?Date=<?php echo $Val['Years']."-".$Val['Month'] ; ?>"><?php echo $Val['Years']."-".$Val['Month'] ; ?></a></th>
        <td nowrap><a href="Dash_Pet?Date=<?php echo $Val['Years']."-".$Val['Month'] ; ?>"><?php echo $Val['美容數量'] ; ?></a></td>
        <td nowrap><?php echo number_format($Val['美容']) ; ?></td>
        <td nowrap><?php echo number_format($Val['住宿']) ; ?></td>
        <td nowrap><?php echo number_format($Val['商品']) ; ?></td>
        <td nowrap><?php echo number_format($Val['加班費']) ; ?></td>
        <td nowrap><?php echo number_format($Val['蝦皮']) ; ?></td>
        <td nowrap><?php echo number_format($Val['其他']) ; ?></td>
        <th nowrap class="<?php if ( $Val['Amount'] > 0 ) { echo "text-success" ; } ; if ( $Val['Amount'] < 0 ) { echo "text-danger" ; } ; ?>"><?php echo number_format($Val['Amount']) ; ?></th>
      </tr><?php }; ?>
    </tbody>
  </table>
</div>
<?php } ?>
<? include("Assets/Footer.php"); ?>