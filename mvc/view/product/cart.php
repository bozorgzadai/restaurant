<section id="content">
  <? $totalPrice = 0;?>
  <table id="cartHolder">
    <colgroup>
      <col style="min-width: 100px">
      <col style="width: 100%">
      <col style="min-width: 100px">
      <col style="min-width: 100px">
      <col style="min-width: 100px">
      <col style="min-width: 50px">
    </colgroup>

    <thead>
      <th>تصویر</th>
      <th>نام غذا</th>
      <th>تعداد</th>
      <th>فی</th>
      <th>قیمت کل</th>
      <th>حذف</th>
    </thead>
    <?
    $i = 0;
    if($orders != null) {
      foreach ($orders as $order) {?>
        <?
        $priceWithDiscount = $order['foodPrice'] - ($order['foodPrice'] * $order['foodDiscount'] / 100);
        $totalPrice += $order['quantity'] * $priceWithDiscount;
        ?>
        <tr id="cartManagmentWrapper">
          <td style="min-width: 100px">
          <span id="cartManagmentImgWrapper">
              <? if ($order['foodImage'] == null) { ?>
                <img class="cartManagmentImg" src="<?= baseUrl() ?>/image/food/default.png">
                <?
              } else { ?>
                <img class="cartManagmentImg" src="<?= baseUrl() ?>/image/food/<?= $order['foodImage'] ?>">
                <?
              } ?>
          </span>
          </td>

          <td style="min-width: 100%">
            <span><?= $order['foodName'] ?></span>
          </td>

          <td style="min-width: 100px">
            <span class="quantityWrapper" data-order-id="<?=$order['order_id']?>"><?=$order['quantity']?></span>
            <div class="plusMinusWrapper">
              <img class="imgPlusMinus plusImage" src="<?= baseUrl() ?>/image/button/plus.png">
              <img class="imgPlusMinus minusImage" style="margin-top: 10px;" src="<?= baseUrl() ?>/image/button/minus.png">
            </div>
          </td>

          <td style="min-width: 100px">
            <span id="fee"><?= $priceWithDiscount;?></span>
          </td>

          <td style="min-width: 100px">
            <span class="totalOrderPrice"></span>
          </td>

          <td style="min-width: 50px">
            <span id="cartManagmentRemoveImg" onclick="removeOrder(<?=$order['order_id']?>)" class="icon icon-cross"></span>
            <input class="idForRemove" type="hidden" value="<?=$order['order_id']?>">
          </td>
        </tr>
        <?
      }
    }?>
  </table>
  <table id="cartManagmentPayWrapper">
    <tr>
      <td id="totalCartPriceWrapper">
        <div>
          <span><?=_finalPrice;?>&nbsp:&nbsp</span>
          <span id="totalCartPrice"><?= $totalPrice;?>&nbsp</span>
          <span><?=_toman?></span>
        </div>
        <div>
          <a id="proccedBtn" style="width: 105%;" href="<?=baseUrl()?>/payment/pay/<?=$invoice_hash?>"><?=_payCart?></a>
        </div>
      </td>
    </tr>
  </table>
</section>

<script>
  function updateOrderPrice(quantitiyControl){
    var parent = quantitiyControl.parentsUntil('#cartManagmentWrapper').parent();
    var feeControl = parent.find('#fee');
    var totalOrderPrice = parent.find('.totalOrderPrice');

    var quantity = quantitiyControl.text();
    var fee = feeControl.text();

    totalOrderPrice.text(quantity * fee);
  }

  function updateCartPrice(){
    var totalCartPrice = 0;
    $('.totalOrderPrice').each(function(){
      var price = $(this).text();
      totalCartPrice += parseInt(price);
    });

    $('#totalCartPrice').text(totalCartPrice);
  }

  function plusOrMinus(control , name){
    var parent = control.parentsUntil('#cartManagmentWrapper');
    var quantity = parent.find('.quantityWrapper');
    var number = quantity.text();

    if(name == 'plus'){
      number++;
    }else if(name == 'minus'){
      number--;
      if(number == 0){
        number = 1;
      }
    }
    quantity.text(number);
    updateOrderPrice(quantity);
    updateCartPrice();

    var orderId = quantity.data('order-id');

    $.ajax({
      url: "<?=baseUrl()?>/product/updateQuantity",
      method: 'POST',
      data: {
        orderId: orderId,
        quantity: number
      }
    }).done(function(output) {
      refreshCartPreview();
    });
  }

  $(function(){
    $('.quantityWrapper').each(function () {
      updateOrderPrice($(this));
    });

    $('.plusImage').on('click', function(){
      var plusControl = $(this);
      plusOrMinus(plusControl , 'plus');
    });

    $('.minusImage').on('click', function(){
      var minusControl = $(this);
      plusOrMinus(minusControl , 'minus');
    });

    updateCartPrice();
  });
</script>