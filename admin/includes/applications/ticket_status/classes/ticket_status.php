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
      $status = '<td><span class="tag ' . $Qdepartments->value('ticket_status_color') . '-bg no-wrap with-small-padding">' . $Qdepartments->value('ticket_status_name') . '</span></td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qdepartments->valueInt('ticket_status_id') . '&action=save')) . '" class="button icon-pencil ' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? 'disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $Qdepartments->valueInt('ticket_status_id') . '\', \'' . $Qdepartments->value('ticket_status_name') . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$check", "$status", "$action");
    }

    return $result;
  }
 /*
  * Delete the ticket status
  *
  * @param integer $id The ticket status id to delete
  * @access public
  * @return array
  */
  public static function delete($id) {
    global $lC_Database;

    $Qdelete = $lC_Database->query('delete from :table_ticket_status where ticket_status_id = :ticket_status_id');
    $Qdelete->bindTable(':table_ticket_status', DB_TABLE_PREFIX . 'ticket_status');
    $Qdelete->bindInt(':ticket_status_id', $id);
    $Qdelete->setLogging($_SESSION['module'], $id);
    $Qdelete->execute();

    if (!$lC_Database->isError()) {
      return true;
    }
  }
 /*
  * Batch delete ticket status records
  *
  * @param array $batch The ticket status id's to delete
  * @access public
  * @return boolean
  */
  public static function batchDelete($batch) {
    foreach ($batch as $id) {
      lC_Ticket_status_Admin::delete($id);
    }
    
    return true;
  }
}
?>