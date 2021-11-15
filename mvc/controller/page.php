<?php
class PageController {
  public function home() {
    $data['activePage'] = 'indexHeader';
    $data['pageId'] = 'pageIndex';
    $data['topTitle'] = 'خوش آمدید';
    $data['pageTitle'] = _restaurantName;
    View::render('default', "/page/home.php" , $data);
  }

  public function contactUs() {
    $data['activePage'] = 'contactUsHeader';
    $data['pageId'] = 'pageContactUs';
    $data['topTitle'] = 'فرم تماس';
    $data['pageTitle'] = 'تماس با ما';
    View::render('default', "/page/contactUs.php", $data);
  }

  public function menu() {
    $db = Db::getInstance();
    $foodTypes = $db->query("SELECT DISTINCT foodType FROM food ORDER BY foodType ASC");

    $data['foodTypes'] = $foodTypes;
    $data['activePage'] = 'menuHeader';
    $data['pageId'] = 'pageMenu';
    $data['topTitle'] = 'لیست غذاها';
    $data['pageTitle'] = 'منو';
    View::render('default', "/page/menu.php", $data);
  }

  public function order() {
    $data['activePage'] = 'orderHeader';
    $data['pageId'] = 'pageOrder';
    $data['topTitle'] = 'سفارشات';
    $data['pageTitle'] = 'سفارشات';
    View::render('default', "/page/order.php", $data);
  }
}