<?php
/**
 * @package  morriganPlugin
 */
namespace Inc\Api;
use Inc\Base\BaseController;

class AdminPageCallbacks extends BaseController{
  public function morriganAdminDashboard(){
    return require_once( "$this->plugin_path/templates/admin.php" );
  }
  public function morriganAdminSlider(){
    return require_once("$this->plugin_path/templates/slider.php" );
  }
  public function morriganOptionsGroup($input){
    return $input;
  }
  public function morriganAdminSection(){
    echo 'Check this section!';
  }
  public function morriganTextExample(){
    $textValue = esc_attr( get_option('text_example') );
    echo '<input type="text" class="regular-text" name="text_example" value="'. $textValue .'" placeholder="Write something here"></input>';
  }
  public function morriganAdminCpt(){
    return require_once("$this->plugin_path/templates/morriganCPT.php" );
  }
}
