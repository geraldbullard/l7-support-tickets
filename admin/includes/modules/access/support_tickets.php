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

  class lC_Access_Support_tickets extends lC_Access {
    var $_module = 'support_tickets',
        $_group = 'support', 
        $_icon = '',
        $_title,
        $_sort_order = 400;

    function lC_Access_Support_tickets() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_support_tickets_title');
    }
  }
?>