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
  }
}
?>