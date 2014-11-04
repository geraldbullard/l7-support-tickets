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

      $Qlatestcomments = $lC_Database->query('select * from :table_ticket_status_history order by ticket_status_history_id desc limit 1');
      $Qlatestcomments->bindTable(':table_ticket_status_history', DB_TABLE_PREFIX . 'ticket_status_history');
      $Qlatestcomments->execute();
      
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qtickets->valueInt('ticket_id') . '" id="' . $Qtickets->valueInt('ticket_id') . '"></td>';
      $ticket = '<td>
                   <span class="tag grey-bg">' . $Qtickets->valueInt('ticket_id') . '</span> ' . $Qtickets->value('subject') . '
                   <p class="small mid-margin-top">' . 
                     '<span class="strong">' . $lC_Language->get('text_ticket_latest_comments') . ': <br />' . 
                     '<span class="anthracite">' . $Qlatestcomments->value('ticket_edited_by') . '<br />' .
                     substr(lC_DateTime::getLong($Qlatestcomments->value('ticket_date_modified'), true), 0, -5) . ' ' . str_replace(' ', '', date("g:i a", strtotime(substr(lC_DateTime::getLong($Qlatestcomments->value('ticket_date_modified'), true), -5)))) . '</span></span>' .
                     '<br class="mid-margin-bottom" /> ' . 
                     $Qlatestcomments->value('ticket_comments') . 
                   '</p>
                 </td>';
      $customer = '<td><a class="strong" href="' . lc_href_link_admin(FILENAME_DEFAULT, 'customers&cID=' . $Qtickets->valueInt('customers_id')) . '" title="' . $lC_Language->get('text_view_customer_listing') . '" target="_blank"><span class="icon-user small-margin-right"></span> ' . $Qtickets->value('customers_name') . '</a></td>';
      $status = '<td><span class="tag ' . self::getStatusColor($Qlatestcomments->valueInt('ticket_status_id')) . '-bg no-wrap with-small-padding">' . ucfirst(self::getStatusTitle($Qlatestcomments->valueInt('ticket_status_id'))) . '</span></td>';
      $priority = '<td>' . self::getPriorityTitle($Qlatestcomments->valueInt('ticket_priority_id')) . '</td>';
      $date = '<td>' . substr(lC_DateTime::getLong($Qtickets->value('date_added'), true), 0, -5) . ' ' . str_replace(' ', '', date("g:i a", strtotime(substr(lC_DateTime::getLong($Qtickets->value('date_added'), true), -5)))) . '</td>';
      $modified = '<td>' . substr(lC_DateTime::getLong($Qlatestcomments->value('ticket_date_modified'), true), 0, -5) . ' ' . str_replace(' ', '', date("g:i a", strtotime(substr(lC_DateTime::getLong($Qlatestcomments->value('ticket_date_modified'), true), -5)))) . '</td>';
      $action = '<td class="align-right vertical-center">
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qtickets->valueInt('ticket_id') . '&action=save')) . '" class="button icon-pencil ' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? 'disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   </span>
                   <span class="button-group">
                     <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $Qtickets->valueInt('ticket_id') . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                   </span>
                 </span></td>';

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

    $Qticket = $lC_Database->query('select t.*, tsh.* from :table_tickets t left join :table_ticket_status_history tsh on (t.ticket_id = tsh.ticket_id) where t.ticket_id = :ticket_id');
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
    $result = array();

    if (is_numeric($id)) {
    } else {
    }

    if ($error === false) {
      /*$lC_Database->startTransaction();

      if (is_numeric($id)) {
        $Qcustomer = $lC_Database->query('update :table_customers set customers_group_id = :customers_group_id, customers_gender = :customers_gender, customers_firstname = :customers_firstname, customers_lastname = :customers_lastname, customers_email_address = :customers_email_address, customers_dob = :customers_dob, customers_newsletter = :customers_newsletter, customers_status = :customers_status, date_account_last_modified = :date_account_last_modified where customers_id = :customers_id');
        $Qcustomer->bindRaw(':date_account_last_modified', 'now()');
        $Qcustomer->bindInt(':customers_id', $id);
      } else {
        $Qcustomer = $lC_Database->query('insert into :table_customers (customers_group_id, customers_gender, customers_firstname, customers_lastname, customers_email_address, customers_dob, customers_newsletter, customers_status, number_of_logons, date_account_created) values (:customers_group_id, :customers_gender, :customers_firstname, :customers_lastname, :customers_email_address, :customers_dob, :customers_newsletter, :customers_status, :number_of_logons, :date_account_created)');
        $Qcustomer->bindInt(':number_of_logons', 0);
        $Qcustomer->bindRaw(':date_account_created', 'now()');
      }

      $dob = (isset($data['dob']) && !empty($data['dob'])) ? lC_DateTime::toDateTime($data['dob']) : '0000-00-00 00:00:00';

      $Qcustomer->bindTable(':table_customers', TABLE_CUSTOMERS);
      $Qcustomer->bindValue(':customers_gender', $data['gender']);
      $Qcustomer->bindValue(':customers_firstname', $data['firstname']);
      $Qcustomer->bindValue(':customers_lastname', $data['lastname']);
      $Qcustomer->bindValue(':customers_email_address', $data['email_address']);
      $Qcustomer->bindValue(':customers_dob', $dob);
      $Qcustomer->bindInt(':customers_newsletter', $data['newsletter']);
      $Qcustomer->bindInt(':customers_status', $data['status']);
      $Qcustomer->bindInt(':customers_group_id', $data['group']);
      $Qcustomer->setLogging($_SESSION['module'], $id);
      $Qcustomer->execute();      

      if (!$lC_Database->isError()) {
        if ( !empty($data['password']) ) {
          $customer_id = ( !empty($id) ) ? $id : $lC_Database->nextID();
          $result['new_customer_id'] = $customer_id;

          $Qpassword = $lC_Database->query('update :table_customers set customers_password = :customers_password where customers_id = :customers_id');
          $Qpassword->bindTable(':table_customers', TABLE_CUSTOMERS);
          $Qpassword->bindValue(':customers_password', lc_encrypt_string(trim($data['password'])));
          $Qpassword->bindInt(':customers_id', $customer_id);
          $Qpassword->setLogging($_SESSION['module'], $customer_id);
          $Qpassword->execute();

          if ( $lC_Database->isError() ) {
            $error = true;
            $result['rpcStatus'] = -1;
          } 
        }
      }*/
    }
    
    if ( $error === false ) {
      /*$lC_Database->commitTransaction();

      if ( $send_email === true ) {
        if ( empty($id) ) {
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
        }
      }*/
      
      $result = 1;

      return $result;
    }

    $lC_Database->rollbackTransaction();

    return $result;
  }
 /*
  * Return the ticket status dropdown
  *
  * @access public
  * @return array
  */ 
  public static function drawTicketStatusDropdown($tid, $classes = null) {
    $data = self::get($tid);
    
    $tsDropdown = '<select class="select withClearFunctions' . ((!empty($classes)) ? ' ' . $classes : null) . '" style="min-width:150px" id="ticket_statuses" name="ticket_statuses">';
    $tsDropdown .= '<option value="-1">Select Status</option>';
    $tsDropdown .= '<option value="1">Select Status</option>';
    $tsDropdown .= '<option value="2">Select Status</option>';
    $tsDropdown .= '<option value="3">Select Status</option>';
    /*foreach ($data['ticketStatusArray'] as $id => $val) {
      $tsDropdown .= '<option value="' . $id . '"';
      if ($data['ticketStatusID'] == $id) {
        $tsDropdown .= ' selected="selected"';
      }
      $tsDropdown .= '>' . $val . '</option>';
    }*/
    $tsDropdown .= '</select>';
    
    return $tsDropdown;
  }
 /*
  * Return the ticket priority dropdown
  *
  * @access public
  * @return array
  */ 
  public static function drawTicketPriorityDropdown($tid, $classes = null) {
    $data = self::get($tid);
    
    $tpDropdown = '<select class="select withClearFunctions' . ((!empty($classes)) ? ' ' . $classes : null) . '" style="min-width:150px" id="ticket_statuses" name="ticket_statuses">';
    $tpDropdown .= '<option value="-1">Select Priority</option>';
    $tpDropdown .= '<option value="1">Select Priority</option>';
    $tpDropdown .= '<option value="2">Select Priority</option>';
    $tpDropdown .= '<option value="3">Select Priority</option>';
    /*foreach ($data['ticketStatusArray'] as $id => $val) {
      $tpDropdown .= '<option value="' . $id . '"';
      if ($data['ticketStatusID'] == $id) {
        $tpDropdown .= ' selected="selected"';
      }
      $tpDropdown .= '>' . $val . '</option>';
    }*/
    $tpDropdown .= '</select>';
    
    return $tpDropdown;
  }
 /*
  * Return the ticket department dropdown
  *
  * @access public
  * @return array
  */ 
  public static function drawTicketDepartmentDropdown($tid, $classes = null) {
    $data = self::get($tid);
    
    $tdDropdown = '<select class="select withClearFunctions' . ((!empty($classes)) ? ' ' . $classes : null) . '" style="min-width:150px" id="ticket_statuses" name="ticket_statuses">';
    $tdDropdown .= '<option value="-1">Select Department</option>';
    $tdDropdown .= '<option value="1">Select Department</option>';
    $tdDropdown .= '<option value="2">Select Department</option>';
    $tdDropdown .= '<option value="3">Select Department</option>';
    /*foreach ($data['ticketStatusArray'] as $id => $val) {
      $tdDropdown .= '<option value="' . $id . '"';
      if ($data['ticketStatusID'] == $id) {
        $tdDropdown .= ' selected="selected"';
      }
      $tdDropdown .= '>' . $val . '</option>';
    }*/
    $tdDropdown .= '</select>';
    
    return $tdDropdown;
  }
 /*
  * Return the ticket response dropdown
  *
  * @access public
  * @return array
  */ 
  public static function drawTicketResponseDropdown($tid, $classes = null) {
    $data = self::get($tid);
    
    $trDropdown = '<select class="select withClearFunctions' . ((!empty($classes)) ? ' ' . $classes : null) . '" style="min-width:150px" id="ticket_statuses" name="ticket_statuses">';
    $trDropdown .= '<option value="-1">Select Response</option>';
    $trDropdown .= '<option value="1">Select Response</option>';
    $trDropdown .= '<option value="2">Select Response</option>';
    $trDropdown .= '<option value="3">Select Response</option>';
    /*foreach ($data['ticketStatusArray'] as $id => $val) {
      $trDropdown .= '<option value="' . $id . '"';
      if ($data['ticketStatusID'] == $id) {
        $trDropdown .= ' selected="selected"';
      }
      $trDropdown .= '>' . $val . '</option>';
    }*/
    $trDropdown .= '</select>';
    
    return $trDropdown;
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
}
?>