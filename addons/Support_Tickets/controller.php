<?php
/**
  @package    catalog::admin::applications
  @author     ContributionCentral
  @copyright  Copyright 2014 ContributionCentral
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: controller.php v1.0 2013-08-08 maestro $
*/

class Support_Tickets extends lC_Addon { // your addon must extend lC_Addon
  /*
  * Class constructor
  */
  public function Support_Tickets() {    
    global $lC_Language;    
   /**
    * The addon type (store listing category)
    * valid types; payment, shipping, themes, checkout, catalog, admin, reports, connectors, inventory, other 
    */    
    $this->_type = 'other';
   /**
    * The addon class name
    */    
    $this->_code = 'Support_Tickets';       
   /**
    * The addon title used in the addons store listing
    */     
    $this->_title = $lC_Language->get('addon_catalog_supporttickets_title');
   /**
    * The addon description used in the addons store listing
    */     
    $this->_description = $lC_Language->get('addon_catalog_supporttickets_description');
   /**
    * The addon blurb used in the addons store listing
    */     
    $this->_blurb = $lC_Language->get('addon_catalog_supporttickets_blurb');    
   /**
    * The developers name
    */    
    $this->_author = 'ContributionCentral';
   /**
    * The developers web address
    */    
    $this->_authorWWW = 'http://www.contributioncentral.com';    
   /**
    * The addon version
    */     
    $this->_version = '1.0.0';
   /**
    * The Loaded 7 core compatibility version
    */     
    $this->_compatibility = '7.001.0.0'; // the addon is compatible with this core version and later 
   /**
    * The addon image used in the addons store listing
    */     
    $this->_thumbnail = lc_image(DIR_WS_CATALOG . 'addons/' . $this->_code . '/images/logo.png');
   /**
    * The addon enable/disable switch
    */    
    $this->_enabled = (defined('ADDONS_CATALOG_SUPPORT_TICKETS_STATUS') && @constant('ADDONS_CATALOG_SUPPORT_TICKETS_STATUS') == '1') ? true : false;
  }
 /**
  * Checks to see if the addon has been installed
  *
  * @access public
  * @return boolean
  */
  public function isInstalled() {
    return (bool)defined('ADDONS_CATALOG_SUPPORT_TICKETS_STATUS');
  }
 /**
  * Install the addon
  *
  * @access public
  * @return void
  */
  public function install() {
    global $lC_Database;

    // Set Configuration Settings
    $lC_Database->simpleQuery("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Enable AddOn', 'ADDONS_CATALOG_SUPPORT_TICKETS_STATUS', '1', 'Do you wish to enable the Support Tickets addon?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    
    // Create Tables
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS " . DB_TABLE_PREFIX . "tickets (ticket_id INT(11) NOT NULL AUTO_INCREMENT,
                                                                                          link_id VARCHAR(32) DEFAULT NULL,
                                                                                          customers_id INT(12) DEFAULT NULL,
                                                                                          customers_email VARCHAR(96) DEFAULT NULL,
                                                                                          customers_name VARCHAR(96) DEFAULT NULL,
                                                                                          orders_id INT(11) DEFAULT NULL,
                                                                                          subject VARCHAR(96) DEFAULT NULL,
                                                                                          status_id INT(5) DEFAULT '1',
                                                                                          department_id INT(5) DEFAULT '1',
                                                                                          priority_id INT(5) DEFAULT '1',
                                                                                          date_added DATETIME DEFAULT '0000-00-00 00:00:00',
                                                                                          last_modified datetime DEFAULT '0000-00-00 00:00:00',
                                                                                          last_customer_modified DATETIME DEFAULT '0000-00-00 00:00:00',
                                                                                          login_required TINYINT(4) DEFAULT '0',
                                                                                          PRIMARY KEY (id)
                                                                                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS " . DB_TABLE_PREFIX . "ticket_admins (ticket_admin_id INT(11) NOT NULL DEFAULT '1',
                                                                                                ticket_language_id INT(11) NOT NULL DEFAULT '1',
                                                                                                ticket_admin_name VARCHAR(255) NOT NULL DEFAULT '',
                                                                                                PRIMARY KEY (ticket_admin_id, ticket_language_id)
                                                                                              ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS " . DB_TABLE_PREFIX . "ticket_department (ticket_department_id INT(5) NOT NULL DEFAULT '1',
                                                                                                    ticket_language_id INT(11) NOT NULL DEFAULT '1',
                                                                                                    ticket_department_name VARCHAR(60) NOT NULL DEFAULT '',
                                                                                                    PRIMARY KEY (ticket_department_id, ticket_language_id)
                                                                                                  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS " . DB_TABLE_PREFIX . "ticket_priority (ticket_priority_id INT(11) NOT NULL DEFAULT '1',
                                                                                                  ticket_language_id INT(11) NOT NULL DEFAULT '1',
                                                                                                  ticket_priority_name VARCHAR(60) NOT NULL DEFAULT '',
                                                                                                  PRIMARY KEY (ticket_priority_id, ticket_language_id)
                                                                                                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS " . DB_TABLE_PREFIX . "ticket_response (ticket_response_id INT(11) NOT NULL DEFAULT '1',
                                                                                                  ticket_language_id INT(11) NOT NULL DEFAULT '1',
                                                                                                  ticket_response_name VARCHAR(255) NOT NULL DEFAULT '',
                                                                                                  ticket_response_text TEXT NOT NULL,
                                                                                                  PRIMARY KEY (ticket_response_id, ticket_language_id)
                                                                                             ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS " . DB_TABLE_PREFIX . "ticket_status (ticket_status_id INT(5) NOT NULL DEFAULT '1',
                                                                                                ticket_language_id INT(11) NOT NULL DEFAULT '1',
                                                                                                ticket_status_name VARCHAR(60) NOT NULL DEFAULT '',
                                                                                                ticket_status_color VARCHAR(60) NOT NULL DEFAULT '',
                                                                                                PRIMARY KEY (ticket_status_id, ticket_language_id)
                                                                                              ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS " . DB_TABLE_PREFIX . "ticket_status_history (ticket_status_history_id INT(11) NOT NULL AUTO_INCREMENT,
                                                                                                        ticket_id INT(11) DEFAULT NULL,
                                                                                                        ticket_status_id INT(5) NOT NULL DEFAULT '1',
                                                                                                        ticket_priority_id INT(5) NOT NULL DEFAULT '1',
                                                                                                        ticket_department_id INT(5) NOT NULL DEFAULT '1',
                                                                                                        ticket_date_modified DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                                                                                                        ticket_customer_notified INT(1) DEFAULT '-1',
                                                                                                        ticket_comments text,
                                                                                                        ticket_edited_by VARCHAR(64) NOT NULL DEFAULT '',
                                                                                                        PRIMARY KEY (ticket_status_history_id)
                                                                                                      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
                                                                                                      
    // Enter Default Values
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_admins VALUES (0, 1, 'Customer Service Admin');");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_department VALUES (1, 1, 'Sales');");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_department VALUES (2, 1, 'Marketing');");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_department VALUES (3, 1, 'Support');");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_priority VALUES (1, 1, 'Low');");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_priority VALUES (2, 1, 'Medium');");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_priority VALUES (3, 1, 'High');");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_priority VALUES (4, 1, 'Urgent');");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_priority VALUES (5, 1, 'Emergency');");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_status VALUES (1, 1, 'Open', 'green');");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_status VALUES (2, 1, 'On Hold', 'red');");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_status VALUES (3, 1, 'Closed', 'anthracite');");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_status VALUES (4, 1, 'Awaiting Admin Reply', 'orange');");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_status VALUES (5, 1, 'Awaiting Customer Reply', 'blue');");
    
    // Enter Configuration Group and Values
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES (429, 'Support Tickets', 'Settings for the Support Ticket System.', 429, 0);");
  }
 /**
  * Remove the addon
  *
  * @access public
  * @return void
  */
  public function remove() {
    global $lC_Database;
    
    if ($this->hasKeys()) {
      $Qdel = $lC_Database->query('delete from :table_configuration where configuration_key in (":configuration_key")');
      $Qdel->bindTable(':table_configuration', TABLE_CONFIGURATION);
      $Qdel->bindRaw(':configuration_key', implode('", "', $this->getKeys()));
      $Qdel->execute();
    }

    $lC_Database->simpleQuery("DROP TABLE IF EXISTS " . DB_TABLE_PREFIX . "tickets;");
    $lC_Database->simpleQuery("DROP TABLE IF EXISTS " . DB_TABLE_PREFIX . "ticket_admins;");
    $lC_Database->simpleQuery("DROP TABLE IF EXISTS " . DB_TABLE_PREFIX . "ticket_department;");
    $lC_Database->simpleQuery("DROP TABLE IF EXISTS " . DB_TABLE_PREFIX . "ticket_priority;");
    $lC_Database->simpleQuery("DROP TABLE IF EXISTS " . DB_TABLE_PREFIX . "ticket_response;");
    $lC_Database->simpleQuery("DROP TABLE IF EXISTS " . DB_TABLE_PREFIX . "ticket_status;");
    $lC_Database->simpleQuery("DROP TABLE IF EXISTS " . DB_TABLE_PREFIX . "ticket_status_history;");
    
    // Remove catalog side files
    unlink(DIR_FS_CATALOG . 'ticket.php');
    unlink(DIR_FS_CATALOG . 'templates/core/modules/boxes/ticket.php');
    unlink(DIR_FS_CATALOG . 'includes/languages/en_US/modules/boxes/ticket.xml');
    unlink(DIR_FS_CATALOG . 'includes/modules/boxes/ticket.php');
    
    // Remove catalog side directories
    self::recursiveRemove(DIR_FS_CATALOG . 'includes/content/ticket/');
    self::recursiveRemove(DIR_FS_CATALOG . 'templates/core/content/ticket/');
    
    // Remove admin side files 
    unlink(DIR_FS_ADMIN . 'includes/languages/en_US/support_tickets.php');
    unlink(DIR_FS_ADMIN . 'includes/languages/en_US/ticket_department.php');
    unlink(DIR_FS_ADMIN . 'includes/languages/en_US/ticket_priority.php');
    unlink(DIR_FS_ADMIN . 'includes/languages/en_US/ticket_response.php');
    unlink(DIR_FS_ADMIN . 'includes/languages/en_US/ticket_status.php');
    unlink(DIR_FS_ADMIN . 'includes/languages/en_US/modules/access/support_tickets.php');
    unlink(DIR_FS_ADMIN . 'includes/languages/en_US/modules/access/ticket_department.php');
    unlink(DIR_FS_ADMIN . 'includes/languages/en_US/modules/access/ticket_priority.php');
    unlink(DIR_FS_ADMIN . 'includes/languages/en_US/modules/access/ticket_response.php');
    unlink(DIR_FS_ADMIN . 'includes/languages/en_US/modules/access/ticket_status.php');
    unlink(DIR_FS_ADMIN . 'includes/modules/access/support_tickets.php');
    unlink(DIR_FS_ADMIN . 'includes/modules/access/ticket_department.php');
    unlink(DIR_FS_ADMIN . 'includes/modules/access/ticket_priority.php');
    unlink(DIR_FS_ADMIN . 'includes/modules/access/ticket_response.php');
    unlink(DIR_FS_ADMIN . 'includes/modules/access/ticket_status.php');
    unlink(DIR_FS_ADMIN . 'templates/default/css/support_tickets.css');
    
    // Remove admin side directories
    /*self::recursiveRemove(DIR_FS_ADMIN . 'includes/applications/support_tickets/');
    self::recursiveRemove(DIR_FS_ADMIN . 'includes/applications/ticket_department/');
    self::recursiveRemove(DIR_FS_ADMIN . 'includes/applications/ticket_priority/');
    self::recursiveRemove(DIR_FS_ADMIN . 'includes/applications/ticket_response/');
    self::recursiveRemove(DIR_FS_ADMIN . 'includes/applications/ticket_status/');*/
  }
 /**
  * Return the configuration parameter keys array
  *
  * @access public
  * @return array
  */
  public function getKeys() {
    if (!isset($this->_keys)) {
      $this->_keys = array('ADDONS_CATALOG_SUPPORT_TICKETS_STATUS');
    }

    return $this->_keys;
  }
 /**
  * Recursively remove a directory and all of it's subfolders and files
  *
  * @access public 
  */
  public function recursiveRemove($dir) {
    $dh = opendir($dir);
    if ($dh) {
      while($file = readdir($dh)) {
        if (!in_array($file, array('.', '..'))) {
          if (is_file($dir . $file)) {
            unlink($dir . $file);
          }
          else if (is_dir($dir . $file)) {
            recursiveRemove($dir . $file);
          }
        }
      }
      rmdir($dir);
    }
  }
}
?>