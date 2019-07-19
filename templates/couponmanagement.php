<form method="post"  >
<h1>Coupon Images Management</h1>

		<?php if(isset($zipGenerated) && $zipGenerated != ''){ ?>
		<div id="message" class="notice is-dismissible notice-success alert-success"><p>Zip has been generated into media successfully and saved with the name <?php echo $zipGenerated; ?>. </p><button type="button" class="notice-dismiss"></button></div>
		<?php } ?>
		<?php if(isset($mediaGenerated) && $mediaGenerated != ''){ ?>
		<div id="message" class="notice is-dismissible notice-success alert-success"><p><?php echo $mediaGenerated; ?> Images have been saved into media successfully.</p><button type="button" class="notice-dismiss"></button></div>
		<?php } ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/css/jquery.dataTables.min.css" />
<table id="coupons_table" class="table">
	<thead>
		<tr>
			<th>Coupon Code</th>
			<th>Marked</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($posts as $key => $value) :?>
	<tr>
		<td><?php echo $value->post_title; ?></td> 
	<td><input type="checkbox" checked name="coupons[]" value="<?php echo $value->post_title; ?>"></td>
	</tr>
	<?php endforeach; ?>
		
	</tbody>
	<tfoot>
		<tr>
			<td>
<label>For marked coupons, generate images and:</label></td>
<td><button class="button" name="zip">Generate ZIP</button><button class="button" name="media">Save in media</button></td>
		</tr>
	</tfoot>
</table>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	(function($){
		$(function(){
			$('#coupons_table').dataTable();
		});
	})(jQuery);
</script>