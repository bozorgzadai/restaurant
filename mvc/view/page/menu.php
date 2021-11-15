<section id="content">
  <div id="menuWrapper">
    <div>
      <span class="titleSortBY"><?=_foodTypeTitle?>&nbsp:</span>
      <select id="foodTypeSelect" class="selectSortBy">
        <option><?=_foodTypeAll?></option>
        <?foreach($foodTypes as $foodType){?>
          <option><?=$foodType['foodType']?></option>
        <?}?>
      </select>

      <span class="titleSortBY" style="margin-right: 5%"><?=_price?>&nbsp:</span>
      <select id="priceSelect" class="selectSortBy">
        <option value="DESC"><?=_desc?></option>
        <option value="ASC"><?=_asc?></option>
      </select>

      <span class="titleSortBY" style="margin-right: 5%"><?=_search?>&nbsp:</span>
      <input id="inputSearch">
    </div>

    <div id="productWrapper">
    </div>
  </div>

  <?if(isset($showOnlyWishList)){ ?>
      <input id="filterWishList" type="hidden" value="showOnlyWishList">
  <?}else{?>
      <input id="filterWishList" type="hidden" alue="showAll">
  <?}?>
</section>

<script>
  $(function(){
    readData();
  });

  $("#foodTypeSelect").change(function(){
    readData();
  });

  $("#priceSelect").change(function(){
    readData();
  });

  $("#inputSearch").on('keyup',function(){
    readData();
  });

  function addToCart($product_id){
    $.ajax({
      url: "<?=baseUrl()?>/product/addToCart/" + $product_id,
      method: 'POST',
      dataType: 'json'
    }).done(function(output){
      $("#cartItems").text(output.orderItems);
      $("#cartPreviewHolder").html(output.cartPreviewOrders);
    });
  }

  function readData(){
    var currentFoodTypeSelected = $("#foodTypeSelect").val();
    var currentPriceSelected = $("#priceSelect").val();
    var searchText = $("#inputSearch").val();
    var wishListFilter = $("#filterWishList").val();

    $.ajax({
      url: "<?=baseUrl()?>/product/fetchProduct",
      method: 'POST',
      data: {
        foodTypeSelected: currentFoodTypeSelected,
        priceSelected: currentPriceSelected,
        searchText: searchText,
        wishListFilter: wishListFilter
      }
    }).done(function(output) {
      $("#productWrapper").empty();
      $("#productWrapper").append(output);
    });
  }
</script>