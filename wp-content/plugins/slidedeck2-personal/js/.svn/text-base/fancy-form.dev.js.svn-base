/*!
 * jQuery Fancy Form plugin
 * 
 * Spice up your form with unique, intuitive form interactions
 * 
 * @author dtelepathy
 * @version 1.0.1
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
var FancyForm;
(function($){
    FancyForm = function(elems){
        var $elems = $(elems);
        
        this.fancy($elems);
    };
    
    FancyForm.prototype.fancyCheckbox = function($elem){
        $elem.wrap('<label class="fancy-checkbox" />');
        var $wrapper = $elem.closest('.fancy-checkbox');
        
        $wrapper.append('<span class="on"><span>On</span></span><span class="off"><span>Off</span></span>')
               .addClass($elem[0].checked ? 'on' : 'off')
               .delegate( 'input[type="checkbox"]', 'click',function(event){
                   if(this.checked)
                       $wrapper.removeClass('off').addClass('on');
                   else
                       $wrapper.removeClass('on').addClass('off');
               });
    };
    
    FancyForm.prototype.fancyRadios = function($elems){
        var $labels = $elems.closest('label');
        
        $labels.wrapAll('<span class="fancy-radios" />');
        
        $labels.filter(':first').addClass('first');
        $labels.filter(':last').addClass('last');
        
        $elems.each(function(i){
            var $elem = $elems.eq(i);
            var $label = $labels.eq(i);
            
            $label.delegate('input[type="radio"]', 'click', function(event){
                if(this.checked){
                    $labels.removeClass('on');
                    $label.addClass('on');
                }
            }).addClass('radio-' + i);
            
            if(this.checked)
                $label.addClass('on');
        });
    };
    
    FancyForm.prototype.fancySelect = function($elem){
        var self = this;
        
        $elem.wrap('<span class="fancy-select" />');
        var $wrapper = $elem.closest('.fancy-select');
        var $options = $elem.find('option');
        
        $wrapper.width($elem.outerWidth())
                .bind('click', function(event){
                    self.hideOptions();
                    self.showOptions($elem);
                });
        
        // Currently selected option
        $wrapper.append('<span class="selected" />');
        var $selected = $wrapper.find('.selected');
        
        // Update currently selected display
        $selected.text($options.filter(':selected').text());
        
        $elem.bind('change', function(){
            $elem.closest('.fancy-select').find('.selected').text($elem.find('option:selected').text());
        });
    };
    
    FancyForm.prototype.showOptions = function($elem){
        var self = this;
        var $options = $elem.find('option');
        
        $('body').bind('click.fancySelect', function(event){
            var $target = $(event.target);
            if(($target.closest('#fancyform-options-dropdown').length < 1) && ($target.closest('.fancy-select').length < 1)){
                self.hideOptions();
            }
        });
        
        this.dropdown = $('#fancyform-options-dropdown');
        
        // Create the drop-down if it doesn't exist yet
        if(this.dropdown.length < 1) {
            $('body').append('<div id="fancyform-options-dropdown"><span class="options"></span></div>');
            this.dropdown = $('#fancyform-options-dropdown');
            
            this.dropdown.delegate('.option', 'click', function(){
                var $select = $.data(self.dropdown[0], 'dom-select');
                var $options = $select.find('option');
                var $value = $(this).attr('data-value');
                var old_value = "";
                
                $options.each(function(i){
                    if(this.selected)
                        old_value = this.value;
                    
                    this.selected = (this.value == $value);
                    
                    if(this.selected == true)
                        $select.siblings('.selected').text(this.text);
                });
                
                self.hideOptions();
                
                // Trigger the "change" event only if the selection was changed
                if(old_value != $value)
                    $select.trigger('change');
            }).bind('click', function(){
                self.hideOptions();
            });
        }
        
        // Create a reference to the actual SELECT element to update
        $.data(this.dropdown[0], 'dom-select', $elem);
        
        // Clean out any possible left over options from other drop-downs
        this.dropdown.find('span.option').remove();
        
        // Build the options for this drop-down
        var optionsHTML = "";
        $options.each(function(i){
            optionsHTML += '<span class="option' + (i == $options.length - 1 ? ' last' : '') + (this.selected == true ? ' selected' : '') + '" data-value="' + this.value + '">' + this.text + '</span>';
        });
        var $dropdown_options = this.dropdown.find('.options');
        $dropdown_options.append(optionsHTML);
        
        this.dropdown.css({
            left: "-999em"
        }).show();
        
        var offset = $elem.closest('.fancy-select').offset(),
            windowHeight = $(window).height(),
            scrollTop = $(window).scrollTop(),
            dropdownHeight = this.dropdown.outerHeight(),
            optionHeight = $dropdown_options.find('.option').outerHeight();
        
        // Apply an "invert" class when the dropdown is too tall to fit going down
        if(((dropdownHeight + offset.top) > (windowHeight + scrollTop)) && (windowHeight > dropdownHeight) && ((offset.top - scrollTop) > dropdownHeight)){
            this.dropdown.addClass('invert');
            offset.top = offset.top - dropdownHeight;
        } else {
            this.dropdown.removeClass('invert');
        }
        
        this.dropdown.css({
            top: offset.top,
            left: offset.left,
            'min-width': $elem.closest('.fancy-select').outerWidth()
        });
        $dropdown_options.css('max-height', optionHeight * 10);
    };
    FancyForm.prototype.hideOptions = function(){
        if(this.dropdown){
            this.dropdown.find('span.option').remove();
            this.dropdown.hide();
        }
        
        $('body').unbind('click.fancySelect');
    };
    
    FancyForm.prototype.fancyText = function($elem) {
        $elem.addClass('fancy-text');
    };
    
    FancyForm.prototype.fancy = function($elems) {
        var self = this;
        // Flags for group processing
        var groups = {};
        
        $elems.each(function(i){
            var $elem = $elems.eq(i);
            
            if($elem.is('input')){
                switch($elem.prop('type')){
                    case "radio":
                        // Only process if the group of radios hasn't been processed yet
                        if(!groups[$elem.prop('name')]){
                            // Process the entire group at once filtered down to the radio group
                            self.fancyRadios($elems.filter('[name="' + $elem.prop('name') + '"]'));
                            // Flag this group as processed so it doesn't get processed more than once
                            groups[$elem.prop('name')] = true;
                        }
                    break;
                    
                    case "checkbox":
                        self.fancyCheckbox($elem);
                    break;
                    
                    case "text":
                    default:
                        self.fancyText($elem);
                    break;
                }
            } else if($elem.is('select')){
                self.fancySelect($elem);
            }
        });
    };
    
    jQuery.fn.fancy = function(){
        var data = $.data(this, 'FancyForm');
        if(!data)
            data = $.data(this, 'FancyForm', new FancyForm(this));
        
        return this;
    };
})(jQuery);