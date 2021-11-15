<? $totalPrice = 0;
if($orders != null) {
  foreach ($orders as $order) {
    $priceWithDiscount = $order['foodPrice'] - ($order['foodPrice'] * $order['foodDiscount'] / 100);
    $totalPrice += $order['quantity'] * $priceWithDiscount;
    ?>
    <div id="cartPreviewWrapper">
      <span id="cartPreviewImgWrapper">
          <? if ($order['foodImage'] == null) {
            ?>
            <img class="cartPreviewImg" src="<?= baseUrl() ?>/image/food/default.png">
            <?
          } else {
            ?>
            <img class="cartPreviewImg" src="<?= baseUrl() ?>/image/food/<?= $order['foodImage'] ?>">
            <?
          } ?>
      </span>
      <div id="cartPreviewRightSide">
        <span ><?= $order['foodName'] ?></span>
        <span><?=$order['quantity']?>x&nbsp<?= $priceWithDiscount;?></span>
      </div>
      <span id="cartPreviewRemoveImg" onclick="removeOrder(<?=$order['order_id']?>)" class="icon icon-cross"></span>
    </div>
    <?
  }
}?>
<div id="cartPreviewPayWrapper">
  <div>
    <span><?=_finalPrice;?>&nbsp:&nbsp</span>
    <span><?= $totalPrice;?>&nbsp</span>
    <span><?=_toman?></span>
  </div>
  <div id="cartPreviewPriceWrapper">
    <a id="proccedBtn" href="<?=baseUrl()?>/payment/pay/<?=$invoice_hash?>"><?=_payCart?></a>
    <a id="proccedBtn" href="<?=baseUrl()?>/product/cart"><?=_cartManagment?></a>
  </div>
</div>

