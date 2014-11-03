<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: ticket_response.php v1.0 2013-08-08 maestro $
*/

  class lC_Access_Ticket_response extends lC_Access {
    var $_module = 'ticket_response',
        $_group = 'support', 
        $_icon = '',
        $_title,
        $_sort_order = 500;

    function lC_Access_Ticket_response() {
      global $lC_Language;

      $this->_title = $lC_Language->get('access_ticket_response_title');
    }
  }
?>