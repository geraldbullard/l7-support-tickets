<?php
/**
  @package    catalog::templates::modules::boxes
  @author     ContributionCentral
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Copyright 2014 ContributionCentral
  @license    http://opensource.org/licenses/MIT
  @version    $Id: ticket.php v1.0 2014-02-01 maestro $
*/
?>
<!--modules/boxes/ticket.php start-->
<div class="well">
  <ul class="box-ticket list-unstyled list-indent-large">
    <li class="box-header small-margin-bottom"><?php echo $lC_Box->getTitle(); ?></li>
    <?php echo $lC_Box->getContent(); ?>
  </ul>
</div>
<!--modules/boxes/ticket.php end-->