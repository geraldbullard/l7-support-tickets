<?php
/**
  @package    catalog::content
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: view.php v1.0 2013-08-08 datazen $
*/
class lC_Ticket_View extends lC_Template {

  /* Private variables */
  var $_module = 'view',
      $_group = 'ticket',
      $_page_title,
      $_page_contents = 'view.php',
      $_page_image = '';

  /* Class constructor */
  public function lC_Ticket_View() {
    global $lC_Language, $lC_Services, $lC_Breadcrumb;

    $this->_page_title = $lC_Language->get('view_ticket_heading');

    if ($lC_Services->isStarted('breadcrumb')) {
      $lC_Breadcrumb->add($lC_Language->get('breadcrumb_view_ticket'), lc_href_link('ticket.php', $this->_module, 'SSL'));
    }
  }
}
?>