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

    $Qstatus = $lC_Database->query('select * from :table_ticket_status where ticket_language_id = :ticket_language_id order by ticket_status_id asc');
    $Qstatus->bindTable(':table_ticket_status', DB_TABLE_PREFIX . 'ticket_status');
    $Qstatus->bindInt(':ticket_language_id', $lC_Language->getID());
    $Qstatus->execute();
    
    $result = array('aaData' => array());
    while ($Qstatus->next()) {
      $check = '<td><input class="batch" type="checkbox" name="batch[]" value="' . $Qstatus->valueInt('ticket_status_id') . '" id="' . $Qstatus->valueInt('ticket_status_id') . '"></td>';
      $status = '<td><span class="tag ' . $Qstatus->value('ticket_status_color') . '-bg no-wrap with-small-padding">' . $Qstatus->value('ticket_status_name') . '</span></td>';
      $action = '<td class="align-right vertical-center"><span class="button-group compact">
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? '#' : lc_href_link_admin(FILENAME_DEFAULT, $_module . '=' . $Qstatus->valueInt('ticket_status_id') . '&action=save')) . '" class="button icon-pencil ' . ((int)($_SESSION['admin']['access'][$_module] < 3) ? 'disabled' : NULL) . '">' . (($media === 'mobile-portrait' || $media === 'mobile-landscape') ? NULL : $lC_Language->get('icon_edit')) . '</a>
                   <a href="' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? '#' : 'javascript://" onclick="deleteEntry(\'' . $Qstatus->valueInt('ticket_status_id') . '\', \'' . $Qstatus->value('ticket_status_name') . '\')') . '" class="button icon-trash with-tooltip' . ((int)($_SESSION['admin']['access'][$_module] < 4) ? 'disabled' : NULL) . '" title="' . $lC_Language->get('icon_delete') . '"></a>
                 </span></td>';

      $result['aaData'][] = array("$check", "$status", "$action");
    }

    return $result;
  }
 /*
  * Return the data used on the dialog forms
  *
  * @param integer $id The ticket status id
  * @access public
  * @return array
  */
  public static function getFormData($id = null) {
    global $lC_Database, $lC_Language;

    $result = array();
    foreach ($lC_Language->getAll() as $l) {
      $result['stName'] .= '<span class="input" style="width:88%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('ticket_status_name[' . $l['id'] . ']', null, 'class="input-unstyled"') . '</span><br />';
    }

    /*if ($id != null && is_numeric($id)) {
      $manufacturers_array = array();
      $Qmanufacturer = $lC_Database->query('select manufacturers_url, languages_id from :table_manufacturers_info where manufacturers_id = :manufacturers_id');
      $Qmanufacturer->bindTable(':table_manufacturers_info', TABLE_MANUFACTURERS_INFO);
      $Qmanufacturer->bindInt(':manufacturers_id', $id);
      $Qmanufacturer->execute();

      while ($Qmanufacturer->next()) {
        $manufacturers_array[$Qmanufacturer->valueInt('languages_id')] = $Qmanufacturer->value('manufacturers_url');
      }
      
      foreach ($lC_Language->getAll() as $l) {
        $result['editStName'] .= '<span class="input" style="width:88%"><label for="name[' . $l['id'] . ']" class="button silver-gradient glossy">' . $lC_Language->showImage($l['code']) . '</label>' . lc_draw_input_field('manufacturers_url[' . $l['id'] . ']', $manufacturers_array[$l['id']], 'class="input-unstyled"') . '</span><br />';
      }
      
      $result['tsData'] = lC_Manufacturers_Admin::getData($id, $lC_Language->getID());
    }*/

    return $result;
  }
 /*
  * Save the ticket status information
  *
  * @param integer $id The ticket status id used on update, null on insert
  * @param array $data An array containing the ticket status information
  * @param boolean $default True = set the ticket status to be the default
  * @access public
  * @return boolean
  */
  public static function save($id = null, $data, $default = false) {
    global $lC_Database, $lC_Language;

    $error = false;

    $lC_Database->startTransaction();

    if (isset($id) && $id != null) {
      $ticket_status_id = $id;
      // ISSUE: if we add a new language, editing values does not save the new language.
      // To cure this, we delete the old records first, then re-insert instead of update.
      lC_Ticket_status_Admin::delete($ticket_status_id);
    } else {
      $Qstatus = $lC_Database->query('select max(ticket_status_id) as ticket_status_id from :table_ticket_status');
      $Qstatus->bindTable(':table_ticket_status', DB_TABLE_PREFIX . 'ticket_status');
      $Qstatus->execute();

      $ticket_status_id = $Qstatus->valueInt('ticket_status_id') + 1;
    }
    
    foreach ($lC_Language->getAll() as $l) {
      $Qstatus = $lC_Database->query('insert into :table_ticket_status (ticket_status_id, ticket_language_id, ticket_status_name, ticket_status_color) values (:ticket_status_id, :ticket_language_id, :ticket_status_name, :ticket_status_color)');
      $Qstatus->bindTable(':table_ticket_status', DB_TABLE_PREFIX . 'ticket_status');
      $Qstatus->bindInt(':ticket_status_id', $ticket_status_id);
      $Qstatus->bindValue(':ticket_status_name', $data['ticket_status_name'][$l['id']]);
      $Qstatus->bindValue(':ticket_status_color', 'grey');
      $Qstatus->bindInt(':ticket_language_id', $l['id']);
      $Qstatus->setLogging($_SESSION['module'], $ticket_status_id);
      $Qstatus->execute();

      if ( $lC_Database->isError() ) {
        $error = true;
        break;
      }
    }

    if ( $error === false ) {
      if ($default === true) {
        /*$Qupdate = $lC_Database->query('update :table_configuration set configuration_value = :configuration_value where configuration_key = :configuration_key');
        $Qupdate->bindTable(':table_configuration', TABLE_CONFIGURATION);
        $Qupdate->bindInt(':configuration_value', $ticket_status_id);
        $Qupdate->bindValue(':configuration_key', 'DEFAULT_ORDERS_STATUS_ID');
        $Qupdate->setLogging($_SESSION['module'], $ticket_status_id);
        $Qupdate->execute();

        if ( $lC_Database->isError() ) {
          $error = true;
        }*/
      }
    }
    
    if ($error === false) {
      $lC_Database->commitTransaction();

      if ($default === true) {
        lC_Cache::clear('configuration');
      }

      return true;
    }

    $lC_Database->rollbackTransaction();

    return false;
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