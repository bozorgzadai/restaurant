<div class="row-bot">
  <div class="row-bot-bg">
    <div class="main">
      <div class="slider-wrapper">
        <div class="slider">
          <ul class="items">
            <li>
              <img src="<?=baseUrl()?>/image/slider/imgSlider1.jpg" alt="" />
            </li>
            <li>
              <img src="<?=baseUrl()?>/image/slider/imgSlider2.jpg" alt="" />
            </li>
            <li>
              <img src="<?=baseUrl()?>/image/slider/imgSlider3.jpg" alt="" />
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<section id="content">
  <div class="ic">

  </div>
  <div class="main">
    <div class="wrapper img-indent-bot">
      <article class="col-1">
        <a href="#"><img class="img-border" src="<?=baseUrl()?>/image/advertisment/banner-1.jpg" alt=""></a>
      </article>
      <article class="col-1">
        <a href="#"><img class="img-border" src="<?=baseUrl()?>/image/advertisment/banner-2.jpg" alt=""></a>
      </article>
      <article class="col-2">
        <a href="#"><img class="img-border" src="<?=baseUrl()?>/image/advertisment/banner-3.jpg" alt=""></a>
      </article>
    </div>
    <div class="wrapper">
      <article class="column-1">
        <div class="indent-left">
          <div class="maxheight indent-bot">
            <h3>خدمات ما</h3>
            <ul class="list-1">
              <li><a href="#">ارائه سرویس در حداقل زمان</a></li>
              <li><a href="#">پذیرایی در مجالش</a></li>
              <li><a href="#">قبول سفارشات شما</a></li>
              <li><a href="#">پخت غذا با دستور پخت شما</a></li>
            </ul>
            <a class="button-1" style="margin-top: 20px" href="#">اطلاعات بیشتر</a>
          </div>
        </div>
      </article>
      <article class="column-2">
        <div class="maxheight indent-bot">
          <h3 class="p1">درباره سایت</h3>
          <h6 class="p2">غدای بیرون بر شهربانو برای کمک به شما همشهریان عزیز جهت لذت از یک غذای خوب و طعمی به یادماندنی</h6>
          <p class="p2">آدرس : اصفهان، خیابان طالقانی، کوچه نارون، ابتدای بازارچه رحیم خان، رستوران شهربانو</p>
          <div id="googleMapWrapper">
            <div id="googleMap" style="width:500px;height:230px;"></div>
          </div>
          <span class="p2">شماره های تماس : </span><span>3-03132363482</span>
          <p class="p2">ارسال با پیک به تمام نقاط اصفهان به صورت رایگان</p>
        </div>
        <a class="button-2" href="#">اطلاعات بیشتر</a>
      </article>
    </div>
  </div>
</section>


<script type="text/javascript"> Cufon.now(); </script>
<script type="text/javascript">
  $(window).load(function() {
    $('.slider')._TMS({
      duration:1000,
      easing:'easeOutQuint',
      preset:'slideDown',
      slideshow:7000,
      banners:false,
      pauseOnHover:true,
      pagination:true,
      pagNums:false
    });
  });

  ///// Google map ////
  $(function(){
    var myCenter=new google.maps.LatLng(32.658734,51.664367);
    google.maps.event.addDomListener(window, 'load', initialize);
    function initialize()
    {
      var mapProp = {
        center:myCenter,
        zoom:17,
        mapTypeId:google.maps.MapTypeId.ROADMAP
      };

      var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
      var marker=new google.maps.Marker({
        position:myCenter,
      });
      marker.setMap(map);
      google.maps.event.trigger(map, "resize");

      var infowindow = new google.maps.InfoWindow({
        content:"&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <?=_restaurantName?>"
      });
      infowindow.open(map,marker);
    }
  });
</script>
