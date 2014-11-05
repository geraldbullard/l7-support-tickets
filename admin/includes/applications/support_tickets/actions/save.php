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
      
      $data = array('ticket_status' => $_POST['ticket_status'],
                    'ticket_priority' => $_POST['ticket_priority'],
                    'ticket_department' => $_POST['ticket_department'],
                    'ckEditorTicketReply' => $_POST['ckEditorTicketReply']);
                    
      $id = lC_Support_tickets_Admin::save((isset($_GET[$this->_module]) && is_numeric($_GET[$this->_module]) ? $_GET[$this->_module] : null), $data, ($_POST['send_email'] == 'on' ? true : false));
      
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