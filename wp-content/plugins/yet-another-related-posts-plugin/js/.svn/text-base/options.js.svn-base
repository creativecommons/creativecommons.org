jQuery(function($) {
	// since 3.3: add screen option toggles
	postboxes.add_postbox_toggles(pagenow);

	function template() {
		var metabox = $(this).closest('#yarpp_display_web, #yarpp_display_rss');
		if ( !metabox.length )
			return;
			
		value = metabox.find('.use_template').val();
		
		metabox.find('.yarpp_subbox').hide();
		metabox.find('.template_options_' + value).show();
		excerpt.apply(metabox);
	}
	$('.use_template').each(template).change(template);
	
	function excerpt() {
		var metabox = $(this).closest('#yarpp_display_web, #yarpp_display_rss');
		metabox.find('.excerpted').toggle( !!(metabox.find('.use_template').val() == 'builtin' &&
			metabox.find('.show_excerpt input').attr('checked')) );
	}
	$('.show_excerpt, .use_template, #yarpp-rss_display').click(excerpt);
	
	var loaded_demo_web = false;
	function display() {
		if ( !$('#yarpp_display_web .inside').is(':visible') )
			return;

		$( '.yarpp_code_display' ).toggle( $('#yarpp_display_code').is(':checked') );			
		if ( $('#yarpp_display_web .yarpp_code_display').is(':visible') && !loaded_demo_web ) {
			loaded_demo_web = true;
			var demo_web = $('#display_demo_web');
			$.ajax({type:'POST',
				url: ajaxurl,
				data: {
					action: 'yarpp_display_demo',
					domain: 'website',
					'_ajax_nonce': $('#yarpp_display_demo-nonce').val()
				},
				beforeSend:function(){demo_web.html(loading)},
				success:function(html){demo_web.html('<pre>'+html+'</pre>')},
				dataType:'html'});
		}
	}
	$('#yarpp_display_web .handlediv, #yarpp_display_web-hide').click(display);
	display();

	var loaded_demo_rss = false;
	function rss_display() {
		if ( !$('#yarpp_display_rss .inside').is(':visible') )
			return;
		if ( $('#yarpp-rss_display').is(':checked') ) {
			$('.rss_displayed').show();
			$( '.yarpp_code_display' ).toggle( $('#yarpp_display_code').is(':checked') );
			if ( $('#yarpp_display_rss .yarpp_code_display').is(':visible') && !loaded_demo_rss ) {
				loaded_demo_rss = true;
				var demo_rss = $('#display_demo_rss');
				$.ajax({type:'POST',
						url: ajaxurl,
						data: {
							action: 'yarpp_display_demo',
							domain: 'rss',
							'_ajax_nonce': $('#yarpp_display_demo-nonce').val()
						},
						beforeSend:function(){demo_rss.html(loading)},
						success:function(html){demo_rss.html('<pre>'+html+'</pre>')},
						dataType:'html'});
			}
			$('#yarpp_display_rss').each(template);
		} else {
			$('.rss_displayed').hide();
		}
	}
	$('#yarpp-rss_display, #yarpp_display_rss .handlediv, #yarpp_display_rss-hide').click(rss_display);
	rss_display();

	var loaded_disallows = false;
	function load_disallows() {
		if ( loaded_disallows || !$('#yarpp_pool .inside').is(':visible') )
			return;
		loaded_disallows = true;
		
		var finished_taxonomies = {},
			term_indices = {};
		function load_disallow(taxonomy) {
			if (taxonomy in finished_taxonomies)
				return;
			var display = $('#exclude_' + taxonomy);
			// only do one query at a time:
			if (display.find('.loading').length)
				return;
			
			if ( taxonomy in term_indices )
				term_indices[taxonomy] = term_indices[taxonomy] + 100;
			else
				term_indices[taxonomy] = 0;
			$.ajax({type:'POST',
					url: ajaxurl,
					data: {	action: 'yarpp_display_exclude_terms',
							taxonomy: taxonomy,
							offset: term_indices[taxonomy],
							'_ajax_nonce': $('#yarpp_display_exclude_terms-nonce').val()
							},
					beforeSend:function(){
						display.append(loading)
					},
					success:function(html){
						display.find('.loading').remove();
						if (':(' == html) { // no more :(
							finished_taxonomies[taxonomy] = true;
							return;
						}
						display.append(html);
					},
					dataType:'html'}
			);
		}
		
		$('.exclude_terms').each(function() {
			var id = jQuery(this).attr('id'), taxonomy;
			if (!id)
				return;
			
			taxonomy = id.replace('exclude_','');
			
			load_disallow(taxonomy);
			$('#exclude_' + taxonomy).parent('.yarpp_scroll_wrapper').scroll(function() {
				var parent = $(this),
					content = parent.children('div');
				if ( parent.scrollTop() + parent.height() > content.height() - 10 )
					load_disallow(taxonomy);
			})
		})
		
	}
	$('#yarpp_pool .handlediv, #yarpp_pool-hide').click(load_disallows);
	load_disallows();

	function show_help( section ) {
		$('#tab-link-' + section + ' a').click();
		$('#contextual-help-link').click();
	}
	$('#yarpp-optin-learnmore').click(function() { show_help('optin'); });
	$('#yarpp-help-cpt').click(function() { show_help('dev'); });
	if ( location.hash == '#help-optin' )
		setTimeout( function() { show_help('optin'); } );

	$('.yarpp_help[data-help]').hover(function() {
		var that = $(this),
		help = '<p>' + that.attr('data-help') + '</p>',
		options = {
			content: help,
			position: {
				edge: isRtl ? 'right' : 'left',
				align: 'center',
				of: that
			},
			document: {body: that}
		};
		
		var pointer = that.pointer(options).pointer('open');
		that.closest('.yarpp_form_row, p').mouseleave(function () {
			pointer.pointer('close');
		});
	});

	$('.yarpp_template_button[data-help]').hover(function() {
		var that = $(this),
		help = '<p>' + that.attr('data-help') + '</p>',
		options = {
			content: help,
			position: {
				edge: 'bottom',
//				align: 'center',
				of: that
			},
			document: {body: that}
		};
		
		var pointer = that.pointer(options).pointer('open');
		that.mouseleave(function () {
			pointer.pointer('close');
		});
	});
	$('.yarpp_template_button:not(.disabled)').click(function() {
		$(this).siblings('input')
			.val($(this).attr('data-value'))
			.change();
		$(this).siblings().removeClass('active');
		$(this).addClass('active');
	});

	$('.yarpp_copy_templates_button').live('click', function() {
		window.location = window.location + (window.location.search.length ? '&' : '?') + 'action=copy_templates&_ajax_nonce=' + $('#yarpp_copy_templates-nonce').val();
	});
	
	function template_info() {
		var template = $(this).find('option:selected'),
		row = template.closest('.yarpp_form_row');
		if ( !!template.attr('data-url') ) {
			row.find('.template_author_wrap')
				.toggle( !!template.attr('data-author') )
				.find('span').empty().append('<a>' + template.attr('data-author') + '</a>')
				.attr('href', template.attr('data-url'));
		} else {
			row.find('.template_author_wrap')
				.toggle( !!template.attr('data-author') )
				.find('span').text(template.attr('data-author'));
		}
		row.find('.template_description_wrap')
			.toggle( !!template.attr('data-description') )
			.find('span').text(template.attr('data-description'));
		row.find('.template_file_wrap')
			.toggle( !!template.attr('data-basename') )
			.find('span').text(template.attr('data-basename'));
	}
	$('#template_file, #rss_template_file')
		.each(template_info)
		.change(template_info);

	var loaded_optin_data = false;
	function _display_optin_data() {
		if ( !$('#optin_data_frame').is(':visible') || loaded_optin_data )
			return;
		loaded_optin_data = true;
		var frame = $('#optin_data_frame');
		$.ajax({type:'POST',
			url: ajaxurl,
			data: {
				action: 'yarpp_optin_data',
				'_ajax_nonce': $('#yarpp_optin_data-nonce').val()
			},
			beforeSend:function(){frame.html(loading)},
			success:function(html){frame.html('<pre>'+html+'</pre>')},
			dataType:'html'});
	}
	function display_optin_data() {
		setTimeout(_display_optin_data, 0);
	}
	$('#yarpp-optin-learnmore, a[aria-controls=tab-panel-optin]').bind('click focus', display_optin_data);
	display_optin_data();
	
	function sync_no_results() {
		var value = $(this).find('input').attr('value');
		if ( $(this).hasClass('sync_no_results') )
			$('.sync_no_results input').attr('value', value);
		if ( $(this).hasClass('sync_rss_no_results') )
			$('.sync_rss_no_results input').attr('value', value);
	}
	$('.sync_no_results, .sync_rss_no_results').change(sync_no_results);
	
	$('#yarpp_display_code').click(function() {
		var args = {
			action: 'yarpp_set_display_code',
			'_ajax_nonce': $('#yarpp_set_display_code-nonce').val()
		};
		if ( $(this).is(':checked') )
			args.checked = true;
		$.ajax({type:'POST', url: ajaxurl, data: args});
		display();
		rss_display();
	});
	
	function auto_display_archive() {
		var available = $('.yarpp_form_post_types').is(':has(input[type=checkbox]:checked)');
		$('#yarpp-auto_display_archive')
			.attr('disabled', !available);
		if ( !available )
			$('#yarpp-auto_display_archive').attr('checked', false);
	}
	$('.yarpp_form_post_types input[type=checkbox]').change(auto_display_archive);
	auto_display_archive();
});