<?php
/**
  @package    catalog::admin::applications
  @author     Loaded Commerce
  @copyright  Copyright 2003-2014 Loaded Commerce, LLC
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: edit.php v1.0 2013-08-08 datazen $
*/
?>
<style>
#editEntry { padding-bottom:20px; }
</style>
<script>
function editEntry(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getFormData&sid=SID'); ?>';
  $.getJSON(jsonLink.replace('SID', parseInt(id)),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href', url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
        return false;
      }
      $.modal({
          content: '<div id="editEntry">'+
                   '  <div id="editEntryForm">'+
                   '    <form name="sEdit" id="sEdit" action="" method="post" enctype="multipart/form-data">'+
                   '      <p><?php echo $lC_Language->get('introduction_edit_ticket_status'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="ticket_status_name" class="label" style="width:33%;"><?php echo $lC_Language->get('field_ticket_status_name'); ?></label>'+
                   '        <span id="editTsName"></span>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_ticket_status'); ?>',
          width: 500,
                actions: {
            'Close' : {
              color: 'red',
              click: function(win) { win.closeModal(); }
            }
          },
          buttons: {
            '<?php echo $lC_Language->get('button_cancel'); ?>': {
              classes:  'glossy',
              click:    function(win) { win.closeModal(); }
            },
            '<?php echo $lC_Language->get('button_save'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win) {
                var bValid = $("#sEdit").validate({
                  rules: {
                    <?php foreach ($lC_Language->getAll() as $l) { ?>
                    'ticket_status_name[<?php echo $l; ?>]': { required: true }
                    <?php } ?>
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&sID=SID&action=save'); ?>';
                  var actionUrl = url.replace('SID', parseInt(id));
                  $("#sEdit").attr("action", actionUrl);
                  $("#sEdit").submit();
                  win.closeModal();
                }
              }
            }
          },
          buttonsLowPadding: true
      });
      //$("#editTicketStatusName").val(data.tsData.ticketStatusDefault);
      $("#editTsName").html(data.editTsName);
    }
  );
}
</script>