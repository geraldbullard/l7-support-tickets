<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.php v1.0 2013-08-08 maestro $
*/
global $lC_Vqmod;
require($lC_Vqmod->modCheck('includes/applications/ticket_priority/classes/ticket_priority.php'));

class lC_Ticket_priority_Admin_rpc {
 /*
  * Returns the ticket priorities data
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Ticket_priority_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
}
?>