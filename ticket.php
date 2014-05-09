<?php
/**
  @package    catalog
  @author     ContributionCentral
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Copyright 2014 ContributionCentral
  @license    http://opensource.org/licenses/MIT
  @version    $Id: ticket.php v1.0 2014-02-01 maestro $
*/
$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require('includes/application_top.php');

if ($lC_Customer->isLoggedOn() === false) {
  $lC_NavigationHistory->setSnapshot();

  lc_redirect(lc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
}

$lC_Language->load('ticket');

if ($lC_Services->isStarted('breadcrumb')) {
  $lC_Breadcrumb->add($lC_Language->get('breadcrumb_ticket'), lc_href_link('ticket.php', null, 'SSL'));
}

$lC_Template = lC_Template::setup('ticket');

require($lC_Vqmod->modCheck('templates/' . $lC_Template->getCode() . '.php'));

require($lC_Vqmod->modCheck('includes/application_bottom.php'));
?>