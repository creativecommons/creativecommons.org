<script type="text/javascript">
	(function($){
		var slideDeckUniqueId = "<?php echo $slidedeck_unique_id; ?>";
		var proportional = <?php echo ( $proportional !== true ) ? 'false' : 'true'; ?>;
		var <?php echo $slidedeck_unique_id; ?>ratio = <?php echo $ratio; ?>;
		
        if( proportional ){
		    jQuery('#' + slideDeckUniqueId + '-wrapper').css( 'height', parseInt( jQuery('#' + slideDeckUniqueId + '-wrapper').width() * <?php echo $slidedeck_unique_id; ?>ratio ) );
		}
	    
	    var ressProperties = <?php echo json_encode( $ress_properties ); ?>;
	    ressProperties.src = "<?php echo $iframe_url; ?>".replace(/width=[0-9]+/,'width=' + parseInt(jQuery('#' + slideDeckUniqueId + '-wrapper').width()) ).replace(/height=[0-9]+/,'height=' + parseInt(jQuery('#' + slideDeckUniqueId + '-wrapper').height()) );
	    new SlideDeckiFrameResize( ressProperties, <?php echo $slidedeck_unique_id; ?>ratio, proportional );

    })(jQuery);
    
</script>