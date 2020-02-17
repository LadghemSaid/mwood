jQuery(document).ready(function($) {

  jQuery("#wplc-delete-chat-history").click(function(e) {
    e.preventDefault();
    return confirm(tcx_messages.historydeleteconfirm);
  });    

});