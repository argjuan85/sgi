

<script type="text/javascript">
        $(document).ready(function() {
	var oTable = $('#example_ad').dataTable( {
		
		  dom: 'T<"clear">lfrtip',  
	
      
           tableTools: {
           
"sSwfPath": "<?php echo base_url(); ?>assets/TableTools/swf/copy_csv_xls.swf",
   
                                                    
                                                                }
    
          
        });  
        
        
        	/* Add the events etc before DataTables hides a column */
			$("thead input").keyup( function () {
				/* Filter on the column (the index) of this element */
				oTable.fnFilter( this.value, oTable.oApi._fnVisibleToColumnIndex( 
					oTable.fnSettings(), $("thead input").index(this) ) );
			} );
			
			/*
			 * Support functions to provide a little bit of 'user friendlyness' to the textboxes
			 */
			$("thead input").each( function (i) {
				this.initVal = this.value;
			} );
			
			$("thead input").focus( function () {
				if ( this.className == "search_init" )
				{
					this.className = "";
					this.value = "";
				}
			} );
			
			$("thead input").blur( function (i) {
				if ( this.value == "" )
				{
					this.className = "search_init";
					this.value = this.initVal;
				}
			} );
        
        
        
        
	
} );
</script>
<div id ="Advertisement">
	<table id="example_ad" class="display" cellspacing="0" width="100%">
		<thead>
			
		
		
			<tr>
			<td align="center"><input type="text" name="search_engine" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_browser" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_platform" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_version" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_grade" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_version" value="" class="search_init" /></td>
			<td align="center"><input type="text" name="search_grade" value="" class="search_init" /></td>
			</tr>
			<tr>
				<th>Title</th>
				<th>Name</th>
				<th>Description</th>
				<th>Price</th>
				<th>Mobile No</th>
				<th>Location</th>
				<th>Date</th>
			</tr>
			</thead>
		<tfoot>
				<tr>
				<th>Title</th>
				<th>Name</th>
				<th>Description</th>
				<th>Price</th>
				<th>Mobile No</th>
				<th>Location</th>
				<th>Date</th>
			</tr>
		</tfoot>
		<tbody>
			<?php
			foreach ($query_ad as $row)
			{
				echo "<tr>";
				echo "<td>". $row->title ."</td>";
				echo "<td>". $row->name ."</td>";
				echo "<td>". $row->description ."</td>";
				echo "<td>". $row->price ."</td>";
				echo "<td>". $row->mobile ."</td>";
				echo "<td>". $row->location ."</td>";
				echo "<td>". $row->date ."</td>";
				echo "</tr>";
			}
			
			?>
		</tbody>
	</table>
	
</div>