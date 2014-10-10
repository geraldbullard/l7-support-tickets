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
    <h1><?php echo (isset($tInfo)) ? '#' . $tInfo['ticket_id'] . ' ' . $tInfo['subject'] : $lC_Language->get('heading_title_new_ticket'); ?></h1>
    <?php
      //print_r($tInfo);
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
          <div class="button-height margin-bottom columns" style="border-bottom:1px solid #ccc; margin-left:0;">
            <div class="four-columns twelve-columns-mobile new-row-mobile" style="border-right:1px solid #ccc; margin-left:-1px; padding-right:10px;">
              <p style="line-height:18px;">
                Last Reply by: <strong>(<?php echo $tInfo['ticket_edited_by']; ?>)</strong><br />
                Date: <strong><?php echo $tInfo['ticket_date_modified']; ?></strong><br />
                Status: <strong><?php echo $tInfo['ticket_status_id']; ?></strong><br />
                Priority: <strong><?php echo $tInfo['ticket_priority_id']; ?></strong><br />
                Comment ID: <strong><?php echo $tInfo['ticket_status_history_id']; ?></strong>
              </p>
            </div>
            <div class="eight-columns twelve-columns-mobile new-row-mobile">
              <p style="line-height:18px;">
                <?php echo $tInfo['ticket_comments']; ?>
              </p>
            </div>
          </div>
          <div class="button-height margin-bottom columns" style="border-bottom:1px solid #ccc; margin-left:0;">
            <div class="four-columns twelve-columns-mobile new-row-mobile" style="border-right:1px solid #ccc; margin-left:-1px; padding-right:10px;">
              <p style="line-height:18px;">
                Last Reply by: <strong>(<?php echo $tInfo['ticket_edited_by']; ?>)</strong><br />
                Date: <strong><?php echo $tInfo['ticket_date_modified']; ?></strong><br />
                Status: <strong><?php echo $tInfo['ticket_status_id']; ?></strong><br />
                Priority: <strong><?php echo $tInfo['ticket_priority_id']; ?></strong><br />
                Comment ID: <strong><?php echo $tInfo['ticket_status_history_id']; ?></strong>
              </p>
            </div>
            <div class="eight-columns twelve-columns-mobile new-row-mobile">
              <p style="line-height:18px;">
                <?php echo $tInfo['ticket_comments']; ?>
              </p>
            </div>
          </div>
          <div class="button-height margin-bottom columns" style="border-bottom:1px solid #ccc; margin-left:0;">
            <div class="four-columns twelve-columns-mobile new-row-mobile" style="border-right:1px solid #ccc; margin-left:-1px; padding-right:10px;">
              <p style="line-height:18px;">
                Last Reply by: <strong>(<?php echo $tInfo['ticket_edited_by']; ?>)</strong><br />
                Date: <strong><?php echo $tInfo['ticket_date_modified']; ?></strong><br />
                Status: <strong><?php echo $tInfo['ticket_status_id']; ?></strong><br />
                Priority: <strong><?php echo $tInfo['ticket_priority_id']; ?></strong><br />
                Comment ID: <strong><?php echo $tInfo['ticket_status_history_id']; ?></strong>
              </p>
            </div>
            <div class="eight-columns twelve-columns-mobile new-row-mobile">
              <p style="line-height:18px;">
                <?php echo $tInfo['ticket_comments']; ?>
              </p>
            </div>
          </div>
          <div class="button-height margin-bottom columns" style="border-bottom:1px solid #ccc; margin-left:0;">
            <div class="four-columns twelve-columns-mobile new-row-mobile" style="border-right:1px solid #ccc; margin-left:-1px; padding-right:10px;">
              <p style="line-height:18px;">
                Last Reply by: <strong>(<?php echo $tInfo['ticket_edited_by']; ?>)</strong><br />
                Date: <strong><?php echo $tInfo['ticket_date_modified']; ?></strong><br />
                Status: <strong><?php echo $tInfo['ticket_status_id']; ?></strong><br />
                Priority: <strong><?php echo $tInfo['ticket_priority_id']; ?></strong><br />
                Comment ID: <strong><?php echo $tInfo['ticket_status_history_id']; ?></strong>
              </p>
            </div>
            <div class="eight-columns twelve-columns-mobile new-row-mobile">
              <p style="line-height:18px;">
                <?php echo $tInfo['ticket_comments']; ?>
              </p>
            </div>
          </div>
          <div class="field-drop button-height black-inputs">
            Replies ection
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