/*
 * Ext JS Library 2.0
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.layout.AbsoluteLayout
 * @extends Ext.layout.AnchorLayout
 * <p>Inherits the anchoring of {@link Ext.layout.AnchorLayout} and adds the ability for x/y positioning using the
 * standard x and y component config options.</p>
 */
Ext.layout.AbsoluteLayout = Ext.extend(Ext.layout.AnchorLayout, {
    extraCls: 'x-abs-layout-item',
    onLayout : function(ct, target){
        target.position();
        Ext.layout.AbsoluteLayout.superclass.onLayout.call(this, ct, target);
    }
    /**
     * @property activeItem
     * @hide
     */
});
Ext.Container.LAYOUTS['absolute'] = Ext.layout.AbsoluteLayout;