<?
if($products != null) {
  foreach ($products as $product) {?>
    <div id="panelProduct">
      <span id="saleFlag"><?= _stock ?></span>
      <?if($product['wish_id'] == null){
          $isInWishList = "";
      }else {
          $isInWishList = "wishBtnFilled";
      }?>
      <span class="wishBtn icon-star-full <?=$isInWishList?>" data-product-id="<?=$product['food_id']?>"></span>
        <span id="imageWrapper">
          <? if ($product['foodImage'] == null) {?>
            <img src="<?= baseUrl() ?>/image/food/default.png">
            <?
          } else {?>
            <img src="<?= baseUrl() ?>/image/food/<?= $product['foodImage'] ?>" style="border-radius: 20px;">
            <?
          } ?>
        </span>

      <div id="pruductName"><?= $product['foodName'] ?></div>
      <div id="priceWrapper">
        <span
          class="currentPrice"><?= $product['foodPrice'] - ($product['foodPrice'] * $product['foodDiscount'] / 100) ?></span>
        <? if ($product['foodDiscount'] != 0) {?>
          <span id="oldPrice">&nbsp;<?= $product['foodPrice'] ?></span>
          <span style="color: #969595">&nbsp;<?= _toman ?></span>
          <?
        } else {?>
          <span class="currentPrice">&nbsp;<?= _toman ?></span>
          <?
        } ?>
      </div>
      <div id="addToCart" onclick="addToCart(<?=$product['food_id']?>)">
        <span class="icon icon-plus" style="padding-top: 3px"></span>
        <span>&nbsp;<?= _addToCart ?></span>
      </div>
    </div>
    <?
  }
}?>

<script>
  $(".wishBtn").on('click',function(){
    var wishBtn = $(this);
    var productId = wishBtn.data('product-id');

    $.ajax({
      url: "<?=baseUrl()?>/user/toggleWishList/1/" + productId,
      method: 'POST',
      dataType: 'json'
    }).done(function(output){
      if(output.isInWishList == 1){
        wishBtn.addClass('wishBtnFilled');
      } else {
        wishBtn.removeClass('wishBtnFilled');
      }
    });
  });
</script>
