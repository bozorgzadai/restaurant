<?
class View {
  public static function render($theme, $filePath, $data = array()){
    extract($data);

    ob_start();
    require_once(getcwd() . "/mvc/view" . $filePath);
    $content = ob_get_clean();

    if($theme == 'default'){
      require_once(getcwd() . "/theme/default.php");
    }elseif ($theme == 'defaultAdminPanel'){
      require_once(getcwd() . "/theme/defaultAdminPanel.php");
    }
  }

  public static function renderPartial($filePath, $data = array()){
    extract($data);

    require_once(getcwd() . "/mvc/view" . $filePath);
  }
}