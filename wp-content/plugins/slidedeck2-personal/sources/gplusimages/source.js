(function($){
    window.GplusImagesSource = {
        elems: {},
        
        updateGplusAlbums: function(){
            var self = this;
            $.ajax({
                url: ajaxurl + "?action=update_gplus_albums&gplus_userid=" + $('#options-gplus_user_id').val(),
                type: "GET",
                success: function(data){
                    $('#gplus-user-albums').html( data ).find('.fancy').fancy();
                    SlideDeckPreview.ajaxUpdate();
                }
            });
        },
        
        initialize: function(){
            var self = this;
            
            this.elems.form = $('#slidedeck-update-form');
            
            this.slidedeck_id = $('#slidedeck_id').val();
            
            // Gplus Userid 
            self.elems.form.delegate('.gplus-images-ajax-update', 'click', function(event){
                event.preventDefault();
                self.updateGplusAlbums();
            });
            // Prevent enter key from submitting text fields
            this.elems.form.delegate('#options-gplus_user_id', 'keydown', function(event){
                if( 13 == event.keyCode){
                    event.preventDefault();
                    $('.gplus-images-ajax-update').click();
                    return false;
                }
                return true;
            })
            
            // Gplus Image Size Slider
            if( $('.slidedeck-content-source.source-gplusimages .image-size-slider').length ){
                // Map the minute values to an array of 10 items (0-9)
                var pixelValues = [94, 110, 128, 200, 220, 288, 320, 400, 512, 576, 640, 720, 800, 912, 1024, 1152, 1280, 1440, 1600]; // Pixels
                var currentValue = $.inArray( parseInt( $('#options-gplus_max_image_size').val() ), pixelValues );
                
                // If the current value is not found, default to 3 or 30 mins.
                if( currentValue == -1 ){
                    currentValue = 3;
                }
                $('#gplus-image-size-slider').slider({
                    value: currentValue,
                    animate: true,
                    min: 0,
                    max: pixelValues.length - 1,
                    step: 1,
                    slide: function( event, ui ) {
                        $( ".slidedeck-content-source.source-gplusimages .gplus-image-size-slider-value" ).html( pixelValues[ ui.value ] + ' pixels' );
                        $('#options-gplus_max_image_size').val( pixelValues[ ui.value ] );
                    },
                    create: function( event, ui ){
                        // Assign the current value (on page load) to the label. 
                        $( ".slidedeck-content-source.source-gplusimages .gplus-image-size-slider-value" ).html( pixelValues[ currentValue ] + ' pixels' );
                    },
                    change: function(){
                        SlideDeckPreview.ajaxUpdate();
                    }
                });
            }
        }
    };
    
    var ajaxOptions = [
        "options[gplus_images_album]"
    ];
    for(var o in ajaxOptions){
        SlideDeckPreview.ajaxOptions.push(ajaxOptions[o]);
    }
    
    $(document).ready(function(){
        GplusImagesSource.initialize();
    });
})(jQuery);
