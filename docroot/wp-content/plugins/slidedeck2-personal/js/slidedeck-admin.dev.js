/*!
 * SlideDeck 2 Pro for WordPress Admin JavaScript
 * 
 * More information on this project:
 * http://www.slidedeck.com/
 * 
 * Full Usage Documentation: http://www.slidedeck.com/usage-documentation 
 * 
 * @package SlideDeck
 * @subpackage SlideDeck 2 Pro for WordPress
 * 
 * @author dtelepathy
 */

/*!
Copyright 2012 digital-telepathy  (email : support@digital-telepathy.com)

This file is part of SlideDeck.

SlideDeck is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

SlideDeck is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with SlideDeck.  If not, see <http://www.gnu.org/licenses/>.
*/
var SlideDeckLensAdmin = {};

var SlideDeckPlugin = {
    namespace: "slidedeck"
};

var tooltipperOffset = {
    Y: -4,
    X: -11
};


/**
 * Update preview form pre-processing
 */
function updateSlideDeckPreview(el){
    var btn = document.getElementById('btn_slidedeck_preview_submit');
    
    var params_raw = btn.href.split('?')[1].split('&');
    var params = {};
    for(var p in params_raw){
        var param = params_raw[p].split('=');
        params[param[0]] = param[1];
    }
    
    params[el.id] = el.value;
    switch(el.id){
        case "preview_w":
            params['width'] = Math.max(630,params[el.id].match(/([0-9]+)/g)[0], 10) + 20;
        break;
        case "preview_h":
            params['height'] = parseInt(params[el.id].match(/([0-9]+)/g)[0], 10) + 200;
        break;
    }

    var href = btn.href.split('?')[0];
    var sep = "?";
    for(var k in params){
        href += sep + k + "=" + params[k];
        sep = "&";
    }

    btn.href = href;
}


/**
 * Watcher for when the preview modal is closed
 */
function closePreviewWatcher(){
    var timer;
    timer = setInterval(function(){
        if(document.getElementById('TB_closeWindowButton')){
            clearInterval(timer);
            jQuery('#TB_closeWindowButton, #TB_overlay').bind('mouseup', function(event){
                cleanUpSlideDecks();
            });
        }
    }, 20);
}


/**
 * Clean up any SlideDeck beacon "bugs" from the view
 */
function cleanUpSlideDecks(){
    jQuery('body > a').filter(function(){
        return (this.id.indexOf('SlideDeck_Bug') != -1);
    }).remove();
}


/**
 * Update ThickBox dimensions override
 */
var updateTBSize = function(){
    var tbWindow = jQuery('#TB_window'), tbTitle = jQuery('#TB_title'), width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width, adminbar_height = 0;
    var tbtitle_height = tbTitle.height();
    
    if(jQuery('body.admin-bar').length) adminbar_height = jQuery('#wpadminbar').height();
    
    if(tbWindow.size()){
        if(tbWindow.find('#slidedeck_preview_window').length){
            var ajaxContent = jQuery('#TB_ajaxContent');
            var slidedeckFrame = tbWindow.find('.slidedeck_frame');
            var slidedeckPreviewWindowWrapper = slidedeckFrame.closest('div:not(.slidedeck_frame)');
            
            var frame = {
                borderLeft: parseInt(slidedeckFrame.css('border-left-width'), 10),
                borderRight: parseInt(slidedeckFrame.css('border-right-width'), 10),
                paddingLeft: parseInt(slidedeckFrame.css('padding-left'), 10),
                paddingRight: parseInt(slidedeckFrame.css('padding-right'), 10)
            };
            for(var k in frame){
                frame[k] = isNaN(frame[k]) ? 0 : frame[k];
            }
            var previewWidth = parseInt(jQuery('#preview_w').val(), 10);
            
            W = previewWidth + frame.borderLeft + frame.borderRight + frame.paddingLeft + frame.paddingRight;
            H = ajaxContent.outerHeight();
            tbWindow.width(W + 40).height(H + tbtitle_height);
            ajaxContent.width(W + 10);
            slidedeckPreviewWindowWrapper.width(W);
        } else {
            tbWindow.width(W - 50).height(H - 45 - adminbar_height);
        }
        jQuery('#TB_iframeContent').width(W - 50).height(H - 75 - adminbar_height);
        tbWindow.css({
            'margin-left': '-' + parseInt((tbWindow.width() / 2), 10) + 'px'
        });
        if(typeof document.body.style.maxWidth != 'undefined'){
            tbWindow.css({
                'top': (20 + adminbar_height) + 'px',
                'margin-top': '0'
            });
        }
    }
    
    return jQuery('a.slide-background-upload').each(function(){
        var href = this.href;
        if(!href) return;
        href = href.replace(/&width=[0-9]+/g, '');
        href = href.replace(/&height=[0-9]+/g, '');
        this.href = href + '&width=' + (W - 80) + '&height=' + (H - 85 - adminbar_height);
    });
};
var tb_position = updateTBSize;


