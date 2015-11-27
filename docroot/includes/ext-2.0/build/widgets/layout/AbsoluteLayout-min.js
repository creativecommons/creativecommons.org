/*
 * Ext JS Library 2.0
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.layout.AbsoluteLayout=Ext.extend(Ext.layout.AnchorLayout,{extraCls:"x-abs-layout-item",onLayout:function(A,B){B.position();Ext.layout.AbsoluteLayout.superclass.onLayout.call(this,A,B)}});Ext.Container.LAYOUTS["absolute"]=Ext.layout.AbsoluteLayout;