<?php
/**
  @package    catalog::admin::applications
  @author     ContributionCentral
  @copyright  Copyright 2014 ContributionCentral
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: main.php v1.0 2013-08-08 maestro $
*/
?>
<!-- Main content -->
<section role="main" id="main">
  <noscript class="message black-gradient simpler"><?php echo $lC_Language->get('ms_error_javascript_not_enabled_warning'); ?></noscript>
  <hgroup id="main-title" class="thin">
    <h1><?php echo $lC_Template->getPageTitle(); ?></h1>
  </hgroup>
  <style>
  .dataColCheck { text-align: left; }
  .dataColTitle { text-align: left; }
  .dataColText { text-align: left; }
  .dataColAction { text-align: right; }
  .dataTables_info { position:absolute; bottom:42px; color:#4c4c4c; }
  .selectContainer { position:absolute; bottom:29px; left:30px }
  </style>
  <?php
    if (defined('ADDONS_CATALOG_SUPPORT_TICKETS_STATUS') && @constant('ADDONS_CATALOG_SUPPORT_TICKETS_STATUS') == 1) {
  ?>
  <div class="with-padding-no-top">
    <form name="batch" id="batch" action="#" method="post">
    <table border="0" width="100%" cellspacing="0" cellpadding="0" class="table responsive-table" id="dataTable">
      <thead>
        <tr>
          <th scope="col" class="hide-on-mobile align-left"><input onclick="toggleCheck();" id="check-all" type="checkbox" value="1" name="check-all"></th>
          <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_response_title'); ?></th>
          <th scope="col" class="align-left"><?php echo $lC_Language->get('table_heading_response_text'); ?></th>
          <th scope="col" class="align-right"><span class="button-group compact"><a href="javascript:void(0);" style="cursor:pointer" onclick="oTable.fnReloadAjax();" class="button with-tooltip icon-redo blue" title="<?php echo $lC_Language->get('button_refresh'); ?>"></a></span><span id="actionText">&nbsp;&nbsp;<?php echo $lC_Language->get('table_heading_action'); ?></span></th>
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="10">&nbsp;</th>
        </tr>
      </tfoot>
    </table>
    <div class="selectContainer">
      <select <?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? NULL : 'onchange="batchDelete();"'); ?> name="selectAction" id="selectAction" class="select blue-gradient glossy<?php echo (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 4) ? ' disabled' : NULL); ?>">
        <option value="0" selected="selected"><?php echo $lC_Language->get('text_with_selected'); ?></option>
        <option value="delete"><?php echo $lC_Language->get('text_delete'); ?></option>
      </select>
    </div>
    </form>
    <div class="clear-both"></div>
  </div>
  <?php
    } else {
  ?>
  <div class="with-padding-no-top">
    <?php 
      if (!defined('ADDONS_CATALOG_SUPPORT_TICKETS_STATUS')) {
        echo $lC_Language->get('text_support_tickets_not_installed');
      }
      if (defined('ADDONS_CATALOG_SUPPORT_TICKETS_STATUS') && @constant('ADDONS_CATALOG_SUPPORT_TICKETS_STATUS') != 1) {
        echo $lC_Language->get('text_support_tickets_not_enabled');
      } 
    ?>
  </div>
  <?php
    }
  ?>
</section>
<?php
  $lC_Template->loadModal($lC_Template->getModule());
?>
<!-- End main content -->