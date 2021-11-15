<?php
class UserModel {
  public static function insert($email, $firstName, $family, $hashedPassword,$address,$mobile, $time){
    $db = Db::getInstance();
    $db->insert("INSERT INTO x_user
      (email , access, firstName, family, password, address, mobile, registerTime) VALUES (:email, :access, :firstName, :family, :hashedPassword, :address,:mobile,:time)" , array(
      'email' => $email,
      'access' => '',
      'firstName' => $firstName,
      'family' => $family,
      'hashedPassword' => $hashedPassword,
      'address' => $address,
      'mobile' => $mobile,
      'time' => $time,
    ));
  }

  public static function fetch_by_email($email){
    $db = Db::getInstance();
    $record = $db->first("SELECT * FROM x_user WHERE email=:email" ,array(
      'email' => $email,
    ));
    return $record;
  }

  public static function promote_user($userId, $access){
    $db = Db::getInstance();
    $db->modify("UPDATE x_user SET access='$access' WHERE user_id=:user_id" , array(
      'user_id' => $userId,
    ));
  }

  public static function get_user_access($userId){
    $db = Db::getInstance();
    $record = $db->first("SELECT access FROM x_user WHERE user_id=:user_id" , array(
      'user_id' => $userId,
    ));
    return $record['access'];
  }
}