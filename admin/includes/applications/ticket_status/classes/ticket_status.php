<?php
/**
  @package    catalog::admin::applications
  @author     ContributionCentral
  @copyright  Copyright 2014 ContributionCentral
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: ticket_status.php v1.0 2013-08-08 maestro $
*/
class lC_Ticket_status_Admin {
 /*
  * Returns the ticket statuses data
  *
  * @access public
  * @return array
  */
  public static function getAll() {
    global $lC_Database, $lC_Language, $_module;

    $lC_Language->loadIniFile('ticket_status.php');

    $Qdepartments = $lC_Database->query('select * from :table_ticket_status where ticket_language_id = :ticket_language_id order by ticket_status_id asc');
    $Qdepartments->bindTable(':table_ticket_status', DB_TABLE_PREFIX . 'ticket_status');
    $Qdepartments->bindInt(':ticket_language_id', $_SESSION['admin']['language_id']);
    $Qdepartments->execute();
    
    $result = array('aaData' => array());
    while ($Qdepartments->next()) {
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qdepartments->valueInt('ticket_status_id') . '" id="' . $Qdepartments->valueInt('ticket_status_id') . '"></td>';
      $status = '<td>' . $Qdepartments->value('ticket_status_name') . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qdepartments->valueInt('ticket_status_id') . '&action=save')) . '" class="button icon-pencil ' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? 'disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $Qdepartments->valueInt('ticket_status_id') . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$check", "$status", "$action");
    }

    return $result;
  }
 /*
  * Returns the ticket information
  *
  * @param integer $id The ticket department id
  * @access public
  * @return array
  */
  public static function get($id) {
    global $lC_Database, $lC_Language;

    /*$Qticket = $lC_Database->query('select t.*, tsh.* from :table_tickets t left join :table_ticket_status_history tsh on (t.ticket_id = tsh.ticket_id) where t.ticket_id = :ticket_id');
    $Qticket->bindTable(':table_tickets', DB_TABLE_PREFIX . 'tickets');
    $Qticket->bindTable(':table_ticket_status_history', DB_TABLE_PREFIX . 'ticket_status_history');
    $Qticket->bindInt(':ticket_id', $id);
    $Qticket->execute();
    
    while ($Qticket->next()) {
      $data[] = $Qticket->toArray();
    }
    
    $Qticket->freeResult();
    
    return $data;*/
  }
 /*
  * Save the ticket information
  *
  * @param integer $id The ticket department id used on update, null on insert
  * @param array $data An array containing the ticket department information
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data = null) { 
    global $lC_Database, $lC_Language, $lC_DateTime;

    $lC_Language->loadIniFile('ticket_status.php'); 

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
  * Returns the ticket status title
  *
  * @access public
  * @return string
  */
  public static function getStatusTitle($did = null) {
    global $lC_Database, $lC_Language;

    /*$Qstatustitle = $lC_Database->query("SELECT ticket_status_name FROM :table_ticket_status WHERE ticket_status_id = :ticket_status_id AND ticket_language_id = :ticket_language_id LIMIT 1");
    $Qstatustitle->bindTable(':table_ticket_status', DB_TABLE_PREFIX . 'ticket_status');
    $Qstatustitle->bindInt(':ticket_status_id', $sid);
    $Qstatustitle->bindInt(':ticket_language_id', $lC_Language->getID());
    $Qstatustitle->execute();
    
    return $Qstatustitle->value('ticket_department_name');*/
  }
}
?>