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
if (is_numeric($_GET[$lC_Template->getModule()])) {
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
            if (isset($tInfo) && $tInfo != '') {
          ?>
          <input id="ticket_customer_id" type="hidden" value="<?php echo $tInfo[0]['customers_id']; ?>" name="ticket_customer_id">
          <?php
              $tshID = 0;
              foreach ($tInfo as $tStatusHistory) {
          ?>
          <div class="button-height margin-bottom columns status-history-block" id="shb_<?php echo $tStatusHistory['ticket_status_history_id']; ?>">
            <div class="five-columns twelve-columns-mobile new-row-mobile status-history-block-info">
              <p>
                <?php echo $lC_Language->get('text_last_reply_by'); ?>: <strong>(<?php echo $tStatusHistory['ticket_edited_by']; ?>)</strong><br />
                <?php echo $lC_Language->get('text_date'); ?>: <strong><?php echo substr(lC_DateTime::getLong($tStatusHistory['ticket_date_modified']), 0, -6) . ' ' . str_replace(' ', '', date("g:i a", strtotime(substr($tStatusHistory['ticket_date_modified'], -8)))); ?></strong><br />
                <?php echo $lC_Language->get('text_priority'); ?>: <strong><?php echo lC_Support_tickets_Admin::getPriorityTitle($tStatusHistory['ticket_priority_id']); ?></strong><br />
                <?php echo $lC_Language->get('text_department'); ?>: <strong><?php echo lC_Support_tickets_Admin::getDepartmentTitle($tStatusHistory['ticket_department_id']); ?></strong><br class="small-margin-bottom" />
                <?php echo $lC_Language->get('text_status'); ?>: <strong><span class="tag <?php echo lC_Support_tickets_Admin::getStatusColor($tStatusHistory['ticket_status_id']); ?>-bg no-wrap with-small-padding"><?php echo lC_Support_tickets_Admin::getStatusTitle($tStatusHistory['ticket_status_id']); ?></strong>
              </p>
            </div>
            <div class="seven-columns twelve-columns-mobile new-row-mobile status-history-block-comment">
              <p>
                <?php echo $tStatusHistory['ticket_comments']; ?>
              </p>
              <?php if ((int)($_SESSION['admin']['access'][$_module] > 4)) { ?>
              <div class="status-history-delete-box">
                <br />
                <div class="status-history-delete">
                  <a class="button compact red-gradient cursor-pointer confirm" href="javascript:deleteStatusHistoryBlock(<?php echo $tStatusHistory['ticket_status_history_id']; ?>);">
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
              $tshID = $tshID-1;
            } else {
          ?>
          <div class="columns">
            <div class="six-columns twelve-columns-mobile new-row-mobile">
              <div class="button-height block-label">
                <label class="label mid-margin-bottom" for="ticket_customer">
                  <?php echo $lC_Language->get('field_customer'); ?>
                  <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_ticket_customer'), null); ?>
                </label>
                <span class="input full-width margin-bottom">
                  <label class="button blue-gradient cursor-default" for="ticket_customer">
                    <span class="icon-white small-margin-right">
                      <span class="small-margin-left" id="edit-company_owner_name">
                        Search
                      </span>
                    </span>
                  </label>
                  <input id="ticket_customer" class="input-unstyled" type="text" onkeyup="ticketCustomerSearch(this.value);" autocomplete="off" placeholder="<?php echo $lC_Language->get('text_customer_search_placeholder'); ?>" value="" name="ticket_customer">
                  <input id="ticket_customer_id" type="hidden" value="" name="ticket_customer_id">
                </span>
                <div id="ticket_customer_results" style="display:none;"></div>
              </div>
              <p class="button-height block-label">
                <label class="label" for="ticket_subject">
                  <?php echo $lC_Language->get('field_subject'); ?>
                  <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_ticket_subject'), null); ?>
                </label>
                <?php echo lc_draw_input_field('ticket_subject', null, 'class="required input full-width mid-margin-top" id="ticket_subject"'); ?>
              </p>
              <p class="button-height block-label disabled">
                <label class="label" for="ticket_customer">
                  <?php echo $lC_Language->get('field_order_id'); ?>
                  <?php echo lc_show_info_bubble($lC_Language->get('info_bubble_ticket_order_id'), null); ?>
                </label>
                <?php echo lC_Support_tickets_Admin::drawTicketOrdersDropdown(1, 'anthracite-gradient'); ?>
              </p>
            </div>
          </div>            
          <?php 
            }
          ?>
          <div class="field-drop button-height black-inputs">
            <div class="columns no-margin-bottom ticket-reply-selections">
              <div class="five-columns twelve-columns-mobile new-row-mobile align-right">
                <div class="columns">
                  <div class="twelve-columns small-margin-bottom">
                    <font class="white font-fourteen mid-margin-right"><?php echo $lC_Language->get('text_status'); ?></font>
                    <?php echo lC_Support_tickets_Admin::drawTicketStatusDropdown($tInfo[$tshID]['ticket_status_id'], 'anthracite-gradient'); ?>
                  </div>
                  <div class="twelve-columns small-margin-bottom">
                    <font class="white font-fourteen mid-margin-right"><?php echo $lC_Language->get('text_priority'); ?></font>
                    <?php echo lC_Support_tickets_Admin::drawTicketPriorityDropdown($tInfo[$tshID]['ticket_priority_id'], 'anthracite-gradient'); ?>
                  </div>
                  <div class="twelve-columns small-margin-bottom">
                    <font class="white font-fourteen mid-margin-right"><?php echo $lC_Language->get('text_department'); ?></font>
                    <?php echo lC_Support_tickets_Admin::drawTicketDepartmentDropdown($tInfo[$tshID]['ticket_department_id'], 'anthracite-gradient'); ?>
                  </div>
                  <div class="twelve-columns small-margin-bottom">
                    <font class="white font-fourteen mid-margin-right"><?php echo $lC_Language->get('text_response'); ?></font>
                    <?php echo lC_Support_tickets_Admin::drawTicketResponseDropdown($tInfo[$tshID]['ticket_response_id'], 'anthracite-gradient'); ?>
                  </div>
                  <div class="twelve-columns small-margin-bottom">
                    <font class="white font-fourteen mid-margin-right"><?php echo $lC_Language->get('text_send_email'); ?></font>
                    <input name="send_email" type="checkbox" class="switch" checked data-text-on="<?php echo $lC_Language->get('text_yes'); ?>" data-text-off="<?php echo $lC_Language->get('text_no'); ?>">
                  </div>
                  <div class="twelve-columns small-margin-bottom">
                    <font class="white font-fourteen mid-margin-right"><?php echo $lC_Language->get('text_login_required'); ?></font>
                    <input name="login_required" type="checkbox" class="switch"<?php echo ($tInfo[0]['login_required'] == '1' ? ' checked' : null); ?> data-text-on="<?php echo $lC_Language->get('text_yes'); ?>" data-text-off="<?php echo $lC_Language->get('text_no'); ?>">
                  </div>
                </div>
              </div>
              <div class="seven-columns twelve-columns-mobile new-row-mobile small-margin-top no-margin-bottom">
                <p class="button-height block-label">
                  <?php echo lc_draw_textarea_field('ckEditorTicketReply', null, null, 10, 'id="ckEditorTicketReply" style="width:97%;" class="input full-width autoexpanding"'); ?>
                </p>
                <?php if (ENABLE_EDITOR == '1') { ?>
                <p class="toggle-html-editor">
                  <a class="white" href="javascript:toggleEditor();"><?php echo $lC_Language->get('text_toggle_html_editor'); ?></a>
                </p>
                <?php } ?>
              </div>            
            </div>
          </div>
        </fieldset>
        <p class="button-height align-right">
          <?php 
            $save = (((int)$_SESSION['admin']['access'][$lC_Template->getModule()] < 2) ? '' : ' onclick="validateForm(\'#ticket\');"');
            $close = lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule());
            button_save_close($save, true, $close);
          ?>
        </p>
      </div> 
    </div>
    <?php 
      echo lc_draw_hidden_field('subaction', 'confirm'); 
      $tshID = null;
    ?>
  </form>
</section>