<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: save.php v1.0 2013-08-08 maestro $
*/
class lC_Application_Support_tickets_Actions_save extends lC_Application_Support_tickets {
 /*
  * Class constructor
  */
  public function __construct() {
    global $lC_Language, $lC_MessageStack, $lC_Currencies, $lC_DateTime;

    parent::__construct();
  
    $this->_page_contents = 'edit.php';

    if ( isset($_POST['subaction']) && ($_POST['subaction'] == 'confirm') ) {
      
      // validate the input
      /*$type = (isset($_POST['type']) && $_POST['type'] != NULL) ? preg_replace('/[^A-Z\s]/', '', $_POST['type']) : 'R';
      $name = (isset($_POST['name']) && $_POST['name'] != NULL) ? preg_replace('/[^A-Za-z0-9\s]/', '', $_POST['name']) : NULL;
      $reward = (isset($_POST['reward']) && $_POST['reward'] != NULL) ? preg_replace('/[^0-9\s\.\%]/', '', $_POST['reward']) : 0.00;
      $mode = (isset($_POST['mode']) && $_POST['mode'] != NULL) ? preg_replace('/[^a-z\s]/', '', $_POST['mode']) : 'coupon';
      $code = (isset($_POST['code']) && $_POST['code'] != NULL) ? preg_replace('/[^A-Za-z0-9\s]/', '', $_POST['code']) : substr(str_shuffle(str_repeat('ABCEFGHJKLMNPRSTUVWXYZabcdefghjklmnpqrstuvwxyz23456789',7)),0,7);
      $purchase_over = (isset($_POST['purchase_over']) && $_POST['purchase_over'] != NULL) ? preg_replace('/[^0-9\s\.\%]/', '', $_POST['purchase_over']) : 0.00;
      $start_date = (isset($_POST['start_date']) && $_POST['start_date'] != NULL) ? preg_replace('/[^0-9\s\/]/', '', $_POST['start_date']) : NULL;
      $expires_date = (isset($_POST['expires_date']) && $_POST['expires_date'] != NULL) ? preg_replace('/[^0-9\s\/]/', '', $_POST['expires_date']) : NULL;
      $uses_per_coupon = (isset($_POST['uses_per_coupon']) && $_POST['uses_per_coupon'] != NULL) ? preg_replace('/[^0-9\s]/', '', $_POST['uses_per_coupon']) : 1;
      $uses_per_customer = (isset($_POST['uses_per_customer']) && $_POST['uses_per_customer'] != NULL) ? preg_replace('/[^0-9\s]/', '', $_POST['uses_per_customer']) : 1;
      $restrict_to_products = (isset($_POST['restrict_to_products']) && $_POST['restrict_to_products'] != NULL) ? preg_replace('/[^0-9\s]/', '', $_POST['restrict_to_products']) : -1;
      $restrict_to_categories = (isset($_POST['restrict_to_categories']) && $_POST['restrict_to_categories'] != NULL) ? preg_replace('/[^0-9\s]/', '', $_POST['restrict_to_categories']) : -1;
      $restrict_to_customers = (isset($_POST['restrict_to_customers']) && $_POST['restrict_to_customers'] != NULL) ? preg_replace('/[^0-9\s]/', '', $_POST['restrict_to_customers']) : -1;
      $status = (isset($_POST['status']) && $_POST['status'] == 'on') ? 1 : -1;
      $sale_exclude = (isset($_POST['sale_exclude']) && $_POST['sale_exclude'] == 'on') ? 1 : -1;
      $notes = (isset($_POST['notes']) && $_POST['notes'] != NULL) ? preg_replace('/[^a-zA-Z0-9\s\.\%\,]/', '', $_POST['notes']) : NULL;

      $data = array('name' => $name,
                    'type' => $type,
                    'mode' => $mode,
                    'code' => $code,
                    'reward' => str_replace("%", "", $reward),
                    'purchase_over' => $purchase_over,                      
                    'start_date' => $start_date,                      
                    'expires_date' => $expires_date,
                    'uses_per_coupon' => $uses_per_coupon,
                    'uses_per_customer' => $uses_per_customer,
                    'restrict_to_products' => $restrict_to_products,
                    'restrict_to_categories' => $restrict_to_categories,
                    'restrict_to_customers' => $restrict_to_customers,
                    'status' => $status,
                    'sale_exclude' => $sale_exclude,
                    'notes' => $notes);*/ 

      $id = lC_Support_tickets_Admin::save((isset($_GET[$this->_module]) && is_numeric($_GET[$this->_module]) ? $_GET[$this->_module] : null), $data);
      
      if (is_numeric($id) && isset($id)) {
        if (!empty($_POST['save_close'])) {
          lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module));
        } else {          
          lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module.'='.$id.'&action=save'));
        }
      } else {
        $lC_MessageStack->add($this->_module, $lC_Language->get('ms_error_action_not_performed'), 'error');
        lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module));
      }
    }
  }
}
?>