<form method="post">
	<input type="hidden" name="change" value="1">
<h1>Coupon Settings</h1>

		<?php if(isset($change) && $change == true){ ?>
		<div id="message" class="notice is-dismissible notice-success alert-success"><p>Changes saved. </p><button type="button" class="notice-dismiss"></button></div>
		<?php } ?>

	<div><input type="submit" style="float:right;margin-right: 4em;" class="button button-primary" value="Save Fields"></div>
		<table align="center" width="100%">
		<tr style="vertical-align: baseline;">
			
<td>
	<h3>
		Coupon Position and font size.
	</h3>
	<p style="max-width: 500px;">These parameters will specify the position of the coupon template the code will be inserted into. X is the number of pixels to the left of the left side of image, while Y is the number of pixels down of the top of image. Please specify font size in pixels</p>
	<table>
		<tr>
			<td><label>X</label></td>
			<td><input type="number" min="0" required  name="posx" value="<?php echo $posx; ?>"></td>
		</tr>
		<tr>
			<td><label>Y</label></td>
			<td><input type="number" min="0" required  name="posy" value="<?php echo $posy; ?>"></td>
		</tr>
		<tr>
			<td><label>Font Size</label></td>
			<td><input type="number" min="0" required  name="textsize" value="<?php echo $textsize; ?>"></td>
		</tr>
		 
		</table>
	</td>
	<td>
<h3>Coupon Template</h3>
<p>Please select an image that will be template for generating coupons.</p>
<input type="button" class="button" id="filechooser" value="Select Image"/>
<?php if(get_option('couponattachment_id',false)){ 
$url = wp_get_attachment_url(get_option('couponattachment_id',false));
	?>
<img src="<?php echo $url; ?>" style="max-width: 300px;display:block;margin-top:1em;">
<?php } ?>
</td>
</tr>
</table>
		<input id="attachment_id" type="hidden" name="attachment_id" value="<?php echo $couponattachment_id; ?>">
</form>
<script type="text/javascript">
	(function($){
		$('#filechooser').on('click',function(){
 	var file_frame = wp.media.frames.file_frame = wp.media({
          
        multiple: false
    });
 
    file_frame.on( 'select', function() {
 var attachment = file_frame.state().get('selection').first().toJSON();
 	$('#attachment_id').val(attachment.id);
    });
  
    file_frame.open();
	});
	})(jQuery);
</script>