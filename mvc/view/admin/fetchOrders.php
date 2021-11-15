<table id="tableWrapper">
  <colgroup>
    <col style="min-width: 200px;">
    <col style="min-width: 100px;">
    <col style="min-width: 150px;">
    <col style="min-width: 500px;">
    <col style="min-width: 150px">
  </colgroup>

  <thead>
  <th style="min-width: 200px;">شماره کاربر</th>
  <th style="min-width: 100px;">نام</th>
  <th style="min-width: 150px;">نام خانوادگی</th>
  <th style="min-width: 500px;">آدرس</th>
  <th style="min-width: 150px">موبایل</th>
  </thead>

  <?
  if($paidCarts != null) {
    $userId = "";
    $fullPrice = 0;

    foreach ($paidCarts as $paidCart) {
      if($userId != $paidCart['user_id']){
        if($userId != ""){
          ?>
          <tr>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
            <td class="fullPriceTag fullPrice"><?=_ap_orders_fullPrice?></td>
            <td class="fullPrice"><?=$fullPrice?></td>
          </tr>
          <tr style="height: 50px;"></tr>
          <?
        }

        $fullPrice = $paidCart['price'];
        ?>

        <tr style="background-color: #E29674;">
          <td><?=$paidCart['user_id']?></td>
          <td><?=$paidCart['firstName']?></td>
          <td><?=$paidCart['family']?></td>
          <td><?=$paidCart['address']?></td>
          <td><?=$paidCart['mobile']?></td>
        </tr>

        <tr style="background-color: #F5BE49">
          <td>نام غذا</td>
          <td>فی</td>
          <td>تعداد</td>
          <td>تخفیف</td>
          <td>قیمت</td>
        </tr>

        <tr style="background-color: #C6DFE4">
          <td><?=$paidCart['foodName']?></td>
          <td><?=$paidCart['foodPrice']?></td>
          <td><?=$paidCart['quantity']?></td>
          <td><?=$paidCart['foodDiscount']?></td>
          <td><?=($paidCart['foodPrice'] * $paidCart['quantity']) - $paidCart['foodPrice'] * $paidCart['quantity'] * $paidCart['foodDiscount'] / 100?></td>
        </tr>
        <?
        $userId = $paidCart['user_id'];
      }else{
        ?>
        <tr style="background-color: #C6DFE4;">
          <td><?=$paidCart['foodName']?></td>
          <td><?=$paidCart['foodPrice']?></td>
          <td><?=$paidCart['quantity']?></td>
          <td><?=$paidCart['foodDiscount']?></td>
          <td><?=($paidCart['foodPrice'] * $paidCart['quantity']) - $paidCart['foodPrice'] * $paidCart['quantity'] * $paidCart['foodDiscount'] / 100?></td>
        </tr>
        <?
      }
    }
    ?>
    <tr>
      <td style="border: none;"></td>
      <td style="border: none;"></td>
      <td style="border: none;"></td>
      <td class="fullPriceTag fullPrice"><?=_ap_orders_fullPrice?></td>
      <td class="fullPrice"><?=$fullPrice?></td>
    </tr>
    <?
  }?>
</table>