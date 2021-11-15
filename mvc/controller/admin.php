<?php
class AdminController {

  public function __construct() {
    if(!canAccessAdminPanel()){
      message('fail', _dont_have_permission, true);
      exit;
    }
  }

  public function fetchOrders() {
    $timeSelected = $_POST['timeSelected'];
    $searchByFamily = $_POST['searchByFamily'];
    $searchByMobile = $_POST['searchByMobile'];

    if($timeSelected == _ap_orders_lastHour){
      $timeSelected = "paymentTime >= DATE_SUB(NOW(),INTERVAL 1 HOUR)";
    }elseif($timeSelected == _ap_orders_today){
      $timeSelected = "DATE(paymentTime) = CURDATE()";
    }elseif($timeSelected == _ap_orders_yesterday){
      $timeSelected = "paymentTime >= subdate(current_date, 1) AND paymentTime < current_date";
    }elseif($timeSelected == _ap_orders_currentWeek){
      $timeSelected = "YEARWEEK(paymentTime) = YEARWEEK(CURDATE())";
    }elseif($timeSelected == _ap_orders_currentMonth){
      $timeSelected = "MONTH(paymentTime) = MONTH(CURDATE()) AND YEAR(paymentTime) = YEAR(CURDATE())";
    }elseif($timeSelected == _ap_orders_lastWeek){
      $timeSelected = "paymentTime >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY AND paymentTime < curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY";
    }elseif($timeSelected == _ap_orders_lastMonth){
      $timeSelected = "paymentTime >= DATE_ADD(LAST_DAY(DATE_SUB(curdate(), INTERVAL 2 MONTH)), INTERVAL 1 DAY) AND paymentTime <= DATE_ADD(LAST_DAY(DATE_SUB(curdate(), INTERVAL 1 MONTH)), INTERVAL 1 DAY)";
    }

    $db = Db::getInstance();
    $paidCarts = $db->query("SELECT * FROM pym_cart INNER JOIN pym_order ON pym_cart.cart_id = pym_order.cart_id INNER JOIN food ON food.food_id = pym_order.product_id INNER JOIN x_user ON pym_cart.user_id=x_user.user_id WHERE pym_cart.paid=1 AND family LIKE '%$searchByFamily%' AND mobile LIKE '%$searchByMobile%' AND $timeSelected" ,array(
      'paid' => 1,
    ));

    $data['paidCarts'] = $paidCarts;
    View::renderPartial("/admin/fetchOrders.php" , $data);
  }

  public function panel(){
    $accessName = getAccessName();
    $accessName = str_replace('نوع حساب : ','',$accessName);

    $data['accessName'] = $accessName;
    View::render('defaultAdminPanel', "/admin/panel.php" , $data);
  }

  public function promote_user_form(){
    View::render('default', "/admin/promote_user.php");
  }

  public function getUserAccess($userId){
    $output['access'] = UserModel::get_user_access($userId);
    echo json_encode($output);
  }

  public function promote(){
    $userId = $_POST['userId'];
    $access = $_POST['access'];

    $access = str_replace(' ', '', $access);
    $access = '|' . str_replace(',', '|' , $access) . '|';

    UserModel::promote_user($userId, $access);
    echo "OK";
  }
}
