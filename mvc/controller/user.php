<?php
class UserController {

  public function __construct(){
  }

  public function logout(){
      session_destroy();
    header("Location: " . fullBaseUrl() . "/user/login");

    session_start();
    session_regenerate_id();
  }

  public function login() {
    if (isset($_POST['email'])){
      $this->loginCheck();
    } else {
      $this->loginForm();
    }
  }

  private function loginForm(){
    $data = returnDafaultData();
    View::render('default', "/user/login.php", $data);
  }

  public function register(){
    if (isset($_POST['email'])){
      $this->registerCheck();
    } else {
      $this->registerForm();
    }
  }

  private function registerForm(){
    $data = returnDafaultData();
    View::render('default', "/user/register.php", $data);
  }

  private function loginCheck(){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $record = UserModel::fetch_by_email($email);
    if ($record == null) {

      message('fail', _email_not_registered, true);
    } else {
      $hashedPassword = encryptPassword($password);
      if ($hashedPassword == $record['password']) {
        $_SESSION['email'] = $record['email'];
        $_SESSION['user_id'] = $record['user_id'];
        $_SESSION['access'] = $record['access'];

        $db = Db::getInstance();
        $productController = new ProductController();
        $cart = $productController->getCurrentCart();

        $db->modify("UPDATE x_invoice SET user_id=:user_id WHERE invoice_id=:invoice_id" , array(
          'user_id' => $record['user_id'],
          'invoice_id' => $cart['invoice_id'],
        ));

        message('success', _login_welcome, true);
      } else {
        message('fail', _invalid_password, true);
      }
    }

    return;
  }


  private function registerCheck(){
    $email = $_POST['email'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    $firstName = $_POST['firstName'];
    $family = $_POST['family'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $time = getCurrentDateTime();

    $record = UserModel::fetch_by_email($email);
    if ($record != null){
      message('fail', _already_registered, true);
    }

    if (strlen($password1)<3 || strlen($password2)<3){
      message('fail', _weak_password, true);
    }

    if ($password1 != $password2){
      message('fail', _password_not_match, true);
    }

    $hashedPassword = encryptPassword($password1);

    UserModel::insert($email, $firstName, $family, $hashedPassword,$address,$mobile, $time);

    message('success', _successfully_registered);
  }

  public function toggleWishList($type , $id){
    $db = DB::getInstance();
    $userId = getUserId();

    if($userId == 0){
      return;
    }

    $wishFound = $db->first("SELECT * FROM user_wish WHERE user_id=:user_id AND resource_id=:resource_id AND resourceType=:resourceType",array(
      'user_id' => $userId,
      'resource_id' => $id,
      'resourceType' => $type,
    ));

    if($wishFound == null){
      $this->addToWishList($type , $id);
      $isInWishList = 1;
    } else {
      $this->removeFromWishList($type, $id);
      $isInWishList = 0;
    }

    $data['isInWishList'] = $isInWishList;
    echo json_encode($data);
  }

  private function addToWishList($type , $id){
    $db = DB::getInstance();
    $userId = getUserId();

    $db->insert("INSERT INTO user_wish (user_id , resource_id , resourceType) VALUES (:user_id , :resource_id , :resourceType)",array(
      'user_id' => $userId,
      'resource_id' => $id,
      'resourceType' => 1,
    ));
  }

  private function removeFromWishList($type , $id){
    $db = DB::getInstance();
    $userId = getUserId();

    $db->modify("DELETE FROM user_wish WHERE user_id=:user_id AND resource_id=:resource_id AND resourceType=:resourceType",array(
      'user_id' => $userId,
      'resource_id' => $id,
      'resourceType' => $type,
    ));
  }

  public function wishList(){
    $db = Db::getInstance();
    $foodTypes = $db->query("SELECT DISTINCT foodType FROM food ORDER BY foodType ASC");

    $data['foodTypes'] = $foodTypes;
    $data['activePage'] = 'menuHeader';
    $data['pageId'] = 'pageMenu';
    $data['topTitle'] = 'لیست غذاهای مورد علاقه';
    $data['pageTitle'] = 'منو';
    $data['showOnlyWishList'] = 1;

    View::render('default', "/page/menu.php" , $data);
  }
}