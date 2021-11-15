<div id="adminPanelHeader">
  <span><i class="fa fa-fw fa-dashboard"></i>&nbsp<?=_ap_orders?></span>
</div>

<div id="adminPanelOrdersTop">
  <span><?=_ap_orders_groupByTime?></span>
  <select id="timeSelelct" class="selectOrderAdminPanel">
    <option><?=_ap_orders_lastHour?></option>
    <option><?=_ap_orders_today?></option>
    <option><?=_ap_orders_yesterday?></option>
    <option><?=_ap_orders_currentWeek?></option>
    <option><?=_ap_orders_currentMonth?></option>
    <option><?=_ap_orders_lastWeek?></option>
    <option><?=_ap_orders_lastMonth?></option>
  </select>

  <span class="adminPanelSearchBy" style="margin-right: 10%"><?=_ph_family?>&nbsp:</span>
  <input class="adminPanelOrdersInput" id="searchByFamily">

  <span class="adminPanelSearchBy" style="margin-right: 5%"><?=_ph_mobile?>&nbsp:</span>
  <input class="adminPanelOrdersInput" id="searchByMobile">
</div>

<div id="ordersWrapper">
</div>

<script>
  $(function(){
    readOrders();
  });

  $("#timeSelelct").change(function(){
    readOrders();
  });

  $("#searchByFamily").on('keyup',function(){
    readOrders();
  });

  $("#searchByMobile").on('keyup',function(){
    readOrders();
  });

  function readOrders(){
    var timeSelected = $("#timeSelelct").val();
    var searchByFamily = $("#searchByFamily").val();
    var searchByMobile = $("#searchByMobile").val();

    $.ajax({
      url: "<?=baseUrl()?>/admin/fetchOrders",
      method: 'POST',
      data: {
        timeSelected: timeSelected,
        searchByFamily: searchByFamily,
        searchByMobile: searchByMobile
      }
    }).done(function(output) {
      $("#ordersWrapper").empty();
      $("#ordersWrapper").append(output);
    });
  }
</script>