/*
 * Maps Widget for Google Maps
 * (c) Web factory Ltd, 2012 - 2019
 */

 
jQuery(document).ready(function($){
  if (typeof gmw_pointers  == 'undefined') {
    return;
  }

  $.each(gmw_pointers, function(index, pointer) {
    if (index.charAt(0) == '_') {
      return true;
    }
    $(pointer.target).pointer({
        content: '<h3>Maps Widget for Google Maps</h3><p>' + pointer.content + '</p>',
        position: {
            edge: pointer.edge,
            align: pointer.align
        },
        width: 320,
        close: function() {
                $.post(ajaxurl, {
                    pointer: index,
                    _ajax_nonce: gmw_pointers._nonce_dismiss_pointer,
                    action: 'gmw_dismiss_pointer'
                });
        }
      }).pointer('open');
  });
});
