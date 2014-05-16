// eri.js
    jQuery(document).ready(function($) {

      jQuery('#eri_safe_ips_input').tagsInput({
        width:'auto',
        'defaultText':'add an IP',
        'removeWithBackspace' : true,
        'height':'100px',
         'width':'600px',
         'interactive':true
      });
      // Uncomment this line to see the callback functions in action
      //      $('input.tags').tagsInput({onAddTag:onAddTag,onRemoveTag:onRemoveTag,onChange: onChangeTag});   
      // Uncomment this line to see an input with no interface for adding new tags.
      //      $('input.tags').tagsInput({interactive:false});

    });