(function($){
    SlideDeckPlugin.DOMUtilities = {
        images: [],
        autoReplaceInputs: function(c){
            this.setContext(c).context.find('input.autoReplace, textarea.autoReplace, input.auto-replace, textarea.auto-replace').addClass('empty').focus(function(e){
                if(this.value == this.defaultValue){
                    this.value = "";
                }
                $(this).addClass('focus').removeClass('empty');
            }).blur(function(e){
                if($.trim(this.value) === ""){
                    this.value = this.defaultValue;
                    $(this).addClass('empty');
                }
                $(this).removeClass('focus');
            });
            return this;
        },
        setContext: function(c){
            if(typeof(c) != "undefined"){
                this.context = $(c);
            }
            return this;
        },
        initialize: function(c){
            if(typeof(c) == "undefined"){
                c = $(document.body);
            }
            this.setContext(c).autoReplaceInputs();
        }
    };
    

    SlideDeckPlugin.FirstSaveDialog = {
        cookiename: 'dont-show-first-save',
        elems: {},
        
        onComplete: function(){
            var self = this;
            
            // Bind don't show to checkbox
            $('#first-save-do-not-show-again').find('input').bind('click', function(event){
                if(this.checked){
                    $.cookie(self.cookiename, 1, {
                        expires: 365
                    });
                    
                    self.modal.close();
                }
            });
            
            // Bind Close Link
            $('#first-save-do-not-show-again .close').bind('click', function(event){
                event.preventDefault();
                self.modal.close();
            });
            
        },
        
        open: function(slidedeck_id){
            var self = this;
            
            // Don't show if the user checked to option
            if($.cookie(this.cookiename))
                return false;
            
            if(!this.modal)
                this.modal = new SimpleModal({
                    context: "firstsave",
                    onComplete: function(modal){
                        self.onComplete();
                    }
                });
            
            $.get(ajaxurl + "?action=slidedeck_first_save_dialog&slidedeck=" + slidedeck_id, function(data){
                self.modal.open(data);
            });
        }
    };
    
    SlideDeckPlugin.GplusPostsModal = {
        elems: {},
        
        close: function(){
            self.modal.close();
        },
        
        open: function(){
            var self = this;
            
            if(!this.modal)
                this.modal = new SimpleModal({
                    context: "gplus-how-to",
                    onComplete: function(){
                        var gplusSlidedeck = $('#gplus-posts-how-to').slidedeck({
                            keys: false,
                            scroll: false,
                            hideSpines: true
                        });
                        
                        var steps = $('#gplus-posts-how-to-steps'),
                            why = $('#gplus-how-to-why'),
                            whyLink = $('#gplus-how-to-why-link'),
                            next = $('#gplus-how-to-next'),
                            current = $('#gplus-how-to-step').find('.current');
                        var stepsLinks = steps.find('a');
                        
                        steps.delegate('a', 'click', function(event){
                            event.preventDefault();
                            
                            var $this = $.data(this, '$this');
                            if(!$this){
                                $this = $(this);
                                $.data(this, '$this');
                            }
                            
                            stepsLinks.removeClass('current');
                            $this.addClass('current');
                            
                            var step = parseInt(this.href.split('#')[1], 10);
                            
                            gplusSlidedeck.goTo(step);
                            current.text(step);
                            
                            if(gplusSlidedeck.current == gplusSlidedeck.slides.length){
                                next.text('Done');
                            } else {
                                next.text('Next');
                            }
                        });
                        
                        whyLink.bind('click', function(event){
                            event.preventDefault();
                            
                            if(whyLink.hasClass('open')){
                                whyLink.removeClass('open');
                                why.removeClass('open');
                            } else {
                                whyLink.addClass('open');
                                why.addClass('open');
                            }
                        });
                        
                        next.bind('click', function(event){
                            event.preventDefault();
                            
                            if(gplusSlidedeck.current == gplusSlidedeck.slides.length){
                                self.modal.close();
                            }
                            
                            gplusSlidedeck.next();
                            
                            current.text(gplusSlidedeck.current);
                            stepsLinks.removeClass('current');
                            stepsLinks.eq(gplusSlidedeck.current - 1).addClass('current');
                            
                            if(gplusSlidedeck.current == gplusSlidedeck.slides.length){
                                next.text('Finished!');
                            } else {
                                next.text('Next Step');
                            }
                        });
                    }
                });
            
            $.get(ajaxurl + "?action=slidedeck_gplus_posts_how_to_modal", function(data){
                self.modal.open(data);
            });
        }
    };
    

    SlideDeckPlugin.InsertModal = {
        elems: {},
        
        insertSlideDecks: function(){
            var datas = this.elems.form.serializeArray(),
                shortcodes = [],
                h = "";
            
            for(var d in datas){
                var data = datas[d];
                if(data.name == "slidedecks[]"){
                    shortcodes.push("[SlideDeck2 id=" + data.value + ( parent.slideDeck2iframeByDefault == true ? " iframe=1" : "" ) + "]");
                }
            }
            
            var ed, mce = typeof(parent.tinymce) != 'undefined', qt = typeof(parent.QTags) != 'undefined';
        
            if ( !parent.wpActiveEditor ) {
                if ( mce && parent.tinymce.activeEditor ) {
                    ed = parent.tinymce.activeEditor;
                    parent.wpActiveEditor = ed.id;
                } else if ( !qt ) {
                    return false;
                }
            } else if ( mce ) {
                if ( parent.tinymce.activeEditor && (parent.tinymce.activeEditor.id == 'mce_fullscreen' || parent.tinymce.activeEditor.id == 'wp_mce_fullscreen') )
                    ed = parent.tinymce.activeEditor;
                else
                    ed = parent.tinymce.get(parent.wpActiveEditor);
            }
            
            if ( ed && !ed.isHidden() ) {
                // restore caret position on IE
                if ( parent.tinymce.isIE && ed.windowManager.insertimagebookmark )
                    ed.selection.moveToBookmark(ed.windowManager.insertimagebookmark);
                
                for(var s in shortcodes){
                    h += '<p>' + shortcodes[s] + '</p>';
                }
                
                ed.execCommand('mceInsertContent', false, h);
            } else if ( qt ) {
                var sep = "";
                for( var s in shortcodes){
                    h += sep + shortcodes[s];
                    sep = "\n\n";
                }
                
                parent.QTags.insertContent(h);
            } else {
                parent.getElementById(parent.wpActiveEditor).value += h;
            }
        
            try{parent.tb_remove();}catch(e){}
        },
        
        updateOrderby: function(){
            var self = this;
            
            $.ajax({
                url: this.elems.form.attr('action'),
                data: this.elems.form.serialize(),
                success: function(data){
                    self.elems.tableContainer.html(data);
                }
            });
        },
        
        initialize: function(){
            var self = this;
            
            this.elems.form = $('#slidedeck-insert-iframe-form');
            
            // Fail silently if the modal doesn't exist
            if(this.elems.form.length < 1){
                return false;
            }
            
            this.elems.tableContainer = $('#slidedeck-insert-iframe-section-table .inner');
            this.elems.cancelLink = $('#slidedeck-insert-iframe-cancel-link');
            
            this.elems.cancelLink.bind('click', function(event){
                event.preventDefault();
                parent.tb_remove();
            });
            
            this.elems.form.delegate('select[name="orderby"]', 'change', function(event){
                self.updateOrderby();
            });
            
            this.elems.form.delegate('td', 'mouseenter mouseleave click', function(event){
                var $this = $.data(this, '$this'),
                    $row = $.data(this, '$row'),
                    $input = $.data(this,'$input'),
                    $siblings = $.data(this, '$siblings');
                
                // Get $(this) and cache
                if(!$this){
                    $this = $(this);
                    $.data(this, '$this', $this);
                }
                
                // Get this cell's row and cache for all sibling cells
                if(!$row){
                    $row = $this.closest('tr');
                    $siblings = $row.children('td');
                    $siblings.each(function(){
                        $.data(this, '$row', $row);
                        $.data(this, '$siblings', $siblings);
                    });
                }
                
                // Get this row's checkbox INPUT and cache for all sibling cells
                if(!$input){
                    $input = $row.find('input.slidedecks-insert');
                    $siblings.each(function(){
                        $.data(this, '$input', $input);
                    });
                }
                
                switch(event.type){
                    case "mouseenter":
                        $row.addClass('hover');
                    break;
                    
                    case "mouseleave":
                        $row.removeClass('hover');
                    break;
                    
                    case "click":
                        if(!$row.hasClass('selected')){
                            $row.addClass('selected');
                            $input[0].checked = true;
                        } else {
                            $input[0].checked = false;
                            $row.removeClass('selected');
                        }
                    break;
                }
            });
            
            this.elems.form.bind('submit', function(event){
                event.preventDefault();
                self.insertSlideDecks();
            });
        }
    };

    /**
     * Lens Management Interaction
     * 
     * Interaction scripting for copying lenses and deleting lenses
     */
    SlideDeckPlugin.LensManagement = {
        elems: {},
        
        deleteLens: function(el){
            var self = this;
            var $form = $(el);
            
            $.ajax({
                url: document.location.href,
                data: $form.serialize(),
                type: 'post',
                dataType: 'json',
                success: function(data){
                    if(data.error === true){
                        // Need permission, redirect to page to handle deletion
                        if(typeof(data.redirect) != 'undefined'){
                            document.location.href = data.redirect;
                            return false;
                        }
                        // Regular error
                        else {
                            alert(data.message);
                            return false;
                        }
                    }
                    
                    $form.closest('.lens').fadeOut(500, function(){
                        $form.closest('.lens').remove();
                        self.elems.lensList.masonry('reload');
                    });
                }
            });
        },
        
        initialize: function(){
            var self = this;
            
            this.elems.lensList = $('#slidedeck-lenses');
            
            if(this.elems.lensList.length < 1){
                return false;
            }
            
            this.elems.lenses = this.elems.lensList.find('.lens');
            
            this.elems.lensList.delegate('.actions form', 'submit', function(event){
                event.preventDefault();
                
                if(confirm("Are you sure you want to delete this lens? THIS CANNOT BE UNDONE.")){
                    self.deleteLens(this);
                }
            });
            
            $('#slidedeck_lens_management').delegate('a.disabled, .disabled a', 'click', function(event){
                event.preventDefault();
                return false;
            });
            
            this.elems.lensList.masonry({
                itemSelector: '.lens',
                columnWidth: 278,
                gutterWidth: 45,
                animationOptions: {
                    duration: 500
                },
                isAnimated: true
            });
        }
    };

    SlideDeckPlugin.LensManager = {
        elems: {},
        
        loadedScripts: {},
        
        select: function($elem){
            var self = this;
            var data = this.elems.form.serialize();
            
            this.elems.optionsSection.find('#slidedeck-section-lenses .lens').removeClass('selected');
            $elem.closest('.lens').addClass('selected');
            
            $.ajax({
                url: ajaxurl,
                type: "GET",
                data: data + "&action=slidedeck_change_lens",
                dataType: "json",
                success: function(data){
                    self.elems.optionsSection.find('.inner').html(data.options_html);
                    
                    // Re-bind the fancy toggles.
                    self.elems.optionsSection.find('.fancy').fancy();
                    
                    SlideDeckPlugin.OptionsNav.initialize();
                    
                    // SlideDeck Tooltipper
                    self.elems.optionsSection.find('.tooltip').tooltipper({
                        namespace: SlideDeckPlugin.slidedeck,
                        offsetY: tooltipperOffset.Y,
                        offsetX: tooltipperOffset.X
                    });
                    
                    // Color picker form elements
                    self.elems.optionsSection.find('input.color-picker').miniColors({
                        change: function(hex, rgb){
                            this.trigger('change');
                        }
                    });
                    
                    self.elems.optionsSection.find('input[type="text"]').each(function(){
                        $.data(this, 'previousValue', $(this).val());
                    });
                    
                    // Disable the covers UI if necessary
                    self.checkDisableCoversUI();
                    
                    // Trigger the custom lens change event
                    $('body').trigger('slidedeck:lens-change-update-choices');
                
                    SlideDeckPreview.ajaxUpdate();
                    
                    if(!self.loadedScripts[data.lens.slug]){
                        if(data.lens.admin_script_url){
                            $('head').append('<script type="text/javascript" src="' + data.lens.admin_script_url + '"></script>');
                            self.loadedScripts[data.lens.slug] = data.lens.admin_script_url;
                        }
                    }
                    
                    if(typeof(SlideDeckLensAdmin[data.lens.slug]) == 'function')
                        SlideDeckLensAdmin[data.lens.slug]();
                }
            });
        },
        
        checkDisableCoversUI: function(){
            var self = this;
            var currentSize = self.elems.optionsSection.find('#slidedeck-sizes :checked').val();
            
            if( currentSize == 'small' ){
                self.elems.optionsSection.find('#slidedeck-covers').append('<div class="disabled-mask"></div>');
            }else{
                self.elems.optionsSection.find('#slidedeck-covers .disabled-mask').remove();
            }
        },
        
        initialize: function(){
            var self = this;
            
            this.elems.optionsSection = $('#slidedeck-section-options');
            this.elems.lensesSection = this.elems.optionsSection.find('#slidedeck-section-lenses');
            
            // Fail silently if the lense choices section does not exist
            if(this.elems.lensesSection.length < 1){
                return false;
            }
            
            this.elems.lenses = this.elems.lensesSection.find('.lens');
            this.elems.form = $('#slidedeck-update-form');
            this.elems.sizes = $('#slidedeck-sizes');
            
            this.elems.optionsSection.delegate('#slidedeck-section-lenses input[type="radio"]', 'click', function(){
                // Load $(this) from cache if it exists
                var $this = $.data(this, '$this');
                // Cache $(this) for later use
                if(!$this){
                    $this = $(this);
                    $.data(this, '$this', $this);
                }
                
                self.select($this);
            });
            
            // Disable the covers UI if necessary
            self.checkDisableCoversUI();
            
            this.elems.lenses.each(function(ind){
                var $lens = self.elems.lenses.eq(ind);
                var slug = $lens.find('input[name="lens"]').val();
                
                if(typeof(SlideDeckLensAdmin[slug]) == 'function')
                    SlideDeckLensAdmin[slug]();
            });
        },
        
        updateChoices: function(){
            var self = this;
            // The current lens
            var selectedLens = this.elems.optionsSection.find('#slidedeck-section-lenses input[name="lens"]:checked').val();
            
            $.ajax({
                url: ajaxurl,
                data: {
                    action: "slidedeck_update_available_lenses",
                    slidedeck_id: $('#slidedeck_id').val(),
                    _wpnonce: $('[name="_wpnonce_update_available_lenses"]').val()
                },
                success: function(data){
                    self.elems.lensesSection = self.elems.optionsSection.find('#slidedeck-section-lenses');
                    self.elems.lensesSection.html(data);
                    
                    // The new lens after updating
                    var newSelectedLens = self.elems.optionsSection.find('#slidedeck-section-lenses input[name="lens"]:checked').val();
                    
                    // Trigger a lens change click if the lens was changed
                    if(newSelectedLens != selectedLens){
                        self.elems.lensesSection.find('input[name="lens"]:checked').click();
                    } 
                    // Otherwise, just update the preview
                    else {
                        SlideDeckPreview.ajaxUpdate();
                    }
                }
            });
        }
    };
    

    SlideDeckPlugin.OptionsNav = {
        elems: {},
        height: 0,
        
        deckLoaded: function(slidedeck){
            var self = this;
            
            self.elems.navigation = $('#slidedeck-options-groups .verticalSlideNav');
            
            self.elems.navigation.delegate('a', 'click', function(event){
                event.preventDefault();
                self.goTo(this.href.split('#')[1]);
                if( self.elems.navigation.find('a').index(this) === 0 ){
                    $('#slidedeck-options-groups dl.slidedeck').addClass('top');
                }else{
                    $('#slidedeck-options-groups dl.slidedeck').removeClass('top');
                }
            });
            
            // Initiate the resize
            self.goTo(1);
            if( $(self.elems.navigation.find('a')[0]).hasClass('nav_1') ){
                $('#slidedeck-options-groups dl.slidedeck').addClass('top');
            }
        },
        
        goTo: function(index){
            var optionIndex = index - 1;
            var $optionGroup = this.elems.optionsGroupsLists.eq(optionIndex);
            
            // Reset the .inner div to height: auto in case height was set by collapsible toggle
            $('#slidedeck-section-options > div.inner').css({'height': 'auto'});
            
            // Adjust the height of the option group area so only this group is shown
            this.elems.optionsGroups.stop().animate({
                'height': $optionGroup.outerHeight()
            }, this.elems.slidedeck.slidedeck().speed);
            
            this.elems.slidedeck.stop().animate({
                'height': $optionGroup.outerHeight() - 1
            }, this.elems.slidedeck.slidedeck().speed);
            
        },
        
        interfaces: function(){
            var self = this;
            
            for(var id in SlideDeckInterfaces){
                var properties = SlideDeckInterfaces[id];
                var $elem = $('#' + id);
                
                // Only process if the element still exists and is not a hidden field
                if($elem.length && !$elem.is('input[type="hidden"]')){
                    switch(properties.type){
                        case "thumbnails":
                            var $elem = $('#' + id);
                            $elem.wrap('<div class="slidedeck2-thumbnail-picker-wrapper"></div>');
                            
                            var thumbnailsHTML = "";
                            for(var i in properties.values){
                                thumbnailsHTML += '<span class="thumbnail' + (i == $elem.val() ? ' selected' : '') + '" data-value="' + i + '"><span style="background-image:url(' + slideDeck2URLPath + properties.values[i] + ');"></span></span>';
                            }
                            
                            $elem.closest('.slidedeck2-thumbnail-picker-wrapper').append('<span class="slidedeck2-thumbnail-picker">' + thumbnailsHTML + '</span>');
                            
                            $('.slidedeck2-thumbnail-picker').delegate('.thumbnail', 'click', function(event){
                                var $this = $.data(this, '$this'),
                                    $select = $.data(this, '$select'),
                                    $thumbnail = $.data(this, '$thumbnail');
                                
                                if(!$this){
                                    $this = $(this);
                                    $.data(this, '$this', $this);
                                }
                                
                                if(!$select){
                                    $select = $this.closest('.slidedeck2-thumbnail-picker-wrapper').find('select');
                                    $.data(this, '$select', $select);
                                }
                                
                                if(!$thumbnail){
                                    $thumbnail = $this.closest('.thumbnail');
                                    $.data(this, '$thumbnail', $thumbnail);
                                }
                                
                                var $thumbnails = $.data($select[0], '$thumbnails');
                                
                                if(!$thumbnails){
                                    $thumbnails = $this.closest('.slidedeck2-thumbnail-picker').find('.thumbnail').siblings('.thumbnail');
                                    $.data($select[0], '$thumbnails', $thumbnails);
                                }
                                
                                var value = $this.attr('data-value');
                                
                                $select.find('option').each(function(){
                                    if(this.value == value){
                                        this.selected = true;
                                    } else {
                                        this.selected = false;
                                    }
                                });
                                $select.val(value).trigger('change');
                                
                                $thumbnails.removeClass('selected');
                                $thumbnail.addClass('selected');
                            });
                        break;
                        
                        case "thumbnails-flyout":
                            var $elem = $('#' + id);
                            
                            $elem.wrap('<div id="slidedeck-2-thumbnail-picker-wrapper-'+ id + '" class="slidedeck2-thumbnail-picker-wrapper"><span class="thumbnail-select"><span class="selected"></span></span></div>');
                            var $wrapper = $( $elem.closest('.slidedeck2-thumbnail-picker-wrapper')[0] );
                            
                            var $thumbnailSelect = $( $wrapper.find('span.thumbnail-select')[0] );
                            var $selected = $( $wrapper.find('span.selected')[0] );
                            
                            var thumbnailsHTML = "";
                            for(var i in properties.values){
                                var url = properties.values[i];
                                if( !url.match( /^http(s)?\:\/\//) ) {
                                    url = slideDeck2URLPath + properties.values[i];
                                }
                                
                                thumbnailsHTML += '<span class="thumbnail' + (i == $elem.val() ? ' selected' : '') + '" data-value="' + i + '"><span style="background-image:url(' + url + ');"></span></span>';
                            }
                            
                            var $flyout = $('#slidedeck2-thumbnail-flyout-'+ id);
                            if($flyout.length < 1){
                                $('body').append('<div id="slidedeck2-thumbnail-flyout-'+ id +'" class="slidedeck2-thumbnail-flyout" style="display:none;"><span class="slidedeck2-thumbnail-picker clearfix"></span></div>');
                                $flyout = $('#slidedeck2-thumbnail-flyout-'+ id);
                            }
                            
                            $flyout.find('.slidedeck2-thumbnail-picker').html(thumbnailsHTML);
                            
                            var selectedThumbSrc = $('#slidedeck2-thumbnail-flyout-'+ id +' .thumbnail.selected span').css('background-image').match( /url\([\'|\"]?([^\'|\"]+)[\'|\"]?\)/ )[1];
                            $selected.append('<img src="'+ selectedThumbSrc +'" alt="" />');
                            
                            $('body').bind('click', function(event){
                                var $target = $(event.target);
                                if(($target.closest('.slidedeck2-thumbnail-flyout').length < 1) && ($target.closest('.thumbnail-select').length < 1)){
                                    $('.slidedeck2-thumbnail-flyout:visible').hide();
                                }
                            });
                            
                            $wrapper.delegate( '.selected', 'click', function(event){
                                var $this = $.data(this, '$this');
                                
                                if(!$this){
                                    $this = $(this);
                                    $.data(this, '$this', $this);
                                }
                                var offset = $this.offset();
                                var selectID = $this.closest('.slidedeck2-thumbnail-picker-wrapper').find('select').attr('id');
                                
                                var $dropdown = $('#slidedeck2-thumbnail-flyout-'+ selectID);
                                var offsetTop = offset.top,
                                    windowHeight = $(window).height(),
                                    scrollTop = $(window).scrollTop(),
                                    dropdownHeight = $dropdown.outerHeight();
                                
                                // Apply an "invert" class when the dropdown is too tall to fit going down
                                if(((dropdownHeight + offset.top) > (windowHeight + scrollTop)) && (windowHeight > dropdownHeight) && ((offset.top - scrollTop) > dropdownHeight)){
                                    $dropdown.addClass('invert');
                                    offsetTop = offsetTop - dropdownHeight - 2;
                                } else {
                                    $dropdown.removeClass('invert');
                                    offsetTop = offsetTop + $thumbnailSelect.outerHeight() + 2;
                                }
                                
                                if( $dropdown.is(':visible') ){
                                    $dropdown.hide();
                                }else{
                                    $dropdown.css({
                                        top: offsetTop,
                                        left: offset.left
                                    }).show();
                                }
                                
                            });
                            
                            $('.slidedeck2-thumbnail-flyout').delegate('.thumbnail', 'click', function(event){
                                var $this = $.data(this, '$this'),
                                    $select = $.data(this, '$select'),
                                    $thumbnail = $.data(this, '$thumbnail');
    
                                if(!$this){
                                    $this = $(this);
                                    $.data(this, '$this', $this);
                                }
                                
                                // Get the Select Element based of Wrapper ID
                                var selectID = $this.closest('.slidedeck2-thumbnail-flyout').attr('id').replace('slidedeck2-thumbnail-flyout-', '');
    
                                if(!$select){
                                    $select = $('#' + selectID );
                                    $.data(this, '$select', $select);
                                }
                                
                                if(!$thumbnail){
                                    $thumbnail = $this.closest('.thumbnail');
                                    $.data(this, '$thumbnail', $thumbnail);
                                }
                                
                                var $thumbnails = $.data($select[0], '$thumbnails');
                                
                                if(!$thumbnails){
                                    $thumbnails = $this.closest('.slidedeck2-thumbnail-picker').find('.thumbnail').siblings('.thumbnail');
                                    $.data($select[0], '$thumbnails', $thumbnails);
                                }
                                
                                var value = $this.attr('data-value');
                                
                                $select.find('option').each(function(){
                                    if(this.value == value){
                                        this.selected = true;
                                    } else {
                                        this.selected = false;
                                    }
                                });
                                $select.val(value).trigger('change');
                                
                                $thumbnails.removeClass('selected');
                                $thumbnail.addClass('selected');
                                
                                var selectedThumbSrc = $thumbnail.find('span').css('background-image').match( /url\([\'|\"]?([^\'|\"]+)[\'|\"]?\)/ )[1];
                                $('#slidedeck-2-thumbnail-picker-wrapper-'+ selectID +' .thumbnail-select .selected img').attr( 'src', selectedThumbSrc );
                                
                                $this.closest('.slidedeck2-thumbnail-flyout').hide();
                            });
                        break;
                        
                        case "slider":
                            var propertiesKey = {
                                animate: true,
                                min: 1,
                                max: 100,
                                orientation: 'horizontal',
                                range: false,
                                step: 1
                            };
                            var sliderOptions = {};
                            for(var key in propertiesKey){
                                if(properties[key]){
                                    sliderOptions[key] = properties[key];
                                } else {
                                    sliderOptions[key] = propertiesKey[key];
                                }
                            }
                            
                            $('#' + id).wrap('<div class="slidedeck2-slider-wrapper"></div>');
                            $('#' + id).before('<div id="' + id + '-slider" class="slidedeck2-slider"><span class="min">' + (properties.minLabel ? properties.minLabel : sliderOptions.min) + '</span><span class="max">' + (properties.maxLabel ? properties.maxLabel : sliderOptions.max) + '</span></div>');
                            
                            var $slider = $('#' + id + '-slider');
                            
                            if($elem.is('select')){
                                $slider.after('<span class="selected">' + $elem.find('option:selected').text() + '</span>');
                            }
                            
                            if(properties.marks){
                                var range = (sliderOptions.max - sliderOptions.min);
                                var totalMarks = (range / sliderOptions.step);
                                var marksHTML = "";
                                
                                for(var i = 0; i < totalMarks; i++){
                                    marksHTML+= '<span class="mark" style="width:' + (100 / totalMarks) + '%">' + (sliderOptions.min + (sliderOptions.step * (i + 1))) + '</span>';
                                }
                                
                                $slider.append('<span class="marks">' + marksHTML + '</span>');
                            }
                            
                            sliderOptions.value = $elem.val();
                            sliderOptions.slide = function(event, ui){
                                var $input = $.data(this, '$input');
                                
                                if(!$input){
                                    var $input = $('#' + ui.handle.parentNode.id.replace('-slider', ""));
                                    $.data(this, $input);
                                }
                                
                                if($input.is('input[type="text"]')){
                                    $input.val(ui.value);
                                } else if($input.is('select')){
                                    $input.find('option').each(function(){
                                        if(this.value == ui.value){
                                            this.selected = true;
                                        } else {
                                            this.selected = false;
                                        }
                                    });
                                    $(ui.handle.parentNode).next('.selected').text($input.find('option:selected').text());
                                }
                            };
                            sliderOptions.change = function(event, ui){
                                var $input = $.data(this, '$input');
                                
                                if(!$input){
                                    var $input = $('#' + ui.handle.parentNode.id.replace('-slider', ""));
                                    $.data(this, '$input', $input);
                                }
                                
                                if(SlideDeckInterfaces[$input.attr('id')].update){
                                    self.interfaceUpdate($input.val(), 'slider', SlideDeckInterfaces[$input.attr('id')].update);
                                }
                                
                                SlideDeckPreview.update($input[0], $input.val());
                            };
                            
                            $slider.slider(sliderOptions);
                            
                            $('#' + id).bind('keyup', function(event){
                                var elem = this;
                                if (this.sliderTimer)
                                    clearTimeout(elem.sliderTimer);
                                
                                // Set delay timer so a check isn't done on every single key stroke
                                this.sliderTimer = setTimeout(function(){
                                    $('#' + elem.id + '-slider').slider('value', elem.value);
                                }, 250 );
                                
                                return true;
                            });
                        break;
                    }
                }
            }
        },
        
        // Update other interface elements based off interaction with an interface
        interfaceUpdate: function(value, type, updateObj){
            switch(type){
                case "slider":
                    var $option = $('#options-' + updateObj.option);
                        $option.val(Math.min(parseInt($option.val(), 10), parseInt(value, 10)));
                    
                    var $slider = $('#options-' + updateObj.option + '-slider');
                    if($slider.length){
                        
                        $slider.slider('option', updateObj.value, value);
                        
                        if(updateObj.value == 'min'){
                            $slider.find('.min').text(value);
                        } else if(updateObj.value == 'max'){
                            $slider.find('.max').text(value);
                        }
                        
                        $slider.slider('value', parseInt($option.val(), 10));
                    }
                break;
            }
        },
        
        initialize: function(){
            var self = this;
            
            this.elems.optionsGroups = $('#slidedeck-options-groups');
            
            // Fail silently if there is no options element
            if(this.elems.optionsGroups.length < 1){
                return false;
            }
            
            this.elems.optionsSection = $('#slidedeck-section-options');
            
            // Process interfaces
            this.interfaces();
            
            // All the option groups in the vertical slides
            this.elems.optionsGroupsLists = this.elems.optionsGroups.find('.options-list');
            // The SlideDeck DOM element (not the extended, rendered SlideDeck object)
            this.elems.slidedeck = this.elems.optionsGroups.find('.slidedeck');
            
            // Apply a minimum height for all the list elements to be at least as tall as the navigation
            this.elems.optionsGroupsLists.css('min-height', this.elems.optionsGroupsLists.length * 84);
            
            // Determine a height for the SlideDeck DOM element based off the tallest option group
            this.elems.optionsGroupsLists.each(function(ind){
                self.height = Math.max(self.elems.optionsGroupsLists.eq(ind).outerHeight(), self.height);
            });
            
            // Define a height for the SlideDeck DOM element itself so it isn't too tall
            this.elems.slidedeck.height(this.height);
            
            // Load the SlideDeck
            this.elems.slidedeck.slidedeck({
                scroll: false,
                keys: false
            }).loaded(function(deck){
                self.deckLoaded(deck);
            }).vertical({
                scroll: false
            });
            
            this.elems.optionsGroups.delegate('#slidedeck-sizes input[type="radio"]', 'click', function(){
                SlideDeckPlugin.LensManager.checkDisableCoversUI();
            });
            
            $(window).resize(function(){
                self.resize();
            });
        },
        
        // Resize options group SlideDeck in real time
        resize: function(){
            var self = this;
            
            // Reset widths on slides and vertical slide containers
            this.elems.slidedeck.find('dd').add('.slidesVertical').width(this.elems.slidedeck.width());
            
            // Reset height
            this.height = 0;
            this.elems.optionsGroupsLists.each(function(ind){
                // Remove height attribute to get auto height
                this.style.height = "";
                self.height = Math.max(self.elems.optionsGroupsLists.eq(ind).outerHeight(), self.height);
            });
            
            // Reset heights
            this.elems.slidedeck.height(this.height).find('dd').height(this.height);
            
            this.elems.optionsGroups.stop().animate({
                height: this.elems.optionsGroupsLists.eq(this.elems.slidedeck.slidedeck().vertical().current).innerHeight() + "px"
            }, 500);
            
            this.elems.slidedeck.stop().animate({
                height: ( this.elems.optionsGroupsLists.eq(this.elems.slidedeck.slidedeck().vertical().current).innerHeight() - 1 ) + "px"
            }, 500);
        }
    };

    SlideDeckPlugin.SourceManager = {
        elems: {},
        
        slidedeckId: null,
        
        deleteSource: function(elem){
            var self = this;
            var source = $(elem).closest('.slidedeck-content-source').find('input[name="source[]"]').val();
            
            $.ajax({
                url: elem.href,
                data: "source=" + source + "&slidedeck=" + this.slidedeckId,
                type: "POST",
                success: function(data){
                    if(data != "false"){
                        self.elems.contentControl.html(data);
                        self.elems.contentControl.find('.fancy').fancy();
                        self.elems.contentControl.find('.tooltip').tooltipper({
                            namespace: SlideDeckPlugin.namespace,
                            offsetY: tooltipperOffset.Y,
                            offsetX: tooltipperOffset.X
                        });
                        SlideDeckPlugin.LensManager.updateChoices();
                    }
                }
            });
        },
        
        open: function(href){
            var self = this;
            
            $.ajax({
                url: href,
                type: "GET",
                success: function(data){
                    self.modal.open(data);
                }
            });
        },
        
        select: function(el){
            var self = this;
            var $form = $(el).closest('form');
            var data = $form.serializeArray();
            var action = "create";
            
            for(var i in data){
                if(data[i].name == "action"){
                    action = data[i].value;
                }
            }
            
            if(action == "create"){
                $form.submit();
            } else {
                $.ajax({
                    url: ajaxurl,
                    type: "GET",
                    data: this.elems.form.serialize() + "&" + $form.serialize(),
                    success: function(data){
                        if(data != "false"){
                            self.elems.contentControl.html(data);
                            self.elems.contentControl.find('.fancy').fancy();
                            self.elems.contentControl.find('.tooltip').tooltipper({
                                namespace: SlideDeckPlugin.namespace,
                                offsetY: tooltipperOffset.Y,
                                offsetX: tooltipperOffset.X
                            });
                            SlideDeckPlugin.LensManager.updateChoices();
                        }
                    }
                });
            }
        },
        
        initialize: function(){
            var self = this;
            
            this.elems.head = $('head');
            this.elems.body = $('body');
            this.elems.form = $('#slidedeck-update-form');
            this.elems.contentControl = $('#slidedeck-content-control');
            this.slidedeckId = $('#slidedeck_id').val();
            
            if(typeof(SimpleModal) != 'undefined'){
                this.modal = new SimpleModal({
                    context: "source",
                    onComplete: function(modal){
                        modal.elems.modal.find('input[type="radio"]').bind('click', function(){
                            var $label = $(this).closest('label');
                            $label.closest('.sources').find('label').removeClass('active');
                            $label.addClass('active');
                            
                            self.select(this);
                            
                            modal.close();
                        });
                    }
                });
            }
            
            $('body').delegate('a.slidedeck-source-modal', 'click', function(event){
                event.preventDefault();
                self.open(this.href);
            });
            
            // Cancel link in the Flyout
            $('#slidedeck-update-form').delegate('.delete.link', 'click', function(event){
                event.preventDefault();
                
                if(confirm("Are you sure you wish to delete this source?")){
                    self.deleteSource(this);
                }
            });
        }
    };
    
    SlideDeckPlugin.CoversEditor = {
        elems: {},
        importedFonts: {},
        
        onComplete: function(modal){
            var self = this;
            
            this.elems.modal = modal.elems.modal;
            
            this.elems.form = this.elems.modal.find('form');
            this.elems.frontOptions = this.elems.modal.find('.options-list.front-options');
            this.elems.backOptions = this.elems.modal.find('.options-list.back-options');
            this.elems.preview = this.elems.modal.find('#slidedeck-covers-preview');
            this.elems.frontCover = this.elems.preview.find('.slidedeck-cover-front');
            this.elems.frontBindingColor = this.elems.frontCover.find('.slidedeck-cover-binding .slidedeck-cover-color');
            this.elems.frontTitle = this.elems.frontCover.find('.slidedeck-cover-title');
            this.elems.frontBack = this.elems.frontCover.find('.slidedeck-cover-wrapper-back');
            this.elems.frontButtonAccent = this.elems.frontCover.find('.slidedeck-cover-open .slidedeck-cover-color');
            this.elems.curatedBy = this.elems.frontCover.find('.slidedeck-cover-curatedby');
            this.elems.backCover = this.elems.preview.find('.slidedeck-cover-back');
            this.elems.backBindingColor = this.elems.backCover.find('.slidedeck-cover-binding .slidedeck-cover-color');
            this.elems.backTitle = this.elems.preview.find('.slidedeck-cover-title');
            this.elems.backCopy = this.elems.preview.find('.slidedeck-cover-bodycopy');
            this.elems.backButton = this.elems.preview.find('.slidedeck-cover-button');
            this.elems.backCoverCTAColor = this.elems.preview.find('.slidedeck-cover-cta .slidedeck-cover-color');
            this.elems.backButtonText = this.elems.backButton.find('span.text');
            this.elems.backBack = this.elems.backCover.find('.slidedeck-cover-wrapper-back');
            this.elems.backButtonAccent = this.elems.backCover.find('.slidedeck-cover-restart .slidedeck-cover-color');
            this.elems.toggles = this.elems.modal.find('#slidedeck-covers-swap .toggle');
            
            // Fade out peekaboo mask manually since there is no window.onload() event to fire
            this.elems.modal.find('.slidedeck-cover-mask').animate({
                opacity: 0.8
            });
            
            // Fancy form elements
            this.elems.modal.find('input, select, textarea').fancy();
            
            // Color picker elements
            var colorPicker = this.elems.modal.find('.color-picker');
            colorPicker.miniColors({
                change: function(hex, rgb){
                    this.trigger('change');
                }
            });
            
            // Use the currently selected accent color for the SlideDeck if covers have not been saved yet
            if(!__hasSavedCovers){
                var slidedeckAccentColor = $('#options-accentColor');
                
                if(slidedeckAccentColor.val() !== ""){
                    colorPicker.val(slidedeckAccentColor.val());
                    colorPicker.trigger('keyup');
                }
            }
            
            // Cancel button close event
            this.elems.modal.find('.cancel-modal').bind('click', function(event){
                event.preventDefault();
                modal.close();
            });
            
            this.elems.form.bind('submit', function(event){
                event.preventDefault();
                
                $.ajax({
                    url: document.location.href.replace(document.location.search, ""),
                    data: self.elems.form.serialize(),
                    type: 'POST',
                    success: function(data){
                        modal.close();
                        SlideDeckPreview.ajaxUpdate();
                        __hasSavedCovers = true;
                    }
                });
            });
            
            // Back title keybind preview
            this.elems.modal.find('#back_title').bind('keyup', function(){
                self.elems.backTitle.html(this.value.replace(/\n/,"<br/>"));
            });
            
            // Back button label keybind preview
            this.elems.modal.find('#button_label').bind('keyup', function(){
                self.elems.backButtonText.html(this.value);
            });
            
            // Back button label keybind preview
            this.elems.modal.find('#button_url').bind('keyup', function(){
                self.elems.backButton.attr('href', this.value);
            });
            
            // Front title keybind preview
            this.elems.modal.find('#front_title').bind('keyup', function(){
                self.elems.frontTitle.html(this.value.replace(/\n/,"<br/>"));
            });
            
            // Front title font preview
            var titleFont = this.elems.modal.find('#title_font');
            titleFont.bind('change', function(){
                var selected = $(this).find('option:selected').val();
                var font = self.fonts[selected];
                
                if(font['import']){
                    if(!self.importedFonts[selected]){
                        $('head').append('<link href="' + font['import'] + '" rel="stylesheet" type="text/css" />');
                        self.importedFonts[selected] = true;
                    }
                }
                
                if(this.id == 'title_font'){
                    self.elems.frontTitle.css('font-family', font.stack);
                    if(font.weight) self.elems.frontTitle.css('font-weight', font.weight);
                    
                    self.elems.backTitle.css('font-family', font.stack);
                    if(font.weight) self.elems.backTitle.css('font-weight', font.weight);
                } else if(this.id == 'copy_font'){
                    self.elems.backCopy.css('font-family', font.stack);
                }
            }).trigger('change');
            
            // Use the currently selected accent color for the SlideDeck if covers have not been saved yet
            if(!__hasSavedCovers){
                var slidedeckTitleFont = $('#options-titleFont');
                
                if(slidedeckTitleFont.val() !== ""){
                    var selectedLabel = "";
                    titleFont.find('option').each(function(){
                        if(this.value == slidedeckTitleFont.val()){
                            this.selected = true;
                            selectedLabel = this.text;
                        } else {
                            this.selected = false;
                        }
                    });
                    
                    titleFont.closest('.fancy-select').find('.selected').text(selectedLabel);
                    titleFont.trigger('change');
                }
            }
            
            // Front accent color preview
            this.elems.modal.find('#accent_color').bind('change', function(){
                
                /**
                 * TODO: Refactor this so it's not so freakin ugly for cover specific updates...
                 * 
                 * Adjusts the gradients of the fosted glass cover and is largely
                 * duplicated code from the public.js file.
                 */
                var rgb = Raphael.getRGB(this.value);
                var frostedColor1 = Raphael.rgb2hsb(rgb.r,rgb.g,rgb.b);
                frostedColor1.s = frostedColor1.s * 0.2;
                frostedColor1.b = 1;
                var frostedColor2 = Raphael.rgb2hsb(rgb.r,rgb.g,rgb.b);
                frostedColor2.s = frostedColor2.s * 0.05;
                frostedColor2.b = 1;
                var glassGradient = '90-hsb('+ frostedColor1.h +','+ frostedColor1.s +','+ frostedColor1.b +')-hsb('+ frostedColor2.h +','+ frostedColor2.s +','+ frostedColor2.b +')';
                
                // Frosted Glass Updates
                if( self.elems.modal.find('.frosted-glass').data('slidedeck-frosted-cover-shine') ){
                    self.elems.modal.find('.frosted-glass').data('slidedeck-frosted-cover-shine').attr('fill', glassGradient);
                }
                if( self.elems.modal.find('.frosted-glass').data('slidedeck-frosted-cover-background') ){
                    self.elems.modal.find('.frosted-glass').data('slidedeck-frosted-cover-background').attr('fill', glassGradient);
                }
                // Frosted Glass (Back) Updates
                if( self.elems.modal.find('.frosted-glass-back').data('slidedeck-frosted-cover-back-shine') ){
                    self.elems.modal.find('.frosted-glass-back').data('slidedeck-frosted-cover-back-shine').attr('fill', glassGradient);
                }
                if( self.elems.modal.find('.frosted-glass-back').data('slidedeck-frosted-cover-back-background') ){
                    self.elems.modal.find('.frosted-glass-back').data('slidedeck-frosted-cover-back-background').attr('fill', glassGradient);
                }
                // End of Frosted Glass Updates.
                
                self.elems.frontBindingColor.css('background-color', this.value);
                self.elems.backBindingColor.css('background-color', this.value);
                self.elems.backCoverCTAColor.css('background-color', this.value);
                
                if(self.elems.frontButtonAccent.data('slidedeck-cover-shape')){
                    var data = self.elems.frontButtonAccent.data('slidedeck-cover-shape');
                    if( jQuery.isArray( data ) ){
                        for (var i=0; i < data.length; i++) {
                            data[i].attr('fill', this.value);
                        }
                    }else{
                        data.attr('fill', this.value);
                    }
                } else {
                    self.elems.frontButtonAccent.css('background-color', this.value);
                }
                
                if(self.elems.backButtonAccent.data('slidedeck-cover-shape')){
                    var data = self.elems.backButtonAccent.data('slidedeck-cover-shape');
                    if( jQuery.isArray( data ) ){
                        for (var i=0; i < data.length; i++) {
                            data[i].attr('fill', this.value);
                        }
                    }else{
                        data.attr('fill', this.value);
                    }
                } else {
                    self.elems.backButtonAccent.css('background-color', this.value);
                }
                
            }).trigger('change');
            
            // Show curator preview
            this.elems.modal.find('[name="show_curator"]').bind('click', function(){
                var value = this.value == 1 ? true : false;
                if(value)
                    self.elems.curatedBy.show();
                else
                    self.elems.curatedBy.hide();
            });
            
            // Peek preview
            this.elems.modal.find('[name="peek"]').bind('click', function(){
                var value = this.value == 1 ? true : false;
                if(value)
                    self.elems.preview.addClass('slidedeck-cover-peek');
                else
                    self.elems.preview.removeClass('slidedeck-cover-peek');
                
                self.elems.modal.find('#cover_style').trigger('change');
            });
            
            // Style preview
            this.elems.modal.find('#cover_style').bind('change', function(){
                var selected = $(this).find('option:selected').val();
                
                $(this).find('option').each(function(){
                    if(!this.selected){
                        self.elems.preview.removeClass("slidedeck-cover-style-" + this.value);
                    }
                });
                self.elems.preview.addClass("slidedeck-cover-style-" + selected);
                
                self.elems.preview.find('.slidedeck-cover-color').data('slidedeck-cover-shape', false).find('svg').remove();
                
                // Run post process functions if they exist
                if(SlideDeckCoverPostProcessFront[selected]){
                    SlideDeckCoverPostProcessFront[selected](self.elems.preview.find('.slidedeck-cover-nav-button'), self.elems.preview.hasClass('slidedeck-cover-peek'));
                }
                if(SlideDeckCoverPostProcessBack[selected]){
                    SlideDeckCoverPostProcessBack[selected](self.elems.preview.find('.slidedeck-cover-nav-button.slidedeck-cover-restart'), self.elems.preview.hasClass('slidedeck-cover-peek'));
                }
                
                // Hide the back cover after post processing it.
                if( self.elems.modal.find('.toggle-back.selected').length === 0 ) {
                    self.elems.preview.find('.slidedeck-cover-back').css({
                        visibility: 'hidden'
                    });
                }
                
                // Update accent color
                self.elems.modal.find('#accent_color').trigger('change');
                
                var variations = self.elems.modal.find('#variation');
                
                var currentlySelected = variations.find('option').filter(':selected').val();
                var options = "";
                for(var i in self.variations[selected]){
                    if( currentlySelected == i ){
                        options += '<option selected="selected" value="' + i + '">' + self.variations[selected][i] + '</option>';
                    }else{
                        options += '<option value="' + i + '">' + self.variations[selected][i] + '</option>';
                    }
                }
                
                if(!$.isEmptyObject(self.variations[selected])){
                    var variationListItem = self.elems.modal.find('#variation').closest('li');
                    variationListItem.slideDown(500);
                    variationListItem.find('.fancy-select, select.fancy').remove();
                    variationListItem.find('.inner').append( '<select class="fancy" id="variation" name="variation" style="">'+ options +'</select>' );
                    variationListItem.find('.fancy').fancy();
                    variationListItem.find('.fancy').trigger('change');
                } else {
                    self.elems.modal.find('#variation').closest('li').slideUp(500);
                }
                
            }).trigger('change');
            
            // Variation preview
            this.elems.modal.find('.options-list.global-options').delegate('#variation', 'change', function(){
                $(this).find('option').each(function(){
                    if(!this.selected){
                        self.elems.preview.removeClass("slidedeck-cover-" + this.value);
                    }
                });
                self.elems.preview.addClass("slidedeck-cover-" + $(this).find('option:selected').val());
            }).trigger('change');
            
            // Switch between front and back covers
            this.elems.modal.delegate('.toggle', 'click', function(event){
                event.preventDefault();
                
                var $this = $.data(this, '$this');
                
                if(!$this){
                    $this = $(this);
                    $.data(this, '$this', $this);
                }
                
                self.elems.toggles.removeClass('selected');
                $this.addClass('selected');
                
                self.toggle(this.href.split('#')[1]);
            });
        },
        
        open: function(){
            var self = this;
            
            var autoDraftID = this.elems.slidedeckPreview.attr('src').match(/slidedeck\=([\d]+)/)[1];
            this.elems.link.attr('href', this.elems.link.attr('href').replace(/slidedeck\=([\d]+)/, "slidedeck=" + autoDraftID));
            
            if(!this.modal)
                this.modal = new SimpleModal({
                    context: "covers",
                    onComplete: function(modal){
                        self.onComplete(modal);
                    }
                });
            
            $.get(this.elems.link.attr('href'), function(data){
                self.modal.open(data);
            });
        },
        
        toggle: function(side){
            switch(side){
                case "front":
                    this.elems.frontCover.css({ visibility: 'visible' });
                    this.elems.backCover.css({ visibility: 'hidden' });
                    this.elems.frontOptions.css('height', 'auto');
                    this.elems.backOptions.css('height', 0);
                break;
                case "back":
                    this.elems.frontCover.css({ visibility: 'hidden' });
                    this.elems.backCover.css({ visibility: 'visible' });
                    this.elems.frontOptions.css('height', 0);
                    this.elems.backOptions.css('height', 'auto');
                break;
            }
        },
        
        initialize: function(){
            var self = this;
            
            this.elems.link = $('#slidedeck-covers-modal-link');
            
            // Fail silently if the cover link does not exist
            if(this.elems.link.length < 1){
                return false;
            }
            
            this.elems.slidedeckPreview = $('#slidedeck-preview');
            this.elems.slidedeckOptions = $('#slidedeck-section-options');
            this.elems.showFrontCoverField = $('input[name="options[show-front-cover]"]');
            this.elems.showBackCoverField = $('input[name="options[show-back-cover]"]');
            
            this.elems.slidedeckOptions.delegate('#slidedeck-covers-modal-link', 'click', function(event){
                event.preventDefault();
                
                self.open();
            });
        }
    };
    
    SlideDeckPlugin.beforeUnload = {
        initialize: function(){
            var self = this;
            
            this.form = $('#slidedeck-update-form');
            
            // Fail silently if this isn't the edit/create page
            if(this.form.length < 1){
                return false;
            }
            
            this.originalSerialize = this.form.serialize();
            
            window.onbeforeunload = function(){
                if(self.originalSerialize != self.form.serialize()){
                    return "You have unsaved changes to this SlideDeck. Are you sure you want to leave without saving?";
                }
            };
            /*
             * VERY ANNOYING FOR DEVELOPMENT! :-D
             * just move it on up into here if you've got a lot of refreshing to do.
             * <-----
             * 
             * 
             */
            
            
            this.form.bind('submit', function(){
                window.onbeforeunload = null;
            });
        }
    };
    

    $(document).ready(function(){
        // Fancy Form Elements
        $('#slidedeck-insert-iframe-section-header').find('.fancy').fancy();
        $('#slidedeck-update-form, #slidedeck-option-wrapper').find('.fancy').fancy();
        
        // Utility functions
        SlideDeckPlugin.DOMUtilities.initialize();
        // Editor Options Navigation
        SlideDeckPlugin.OptionsNav.initialize();
        // Source Modal
        SlideDeckPlugin.SourceManager.initialize();
        // Lens Management Page
        SlideDeckPlugin.LensManagement.initialize();
        // Lens Picker and Interface Updater
        SlideDeckPlugin.LensManager.initialize();
        // Insert Modal
        SlideDeckPlugin.InsertModal.initialize();
        // Covers Editor Modal
        SlideDeckPlugin.CoversEditor.initialize();
        // Before unload
        SlideDeckPlugin.beforeUnload.initialize();
        
        if( $('#slidedeck-table').length === 0 ){
            if(typeof($.fn.tooltipper) == 'function'){
                // SlideDeck Tooltipper
                $('.tooltip').tooltipper({
                    namespace: SlideDeckPlugin.namespace,
                    offsetY: tooltipperOffset.Y,
                    offsetX: tooltipperOffset.X
                });
            }
        }else{
            if(typeof($.fn.tooltipper) == 'function'){
                // SlideDeck Tooltipper
                $('#slidedeck-table .tooltip').tooltipper({
                    speed: 0,
                    delay: 0,
                    namespace: SlideDeckPlugin.namespace,
                    offsetY: tooltipperOffset.Y - 10,
                    offsetX: tooltipperOffset.X
                });
            }
        }
        

        if(typeof($.fn.miniColors) == 'function'){
            // Color picker form elements
            $('input.color-picker').miniColors({
                change: function(hex, rgb){
                    this.trigger('change');
                }
            });
        }
        
        $('#slidedeck-table').delegate('.slidedeck-preview-link', 'click', function(event){
            event.preventDefault();
            
            var $this = $.data(this, '$this'),
                $iframe = $.data(this, '$iframe'),
                $td = $.data(this, '$td'),
                iframeSrc = this.href,
                width = parseInt(this.href.match(/\&width=(\d+)/)[1], 10);
                height = parseInt(this.href.match(/\&height=(\d+)/)[1], 10);
            
            // Load and cache $(this)
            if(!$this){
                $this = $(this);
                $.data(this, '$this', $this);
            }
            
            // Only animate if we aren't already
            if($this.hasClass('animating')){
                return false;
            }
            $this.addClass('animating');
            
            // Load and cache the related IFRAME
            if(!$iframe){
                var iframeId = $this.attr('data-for');
                $iframe = $('#' + iframeId);
                $.data(this, '$iframe', $iframe);
            }
            
            if($iframe.hasClass('open')){
                $iframe.removeClass('open').animate({
                    height: 0,
                    marginTop: 0,
                    marginBottom: 0
                }, 500, function(){
                    $iframe[0].src = "about:blank";
                    $this.removeClass('animating');
                });
            } else {
                $iframe.css('width', width).animate({
                    height: height,
                    marginTop: 20,
                    marginBottom: 20
                }, 500, function(){
                    $iframe[0].src = iframeSrc;
                    $this.removeClass('animating');
                }).addClass('open');
                
                if( SlideDeckAnonymousStats.optin == true ) {
                    var isCustom = $this.closest('.slidedeck-row').find('>img.icon').attr('src').indexOf("/custom/images/icon.png") != -1 ? true : false;
                    var img = new Image();
                    img.src = "http://trk.kissmetrics.com/e?_k=" + SlideDeckAnonymousStats.apikey + "&_p=" + SlideDeckAnonymousStats.hash + "&_n=" + escape( "Preview SlideDeck" ) + "&" + escape( "SlideDeck Type" ) + "=" + ( isCustom ? "custom" : "dynamic" );
                }
            }
        }).delegate('.slidedeck-getcode-link', 'click', function(event){
            event.preventDefault();
            
            var $this = $.data(this, '$this'),
                modal = $.data(this, 'modal');
                
            if(!$this){
                $this = $(this);
                $.data(this, '$this', $this);
            }
            
            if(!modal)
                modal = new SimpleModal({
                    context: "firstsave",
                    onComplete: function(modal){
                        SlideDeckPlugin.FirstSaveDialog.onComplete(modal);
                        $('#get-code-close').delegate('a.close', 'click', function(event){
                            event.preventDefault();
                            modal.close();
                        });
                    }
                });
            
            $.get($this.attr('href'), function(data){
                modal.open(data);
            });
        });
        
        // Gplus How-To Modal
        $('#slidedeck-update-form').delegate('#gplus-how-to', 'click', function(event){
            event.preventDefault();
            SlideDeckPlugin.GplusPostsModal.open();
        });
        
        // Source Configure Icon
        $('#slidedeck-update-form').delegate('.configure-source', 'click', function(event){
            event.preventDefault();
            var $thisContentSource = $(this).siblings('.slidedeck-content-source');
            $('.slidedeck-content-source').not($thisContentSource).addClass('hidden');
            $thisContentSource.toggleClass('hidden');
            
            /**
             * Here we handle the auto-hiding of the flyout.
             * 
             * There's some debate about this feature, so the main action will be
             * commented out until we make the opening/closing of the flyout non-destructive.
             * That is, it doesn't use Ajax to load every time. 
             */
            if( !$thisContentSource.hasClass('hidden') ) {
	            // Bind the click event to the body
            	$('body').bind( "click.hideFlyout", function( event ){
            		if( $(event.target).parents('div.slidedeck-content-source').length == 0 ){
            			if( !$(event.target).parent().hasClass('configure-source') ){
            				// TODO: Dave wants to make the flyouts not use Ajax when re-opening befiore we turn this back on.
	            			//$('.slidedeck-content-source .actions .cancel').trigger('click');
            			}
            		}
            	});
            }else{
            	// Unbind the click event on the body
            	$('body').unbind( "click.hideFlyout" );
            }
        });
        
        // Cancel link in the Flyout
        $('#slidedeck-update-form').delegate('.cancel.link', 'click', function(event){
            event.preventDefault();
            $(this).closest('.slidedeck-content-source').addClass('hidden');
            
            // Unbind the click event on the body
            $('body').unbind( "click.hideFlyout" );
        });
        
        // Flyout Cache Duration Slider
        if( $('.slidedeck-content-source .cache-slider').length ){
            // Map the minute values to an array of 10 items (0-9)
            var $this = $(this);
            var $container = $this.closest('.slidedeck-content-slider');
            var $feedCacheDuration = $container.find('[name="options[feedCacheDuration]"]');
            var minutesValues = [60, 300, 600, 900, 1800, 2700, 3600, 7200, 10800, 21600, 43200, 86400]; // Seconds
            var humanValues = ['1 minute', '5 minutes', '10 minutes', '15 minutes', '30 minutes', '45 minutes', '1 hour', '2 hours', '3 hours', '6 hours', '12 hours', '1 day'];
            var currentValue = $.inArray( parseInt( $feedCacheDuration.val(), 10 ), minutesValues );
            
            // If the current value is not found, default to 3 or 30 mins.
            if( currentValue == -1 ){
                currentValue = 3;
            }
            $('.slidedeck-content-source .cache-slider').slider({
                value: currentValue,
                animate: true,
                min: 0,
                max: 11,
                step: 1,
                slide: function( event, ui ) {
                    $container.find( ".cache-slider-value" ).html( humanValues[ ui.value ] );
                    $feedCacheDuration.val( minutesValues[ ui.value ] );
                },
                create: function( event, ui ){
                    // Assign the current value (on page load) to the label. 
                    $container.find( ".cache-slider-value" ).html( humanValues[ currentValue ] );
                }
            });
        }
        
        // Collapsible sections on editor view
        $('#slidedeck-update-form').delegate('.slidedeck-form-section.collapsible .hndl', 'click', function(event){
            var $this = $.data(this, '$this'),
                $inner = $.data(this, '$inner'),
                $section = $.data(this, '$section'),
                slidedeck_id = $.data(document.body, 'slidedeck_id');
            
            // Load and cache $(this)
            if(!$this){
                $this = $(this);
                $.data(this, '$this', $this);
            }
            
            // Do nothing if already animating
            if($this.hasClass('animating'))
                return false;
            
            $this.addClass('animating');
            
            // Load SlideDeck's ID if it isn't cached yet
            if(!slidedeck_id){
                slidedeck_id = $('#slidedeck_id').val();
                $.data(document.body, 'slidedeck_id', slidedeck_id);
            }
            
            // Load and cache the $('.inner') sibling
            if(!$inner){
                $inner = $this.closest('.hndl-container').next('.inner');
                $.data(this, '$inner', $inner);
            }
            
            // Load and cache the parent $('.slidedeck-form-section')
            if(!$section){
                $section = $this.closest('.slidedeck-form-section.collapsible');
                $.data(this, '$section', $section);
            }
            
            var section_id = $section.prop('id');
            var cookieName = 'hide--' + slidedeck_id + '--' + section_id;
            var cookieVal = null;
            var inner_height = $.data($inner[0], 'inner_height');
            var animate_height = inner_height;
            
            // Open
            if($section.hasClass('closed')){
                $section.removeClass('closed');
            }
            // Close
            else {
                $.data($inner[0], 'inner_height', $inner.height());
                $section.addClass('closed');
                animate_height = 0;
                cookieVal = 1;
            }
            
            $inner.animate({
                height: animate_height + 'px'
            }, 500, function(){
                $this.removeClass('animating');
            });
            // Set open state
            $.cookie(cookieName, cookieVal);
        }).find('.slidedeck-form-section.collapsible').each(function(){
            var $this = $(this);
            var $inner = $this.find('.inner');
            var $section = $inner.closest('.slidedeck-form-section.collapsible');
            
            var slidedeck_id = $.data(document.body, 'slidedeck_id');
            if(!slidedeck_id){
                slidedeck_id = $('#slidedeck_id').val();
                $.data(document.body, 'slidedeck_id', slidedeck_id);
            }
            
            // Cache the inner element
            $.data(this, '$inner', $inner);
            
            // Cache the fieldset element
            $.data(this, '$section', $section);
            
            // Log the opened height for use in the collapse action
            $.data($inner[0], 'inner_height', $inner.height());
            
            // Close if cookied for this SlideDeck and section
            if($.cookie('hide--' + slidedeck_id + '--' + this.id)){
                $this.addClass('closed');
                $inner.css('height', 0);
            }
        });
        
        // Switching the textures for the lens preview panel
        $('#preview-textures a').bind('click', function(event){
            event.preventDefault();
            
            var $this = $.data(this, '$this'),
                $chicklets = $.data(this, '$chicklets'),
                $stage = $.data(this, '$stage');
            
            if(!$this){
                $this = $(this);
                $.data(this, '$this', $this);
            }
            
            if(!$chicklets){
                $chicklets = $('#preview-textures').find('a');
                $.data(this, '$chicklets', $chicklets);
            }
            
            if(!$stage){
                $stage = $('#slidedeck-section-preview').find('.inner');
                $.data(this, '$stage', $stage);
            }
            
            $chicklets.removeClass('active');
            $('#preview-textures').find('li').removeClass('active');
            $this.addClass('active');
            $this.closest('li').addClass('active');
            
            // RegExp pattern to get background
            var pattern = new RegExp("background\=([a-zA-Z0-9\-_]+)");
            
            // The selected texture
            var texture = this.href.match(pattern)[1];
            
            // Loop through available textures and apply or remove classes appropriately
            $chicklets.each(function(){
                var thisTexture = this.href.match(pattern)[1];
                if(texture == thisTexture){
                    $stage.addClass('texture-' + thisTexture);
                } else {
                    $stage.removeClass('texture-' + thisTexture);
                }
            });
            
            // Update saved value on the server
            $.ajax({
                url: ajaxurl,
                data: this.href.split('?')[1],
                type: 'POST'
            });
        });
        
        
        // Editing interface auto replacement text style for name field
        if($('#form_action').val() == "create"){
            $('#titlewrap #title').css({
                color: '#999',
                fontStyle: 'italic'
            }).focus(function(event){
                this.style.color = "";
                this.style.fontStyle = "";
                if(this.value == this.defaultValue){
                    this.value = "";
                }
            });
        }
        
        $('#title-display').bind('click', function(event){
            event.preventDefault();
            $('#titlewrap').addClass('editing');
            $('#title').focus();
        });
        $('#title').bind('blur', function(event){
            if($('#form_action').val() != "create"){
                $('#titlewrap').removeClass('editing');
            }
        }).bind('keydown keyup', function(event){
            if(event.keyCode != 13 && event.keyCode != 27){
                var titleWidth = $('#title-display').find('.title').text(this.value).width();
                $('#title').css('min-width', titleWidth + 50);
            } else {
                $(this).blur();
            }
        });
        
        // License key Verification Ajax
        if( $('.license-key-text-field').length ){
            $('.slidedeck-license-key-wrapper').delegate('.verify-license-key.button', 'click', function(event){
                event.preventDefault();
                $.ajax({
                    url: ajaxurl + '?action=slidedeck_verify_license_key&verify_license_nonce=' + $('#verify_license_nonce').val() + '&key=' + $('.license-key-text-field').val(),
                    success: function( response ){
                        $('.license-key-verification-response').html( response );
                    }
                });
            });
            $('.slidedeck-license-key-wrapper .verify-license-key').click();
        }
        
        // Addon License Verification
        if( $('.license-key-text-field').length ){
            $('.slidedeck-license-key-wrapper').delegate('.verify-license-key.button', 'click', function(event){
                event.preventDefault();
                $.ajax({
                    url: ajaxurl + '?cachebreaker=' + Math.floor( Math.random() * 100000 ) + '&' + $(this).parents('form').serialize(),
                    success: function( response ){
                        $('.addon-verification-response').html( response )
                            .find('a').each(function(){
                                if( SlideDeckAnonymousStats.optin == true ) {
                                    if( this.href.match(/dtelepathy\.com/) ) {
                                        this.search += "&kmi=" + SlideDeckAnonymousStats.hash;
                                    }
                                }
                            });
                    }
                });
            });
            $('.slidedeck-license-key-wrapper .verify-license-key').click();
        }
        
        // Delete SlideDeck button binding for SlideDeck management view
        $('#slidedeck-table').delegate('form.delete-slidedeck', 'submit.' + SlideDeckPlugin.namespace, function(event){
            event.preventDefault();
            var $this = $(this);
            var row = $this.closest('li');
            var innerDiv = row.parents('.inner');
            var preview = row.next('div.slidedeck-preview-wrapper');
            var list = $this.closest('ul');
            
            if(confirm("Are you sure you want to delete this SlideDeck?\nThis CANNOT be undone.")){
                $.ajax({
                    url: document.location.href,
                    type: this.method,
                    data: $this.serialize(),
                    success: function(){
                        row.fadeOut(500,function(){
                            row.remove();
                            preview.remove();
                            if( list.find('li').length < 1 ){
                                innerDiv.remove();
                                $('#no-decks-placeholder').show();
                            }
                        });
                    }
                });
            }
        });
        
        // Duplicate SlideDeck button binding for SlideDeck management view
        $('#slidedeck-table').delegate('form.duplicate-slidedeck', 'submit.' + SlideDeckPlugin.namespace, function(event){
            event.preventDefault();
            var $this = $(this);
            var $table = $('#slidedeck-table').find('.float-wrapper .left');
            
            $this.closest('.slidedeck-duplicate').addClass('loading');
            
            $.ajax({
                url: document.location.href,
                type: this.method,
                data: $this.serialize(),
                success: function(data){
                    if(data != "false"){
                        $table.html(data);
                        $('.tooltipper.slidedeck').remove();
                        $table.find('.tooltip').tooltipper({
                            namespace: SlideDeckPlugin.namespace,
                            offsetY: tooltipperOffset.Y,
                            offsetX: tooltipperOffset.X
                        });
                    }
                }
            });
        });
        
        // Delete link in SlideDeck editing interface
        $('#delete-slidedeck').bind('click', function(event){
            event.preventDefault();
            var slidedeck_id = this.href.match(/slidedeck(\=|\%3D)([\d]+)/)[2];
            var _wpnonce = this.href.match(/_wpnonce(\=|\%3D)([a-zA-Z0-9]+)/)[2];
            
            if(confirm("Are you sure you want to delete this SlideDeck?\nThis CANNOT be undone.")){
                $.ajax({
                    url: document.location.href.replace(document.location.search, ""),
                    type: "POST",
                    data: "slidedeck=" + slidedeck_id + "&_wpnonce=" + _wpnonce + "&redirect=1",
                    success: function(data){
                        document.location.href = data;
                    }
                });
            }
        });
        
        // Preview modal dimension form pre-processing
        $('#template_snippet_w, #template_snippet_h').bind('keyup.' + SlideDeckPlugin.namespace, function(event){
            var element = this;
            if (this.timer) {
                clearTimeout(element.timer);
            }
            this.timer = setTimeout(function(){
                var w = $('#template_snippet_w').val(),
                    h = $('#template_snippet_h').val(),
                    slidedeck_id = $('#slidedeck_id').val();
                
                var snippet = "<" + "?php slidedeck( " + slidedeck_id + ", array( 'width' => '" + w + "', 'height' => '" + h + "' ) ); ?" + ">";
                
                $('#slidedeck-template-snippet').val(snippet);
            },100);
            return true;
        });
        
        $('#slidedeck-template-snippet').focus(function(){
            this.select();
        });
        
        updateTBSize();
        
        var expiredFor = Math.round( new Date().getTime() / 1000 ) - SlideDeckLicenseExpiredOn;
        if( expiredFor < 1209600 ) {
            // Refresh the upgrade/renew button with fresh content
            // Only if the last saved expiration date is expired.
            if( $('div.upgrade-button-cta').length ) {
                if( SlideDeckLicenseExpired ) {
                    $.ajax({
                        url: ajaxurl,
                        data: "action=slidedeck_check_license_expiry&_license_status_nonce=" + $('div.upgrade-button-cta').data('nonce') + "&context=" + $('div.upgrade-button-cta').data('context'),
                        type: 'GET',
                        complete: function(data){
                            $('div.upgrade-button-cta').replaceWith( data.responseText );
                        }
                    });
                }
            }
        }


        // SlideDeck.com blog RSS feed AJAX update
        if($('#slidedeck-blog-rss-feed').length){
            $.ajax({
                url: ajaxurl,
                data: "action=slidedeck2_blog_feed",
                type: 'GET',
                complete: function(data){
                    var response = data.responseText;
                    var feedBlock = $('#slidedeck-blog-rss-feed');
                    
                    if(response != "false"){
                        feedBlock.html(data.responseText);
                    } else {
                        feedBlock.text("Unable to connect to feed!");
                    }
                }
            });
        }
        
        // Tweet SlideDeck on manage page AJAX update
        if($('#slidedeck-latest-tweets').length){
            $.ajax({
                url: ajaxurl,
                data: "action=slidedeck2_tweet_feed",
                type: 'GET',
                complete: function(data){
                    var response = data.responseText;
                    var responseBlock = $('#slidedeck-latest-tweets');
                    
                    if(response != "false"){
                        responseBlock.html(data.responseText);
                        
                        // Create tha deck!
                        var tweetSlideDeck = responseBlock.find('.slidedeck').slidedeck({
                            hideSpines: true,
                            keys: false,
                            scroll: false,
                            autoPlay: true,
                            cycle: true
                        });
                        
                        // Bind Prev/Next
                        responseBlock.find('a.navigation').click(function(event){
                            event.preventDefault();
                            tweetSlideDeck.pauseAutoPlay = true;
                            if( this.href.match(/next/) ){
                                tweetSlideDeck.next();
                            }else{
                                tweetSlideDeck.prev();
                            }
                        });
                        
                        // Add dot navigation
                        var slideCount = tweetSlideDeck.slides.length;
                        var navWrapper = responseBlock.find('.nav-wrapper');
                        var i = 1;
                        while(i <= slideCount && i <= 10){
                            jQuery('<span class="nav-dot">&bull;</span>').appendTo(navWrapper);
                            i++;
                        }
                        
                        // Bind click to the nav dots
                        navWrapper.find('.nav-dot').click(function(){
                            var $self = jQuery(this);
                            navWrapper.find('.nav-dot').removeClass('active');
                            $self.addClass('active');
                            tweetSlideDeck.pauseAutoPlay = true;
                            tweetSlideDeck.goTo($self.index()+1);
                        });
                        
                        // Add the before callback to update the dot nav
                        tweetSlideDeck.options.before = function( deck ){
                            responseBlock.find('.nav-dot').removeClass('active');
                            responseBlock.find('.nav-dot').eq(deck.current-1).addClass('active');
                        };
                        
                        // Do the initial dot nav update 
                        tweetSlideDeck.loaded(function( deck ){
                            responseBlock.find('.nav-dot').eq(deck.current-1).addClass('active');
                        });
                        
                        // Center the dot nav
                        responseBlock.find('.nav-wrapper').css({
                            marginLeft: '-' + Math.round( responseBlock.find('.nav-wrapper').outerWidth() / 2 ) + 'px'
                        });
                        
                        
                    } else {
                        responseBlock.text("Unable to connect to Twitter!");
                    }
                }
            });
        }
        
        
        if($('#slidedeck-sizes').length){
            $('#slidedeck-section-options').delegate('#slidedeck-sizes input[type="radio"]', 'click', function(event){
                if(this.value == "custom"){
                    $('#slidedeck-custom-dimensions').addClass('selected').animate({
                        height: 32,
                        opacity: 1
                    }, 500, function(){
                        SlideDeckPlugin.OptionsNav.resize();
                    }).find('input').each(function(){
                        this.disabled = false;
                    });
                } else {
                    $('#slidedeck-custom-dimensions').removeClass('selected').animate({
                        height: 0,
                        opacity: 0
                    }, 500, function(){
                        SlideDeckPlugin.OptionsNav.resize();
                    }).find('input').each(function(){
                        this.disabled = true;
                    });
                }
            });
        }
        
        $('#slidedeck-table-sort-select').bind('change', function(event){
            var $this = $.data(this, '$this'),
                $form = $.data(this, '$form'),
                $table = $.data(this, '$table');
            
            if(!$this){
                $this = $(this);
                $.data(this, '$this', $this);
            }
            
            if(!$form){
                $form = $('#slidedeck-table-sort');
                $.data(this, '$form', $form);
            }
            
            if(!$table){
                $table = $('#slidedeck-table').find('.float-wrapper .left');
                $.data(this, '$table', $table);
            }
            
            $.ajax({
                url: ajaxurl,
                type: "get",
                data: $form.serialize(),
                success: function(data){
                    if(data != "false"){
                        $table.html(data);
                        $table.find('.tooltip').tooltipper({
                            namespace: SlideDeckPlugin.namespace,
                            offsetY: tooltipperOffset.Y,
                            offsetX: tooltipperOffset.X
                        });
                    }
                }
            });
        });
        
        // Bind the "Need Support?" Link
        $('.wp-submenu a[href$="slidedeck2-lite.php/need-support"]').addClass('upgrade-modal').attr('rel', 'need-support');
        
        // Modals for the upsells
        if( $('.upgrade-modal').length ){
            var context = 'upsell';
            
            // Generic Upgrade modal.
            SlideDeckPlugin.UpgradeModal = {
                addForClass: function( theClass ){
                    // Remove the previous pattern
                    $('#slidedeck-' + context + '-simplemodal')[0].className = $('#slidedeck-' + context + '-simplemodal')[0].className.replace(/for\-[a-z]+\s?/, '');
                    // Add the new class
                    $('#slidedeck-' + context + '-simplemodal').addClass( 'for-' + theClass );
                },
                
                open: function(data){
                    var self = this;
                    
                    if(!this.modal){
                        this.modal = new SimpleModal({
                            context: context
                        });
                    }
                    this.modal.open(data);
                }
            };
            
            $('#wpwrap').delegate( '.upgrade-modal', 'click', function(event){
                event.preventDefault();
                var slug = $(this).attr('rel');
                 
                $.get(ajaxurl + "?action=slidedeck_upsell_modal_content&feature=" + slug , function(data){
                    SlideDeckPlugin.UpgradeModal.open(data);
                    SlideDeckPlugin.UpgradeModal.addForClass( slug );
                    
                    // Make sure the <a> tags do nothing in the lenses upgrade modal
                     $('#slidedeck-upsell-simplemodal a.lens.placeholder').bind( 'click', function(event){
                        event.preventDefault();
                     });
                });
            });
        }
        
        if( !SlideDeckAnonymousStats.opted ) {
            SlideDeckPlugin.anonymousStatsOptinModal = new SimpleModal({
                context: "anonymous-stats",
                onComplete: function(modal){
                    modal.elems.modal.on('submit', 'form', function(event){
                        event.preventDefault();
                        
                        $.ajax({
                            type: this.getAttribute('method'),
                            url: this.getAttribute('action'),
                            data: $(this).serialize()
                        });
                        
                        SlideDeckPlugin.anonymousStatsOptinModal.close();
                    }).on('click', 'input[type="radio"]', function(event){
                        $(this).closest('form').submit();
                    });
                }
            });
        
            $.get(ajaxurl + "?action=slidedeck_anonymous_stats_optin", function(data){
                SlideDeckPlugin.anonymousStatsOptinModal.open(data);
            });
        }

    }); // End of DOM Ready
    
    
    // thickbox settings
    $(window).resize(function() {
        updateTBSize();
    });
})(jQuery);

/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * Create a cookie with the given name and value and other optional parameters.
 *
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Set the value of a cookie.
 * @example $.cookie('the_cookie', 'the_value', { expires: 7, path: '/', domain: 'jquery.com', secure: true });
 * @desc Create a cookie with all available options.
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Create a session cookie.
 * @example $.cookie('the_cookie', null);
 * @desc Delete a cookie by passing null as value. Keep in mind that you have to use the same path and domain
 *       used when the cookie was set.
 *
 * @param String name The name of the cookie.
 * @param String value The value of the cookie.
 * @param Object options An object literal containing key/value pairs to provide optional cookie attributes.
 * @option Number|Date expires Either an integer specifying the expiration date from now on in days or a Date object.
 *                             If a negative value is specified (e.g. a date in the past), the cookie will be deleted.
 *                             If set to null or omitted, the cookie will be a session cookie and will not be retained
 *                             when the the browser exits.
 * @option String path The value of the path atribute of the cookie (default: path of page that created the cookie).
 * @option String domain The value of the domain attribute of the cookie (default: domain of page that created the cookie).
 * @option Boolean secure If true, the secure attribute of the cookie will be set and the cookie transmission will
 *                        require a secure protocol (like HTTPS).
 * @type undefined
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */

/**
 * Get the value of a cookie with the given name.
 *
 * @example $.cookie('the_cookie');
 * @desc Get the value of a cookie.
 *
 * @param String name The name of the cookie.
 * @return The value of the cookie.
 * @type String
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie !== '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};
