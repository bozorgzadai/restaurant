<?php
function hr($return = false){
  if ($return){
    return "<hr>\n";
  } else {
    echo "<hr>\n";
  }
}

function br($return = false){
  if ($return){
    return "<br>\n";
  } else {
    echo "<br>\n";
  }

}

function dump($var, $return = false){
  if (is_array($var)){
    $out = print_r($var, true);
  } else if (is_object($var)) {
    $out = var_export($var, true);
  } else {
    $out = $var;
  }

  if ($return){
    return "\n<pre style='direction: ltr'>$out</pre>\n";
  } else {
    echo "\n<pre style='direction: ltr'>$out</pre>\n";
  }
}

function getCurrentDateTime(){
  return date("Y-m-d H:i:s");
}

function encryptPassword($password){
  global $config;
  return md5($password . $config['salt']);
}

function getFullUrl(){
  $fullurl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  return $fullurl;
}

function getRequestUri(){
  return $_SERVER['REQUEST_URI'];
}

function baseUrl(){
  global $config;
  return $config['base'];
}

function fullBaseUrl(){
  global $config;
  return 'http://' . $_SERVER['HTTP_HOST'] . $config['base'];
}

function strhas($string, $search, $caseSensitive = false){
  if ($caseSensitive){
    return strpos($string, $search) !== false;
  } else {
    return strpos(strtolower($string), strtolower($search)) !== false;
  }
}

function message($type, $message, $mustExit = false) {
  $data = returnDafaultData();
  $data['message'] = $message;
  View::render('default', "/message/$type.php", $data);
  if ($mustExit){
    exit;
  }
}

function twoDigitNumber($number){
  return ($number < 10) ? $number = "0" . $number : $number;
}

function jdate($date, $format="Y-m-d"){
  $timestamp = strtotime($date);
  $secondsInOneDay = 24*60*60;
  $daysPassed = floor($timestamp / $secondsInOneDay) + 1;

  $days = $daysPassed;
  $month = 11;
  $year = 1348;

  $days -= 19;

  $daysInMonths = array( 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29 );

  $monthNames = array(
    'فروردین',
    'اردیبهشت',
    'خرداد',
    'تیر',
    'مرداد',
    'شهریور',
    'مهر',
    'آبان',
    'آذر',
    'دی',
    'بهمن',
    'اسفند',
  );


  while (true){
    if ($days > $daysInMonths[$month-1]){
      $days -= $daysInMonths[$month-1];
      $month++;
      if ($month == 13){
        $year++;
        if (($year - 1347) % 4 == 0){
          $days--;
        }
        $month = 1;
      }
    } else {
      break;
    }
  }

  $month = twoDigitNumber($month);
  $days =  twoDigitNumber($days);

  $monthName = $monthNames[$month-1];

  $output = $format;
  $output = str_replace("Y", $year, $output);
  $output = str_replace("m", $month, $output);
  $output = str_replace("d", $days, $output);
  $output = str_replace("M", $monthName, $output);

  return $output;
}

function pagination($url, $showCount, $activeClass, $deactiveClass, $currentPageIndex, $pageCount, $jsFunction = null){
  ob_start();

  if ($jsFunction){
    $tags = "span";
    $action = 'onclick="' . $jsFunction . '(#)"';
  } else {
    $tags = "a";
    $action = 'href="' . $url . '/#"';
  }
  ?>

  <? $rAction = str_replace("#", "1", $action); ?>
  <<?=$tags?> <?=$rAction?> class="<?=$activeClass?>">1</<?=$tags?>>
  <span>..</span>
  <? for ($i=$currentPageIndex-$showCount; $i<=$currentPageIndex+$showCount; $i++){ ?>
    <? if ($i <= 1) { continue; } ?>
    <? if ($i >= $pageCount) { continue; } ?>
    <? if ($i == $currentPageIndex) { ?>
      <span class="<?=$deactiveClass?>"><?=$i?></span>
    <? } else { ?>
      <? $rAction = str_replace("#", $i, $action); ?>
      <<?=$tags?> <?=$rAction?> class="<?=$activeClass?>"><?=$i?></<?=$tags?>>
    <? } ?>
  <? } ?>
  <span>..</span>
  <? $rAction = str_replace("#", $pageCount, $action); ?>
  <<?=$tags?> <?=$rAction?> class="<?=$activeClass?>"><?=$pageCount?></<?=$tags?>>

  <?
  $output = ob_get_clean();
  return $output;
}

function generateHash($length = 32) {
  $characters = '2345679acdefghjkmnpqrstuvwxyz';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

function returnDafaultData(){
    $data['activePage'] = 'indexHeader';
    $data['pageId'] = 'pageIndex';
    $data['topTitle'] = 'ثبت نام';
    $data['pageTitle'] = _restaurantName;
    return $data;
}

function  afterCloseTransaction($info){
  $db = Db::getInstance();
  $hash = $info['invoiceHash'];

  $invoice_id = $db->first("SELECT * FROM x_invoice WHERE hash=:hash" , array(
    'hash' => $hash,
  ) , 'invoice_id');

  $paymentTime = $db->first("SELECT * FROM x_transaction WHERE invoice_hash=:invoice_hash" , array(
    'invoice_hash' => $hash,
  ) , 'paymentTime');

  $db->modify("UPDATE pym_cart SET paid=:paid AND paymentTime=:paymentTime WHERE invoice_id=:invoice_id" , array(
    'paid' => 1,
    'paymentTime' => $paymentTime,
    'invoice_id' => $invoice_id,
  ));

}

function getUserId(){
  if(isset($_SESSION['user_id'])){
    return $_SESSION['user_id'];
  }else{
    return 0;
  }
}