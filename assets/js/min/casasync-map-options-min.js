jQuery(document).ready(function($){

  // media upload
  var _custom_media = true,
      _orig_send_attachment = wp.media.editor.send.attachment;

  $('.casasync_map_upload').click(function(e) {
    var send_attachment_bkp = wp.media.editor.send.attachment;
    var button = $(this);
    var id = button.attr('id').replace('_button', '');
    _custom_media = true;
    wp.media.editor.send.attachment = function(props, attachment){
      if ( _custom_media ) {
        $("#"+id).val(attachment.id);
        $("#"+id+"_src").prop('src', attachment.sizes.full.url);
      } else {
        return _orig_send_attachment.apply( this, [props, attachment] );
      };
    }

    wp.media.editor.open(button);
    return false;
  });

  $('.delete_media').on('click', function(){
    $('#casasync_map_upload_marker_image').val(null);
  });


});

// filter type
jQuery(document).ready(function($){
  function showFilterTypeSection(selector) {
    var value = $(selector).val();
    $('div[data-filter-type]').hide();
    $('div[data-filter-type='+value+']').show();
  }
  var selector = $('select[name="casasync_map[csm_filter_typ]"]');
  $('div[data-filter-type]').hide();
  showFilterTypeSection(selector);
  jQuery(selector).on('change', function(){
    showFilterTypeSection(selector);
  });


  //javascript json generation
  $("#CsmFilterBasicTable").change(function(){
    //var serialized = $("#CsmFilterBasicTable input, #CsmFilterBasicTable select").serializeArray();
    //var jsonified = JSON.stringify(serialized);
    //console.log(jsonified);
    //$('#csm_filter_basic').attr('value', jsonified);

    var result = [];

    $("#CsmFilterBasicTable .data-row").each(function(key, element){
      var visibleElem = $(element).find('[name="filters['+key+'][visible]"]:checked');
      result.push({
        taxonomy: $(element).find('[name="filters['+key+'][taxonomy]"]').val(),
        visible: (visibleElem.length ? true : false),
        inclusive: ($(element).find('[name="filters['+key+'][inclusive]"]').find(":selected").val() == "1" ? true : false),
        label: $(element).find('[name="filters['+key+'][label]"]').val(),
        filter_terms: $(element).find('[name="filters['+key+'][filter_terms]"]').val()
      });
      
    })

    

    $('#csm_filter_basic').attr('value', JSON.stringify(result, null, 4));

  });


});



