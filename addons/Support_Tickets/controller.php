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

    $lC_Database->simpleQuery("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Enable AddOn', 'ADDONS_CATALOG_SUPPORT_TICKETS_STATUS', '1', 'Do you want to enable this addon?', '6', '0', 'lc_cfg_use_get_boolean_value', 'lc_cfg_set_boolean_value(array(1, -1))', now())");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS " . DB_TABLE_PREFIX . "tickets (id int(11) NOT NULL AUTO_INCREMENT,
                                                                                          link_id varchar(32) DEFAULT NULL,
                                                                                          customers_id int(12) DEFAULT NULL,
                                                                                          customers_email varchar(96) DEFAULT NULL,
                                                                                          customers_name varchar(96) DEFAULT NULL,
                                                                                          orders_id int(11) DEFAULT NULL,
                                                                                          subject varchar(96) DEFAULT NULL,
                                                                                          status_id int(5) DEFAULT '1',
                                                                                          department_id int(5) DEFAULT '1',
                                                                                          priority_id int(5) DEFAULT '1',
                                                                                          date_added datetime DEFAULT '0000-00-00 00:00:00',
                                                                                          last_modified datetime DEFAULT '0000-00-00 00:00:00',
                                                                                          last_customer_modified datetime DEFAULT '0000-00-00 00:00:00',
                                                                                          login_required tinyint(4) DEFAULT '0',
                                                                                          PRIMARY KEY (id)
                                                                                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS " . DB_TABLE_PREFIX . "ticket_admins (ticket_admin_id int(11) NOT NULL default '1',
                                                                                                ticket_language_id int(11) NOT NULL default '1',
                                                                                                ticket_admin_name varchar(255) NOT NULL default '',
                                                                                                PRIMARY KEY (ticket_admin_id, ticket_language_id)
                                                                                              ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS " . DB_TABLE_PREFIX . "ticket_department (ticket_department_id int(5) NOT NULL default '1',
                                                                                                    ticket_language_id int(11) NOT NULL default '1',
                                                                                                    ticket_department_name varchar(60) NOT NULL default '',
                                                                                                    PRIMARY KEY (ticket_department_id, ticket_language_id)
                                                                                                  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS " . DB_TABLE_PREFIX . "ticket_priority (ticket_priority_id int(11) NOT NULL default '1',
                                                                                                  ticket_language_id int(11) NOT NULL default '1',
                                                                                                  ticket_priority_name varchar(60) NOT NULL default '',
                                                                                                  PRIMARY KEY (ticket_priority_id, ticket_language_id)
                                                                                                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS " . DB_TABLE_PREFIX . "ticket_reply (ticket_reply_id int(11) NOT NULL default '1',
                                                                                               ticket_language_id int(11) NOT NULL default '1',
                                                                                               ticket_reply_name varchar(255) NOT NULL default '',
                                                                                               ticket_reply_text text NOT NULL,
                                                                                               PRIMARY KEY (ticket_reply_id, ticket_language_id)
                                                                                             ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS " . DB_TABLE_PREFIX . "ticket_status (ticket_status_id int(5) NOT NULL default '1',
                                                                                                ticket_language_id int(11) NOT NULL default '1',
                                                                                                ticket_status_name varchar(60) NOT NULL default '',
                                                                                                PRIMARY KEY (ticket_status_id, ticket_language_id)
                                                                                              ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    $lC_Database->simpleQuery("CREATE TABLE IF NOT EXISTS " . DB_TABLE_PREFIX . "ticket_status_history (ticket_status_history_id int(11) NOT NULL AUTO_INCREMENT,
                                                                                                        ticket_id int(11) NOT NULL default '',
                                                                                                        ticket_status_id int(5) NOT NULL default '1',
                                                                                                        ticket_priority_id int(5) NOT NULL default '1',
                                                                                                        ticket_department_id int(5) NOT NULL default '1',
                                                                                                        ticket_date_modified datetime NOT NULL default '0000-00-00 00:00:00',
                                                                                                        ticket_customer_notified int(1) default '-1',
                                                                                                        ticket_comments text,
                                                                                                        ticket_edited_by varchar(64) NOT NULL default '',
                                                                                                        PRIMARY KEY (ticket_status_history_id)
                                                                                                      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
                                                                                                      
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_admins VALUES (0, 1, 'Customer Service Admin')");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_department VALUES (1, 1, 'Sales')");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_department VALUES (2, 1, 'Marketing')");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_department VALUES (3, 1, 'Support')");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_priority VALUES (1, 1, 'Low')");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_priority VALUES (2, 1, 'Medium')");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_priority VALUES (3, 1, 'High')");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_priority VALUES (4, 1, 'Urgent')");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_priority VALUES (5, 1, 'Emergency')");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_status VALUES (1, 1, 'Open')");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_status VALUES (2, 1, 'On Hold')");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_status VALUES (3, 1, 'Closed')");
    $lC_Database->simpleQuery("INSERT INTO " . DB_TABLE_PREFIX . "ticket_status VALUES (4, 1, 'Awaiting Reply')");
  }
 /**
  * Remove the addon
  *
  * @access public
  * @return void
  */
  public function remove() {
    global $lC_Database;

    $lC_Database->simpleQuery("DROP TABLE IF EXISTS " . DB_TABLE_PREFIX . "tickets;");
    $lC_Database->simpleQuery("DROP TABLE IF EXISTS " . DB_TABLE_PREFIX . "ticket_admins;");
    $lC_Database->simpleQuery("DROP TABLE IF EXISTS " . DB_TABLE_PREFIX . "ticket_department;");
    $lC_Database->simpleQuery("DROP TABLE IF EXISTS " . DB_TABLE_PREFIX . "ticket_priority;");
    $lC_Database->simpleQuery("DROP TABLE IF EXISTS " . DB_TABLE_PREFIX . "ticket_reply;");
    $lC_Database->simpleQuery("DROP TABLE IF EXISTS " . DB_TABLE_PREFIX . "ticket_status;");
    $lC_Database->simpleQuery("DROP TABLE IF EXISTS " . DB_TABLE_PREFIX . "ticket_status_history;");
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
}
?>