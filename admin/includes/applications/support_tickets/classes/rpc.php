<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: rpc.js.php v1.0 2013-08-08 maestro $
*/
global $lC_Vqmod;
require($lC_Vqmod->modCheck('includes/applications/support_tickets/classes/support_tickets.php'));

class lC_Support_tickets_Admin_rpc {
 /*
  * Returns the support_tickets data
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Support_tickets_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Returns the open ticket count
  *
  * @access public
  * @return json
  */
  public static function openTicketCount() {
    $result = lC_Support_tickets_Admin::openTicketCount();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    
    echo json_encode($result);
  }
 /*
  * Deletes the status history row matching the shid from the $_GET
  *
  * @access public
  * @return json
  */
  public static function deleteStatusHistoryBlock() {
    if (lC_Support_tickets_Admin::deleteStatusHistory($_GET['shid']) == 1) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    } 
    
    echo json_encode($result);
  }
}
?>