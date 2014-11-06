<?php
/**
  @package    catalog::admin::applications
  @author     ContributionCentral
  @copyright  Copyright 2014 ContributionCentral
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: support_tickets.php v1.0 2013-08-08 maestro $
*/
global $lC_Vqmod;
require_once($lC_Vqmod->modCheck('includes/applications/orders/classes/orders.php'));

class lC_Support_tickets_Admin {
 /*
  * Returns the support_tickets data
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $_module;

    $lC_Language->loadIniFile('support_tickets.php');

    $Qtickets = $lC_Database->query('select * from :table_tickets order by date_added desc');
    $Qtickets->bindTable(':table_tickets', DB_TABLE_PREFIX . 'tickets');
    $Qtickets->execute();
    
    $result = array('aaData' => array());
    while ($Qtickets->next()) {

      $Qlatestcomments = $lC_Database->query('select * from :table_ticket_status_history where ticket_id = :ticket_id order by ticket_status_history_id desc limit 1');
      $Qlatestcomments->bindTable(':table_ticket_status_history', DB_TABLE_PREFIX . 'ticket_status_history');
      $Qlatestcomments->bindInt(':ticket_id', $Qtickets->valueInt('ticket_id'));
      $Qlatestcomments->execute();
      
      $oValid = array();
      $oValid = lC_Orders_Admin::getInfo($Qtickets->valueInt('orders_id'));
      
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qtickets->valueInt('ticket_id') . '" id="' . $Qtickets->valueInt('ticket_id') . '"></td>';
      $ticket = '<td>
                   <span class="tag grey-bg">' . $Qtickets->valueInt('ticket_id') . '</span> ' . $Qtickets->value('subject') . '
                   <p class="small mid-margin-top">' . 
                     '<span class="strong">' . $lC_Language->get('text_ticket_latest_comments') . ': <br class="small-margin-bottom" />' . 
                     '<span class="anthracite">' . $Qlatestcomments->value('ticket_edited_by') . '<br class="small-margin-bottom" />' .
                     substr(lC_DateTime::getLong($Qlatestcomments->value('ticket_date_modified'), true), 0, -5) . ' ' . str_replace(' ', '', date("g:i a", strtotime(substr(lC_DateTime::getLong($Qlatestcomments->value('ticket_date_modified'), true), -5)))) . '</span></span>' .
                     '<br class="mid-margin-bottom" /> ' . 
                     $Qlatestcomments->value('ticket_comments') . 
                   '</p>
                 </td>';
      $customer = '<td>
                     <a class="strong" href="' . lc_href_link_admin(FILENAME_DEFAULT, 'customers&cID=' . $Qtickets->valueInt('customers_id')) . '" title="' . $lC_Language->get('text_view_customer_listing') . '" target="_blank">
                       <span class="icon-user small-margin-right"></span> 
                       ' . $Qtickets->value('customers_name') . '
                     </a>';
      if ($Qtickets->valueInt('orders_id') != '-1' && $oValid['error'] != true) {               
        $customer .= '<br class="mid-margin-bottom">
                       <a href="' . lc_href_link_admin(FILENAME_DEFAULT, 'orders=' . $Qtickets->valueInt('orders_id')) . '&action=save" target="_blank" class="strong">
                         <span class="icon-price-tag red small-margin-right"></span> Related Order
                       </a>';
      }               
      $customer .= '</td>';
      $status = '<td><span class="tag ' . self::getStatusColor($Qlatestcomments->valueInt('ticket_status_id')) . '-bg no-wrap with-small-padding">' . ucfirst(self::getStatusTitle($Qlatestcomments->valueInt('ticket_status_id'))) . '</span></td>';
      $priority = '<td><span class="tag ' . self::getPriorityColor($Qlatestcomments->valueInt('ticket_priority_id')) . '-bg no-wrap with-small-padding">' . self::getPriorityTitle($Qlatestcomments->valueInt('ticket_priority_id')) . '</span></td>';
      $date = '<td>' . substr(lC_DateTime::getLong($Qtickets->value('date_added'), true), 0, -5) . ' ' . str_replace(' ', '', date("g:i a", strtotime(substr(lC_DateTime::getLong($Qtickets->value('date_added'), true), -5)))) . '</td>';
      $modified = '<td>' . substr(lC_DateTime::getLong($Qlatestcomments->value('ticket_date_modified'), true), 0, -5) . ' ' . str_replace(' ', '', date("g:i a", strtotime(substr(lC_DateTime::getLong($Qlatestcomments->value('ticket_date_modified'), true), -5)))) . '</td>';
      $action = '<td class="align-right vertical-center">
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qtickets->valueInt('ticket_id') . '&action=save')) . '" class="button icon-pencil ' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? 'disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   </span>
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteTicket(\'' . $Qtickets->valueInt('ticket_id') . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                   </span>
                 </span></td>';
                 
      $Qlatestcomments->freeResult();                 

      $result['aaData'][] = array("$check", "$ticket", "$customer", "$status", "$priority", "$date", "$modified", "$action");
      
    }

    return $result;
  }
 /*
  * Returns the ticket information
  *
  * @param integer $id The ticket id
  * @access public
  * @return array
  */
  public static function get($id) {
    global $lC_Database, $lC_Language;

    $Qticket = $lC_Database->query('select t.*, tsh.* from :table_tickets t left join :table_ticket_status_history tsh on (t.ticket_id = tsh.ticket_id) where t.ticket_id = :ticket_id order by tsh.ticket_status_history_id asc');
    $Qticket->bindTable(':table_tickets', DB_TABLE_PREFIX . 'tickets');
    $Qticket->bindTable(':table_ticket_status_history', DB_TABLE_PREFIX . 'ticket_status_history');
    $Qticket->bindInt(':ticket_id', $id);
    $Qticket->execute();
    
    while ($Qticket->next()) {
      $data[] = $Qticket->toArray();
    }
    
    $Qticket->freeResult();
    
    return $data;
  }
 /*
  * Save the ticket information
  *
  * @param integer $id The ticket id used on update, null on insert
  * @param array $data An array containing the ticket information
  * @param boolean $send_email True = send email
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data = null, $send_email = true) { 
    global $lC_Database, $lC_Language, $lC_DateTime;
    
    $lC_Language->loadIniFile('support_tickets.php');
    
    $error = false;
    $lC_Database->startTransaction();
    
    $Qcustomer = $lC_Database->query('select customers_firstname, customers_lastname, customers_email_address from :table_customers where customers_id = :customers_id limit 1;');
    $Qcustomer->bindTable(':table_customers', TABLE_CUSTOMERS);
    $Qcustomer->bindInt(':customers_id', $data['ticket_customer_id']);
    $Qcustomer->execute(); 
    
    if (!empty($id) && is_numeric($id)) {
      $Qticket = $lC_Database->query('update :table_tickets set status_id = :status_id, department_id = :department_id, priority_id = :priority_id, last_modified = :last_modified where ticket_id = :ticket_id');
      $Qticket->bindInt(':ticket_id', $id);
    } else {
      $Qticket = $lC_Database->query('insert into :table_tickets (link_id, customers_id, customers_email, customers_name, orders_id, subject, status_id, department_id, priority_id, date_added, last_modified, login_required) values (:link_id, :customers_id, :customers_email, :customers_name, :orders_id, :subject, :status_id, :department_id, :priority_id, :date_added, :last_modified, :login_required);');
      $Qticket->bindValue(':link_id', substr(str_shuffle("abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ"), 0, 1) . substr(str_shuffle("23456789abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ"), 0, 11));
      $Qticket->bindInt(':customers_id', $data['ticket_customer_id']);
      $Qticket->bindValue(':customers_email', $Qcustomer->value('customers_email_address'));
      $Qticket->bindValue(':customers_name', $Qcustomer->value('customers_firstname') . ' ' . $Qcustomer->value('customers_lastname'));
      $Qticket->bindRaw(':date_added', 'now()');
      $Qticket->bindInt(':orders_id', $data['ticket_order_id']);
      $Qticket->bindValue(':subject', $data['ticket_subject']);
    }

    $Qticket->bindTable(':table_tickets', DB_TABLE_PREFIX . 'tickets');
    $Qticket->bindInt(':status_id', $data['ticket_status']);
    $Qticket->bindInt(':department_id', $data['ticket_department']);
    $Qticket->bindInt(':priority_id', $data['ticket_priority']);
    $Qticket->bindRaw(':last_modified', 'now()');
    $Qticket->bindInt(':login_required', $data['login_required']);
    $Qticket->setLogging($_SESSION['module'], $id);
    $Qticket->execute();
    
    $tid = (!empty($id) && is_numeric($id)) ? $id : $lC_Database->nextID();      

    if ($lC_Database->isError()) {
      $error = true;
    } else {      
      $Qhistory = $lC_Database->query('insert into :table_ticket_status_history (ticket_id, ticket_status_id, ticket_priority_id, ticket_department_id, ticket_date_modified, ticket_customer_notified, ticket_comments, ticket_edited_by) values (:ticket_id, :ticket_status_id, :ticket_priority_id, :ticket_department_id, :ticket_date_modified, :ticket_customer_notified, :ticket_comments, :ticket_edited_by);');
      $Qhistory->bindTable(':table_ticket_status_history', DB_TABLE_PREFIX . "ticket_status_history");
      $Qhistory->bindInt(':ticket_id', $tid);
      $Qhistory->bindInt(':ticket_status_id', $data['ticket_status']);
      $Qhistory->bindInt(':ticket_priority_id', $data['ticket_priority']);
      $Qhistory->bindInt(':ticket_department_id', $data['ticket_department']);
      $Qhistory->bindRaw(':ticket_date_modified', 'now()');
      $Qhistory->bindInt(':ticket_customer_notified', ($send_email === true) ? 1 : -1);
      $Qhistory->bindValue(':ticket_comments', $data['ckEditorTicketReply']);
      $Qhistory->bindValue(':ticket_edited_by', $Qcustomer->value('customers_email_address'));
      $Qhistory->setLogging($_SESSION['module'], $tid);
      $Qhistory->execute();

      if ($lC_Database->isError()) {
        $error = true;
      }
    }
    
    if ($error === false) {
      //$lC_Database->commitTransaction();

      /*if ($send_email === true) {
        $full_name = trim($data['firstname'] . ' ' . $data['lastname']);
        $email_text = '';
        if ( ACCOUNT_GENDER > -1 ) {
          if ( $data['gender'] == 'm' ) {
            $email_text .= sprintf($lC_Language->get('email_greet_mr'), trim($data['lastname'])) . "\n\n";
          } else {
            $email_text .= sprintf($lC_Language->get('email_greet_ms'), trim($data['lastname'])) . "\n\n";
          }
        } else {
          $email_text .= sprintf($lC_Language->get('email_greet_general'), $full_name) . "\n\n";
        }
        $email_text .= sprintf($lC_Language->get('email_text'), STORE_NAME, STORE_OWNER_EMAIL_ADDRESS, trim($data['password']));
        $email_subject = sprintf($lC_Language->get('email_subject'), STORE_NAME);
        lc_email($full_name, $data['email_address'], $email_subject, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }*/

