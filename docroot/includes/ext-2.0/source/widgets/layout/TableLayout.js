/*
 * Ext JS Library 2.0
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.layout.TableLayout
 * @extends Ext.layout.ContainerLayout
 * <p>This layout allows you to easily render content into an HTML table.  The total number of columns can be
 * specified, and rowspan and colspan can be used to create complex layouts within the table.
 * This class is intended to be extended or created via the layout:'table' {@link Ext.Container#layout} config,
 * and should generally not need to be created directly via the new keyword.</p>
 * <p>Note that when creating a layout via config, the layout-specific config properties must be passed in via
 * the {@link Ext.Container#layoutConfig} object which will then be applied internally to the layout.  In the
 * case of TableLayout, the only valid layout config property is {@link #columns}.  However, the items added to a
 * TableLayout can supply table-specific config properties of <b>rowspan</b> and <b>colspan</b>, as explained below.</p>
 * <p>The basic concept of building up a TableLayout is conceptually very similar to building up a standard
 * HTML table.  You simply add each panel (or "cell") that you want to include along with any span attributes
 * specified as the special config properties of rowspan and colspan which work exactly like their HTML counterparts.
 * Rather than explicitly creating and nesting rows and columns as you would in HTML, you simply specify the
 * total column count in the layoutConfig and start adding panels in their natural order from left to right,
 * top to bottom.  The layout will automatically figure out, based on the column count, rowspans and colspans,
 * how to position each panel within the table.  Just like with HTML tables, your rowspans and colspans must add
 * up correctly in your overall layout or you'll end up with missing and/or extra cells!  Example usage:</p>
 * <pre><code>
// This code will generate a layout table that is 3 columns by 2 rows
// with some spanning included.  The basic layout will be:
// +--------+-----------------+
// |   A    |   B             |
// |        |--------+--------|
// |        |   C    |   D    |
// +--------+--------+--------+
var table = new Ext.Panel({
    title: 'Table Layout',
    layout:'table',
    defaults: {
        // applied to each contained panel
        bodyStyle:'padding:20px'
    },
    layoutConfig: {
        // The total column count must be specified here
        columns: 3
    },
    items: [{
        html: '&lt;p&gt;Cell A content&lt;/p&gt;',
        rowspan: 2
    },{
        html: '&lt;p&gt;Cell B content&lt;/p&gt;',
        colspan: 2
    },{
        html: '&lt;p&gt;Cell C content&lt;/p&gt;'
    },{
        html: '&lt;p&gt;Cell D content&lt;/p&gt;'
    }]
});
</code></pre>
 */
Ext.layout.TableLayout = Ext.extend(Ext.layout.ContainerLayout, {
    /**
     * @cfg {Number} columns
     * The total number of columns to create in the table for this layout.  If not specified, all panels added to
      * this layout will be rendered into a single row using a column per panel.
     */

    // private
    monitorResize:false,

    // private
    setContainer : function(ct){
        Ext.layout.TableLayout.superclass.setContainer.call(this, ct);

        this.currentRow = 0;
        this.currentColumn = 0;
        this.spanCells = [];
    },

    // private
    onLayout : function(ct, target){
        var cs = ct.items.items, len = cs.length, c, i;

        if(!this.table){
            target.addClass('x-table-layout-ct');

            this.table = target.createChild(
                {tag:'table', cls:'x-table-layout', cellspacing: 0, cn: {tag: 'tbody'}}, null, true);

            this.renderAll(ct, target);
        }
    },

    // private
    getRow : function(index){
        var row = this.table.tBodies[0].childNodes[index];
        if(!row){
            row = document.createElement('tr');
            this.table.tBodies[0].appendChild(row);
        }
        return row;
    },

    // private
	getNextCell : function(c){
        var td = document.createElement('td'), row, colIndex;
        if(!this.columns){
            row = this.getRow(0);
        }else {
        	colIndex = this.currentColumn;
            if(colIndex !== 0 && (colIndex % this.columns === 0)){
                this.currentRow++;
                colIndex = (c.colspan || 1);
            }else{
                colIndex += (c.colspan || 1);
            }
            
            //advance to the next non-spanning row/col position
            var cell = this.getNextNonSpan(colIndex, this.currentRow);
            this.currentColumn = cell[0];
            if(cell[1] != this.currentRow){
            	//we are on a new row
            	this.currentRow = cell[1];
            	if(c.colspan){
            		//since the col index is now set at the start of the 
            		//new cell, any colspan needs to get reapplied.  This is
            		//only necessary if the row changed since the col index
            		//only gets reset in that case
            		this.currentColumn += c.colspan - 1;
            	}
            }
            row = this.getRow(this.currentRow);
        }
        if(c.colspan){
            td.colSpan = c.colspan;
        }
		td.className = 'x-table-layout-cell';
        if(c.rowspan){
            td.rowSpan = c.rowspan;
			var rowIndex = this.currentRow, colspan = c.colspan || 1;
			//track rowspanned cells to add to the column index during the next call to getNextCell
			for(var r = rowIndex+1; r < rowIndex+c.rowspan; r++){
				for(var col=this.currentColumn-colspan+1; col <= this.currentColumn; col++){
					if(!this.spanCells[col]){
						this.spanCells[col] = [];
					}
					this.spanCells[col][r] = 1;
				}
			}
        }
        row.appendChild(td);
        return td;
    },
    
    // private
    getNextNonSpan: function(colIndex, rowIndex){
    	var c = (colIndex <= this.columns ? colIndex : this.columns), r = rowIndex;
        for(var i=c; i <= this.columns; i++){
        	if(this.spanCells[i] && this.spanCells[i][r]){
        		if(++c > this.columns){
        			//exceeded column count, so reset to the start of the next row
	                return this.getNextNonSpan(1, ++r);
        		}
        	}else{
        		break;
        	}
        }
        return [c,r];
    },

    // private
    renderItem : function(c, position, target){
        if(c && !c.rendered){
            c.render(this.getNextCell(c));
        }
    },

    // private
    isValidParent : function(c, target){
        return true;
    }

    /**
     * @property activeItem
     * @hide
     */
});

Ext.Container.LAYOUTS['table'] = Ext.layout.TableLayout;