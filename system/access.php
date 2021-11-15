<?php
function has_access($access, $targetAccess){
  return strhas($access, "|$targetAccess|") ? true : false;
}

function getAccessName(){
  $accessName = "";

  if (isGuest()){
    return "میهمان";
  }

  if (isAdmin()){
    $accessName = "مدیر";
    $accessName = " نوع حساب : " . $accessName;
    return $accessName;
  }

  if (isAccountant()){
    $accessName = ", ". "حسابدار";
  }

  if (isWriter()){
    $accessName .= ", ". "نویسنده";
  }

  if (isSecretary()){
    $accessName .= ", ". "منشی";
  }

  if (!$accessName == ""){
    $accessName = substr($accessName , 2 , strlen($accessName));
    $accessName = " نوع حساب : " . $accessName;
  }

  return $accessName;
}

function isAdmin(){
  if (isGuest()) { return false; }

  $access = $_SESSION["access"];

  if (has_access($access, 'مدیر')){
    return true;
  }
  return false;
}

function isWriter(){
  if (isGuest()) { return false; }

  $access = $_SESSION["access"];

  if (has_access($access, 'نویسنده')){
    return true;
  }
  return false;
}


function isAccountant(){
  if (isGuest()) { return false; }

  $access = $_SESSION["access"];

  if (has_access($access, 'حسابدار')){
    return true;
  }

  return false;
}

function isSecretary(){
  if (isGuest()) { return false; }

  $access = $_SESSION["access"];

  if (has_access($access, 'منشی')){
    return true;
  }
  return false;
}

function isNormalUser(){
  if (isGuest()) { return false; }

  $access = $_SESSION["access"];

  if ($access == ""){
    return true;
  }
  return false;
}

function isUser(){
  return isset($_SESSION["access"]) ? true : false;
}

function isGuest(){
  return !isset($_SESSION["email"]) ? true : false;
}

function grantAdmin(){
  if (!isAdmin()){
    message('fail', _dont_have_permission, true);
    exit;
  }
}

function canAccessAdminPanel(){
  if(!isNormalUser() && !isGuest()){
    return true;
  }
  return false;
}