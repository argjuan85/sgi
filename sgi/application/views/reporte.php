<h1>Subscriber management</h1>
<?php //echo $this->table->generate(); ?>
</div>

<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function() {
$('#example').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sAjaxSource": "<?php site_url(); ?>index.php/Reporte/getdatabyajax",
"sServerMethod": "POST"
} );
} );
</script>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
<thead>
<tr>
<th width="20%">Username</th>
<th width="25%">Email Address</th>
<th width="25%">Salt</th>
</tr>
</thead>
<tbody>
<tr>
<td colspan="5" class="dataTables_empty">Loading data from server</td>
</tr>
</tbody>
</table>