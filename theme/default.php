<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">

  <title><?=$pageTitle?></title>

  <link rel="stylesheet" href="<?=baseUrl()?>/asset/css/base.css">
  <link rel="stylesheet" href="<?=baseUrl()?>/asset/css/main/reset.css">
  <link rel="stylesheet" href="<?=baseUrl()?>/asset/css/main/style.css">
  <link rel="stylesheet" href="<?=baseUrl()?>/asset/css/main/layout.css">
  <script src="<?=baseUrl()?>/asset/js/main/common.js"></script>
  <script src="<?=baseUrl()?>/asset/js/main/jquery-1.11.3.min.js"></script>
  <script src="<?=baseUrl()?>/asset/js/main/cufon-yui.js"></script>
  <script src="<?=baseUrl()?>/asset/js/main/Dynalight_400.font.js"></script>
  <script src="<?=baseUrl()?>/asset/js/main/jquery.equalheights.js"></script>
  <script src="<?=baseUrl()?>/asset/js/main/jquery.bxSlider.js"></script>
  <script src="<?=baseUrl()?>/asset/js/main/tms-0.3.js"></script>
  <script src="<?=baseUrl()?>/asset/js/main/tms_presets.js"></script>
  <script src="<?=baseUrl()?>/asset/js/main/jquery.easing.1.3.js"></script>
  <script src="<?=baseUrl()?>/asset/js/main/googleMap.js"></script>
</head>

<body id="<?=$pageId?>">
<!--==============================header=================================-->
<header>
  <div class="row-top">
    <div class="main">
      <div class="wrapper">
        <div style="margin-bottom: 5px;">
          <span id="accessName"><?=getAccessName()?></span>
          <?if(isset($_SESSION['email'])){?>
            <a class="icon icon-star-full" id="wishList" href="<?=fullBaseUrl()?>/user/wishList"></a>
            <span id="userEmail"><?=$_SESSION['email'];?></span>
            <div id="cartWrapper">
              <span class="icon icon-cart" id="cart"></span>
              <span id="cartItems"></span>
            </div>
            <a id="btnLogout" href="<?=fullBaseUrl()?>/user/logout" class="btn-blue"><?=_btn_logout?></a>
          <?}else{?>
            <div id="cartWrapper" style="margin: 0 30px 0 30px;">
              <span class="icon icon-cart" id="cart"></span>
              <span id="cartItems"></span>
            </div>
            <a style="margin-left: 5px;" href="<?=fullBaseUrl()?>/user/login" class="btn-blue"><?=_btn_login?></a>
            <a href="<?=fullBaseUrl()?>/user/register" class="btn-blue"><?=_btn_register?></a>
          <?}?>
        </div>
        <div id="cartPreviewHolder"></div>

        <h1><a href="<?=fullBaseUrl()?>/page/home"><?=_restaurantName?></a></h1>
        <nav>
          <ul class="menu">
            <? if(canAccessAdminPanel()){ ?>
              <li><a id="adminPanelTitle" href="<?=fullBaseUrl()?>/admin/panel"><?=_ap_title?></a></li>
              <li id="titleDivider">|</li>
            <? } ?>
            <li><a id="activeColor" <? if($activePage == 'contactUsHeader'){?> class="active" <?}?> href="<?=fullBaseUrl()?>/page/contactUs">تماس با ما</a></li>
            <li><a id="activeColor" <? if($activePage == 'menuHeader'){?> class="active" <?}?> href="<?=fullBaseUrl()?>/page/menu">منو</a></li>
            <li><a id="activeColor" <? if($activePage == 'indexHeader'){?> class="active" <?}?> href="<?=fullBaseUrl()?>/page/home">صفحه اصلی</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
  <div class="row-bot">
    <div class="row-bot-bg">
      <div class="main">
        <h2><?=$topTitle?></h2>
      </div>
    </div>
  </div>
</header>

<!--==============================content================================-->
<?=$content?>

<!--==============================footer=================================-->
<footer>
  <div class="main">
    <div class="aligncenter"  >
      <h4 class="indent-bot" style="color: tomato"><?=_restaurantName?></h4>
      <span>Design by: A.bozorgzad1995@gmail.com</span>
      <span>(09136482921)</span>
    </div>
  </div>
</footer>

</body>
</html>

<script>
  $(function(){
    refreshCartPreview();

    $("#cartWrapper").on('click' , function(){
      $("#cartPreviewHolder").toggle();
    });
  });

  function removeOrder($order_id){
    $.ajax({
      url: "<?=baseUrl()?>/product/removeOrder/" + $order_id,
      method: 'POST',
      dataType: 'json'
    }).done(function(output){
      $("#cartItems").text(output.orderItems);
      $("#cartPreviewHolder").html(output.cartPreviewOrders);

      $('.idForRemove').each(function(){
        if($(this).val() == $order_id){
          $(this).parentsUntil('#cartManagmentWrapper').parent().remove();
        }
      });
      updateCartPrice();
    });
  }

  function refreshCartPreview(){
    $.ajax({
      url: "<?=baseUrl()?>/product/refreshCartPreview",
      method: 'POST',
      dataType: 'json'
    }).done(function(output){
      $("#cartItems").text(output.orderItems);
      $("#cartPreviewHolder").html(output.cartPreviewOrders);
    });
  }

</script>
