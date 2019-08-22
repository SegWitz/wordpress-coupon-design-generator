<?php 
<?php 
/*
 * Plugin Name: SegWitz - WP Coupon Code Design Generator
 * Plugin URI:  https://segwitz.com
 * Author: SegWitz
 * Author URI: https://segwitz.com
 * Version: 1.0.0
 * Description: This plugin generates coupon codes onto your designed coupons.
*/

class CouponImages{
 

	/*
	 * 
	 */


    public function attach_assets(){   		 
		wp_enqueue_script('asyncfileuploadjs',plugin_dir_url(__FILE__).'js/couponimages.js'); 
		
    }

  

	/**
	 * This function renders sirena widget settings page,
	 * and it updates its parameters when they are changed.
	 *
	 * @since 1.0.0
	 *
	 */

	public function settings(){
		$change = false;
		if(isset($_POST['change']))
		{
		update_option("posx",$_POST['posx']);
		update_option("posy",$_POST['posy']);  
		update_option("textsize",$_POST['textsize']);  
		update_option("couponattachment_id",$_POST['attachment_id']);
		$change = true;
		} 
		$posx = get_option("posx","0"); 
		$posy = get_option("posy","10"); 
		$textsize = get_option("textsize","10"); 
		$couponattachment_id = get_option("couponattachment_id","");  
		
		include("templates/settings-page.php");
	}

	public function couponmanagement(){
		$query = new WP_Query(['post_type'=>'shop_coupon']);
		
    	if (isset($_POST['coupons'])) {  
	if(isset($_POST['zip'])){ 
	$name = 'couponsZip-'.time().'.zip';
	$this->zipFilesAndDownload($name);
	$zipGenerated = $name;
	}
	elseif(isset($_POST['media'])){ 
		$i = 0;
		foreach ($_POST['coupons'] as $key => $value) {
			$i++;
			$this->make_coupon($value,true); 
		} 
		$mediaGenerated = $i;
	}
}

		$posts = $query->get_posts(); 
		
		include("templates/couponmanagement.php");
	}

	/**
	 * This function hooks the options page for sirena widget settings
	 * to Wordpress dashboard.
	 *
	 * @since 1.0.0
	 *
	 * @see settings 
	 *
	 */

	public function add_pages(){
		add_options_page('Coupon Settings','Coupon Settings','manage_options','coupon_settings',[$this,'settings']);
		add_menu_page('Coupons','Bulk Coupon Management','manage_options','couponmanagement',[$this,'couponmanagement']);
	}

    /**
	 * This function saves a post meta for disabling
	 * sirena widget script embedding, if the corresponding checkbox
	 * in post edit area was checked.
	 *
	 * @since 1.0.0 
	 *
	 * @param string $id The ID of post being saved. 
	 */

	public function make_coupon($text,$save=false){
 

		$posx = get_option("posx","0"); 
		$posy = get_option("posy","10"); 
		$textsize = get_option("textsize","10");  
	  $path = get_attached_file(get_option('couponattachment_id'));
	 // die(var_dump(get_option('couponattachment_id')));
 
	  if(strpos(strtolower($path),'.jpeg') !== FALSE || strpos(strtolower($path),'.jpg') !== FALSE)
      $jpg_image = imagecreatefromjpeg( $path);
      elseif(strpos(strtolower($path),'.png') !== FALSE)
		$jpg_image = imagecreatefrompng( $path); 
      elseif(strpos(strtolower($path),'.gif') !== FALSE)
		$jpg_image = imagecreatefromgif( $path); 
      elseif(strpos(strtolower($path),'.bmp') !== FALSE)
		$jpg_image = imagecreatefrombmp( $path); 

      $black = imagecolorallocate($jpg_image, 102, 155, 68);
       
      $font_path = dirname(__FILE__).'/Prototype.ttf'; 
      $actual = wp_upload_dir()['path'].'/image-'.substr(md5(rand(0,99999)),0,12).'-'.time().'.jpg';
     
      imagettftext($jpg_image, $textsize, 0, $posx, $posy, $black, $font_path, $text);
      imagejpeg($jpg_image,$actual); 
      if($save){  
      	 $attachment = array(
				'post_mime_type' => 'image/jpeg', //'application/pdf'
				'guid' => home_url(), 
				'post_title' => 'Image for Coupon '.$text,
				'post_content' => '',
			);
			// Save the attachment metadata
			$id = wp_insert_attachment($attachment, $actual);

			require_once( ABSPATH . 'wp-admin/includes/image.php' );
 
	// Generate and save the attachment metas into the database
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $actual ) );	
 
      }

      // Clear Memory
      imagedestroy($jpg_image);
      return $actual;
	}

	public function zipFilesAndDownload($archive_file_name)
	{ 
		error_reporting(E_ALL & E_STRICT);
		ini_set('display_errors', '1');
		ini_set('log_errors', '0');
		ini_set('error_log', './');
		$file_names = [];
		$fakenames = [];

		foreach ($_POST['coupons'] as $key => $value) {
			$file_names[] = $this->make_coupon($value);
			$fakenames[] = $value.'.jpg';
		}
		$file_path =  wp_upload_dir()['path']; 
		$fn = $archive_file_name;
		$archive_file_name = $posterior_name = $file_path .'/'. $archive_file_name;

	    $zip = new ZipArchive();
	    //create the file and throw the error if unsuccessful
	    if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
	        exit("cannot open <$posterior_name>\n");
	    }
	    //add each files of $file_name array to archive
	    foreach($file_names as $k=>$files)
	    { 
	        ($zip->addFile($files,$fakenames[$k]));
	        //echo $file_path.$files,$files."

	    } 
	    $zip->close();

	    $attachment = array(
		'post_mime_type' => 'application/zip', //'application/pdf'
		'guid' => home_url().$f_dir, 
		'post_title' => 'Another zip',
		'post_content' => '',
	);
	// Save the attachment metadata
	$id = wp_insert_attachment($attachment, $posterior_name);
	if ( !is_wp_error($id) ){
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $posterior_name ) );
	}
 

	// Generate and save the attachment metas into the database
	update_post_meta($upload_id,'_wp_attached_file',$posterior_name);
	    //then send the headers to force download the zip file
	     
	}

	function add_action($actions, $post)
  {  
  	if(isset($_GET['post_type']) && $_GET['post_type']=='shop_coupon')
  	$actions['generate'] = '<a href="#">Generate Image</a>';
    return $actions;
  }
 

	public function enqueue_scripts() {
 
        wp_enqueue_media();
 
 
    }

    /**
	 * This function is the entry point for our plugin.
	 * Here all the functionality is added to Wordpress hooks.
	 *
	 * @since 1.0.0 
	 * 
	 */
 

    function __construct(){  
        add_action('wp_enqueue_scripts',[$this,'enqueue_scripts']);  
        add_action('admin_enqueue_scripts',[$this,'enqueue_scripts']); 
		add_action('admin_menu',[$this,'add_pages']);   
    }



}



$ci = new CouponImages();