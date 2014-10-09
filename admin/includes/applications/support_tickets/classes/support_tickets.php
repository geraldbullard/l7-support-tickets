<?php
/**
  @package    catalog::admin::applications
  @author     ContributionCentral
  @copyright  Copyright 2014 ContributionCentral
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: support_tickets.js.php v1.0 2013-08-08 maestro $
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
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qtickets->valueInt('id') . '" id="' . $Qtickets->valueInt('id') . '"></td>';
      $ticket = '<td><span class="tag grey-bg">' . $Qtickets->value('id') . '</span> ' . $Qtickets->value('subject') . '</td>';
      $customer = '<td>' . $Qtickets->value('customers_name') . '</td>';
      $status = '<td><span class="tag ' . self::getStatusColor($Qtickets->value('status_id')) . '-bg no-wrap">' . ucfirst(self::getStatusTitle($Qtickets->value('status_id'))) . '</span></td>';
      $date = '<td>' . lC_DateTime::getShort($Qtickets->value('date_added')) . '</td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qtickets->valueInt('id') . '&action=save')) . '" class="button icon-pencil ' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? 'disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $Qtickets->valueInt('id') . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$check", "$ticket", "$customer", "$status", "$date", "$action");
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

    $Qticket = $lC_Database->query('select * from :table_tickets where id = :id');
    $Qticket->bindTable(':table_tickets', DB_TABLE_PREFIX . 'tickets');
    //$Qticket->bindTable(':table_ticket_status_history', DB_TABLE_PREFIX . '');
    $Qticket->bindInt(':id', $id);
    $Qticket->execute();
    
    $data = $Qticket->toArray();
    
    $Qticket->freeResult();
    
    return $data;
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
    if ($sid == 1) {
      $bg = 'green';
    } else if ($sid == 2) {
      $bg = 'orange';
    } else if ($sid == 3) {
      $bg = 'red';
    } else if ($sid == 4) {
      $bg = 'blue';
    } else {
      $bg = 'anthracite';
    }
    
    return $bg;
  } 
}
?>