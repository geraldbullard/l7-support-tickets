<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: ticket_status.php v1.0 2013-08-08 maestro $
*/
global $lC_Vqmod;
require($lC_Vqmod->modCheck('includes/applications/ticket_status/classes/ticket_status.php'));

class lC_Application_Ticket_status extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'ticket_status',
            $_page_title,
            $_page_contents = 'main.php';
 /*
  * Class constructor
  */
  function __construct() {
    global $lC_Language;

    $this->_page_title = $lC_Language->get('heading_title'); 

    if ( !isset($_GET['action']) ) {
      $_GET['action'] = '';
    }

    if ( !empty($_GET['action']) ) {
      switch ( $_GET['action'] ) {
        case 'save':
          /*
          * Save the ticket staus information
          *
          * @param integer $_GET['sID'] The ticket status id
          * @param array $data The status information
          * @access public
          * @return boolean
          */
          $data = array('ticket_status_name' => $_POST['ticket_status_name']);
          
          $default = ((isset($_POST['default']) && $_POST['default'] == 'on') ? true : false);
          
          if (lC_Ticket_status_Admin::save((isset($_GET['sID']) && is_numeric($_GET['sID']) ? $_GET['sID'] : null), $data, $default)) { 
            lc_redirect_admin(lc_href_link_admin(FILENAME_DEFAULT, $this->_module));
          } else {
            $_SESSION['error'] = true;
            $_SESSION['errmsg'] = $lC_Language->get('ms_error_action_not_performed');
          }
          break;
      }
    }
  }
}
?>