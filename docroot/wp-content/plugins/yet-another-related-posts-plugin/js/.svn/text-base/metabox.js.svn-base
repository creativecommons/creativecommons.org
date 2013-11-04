jQuery(function($) {
	var loaded_metabox = false;
	var display = $('#yarpp-related-posts');
	function metabox_display() {
		if ( !$('#yarpp_relatedposts .inside').is(':visible') ||
			 !display.length ||
			 !$('#post_ID').val() )
			return;
		if ( !loaded_metabox ) {
			loaded_metabox = true;
			$.ajax({type:'POST',
				url: ajaxurl,
				data: {
					action: 'yarpp_display',
					domain: 'metabox',
					ID: $('#post_ID').val(),
					'_ajax_nonce': $('#yarpp_display-nonce').val()
				},
				success:function(html){display.html(html)},
				dataType:'html'});
		}
	}
	$('#yarpp_relatedposts .handlediv, #yarpp_relatedposts-hide').click(
		function() {
			setTimeout(metabox_display, 0);
		});
	metabox_display();
});
