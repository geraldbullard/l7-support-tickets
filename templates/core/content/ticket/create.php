<?php
/**
  @package    catalog::templates::content
  @author     ContributionCentral
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Copyright 2014 ContributionCentral
  @license    http://opensource.org/licenses/MIT
  @version    $Id: create.php v1.0 2014-02-01 maestro $
*/
?>
<!--content/ticket/create.php start-->
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo $lC_Template->getPageTitle(); ?></h1>
    <?php 
      if ( $lC_MessageStack->size('create') > 0 ) echo '<div class="message-stack-container alert alert-danger small-margin-bottom small-margin-left">' . $lC_MessageStack->get('create') . '</div>' . "\n"; 
    ?>
    <div class="row">
      <form role="form" class="form-inline" name="create" id="create" action="<?php echo lc_href_link('ticket.php', 'create=save', 'SSL'); ?>" method="post" onsubmit="return check_form(create);">
        <div class="col-sm-12 col-lg-12">
          <div class="well no-padding-top">
            <h3><?php echo $lC_Language->get('some_title'); ?></h3>
            Left Content Here        
          </div>
        </div>
      </form>
    </div>
    <div class="btn-set small-margin-top clearfix">
      <button class="pull-right btn btn-lg btn-primary" onclick="$('#create').submit();" type="button"><?php echo $lC_Language->get('submit'); ?></button>
      <form action="<?php echo lc_href_link('ticket.php', null, 'AUTO'); ?>" method="post"><button class="pull-left btn btn-lg btn-default" onclick="$(this).closest('form').submit();" type="submit"><?php echo $lC_Language->get('button_back'); ?></button></form>
    </div> 
    <hr>
  </div>
</div>    
<script>
$(document).ready(function() {
  $('.datepicker').datepicker();
});
</script>                       
<!--content/ticket/create.php end-->