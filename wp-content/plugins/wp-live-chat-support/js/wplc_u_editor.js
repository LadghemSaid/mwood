jQuery(document).on("wplc_animation_done", function(e) {
    //Hide box originally
    //jQuery("#nifty_text_editor_holder").hide();

    jQuery(function(){

        /*jQuery("#wplc_chatmsg").focus(function(){
            //Show editor Panel
            jQuery("#nifty_text_editor_holder").show();
            jQuery("#wplc_msg_notice").hide();
            
            
        });
        
        jQuery("#wplc_chatmsg").focusout(function(){
            //Hide editor Panel
            setTimeout(function(){
                if(document.activeElement.id !== "wplc_chatmsg"){
                    jQuery("#nifty_text_editor_holder").hide();
                    jQuery("#wplc_msg_notice").show();
                }
            },200);
        });*/

        /*Text editor Support*/
        jQuery("#nifty_tedit_b").click(function(evt){
            evt.stopImmediatePropagation();
            niftyTextEdit("b");
        });
        jQuery("#nifty_tedit_i").click(function(evt){
            evt.stopImmediatePropagation();
            niftyTextEdit("i");
        });
        jQuery("#nifty_tedit_u").click(function(evt){
            evt.stopImmediatePropagation();
            niftyTextEdit("u");
        });
        jQuery("#nifty_tedit_strike").click(function(evt){
            evt.stopImmediatePropagation();
            niftyTextEdit("strike");
        });
        jQuery("#nifty_tedit_mark").click(function(evt){
            evt.stopImmediatePropagation();
            niftyTextEdit("mark");
        });
        jQuery("#nifty_tedit_sub").click(function(evt){
            evt.stopImmediatePropagation();
            niftyTextEdit("sub");
        });
        jQuery("#nifty_tedit_sup").click(function(evt){
            evt.stopImmediatePropagation();
            niftyTextEdit("sup");
        });
        jQuery("#nifty_tedit_link").click(function(evt){
            evt.stopImmediatePropagation();
            niftyTextEdit("link");
        });

    });

});

var selectedIndexStart;
var selectedIndexEnd;
var checkSelection = true;
function getText(elem) {
  if(checkSelection){

      if(elem !== null && (elem.selectionStart !== null && typeof elem.selectionStart !== 'undefined')){
          if(selectedIndexStart !== elem.selectionStart){
              selectedIndexStart = elem.selectionStart;
          }
      }

      if(elem !== null && ( typeof elem.selectionEnd !== 'undefined' && elem.selectionEnd !== null)){
          if(selectedIndexEnd !== elem.selectionEnd){
              selectedIndexEnd = elem.selectionEnd;
          }
      }
  }
   
}

setInterval(function() {
    getText(document.getElementById("wplc_chatmsg"));
}, 1000);

function niftyTextEdit(insertContent){
    checkSelection = false;
    /*Text editor Code here*/
    
    jQuery("#wplc_chatmsg").focus();

    var current = jQuery("#wplc_chatmsg").val();

    var pre = current.substr(0, (selectedIndexStart > 0) ? selectedIndexStart : 0);
    var selection = current.substr(selectedIndexStart, selectedIndexEnd - selectedIndexStart);
    var post = current.substr(((selectedIndexEnd < current.length) ? selectedIndexEnd : current.length ), current.length);

    current = pre + insertContent + ":" + selection + ":" + insertContent + post;
    jQuery("#wplc_chatmsg").val(current);

    checkSelection = true;
}