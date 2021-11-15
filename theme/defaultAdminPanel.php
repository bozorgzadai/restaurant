<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?=_ap_title?></title>

  <link rel="stylesheet" href="<?=baseUrl()?>/asset/css/base.css">

  <link href="<?=baseUrl()?>/asset/css/adminPanel/bootstrap.css" rel="stylesheet">
  <link href="<?=baseUrl()?>/asset/css/adminPanel/bootstrap-rtl.css" rel="stylesheet">
  <link href="<?=baseUrl()?>/asset/css/adminPanel/sb-admin.css" rel="stylesheet">
  <link href="<?=baseUrl()?>/asset/css/adminPanel/sb-admin-rtl.css" rel="stylesheet">
  <link href="<?=baseUrl()?>/asset/font-awesome/css/font-awesome.css" rel="stylesheet">

  <script src="<?=baseUrl()?>/asset/js/main/jquery-1.11.3.min.js"></script>
  <script src="<?=baseUrl()?>/asset/js/adminPanel/bootstrap.js"></script>
</head>

<body>

<div id="wrapper">

  <!-- Navigation -->
  <nav class="navbar navbar-dark bg-inverse navbar-fixed-top">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button class="navbar-toggler hidden-sm-up pull-sm-right" type="button" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      </button>
      <a class="navbar-brand" href="<?=fullBaseUrl()?>/admin/panel"><?=_ap_title?></a>
    </div>
    <!-- Top Menu Items -->
    <ul class="nav navbar-nav top-nav navbar-right pull-xs-right">
      <li class="dropdown nav-item">
        <a href="javascript:;" class="nav-link dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i>&nbsp<?=$accessName?><b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li class="dropdown-item">
            <a href="javascript:;"><i class="fa fa-fw fa-power-off"></i>&nbsp<?=_btn_logout?></a>
          </li>
        </ul>
      </li>
    </ul>

    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-toggleable-sm navbar-ex1-collapse">
      <ul class="nav navbar-nav side-nav list-group" id="rightPanelItem">
        <li class="list-group-item" id="rightPanelItem">
          <a href="<?=fullBaseUrl()?>/admin/panel"><i class="fa fa-fw fa-dashboard"></i>&nbsp<?=_ap_orders?></a>
        </li>

        <? if(isAdmin() || iswriter()){ ?>
          <li class="list-group-item" id="rightPanelItem">
            <a href=""><i class="fa fa-fw fa-dashboard"></i>&nbsp<?=_ap_foodMangment?></a>
          </li>
        <? } ?>

        <? if(isAdmin() || isAccountant()){ ?>
          <li class="list-group-item" id="rightPanelItem">
            <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i>&nbsp<?=_ap_report?><i class="fa fa-fw fa-caret-down"></i></a>
            <ul id="demo" class="list-group collapse">
              <li class="list-group-item listItemInside"  style="border-radius: 0;">
                <a href="javascript:;"><i class="fa fa-fw fa-dashboard"></i>&nbsp<?=_ap_report_money?></a>
              </li>
              <li class="list-group-item listItemInside">
                <a href="javascript:;"><i class="fa fa-fw fa-dashboard"></i>&nbsp<?=_ap_report_count?></a>
              </li>
            </ul>
          </li>
        <?}?>

      </ul>
    </div>
    <!-- /.navbar-collapse -->
  </nav>

  <div id="page-wrapper">

    <div class="container-fluid">

      <!--==============================content================================-->
      <?=$content?>

    </div>
  </div>
  <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
</body>
</html>
