<?php
/**
  @package    catalog::admin::applications
  @author     ContributionCentral
  @copyright  Copyright 2014 ContributionCentral
  @copyright  Portions Copyright 2003 osCommerce
  @copyright  Template built on Developr theme by DisplayInline http://themeforest.net/user/displayinline under Extended license 
  @license    https://github.com/loadedcommerce/loaded7/blob/master/LICENSE.txt
  @version    $Id: support_tickets.js.php v1.0 2013-08-08 maestro $
*/
global $lC_Template, $lC_Language, $tInfo;
?>      
<script>
$(document).ready(function() {
  var paginationType = ($.template.mediaQuery.isSmallerThan('tablet-portrait')) ? 'two_button' : 'full_numbers';            
  var dataTableDataURL = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=getAll&media=MEDIA'); ?>';
  var quickAdd = '<?php echo (isset($_GET['action']) && $_GET['action'] == 'quick_add') ? true : false; ?>';
     
  oTable = $('#dataTable').dataTable({
    "bProcessing": true,
    "sAjaxSource": dataTableDataURL.replace('MEDIA', $.template.mediaQuery.name),
    "sPaginationType": paginationType, 
    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], 
    "aoColumns": [{ "sWidth": "10px", "bSortable": false, "sClass": "dataColCheck" },
                  { "sWidth": "30%", "bSortable": false, "sClass": "dataColTicket" },
                  { "sWidth": "15%", "bSortable": true, "sClass": "dataColCustomer hide-on-mobile-portrait" },
                  { "sWidth": "10%", "bSortable": true, "sClass": "dataColStatus hide-on-mobile-portrait" },
                  { "sWidth": "10%", "bSortable": true, "sClass": "dataColPriority hide-on-mobile-portrait" },
                  { "sWidth": "10%", "bSortable": false, "sClass": "dataColDate hide-on-mobile-portrait" },
                  { "sWidth": "10%", "bSortable": false, "sClass": "dataColModified hide-on-mobile-portrait" },
                  { "sWidth": "15%", "bSortable": false, "sClass": "dataColAction" }]
  });
  $('#dataTable').responsiveTable();
       
  if ($.template.mediaQuery.isSmallerThan('tablet-portrait')) {
    $('#main-title > h1').attr('style', 'font-size:1.8em;');
    $('#main-title').attr('style', 'padding: 0 0 0 20px;');
    $('#dataTable_info').attr('style', 'position: absolute; bottom: 42px; color:#4c4c4c;');
    $('#dataTable_length').hide();
    $('#actionText').hide();
    $('.on-mobile').show();
    $('.selectContainer').hide();
  }     
  var error = '<?php echo $_SESSION['error']; ?>';
  if (error) {
    var errmsg = '<?php echo $_SESSION['errmsg']; ?>';
    $.modal.alert(errmsg);
  } 
  
  if (quickAdd) {
    //newTicket();
  }
  
  <?php
    if (isset($tInfo)) {
      if (ENABLE_EDITOR == 1) { 
        if (USE_DEFAULT_TEMPLATE_STYLESHEET == 1) { 
          echo "CKEDITOR.replace('ckEditorTicketReply', {toolbar: 'Minimum', height: 200, width: '99%', contentsCss: '../templates/" . DEFAULT_TEMPLATE . "/css/styles.css', stylesSet: [] });";
        } else {
          echo "CKEDITOR.replace('ckEditorTicketReply', {toolbar: 'Minimum', height: 200, width: '99%' });";
        }
      } else {
        echo '$("#ckEditorTicketReply").css("height", "200px").css("width", "99.8%");';
      }
    }
  ?>   
});
  
function validateForm(e) {
  // turn off messages
  jQuery.validator.messages.required = "";

  var tid = '<?php echo $_GET[$lC_Template->getModule()]; ?>';
  var bValid = $("#ticket").validate({ 
    invalidHandler: function(event, validator) {
    },
    ignore: "",
    rules: {
    },
    messages: {
    }, 
  }).form();
  if (bValid) {
    $(e).submit();
  }

  return false;
}

function deleteStatusHistoryBlock(shid) {
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=deleteStatusHistoryBlock&shid=SHID'); ?>';
  $.getJSON(jsonLink.replace('SHID', parseInt(shid)),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href', url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_action_not_performed'); ?>');
        return false;
      }
      if (data.rpcStatus == 1) {
        $("#shb_" + shid).remove();
      }
    }
  );
}

function toggleEditor(id) {
  if ($("#ckEditorTicketReply").is(":visible")) {
    $("#ckEditorTicketReply").hide();
    $("#cke_ckEditorTicketReply").show();
  } else {
    $("#ckEditorTicketReply").attr("style", "width:99%").attr("style", "height:200px!important");
    $("#cke_ckEditorTicketReply").hide();
  }
}

function updateReply(text) {
  var oEditor = CKEDITOR.instances.ckEditorTicketReply;
  oEditor.insertHtml(text);
}
</script>