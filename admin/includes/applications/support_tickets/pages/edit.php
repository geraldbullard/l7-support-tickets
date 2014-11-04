<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: edit.php v1.0 2013-08-08 maestro $
*/
if ( is_numeric($_GET[$lC_Template->getModule()]) ) {
  $tInfo = lC_Support_tickets_Admin::get($_GET[$lC_Template->getModule()]);
}
?>
<style scoped="scoped">
  .legend { font-weight:bold; font-size: 1.1em; }
  LABEL { font-weight:bold; }
  TD { padding: 5px 0 0 5px; }
</style>
<!-- Main content -->
<section role="main" id="main">
  <hgroup id="main-title" class="thin">
    <h1><?php echo (isset($tInfo)) ? '#' . $tInfo[0]['ticket_id'] . ' ' . $tInfo[0]['subject'] : $lC_Language->get('heading_title_new_ticket'); ?></h1>
    <?php
      if ( $lC_MessageStack->exists($lC_Template->getModule()) ) {
        echo $lC_MessageStack->get($lC_Template->getModule());
      }
    ?>
  </hgroup>
  <form name="ticket" id="ticket" class="dataForm" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . (isset($tInfo) ? $_GET[$lC_Template->getModule()] : '') . '&action=save'); ?>" method="post" enctype="multipart/form-data">
    <div id="ticket_div" class="columns with-padding-no-top">
      <div class="new-row-mobile twelve-columns twelve-columns-mobile" id="ticket_content">
        <fieldset class="fieldset">
          <legend class="legend"><?php echo $lC_Language->get('legend_ticket_details'); ?></legend>
          <?php
            $tshID = 0;
            foreach ($tInfo as $tStatusHistory) {
          ?>
          <div class="button-height margin-bottom columns status-history-block">
            <div class="four-columns twelve-columns-mobile new-row-mobile status-history-block-info">
              <p>
                <?php echo $lC_Language->get('text_last_reply_by'); ?>: <strong>(<?php echo $tStatusHistory['ticket_edited_by']; ?>)</strong><br />
                <?php echo $lC_Language->get('text_date'); ?>: <strong><?php echo substr(lC_DateTime::getLong($tStatusHistory['ticket_date_modified']), 0, -6) . ' ' . str_replace(' ', '', date("g:i a", strtotime(substr($tStatusHistory['ticket_date_modified'], -8)))); ?></strong><br />
                <?php echo $lC_Language->get('text_priority'); ?>: <strong><?php echo lC_Support_tickets_Admin::getPriorityTitle($tStatusHistory['ticket_priority_id']); ?></strong><br class="small-margin-bottom" />
                <?php echo $lC_Language->get('text_status'); ?>: <strong><span class="tag <?php echo lC_Support_tickets_Admin::getStatusColor($tStatusHistory['ticket_status_id']); ?>-bg no-wrap with-small-padding"><?php echo lC_Support_tickets_Admin::getStatusTitle($tStatusHistory['ticket_status_id']); ?></strong>
              </p>
            </div>
            <div class="eight-columns twelve-columns-mobile new-row-mobile status-history-block-comment">
              <p>
                <?php echo $tStatusHistory['ticket_comments']; ?>
              </p>
              <?php if ((int)($_SESSION['admin']['access'][$_module] > 4)) { ?>
              <div class="status-history-delete-box">
                <br />
                <div class="status-history-delete">
                  <a class="button compact red-gradient" href="javascript:void(0);">
                    <?php echo $lC_Language->get('button_delete'); ?>
                  </a>
                </div>
              </div>
              <?php } ?>
            </div>
          </div>
          <?php 
              $tshID++;
            }
          ?>
          <div class="field-drop button-height black-inputs">
            <div class="columns no-margin-bottom">
              <div class="five-columns twelve-columns-mobile new-row-mobile align-right" style="margin-left:-200px;">
                <div class="columns">
                  <div class="twelve-columns small-margin-bottom">
                    <font class="white font-eightteen mid-margin-right"><?php echo $lC_Language->get('text_status'); ?></font>
                    <?php echo lC_Support_tickets_Admin::drawTicketStatusDropdown($tInfo[$tshID]['ticket_id'], 'anthracite-gradient'); ?>
                  </div>
                  <div class="twelve-columns small-margin-bottom">
                    <font class="white font-eightteen mid-margin-right"><?php echo $lC_Language->get('text_priority'); ?></font>
                    <?php echo lC_Support_tickets_Admin::drawTicketPriorityDropdown($tInfo[2]['ticket_id'], 'anthracite-gradient'); ?>
                  </div>
                  <div class="twelve-columns small-margin-bottom">
                    <font class="white font-eightteen mid-margin-right"><?php echo $lC_Language->get('text_department'); ?></font>
                    <?php echo lC_Support_tickets_Admin::drawTicketDepartmentDropdown($tInfo[2]['ticket_id'], 'anthracite-gradient'); ?>
                  </div>
                  <div class="twelve-columns small-margin-bottom">
                    <font class="white font-eightteen mid-margin-right"><?php echo $lC_Language->get('text_response'); ?></font>
                    <?php echo lC_Support_tickets_Admin::drawTicketResponseDropdown($tInfo[2]['ticket_id'], 'anthracite-gradient'); ?>
                  </div>
                </div>
              </div>            
            </div>
          </div>
        </fieldset>
      </div> 
    </div>
    <?php echo lc_draw_hidden_field('subaction', 'confirm'); ?>
    <div class="clear-both"></div>
    <div class="six-columns twelve-columns-tablet">
      <div id="buttons-menu-div-listing">
        <div id="buttons-container" style="position: relative;" class="clear-both">
          <div class="align-right">
            <p class="button-height">
              <?php 
                $save = (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '' : ' onclick="validateForm(\'#ticket\');"');
                $close = lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule());
                button_save_close($save, true, $close);
              ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </form>
</section>