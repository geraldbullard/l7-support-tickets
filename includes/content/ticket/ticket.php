<?php
/**
  @package    catalog::content
  @author     ContributionCentral
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Copyright 2014 ContributionCentral
  @license    http://opensource.org/licenses/MIT
  @version    $Id: view.php v1.0 2014-02-01 maestro $
*/
class lC_Ticket_Ticket extends lC_Template {

  /* Private variables */
  var $_module = 'ticket',
      $_group = 'ticket',
      $_page_title,
      $_page_contents = 'ticket.php',
      $_page_image = '';

  public function lC_Ticket_Ticket() {
    global $lC_Language, $lC_Vqmod;

    //require_once($lC_Vqmod->modCheck('includes/classes/customer.php'));
    //require_once($lC_Vqmod->modCheck('includes/classes/order.php'));
    //require_once($lC_Vqmod->modCheck('includes/classes/ticket.php'));

    $this->_page_title = $lC_Language->get('ticket_heading');
  }
}
?>