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
require($lC_Vqmod->modCheck('includes/applications/ticket_status/classes/ticket_status.php'));

class lC_Ticket_status_Admin_rpc {
 /*
  * Returns the ticket statuses data
  *
  * @access public
  * @return json
  */
  public static function getAll() {
    $result = lC_Ticket_status_Admin::getAll();
    $result['rpcStatus'] = RPC_STATUS_SUCCESS;

    echo json_encode($result);
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $_GET['sid'] The ticket status id
  * @param boolean $edit True = called from edit dialog else called from delete dialog
  * @access public
  * @return json
  */
  public static function getFormData() {
    $result = lC_Ticket_status_Admin::getFormData($_GET['sid']);
    
    if (!isset($result['rpcStatus'])) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Delete the ticket status record
  *
  * @param integer $_GET['sid'] The ticket status id to delete
  * @access public
  * @return json
  */    
  public static function deleteEntry() {
    $result = array();
    $deleted = lC_Ticket_status_Admin::delete($_GET['sid']);
    
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  }
 /*
  * Batch delete ticket status records
  *
  * @param array $_GET['batch'] An array of ticket status id's
  * @access public
  * @return json
  */ 
  public static function batchDeleteEntries() {
    $result = array();
    $deleted = lC_Ticket_status_Admin::batchDelete($_GET['batch']);
    
    if ($deleted) {
      $result['rpcStatus'] = RPC_STATUS_SUCCESS;
    }

    echo json_encode($result);
  } 
}
?>