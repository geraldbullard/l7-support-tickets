<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: support_tickets.js.php v1.0 2013-08-08 maestro $
*/
global $lC_Vqmod;
require($lC_Vqmod->modCheck('includes/applications/support_tickets/classes/support_tickets.php'));

class lC_Application_Support_tickets extends lC_Template_Admin {
 /*
  * Protected variables
  */
  protected $_module = 'support_tickets',
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