      return $tid;
    } else {
      //$lC_Database->rollbackTransaction();
      return false;
    }
  }
 /*
  * Returns the ticket priority title
  *
  * @access public
  * @return string
  */
  public static function delete($tid = null) {
    global $lC_Database;
    
    $error = false;
    $lC_Database->startTransaction();
    
    $Qdelt = $lC_Database->query("DELETE FROM :table_tickets WHERE ticket_id = :ticket_id limit 1");
    $Qdelt->bindTable(':table_tickets', DB_TABLE_PREFIX . 'tickets');
    $Qdelt->bindInt(':ticket_id', $tid);
    $Qdelt->setLogging($_SESSION['module'], $tid);
    $Qdelt->execute(); 
    
    if ($lC_Database->isError()) {
      $error = true;
    }
    
    $Qdeltsh = $lC_Database->query("DELETE FROM :table_ticket_status_history WHERE ticket_id = :ticket_id");
    $Qdeltsh->bindTable(':table_ticket_status_history', DB_TABLE_PREFIX . 'ticket_status_history');
    $Qdeltsh->bindInt(':ticket_id', $tid);
    $Qdeltsh->execute();
    
    if ($lC_Database->isError()) {
      $error = true;
    }
    
    if ($error === false) {
      $lC_Database->commitTransaction();
      return true;
    } else {
      $lC_Database->rollbackTransaction();
      return false;
    }
  }
 /*
  * Return the ticket status dropdown
  *
  * @access public
  * @return array
  */ 
  public static function drawTicketStatusDropdown($tid, $classes = null) { 
    global $lC_Database, $lC_Language;

    $Qstatus = $lC_Database->query('select ticket_status_id, ticket_status_name from :table_ticket_status where ticket_language_id = :ticket_language_id order by ticket_status_id asc');
    $Qstatus->bindTable(':table_ticket_status', DB_TABLE_PREFIX . 'ticket_status');
    $Qstatus->bindInt(':ticket_language_id', $lC_Language->getID());
    $Qstatus->execute();
    
    $Qstatusarray = array();
    while ($Qstatus->next()) {
      $Qstatusarray[$Qstatus->valueInt('ticket_status_id')] = $Qstatus->value('ticket_status_name');
    }
    
    $tsDropdown = '<select class="select withClearFunctions' . ((!empty($classes)) ? ' ' . $classes : null) . '" style="min-width:150px" id="ticket_status" name="ticket_status">';
    foreach ($Qstatusarray as $id => $val) {
      $tsDropdown .= '<option value="' . $id . '"';
      if ($tid == $id) {
        $tsDropdown .= ' selected="selected"';
      }
      $tsDropdown .= '>' . $val . '</option>';
    }
    $tsDropdown .= '</select>';
    
    return $tsDropdown;
  }
 /*
  * Return the ticket priority dropdown
  *
  * @access public
  * @return array
  */ 
  public static function drawTicketPriorityDropdown($pid, $classes = null) { 
    global $lC_Database, $lC_Language;

    $Qpriority = $lC_Database->query('select ticket_priority_id, ticket_priority_name from :table_ticket_priority where ticket_language_id = :ticket_language_id order by ticket_priority_id asc');
    $Qpriority->bindTable(':table_ticket_priority', DB_TABLE_PREFIX . 'ticket_priority');
    $Qpriority->bindInt(':ticket_language_id', $lC_Language->getID());
    $Qpriority->execute();
    
    $Qpriorityarray = array();
    while ($Qpriority->next()) {
      $Qpriorityarray[$Qpriority->valueInt('ticket_priority_id')] = $Qpriority->value('ticket_priority_name');
    }

    $tpDropdown = '<select class="select withClearFunctions' . ((!empty($classes)) ? ' ' . $classes : null) . '" style="min-width:150px" id="ticket_priority" name="ticket_priority">';
    foreach ($Qpriorityarray as $id => $val) {
      $tpDropdown .= '<option value="' . $id . '"';
      if ($pid == $id) {
        $tpDropdown .= ' selected="selected"';
      }
      $tpDropdown .= '>' . $val . '</option>';
    }
    $tpDropdown .= '</select>';
    
    return $tpDropdown;
  }
 /*
  * Return the ticket department dropdown
  *
  * @access public
  * @return array
  */ 
  public static function drawTicketDepartmentDropdown($did, $classes = null) { 
    global $lC_Database, $lC_Language;

    $Qdepartment = $lC_Database->query('select ticket_department_id, ticket_department_name from :table_ticket_department where ticket_language_id = :ticket_language_id order by ticket_department_id asc');
    $Qdepartment->bindTable(':table_ticket_department', DB_TABLE_PREFIX . 'ticket_department');
    $Qdepartment->bindInt(':ticket_language_id', $lC_Language->getID());
    $Qdepartment->execute();
    
    $Qdepartmentarray = array();
    while ($Qdepartment->next()) {
      $Qdepartmentarray[$Qdepartment->valueInt('ticket_department_id')] = $Qdepartment->value('ticket_department_name');
    }
    
    $tdDropdown = '<select class="select withClearFunctions' . ((!empty($classes)) ? ' ' . $classes : null) . '" style="min-width:150px" id="ticket_department" name="ticket_department">';
    foreach ($Qdepartmentarray as $id => $val) {
      $tdDropdown .= '<option value="' . $id . '"';
      if ($did == $id) {
        $tdDropdown .= ' selected="selected"';
      }
      $tdDropdown .= '>' . $val . '</option>';
    }
    $tdDropdown .= '</select>';
    
    return $tdDropdown;
  }
 /*
  * Return the ticket response dropdown
  *
  * @access public
  * @return array
  */ 
  public static function drawTicketResponseDropdown($rid, $classes = null) { 
    global $lC_Database, $lC_Language;

    $Qresponse = $lC_Database->query('select ticket_response_text, ticket_response_name from :table_ticket_response where ticket_language_id = :ticket_language_id order by ticket_response_id asc');
    $Qresponse->bindTable(':table_ticket_response', DB_TABLE_PREFIX . 'ticket_response');
    $Qresponse->bindInt(':ticket_language_id', $lC_Language->getID());
    $Qresponse->execute();
    
    $Qresponsearray = array();
    while ($Qresponse->next()) {
      $Qresponsearray[$Qresponse->value('ticket_response_text')] = $Qresponse->value('ticket_response_name');
    }
    
    $trDropdown = '<select onchange="updateReply(this.value + \' \');" class="select withClearFunctions' . ((!empty($classes)) ? ' ' . $classes : null) . '" style="min-width:150px" id="ticket_response" name="ticket_response">';
    $trDropdown .= '<option value="">' . $lC_Language->get('text_select_response') . '</option>';
    foreach ($Qresponsearray as $id => $val) {
      $trDropdown .= '<option value="' . $id . '">' . $val . '</option>';
    }
    $trDropdown .= '</select>';
    
    return $trDropdown;
  }
 /*
  * Return the customer orders dropdown
  *
  * @access public
  * @return array
  */ 
  public static function drawTicketOrdersDropdown($cid, $classes = null) { 
    global $lC_Database, $lC_Language;

    //$Qorders = $lC_Database->query('select ticket_response_text, ticket_response_name from :table_ticket_response where ticket_language_id = :ticket_language_id order by ticket_response_id asc');
    //$Qorders->bindTable(':table_ticket_response', DB_TABLE_PREFIX . 'ticket_response');
    //$Qorders->bindInt(':ticket_language_id', $lC_Language->getID());
    //$Qorders->execute();
    
    //$Qordersarray = array();
    //while ($Qorders->next()) {
    //  $Qordersarray[$Qorders->value('')] = $Qorders->value('');
    //}
    
    $coDropdown = '<select class="select withClearFunctions' . ((!empty($classes)) ? ' ' . $classes : null) . '" style="min-width:150px" id="ticket_order_id" name="ticket_order_id">';
    $coDropdown .= '<option value="">' . $lC_Language->get('text_select_order') . '</option>';
    $coDropdown .= '<option value="1">#1 $13.98 10/22/2014</option>';
    /*foreach ($Qordersarray as $id => $val) {
      $coDropdown .= '<option value="' . $id . '">' . $val . '</option>';
    }*/
    $coDropdown .= '</select>';
    
    return $coDropdown;
  }
 /*
  * Returns the number of open tickets
  *
  * @access public
  * @return array
  */
  public static function openTicketCount() {
    global $lC_Database;

    $Qopentickets = $lC_Database->query("SELECT COUNT(*) AS count FROM :table_tickets WHERE status_id = 1");
    $Qopentickets->bindTable(':table_tickets', DB_TABLE_PREFIX . 'tickets');
    $Qopentickets->execute();
    
    $result['count'] = $Qopentickets->value('count');
    
    return $result;
  }
 /*
  * Returns the ticket status title
  *
  * @access public
  * @return string
  */
  public static function getStatusTitle($sid = null) {
    global $lC_Database, $lC_Language;

    $Qstatustitle = $lC_Database->query("SELECT ticket_status_name FROM :table_ticket_status WHERE ticket_status_id = :ticket_status_id AND ticket_language_id = :ticket_language_id LIMIT 1");
    $Qstatustitle->bindTable(':table_ticket_status', DB_TABLE_PREFIX . 'ticket_status');
    $Qstatustitle->bindInt(':ticket_status_id', $sid);
    $Qstatustitle->bindInt(':ticket_language_id', $lC_Language->getID());
    $Qstatustitle->execute();
    
    return $Qstatustitle->value('ticket_status_name');
  }
 /*
  * Returns the ticket status color
  *
  * @access public
  * @return string
  */
  public static function getStatusColor($sid = null) {
    global $lC_Database, $lC_Language;

    $Qstatuscolor = $lC_Database->query("SELECT ticket_status_color FROM :table_ticket_status WHERE ticket_status_id = :ticket_status_id AND ticket_language_id = :ticket_language_id LIMIT 1");
    $Qstatuscolor->bindTable(':table_ticket_status', DB_TABLE_PREFIX . 'ticket_status');
    $Qstatuscolor->bindInt(':ticket_status_id', $sid);
    $Qstatuscolor->bindInt(':ticket_language_id', $lC_Language->getID());
    $Qstatuscolor->execute();
    
    return $Qstatuscolor->value('ticket_status_color');
  }
 /*
  * Returns the ticket priority title
  *
  * @access public
  * @return string
  */
  public static function getPriorityTitle($pid = null) {
    global $lC_Database, $lC_Language;

    $Qprioritytitle = $lC_Database->query("SELECT ticket_priority_name FROM :table_ticket_priority WHERE ticket_priority_id = :ticket_priority_id AND ticket_language_id = :ticket_language_id LIMIT 1");
    $Qprioritytitle->bindTable(':table_ticket_priority', DB_TABLE_PREFIX . 'ticket_priority');
    $Qprioritytitle->bindInt(':ticket_priority_id', $pid);
    $Qprioritytitle->bindInt(':ticket_language_id', $lC_Language->getID());
    $Qprioritytitle->execute();
    
    return $Qprioritytitle->value('ticket_priority_name');
  }
 /*
  * Returns the ticket priority color
  *
  * @access public
  * @return string
  */
  public static function getPriorityColor($pid = null) {
    global $lC_Database, $lC_Language;

    $Qprioritycolor = $lC_Database->query("SELECT ticket_priority_color FROM :table_ticket_priority WHERE ticket_priority_id = :ticket_priority_id AND ticket_language_id = :ticket_language_id LIMIT 1");
    $Qprioritycolor->bindTable(':table_ticket_priority', DB_TABLE_PREFIX . 'ticket_priority');
    $Qprioritycolor->bindInt(':ticket_priority_id', $pid);
    $Qprioritycolor->bindInt(':ticket_language_id', $lC_Language->getID());
    $Qprioritycolor->execute();
    
    return $Qprioritycolor->value('ticket_priority_color');
  }
 /*
  * Returns the ticket department title
  *
  * @access public
  * @return string
  */
  public static function getDepartmentTitle($did = null) {
    global $lC_Database, $lC_Language;

    $Qdepartmenttitle = $lC_Database->query("SELECT ticket_department_name FROM :table_ticket_department WHERE ticket_department_id = :ticket_department_id AND ticket_language_id = :ticket_language_id LIMIT 1");
    $Qdepartmenttitle->bindTable(':table_ticket_department', DB_TABLE_PREFIX . 'ticket_department');
    $Qdepartmenttitle->bindInt(':ticket_department_id', $did);
    $Qdepartmenttitle->bindInt(':ticket_language_id', $lC_Language->getID());
    $Qdepartmenttitle->execute();
    
    return $Qdepartmenttitle->value('ticket_department_name');
  }
 /*
  * Returns the ticket priority title
  *
  * @access public
  * @return string
  */
  public static function deleteStatusHistory($shid = null) {
    global $lC_Database;

    $QdelStatHist = $lC_Database->query("DELETE FROM :table_ticket_status_history WHERE ticket_status_history_id = :ticket_status_history_id LIMIT 1");
    $QdelStatHist->bindTable(':table_ticket_status_history', DB_TABLE_PREFIX . 'ticket_status_history');
    $QdelStatHist->bindInt(':ticket_status_history_id', $shid);
    $QdelStatHist->execute();
    
    if ($lC_Database->isError()) {
      return false;
    } else {
      return true;
    }
  } 
 /*
  * Returns the customer search results from database
  *
  * @param string $_GET['q'] The search string
  * @access public
  * @return array
  */
  public static function cSearch($search) {
    global $lC_Database, $lC_Language, $lC_Currencies;
    
    if ($search) {
      // start building the main <ul>
      $result['html'] = '' . "\n";
      
      // return customer data
      $Qcustomers = array();    
      $Qcustomers = $lC_Database->query("select customers_id, 
                                                customers_firstname, 
                                                customers_lastname, 
                                                customers_email_address 
                                           from :table_customers 
                                          where convert(customers_firstname using utf8) regexp '" . $search . "' 
                                             or convert(customers_lastname using utf8) regexp '" . $search . "' 
                                             or convert(customers_email_address using utf8) regexp '" . $search . "' 
                                             or convert(customers_telephone using utf8) regexp '" . $search . "';");
                                                                            
      $Qcustomers->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qcustomers->execute();
      
      // set the customers data results as an array
      while($Qcustomers->next()) {
        $QcustomerResults[] = $Qcustomers->toArray();
      }   
       
      // build the customers results <li> html for output
      // return <li> only if greater than 0 results from customers query  
      if ($QcustomerResults > 0) {
        $result['html'] .= '    <ul class="customers-menu">' . "\n";
        foreach ($QcustomerResults as $key => $value) { 
          $result['html'] .= '      <li class="bevel" title="' . $lC_Language->get('customer_view_details') . ' ' . $value['customers_firstname'] . ' '  . $value['customers_lastname'] . '">' . "\n" . 
                             '        <a href="#" onclick="setTicketCustomer(\'' . $value['customers_id'] . '\', \'' . $value['customers_firstname'] . ' '  . $value['customers_lastname'] . '\');">' . "\n" .
                             '          <span class="float-right">' . $value['customers_id'] . '</span>' . "\n" . 
                             '          <time><i class="icon-user icon-size2 icon-grey"></i></time>' . "\n" . 
                             '          <span class="green"><b>' . $value['customers_firstname'] . ' '  . $value['customers_lastname'] . '</b></span><small>'  . $value['customers_email_address'] . '</small>' . "\n" . 
                             '        </a>' . "\n" .
                             '      </li>';
        }
        $result['html'] .= '    </ul>' . "\n";
      } else {
        $result['html'] .= '    <ul class="customers-menu">' . "\n"; 
        $result['html'] .= '      <li class="bevel">' . "\n" . 
                           '        <span class="float-right">&nbsp;</span>' . "\n" . 
                           '        <time style="margin-left:-47px;"><i class="icon-forbidden icon-size2 icon-grey"></i></time>' . "\n" . 
                           '        <span class="green" style="margin-left:-20px;"><b>No Results Found</b></span><small style="margin-left:-20px;">Please try another search</small>' . "\n" . 
                           '      </li>';
        $result['html'] .= '    </ul>' . "\n";
      }
       
      return $result;
    } else {
      // we have nothing being sent from search field 
      $result['html'] = '' . "\n";
      
      return $result;
    }
  } 
}
?>