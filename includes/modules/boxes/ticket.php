<?php
/**
  @package    catalog::modules::boxes
  @author     ContributionCentral
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Copyright 2014 ContributionCentral
  @license    http://opensource.org/licenses/MIT
  @version    $Id: ticket.php v1.0 2014-02-01 maestro $
*/
class lC_Boxes_ticket extends lC_Modules {
  var $_title,
      $_code = 'ticket',
      $_author_name = 'ContributionCentral',
      $_author_www = 'http://www.contributioncentral.com',
      $_group = 'boxes';

  public function lC_Boxes_ticket() {
    global $lC_Language;

    if (function_exists($lC_Language->injectDefinitions))$lC_Language->injectDefinitions('modules/' . $_GET['set'] . '/' . $this->_code . '.xml');
    
    $this->_title = $lC_Language->get('box_ticket_heading');
  }

  public function initialize() {
    global $lC_Language, $lC_Template;
    
    $this->_content = '<li class="box-ticket-text">' . lc_link_object(lc_href_link('ticket.php', null, 'SSL'), $lC_Language->get('my_tickets')) . '</li>' . "\n" .
                      '<li class="box-ticket-text">' . lc_link_object(lc_href_link('ticket.php', 'create', 'SSL'), $lC_Language->get('create_ticket')) . '</li>' . "\n";
  }
}
?>