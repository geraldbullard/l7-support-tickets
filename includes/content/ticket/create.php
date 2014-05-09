<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: create.php v1.0 2013-08-08 datazen $
*/
class lC_Ticket_Create extends lC_Template {

  /* Private variables */
  var $_module = 'create',
      $_group = 'ticket',
      $_page_title,
      $_page_contents = 'create.php',
      $_page_image = '';

  /* Class constructor */
  public function lC_Ticket_Create() {
    global $lC_Language, $lC_Services, $lC_Breadcrumb;

    $this->_page_title = $lC_Language->get('create_ticket_heading');

    if ($_GET[$this->_module] == 'success') {
      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_ticket_created'));
      }

      $this->_page_title = $lC_Language->get('create_ticket_success_heading');
      $this->_page_contents = 'create_success.php';
    } else {
      if ($lC_Services->isStarted('breadcrumb')) {
        $lC_Breadcrumb->add($lC_Language->get('breadcrumb_create_ticket'), lc_href_link('ticket.php', $this->_module, 'SSL'));
      }
      
      //$this->addJavascriptPhpFilename('templates/' . $this->getCode() . '/javascript/form_check.js.php');
    }

    if ($_GET[$this->_module] == 'save') {
      $this->_process();
    }
  }

  /* Private methods */
  protected function _process() {
    global $lC_MessageStack, $lC_Database, $lC_Language, $lC_Customer, $lC_Vqmod;

    require_once($lC_Vqmod->modCheck('includes/classes/ticket.php'));
    
    $data = array();
    
    /*if (DISPLAY_PRIVACY_CONDITIONS == '1') {
      if (isset($_POST['privacy_conditions']) && ($_POST['privacy_conditions'] == '1' || $_POST['privacy_conditions'] == 'on'))  {
      } else {
        $lC_MessageStack->add($this->_module, $lC_Language->get('error_privacy_statement_not_accepted'));
      }
    }

    if (isset($_POST['firstname'])) {
      $data['firstname'] = $_POST['firstname'];
    } else {
      $lC_MessageStack->add($this->_module, sprintf($lC_Language->get('field_customer_first_name_error'), ACCOUNT_FIRST_NAME));
    }

    if (isset($_POST['lastname'])) {
      $data['lastname'] = $_POST['lastname'];
    } else {
      $lC_MessageStack->add($this->_module, sprintf($lC_Language->get('field_customer_last_name_error'), ACCOUNT_LAST_NAME));
    }*/

    if ($lC_MessageStack->size($this->_module) === 0) {
      if (lC_Ticket::createEntry($data)) {
        $lC_MessageStack->add('create', $lC_Language->get('success_ticket_updated'), 'success');
      }
      
      lc_redirect(lc_href_link('ticket.php', 'create=success', 'SSL'));
    }
  }
}
?>