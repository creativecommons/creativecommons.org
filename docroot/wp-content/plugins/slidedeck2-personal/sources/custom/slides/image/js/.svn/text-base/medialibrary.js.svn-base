/**
 * Custom Media Library modal window interaction
 * 
 * @package SlideDeck
 * @author dtelepathy
 * @version 1.0.0
 */

var SlideDeckMediaLibrary = function(){
    // Class for single add buttons
    this.singleAddClass = "add-to-slidedeck-button";
    // ID for multi add button
    this.addAllId = "slidedeck-add-all-images";
    // ID for add-selected button
    this.addSelectedId = "slidedeck-add-selected-images";
    // Class for add multiple checkboxes
    this.addMultipleCheckboxClass = "slidedeck-add-multiple";
    // Buttons used by this Class
    this.buttons = {};
    // Current Add Media tab
    this.tab = "upload";
    
    // The current slide ID
    this.slideId = -1;
    
    // Image container on parent document
    this.imageContainer;
    // Content Source Flyout
    this.contentSource;
    
    // Initiate Class
    this.__construct();
};

(function($, window, undefined){
    // Class construct routine
    SlideDeckMediaLibrary.prototype.__construct = function(){
        var self = this;
        
        // This isn't a SlideDeck media upload, do NOT process further
        if(!parent.document.location.search.match(/page\=slidedeck2\.php/))
            return false;
        
        if(parent.jQuery('input[name="source[]"]').val() != "custom")
            return false;
        
        this.isBulkUpload = (document.location.search.match(/slidedeck_bulkupload\=1/) != undefined);
        
        $(document).ready(function(){
            self.initialize();
        });
    };
    
    // Add images to the SlideDeck - accepts a single ID or an array of IDs
    SlideDeckMediaLibrary.prototype.addImage = function(mediaId){
        var self = this;
        
        var queryString = 'action=slidedeck_slide_add_from_medialibrary';
            queryString += '&slide_id=' + this.slideId;
            queryString += '&media_id=' + mediaId;
            queryString += '&_wpnonce=' + _medialibrary_nonce;
        
        $.ajax({
            url: ajaxurl,
            data: queryString,
            dataType: "json",
            success: function(data){
                if(data.valid === true){
                    var $thumbnail = parent.jQuery('#slidedeck-custom-slide-editor-form').find('.sd-flyout-thumbnail');
                    var label = data.filename.length > 50 ? data.filename.substr(0,50) + "&hellip;" : data.filename;
                    $thumbnail.find('img').attr('src', data.media_meta.src[0]);
                    $thumbnail.find('.label').html(label);
                    $thumbnail.slideDown(500);
                    parent.jQuery('#sd-image-upload-container, #sd-image-upload, #slidedeck-custom-slide-editor-form .select-source').slideUp(500);
                    
                    // Close the Thickbox
                    parent.tb_remove();
                }
            }
        });
    };
    
    SlideDeckMediaLibrary.prototype.addImages = function(mediaIds){
        var self = this;
        
        var queryString = 'action=slidedeck_slide_bulk_upload';
            queryString += '&slidedeck=' + this.slidedeckId;
            for (var i=0; i < mediaIds.length; i++) {
                queryString += '&media[]=' + mediaIds[i];
            };
            queryString += '&_wpnonce=' + _medialibrary_nonce;
        
        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: queryString,
            dataType: "json",
            success: function(data){
                if(data.valid === true){
                    parent.SlideDeckSourceCustom.updateContentControl(data.html);
                    parent.SlideDeckSourceCustom.close();
                    parent.tb_remove();
                }
            }
        });
    };
    
    // Bind all submission events to appropriate buttons
    SlideDeckMediaLibrary.prototype.bind = function(){
        var self = this;
        
        $('body').delegate('.' + this.singleAddClass, 'click', function(event){
            event.preventDefault();
            
            var mediaId = $(this).val();
            // Bulk upload single image insertion choice
            if(isNaN(mediaId)){
                mediaId = $.data(this, 'mediaId');
                self.addImages([mediaId]);
            }
            // Single slide "From Media Library" image source choice
            else {
                self.addImage(mediaId);
            }
        });
        
        $('#' + this.addAllId).bind('click', function(event){
            event.preventDefault();
            
            var mediaIds = [];
            $('.' + self.singleAddClass).each(function(ind){
                var mediaId = $.data(this, 'mediaId');
                mediaIds.push(mediaId);
            });
            
            self.addImages(mediaIds);
        });
        
        $('#' + this.addSelectedId).bind('click', function(event){
            event.preventDefault();
            
            var mediaIds = [];
            $('.' + self.addMultipleCheckboxClass).each(function(ind){
                if(this.checked)
                    mediaIds.push(this.value);
            });
            
            self.addImages(mediaIds);
        });
    };
    
    // Route which tab initialize routine to run
    SlideDeckMediaLibrary.prototype.initialize = function(){
        // Get the current tab
        var location = document.location.search.match(/tab\=([a-zA-Z0-9\-_]+)/);
        if(location)
            this.tab = location[1];
        
        // Do actions for regular single file choice
        if(!this.isBulkUpload){
            this.initializeSingleChoice();
        }
        // Process as regular bulk upload
        else {
            this.initializeBulkUpload();
        }
        
        $('head').append('<style type="text/css">#gallery-settings,#save-all,#gallery-form table.widefat,#sort-buttons,#save,#filter>.subsubsub,.menu_order,.media-item table.describe > tbody tr[class] {display:none !important;}</style>');
        
        switch(this.tab){
            case "upload":
            case "type":
                this.tabUpload();
            break;
            
            case "gallery":
            case "library":
                this.tabLibrary();
            break;
        }
    };
    
    SlideDeckMediaLibrary.prototype.initializeSingleChoice = function(){
        this.imageContainer = parent.jQuery('#slidedeck-medialibrary-images');
        this.contentSource = parent.jQuery('#slidedeck-content-source');
        
        // This slide's ID
        this.slideId = document.location.search.match(/slide_id=([0-9]+)/)[1];
        $('#filter').append('<input id="slide_id" type="hidden" name="slide_id" value="' + this.slideId + '" />');
        
        // The parent post's ID
        this.slidedeckId = document.location.search.match(/post_id=([0-9]+)/)[1];

        // Add the SlideDeck UI type
        this.addSlideDeckUIField( 'slidedeck_custom' );
        
        // Hide the navigation tabs to prevent confusion
        $('#media-upload-header').remove();
    };
    
    SlideDeckMediaLibrary.prototype.initializeBulkUpload = function(){
        this.slidedeckId = document.location.search.match(/post_id=([0-9]+)/)[1];
        
        // Add the SlideDeck UI type
        this.addSlideDeckUIField( 'slidedeck_bulkupload' );
        
        // Remove the single URL tab
        $('#media-upload-header').find('#tab-type_url').remove();
    };
    
    // Adds the hidden field to keep track of the SlideDeck UI
    SlideDeckMediaLibrary.prototype.addSlideDeckUIField = function( keyName ){
        $('#slidedeck_ui').remove();
        $('#filter').append('<input id="slidedeck_ui" type="hidden" name="' + keyName + '" value="1" />');
    };
    
    // Method for replacing "Insert into Post" buttons with "Add to SlideDeck" buttons
    SlideDeckMediaLibrary.prototype.replaceButton = function(el){
        var $button = $(el);
        var buttonId = $button.attr('id');
        var mediaId = buttonId.match(/\[(\d+)\]/)[1];
        
        $button.replaceWith('<input type="hidden" id="' + buttonId + '" class="add-to-slidedeck-button" value="Add to SlideDeck" />');
        
        // Map the mediaId for the image as a data property for access later
        $.data(document.getElementById(buttonId), 'mediaId', mediaId);
    };
    
    // Media Library tab
    SlideDeckMediaLibrary.prototype.tabLibrary = function(){
        var self = this;
        var $mediaItems = $('#media-items');
        var $buttons = $mediaItems.find('input[type="submit"]');
        
        $buttons.each(function(ind){
            self.replaceButton(this);
        });
        
        $mediaItems.find('.toggle.describe-toggle-on').each(function(){
            var $this = $(this);
            var mediaId = $this.closest('.media-item').attr('id').split('-')[2];
            
            if(self.isBulkUpload){
                $this.before('<input type="checkbox" value="' + mediaId + '" class="' + self.addMultipleCheckboxClass + '" style="float:right;margin:12px 15px 0 5px;" />');
            } else {
                $this.before('<button value="' + mediaId + '" class="' + self.singleAddClass + '" style="float:right;margin:12px 15px 0 5px;">Add to SlideDeck</button>');
            }
        });
        
        if(this.isBulkUpload){
            $mediaItems.find('.media-item:first-child').before('<p style="margin:5px;text-align:right;"><label style="margin-right:8px;font-weight:bold;font-style:italic;">Select All to add to SlideDeck <input type="checkbox" id="slidedeck-add-multiple-select-all" style="margin-left:5px;" /></label></p>');
            $('#slidedeck-add-multiple-select-all').bind('click', function(event){
                var selectAll = this;
                
                $mediaItems.find('.' + self.addMultipleCheckboxClass).each(function(){
                    this.checked = selectAll.checked;
                });
            });
            
            $('.ml-submit').append('<a href="#" id="' + this.addSelectedId + '" class="button">Add Selected to SlideDeck</a>');
        }
        
        this.bind();
    };
    
    // Upload tab
    SlideDeckMediaLibrary.prototype.tabUpload = function(){
        $('.savebutton.ml-submit').append('<a href="#" id="' + this.addAllId + '" class="button" style="margin-left: 10px;">Add all to SlideDeck</a>');
        
        new this.Watcher('image-form');
        
        this.bind();
    };
    
    // Watcher Class for Upload tab - watches for addition of "Insert into Post" buttons to replace them
    SlideDeckMediaLibrary.prototype.Watcher = function(el){
        var self = this;
        this.el = document.getElementById(el);
        
        this.getButtons = function(){
            var inputs = self.el.getElementsByTagName('input'),
                count = 0,
                buttons = [];
                
            for(var i in inputs){
                if(inputs[i].type == "submit" && inputs[i].id.match(/send\[(\d+)\]/)){
                    buttons.push(inputs[i]);
                }
            }
            
            return buttons;
        };
        
        this.checker = function(){
            var buttons = self.getButtons();
            
            for(var b in buttons){
                SlideDeckMediaLibrary.prototype.replaceButton(buttons[b]);
            }
        };
        
        this.interval = setInterval(this.checker, 100);
    };
    
    new SlideDeckMediaLibrary();
})(jQuery, window, null);