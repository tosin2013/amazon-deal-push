<?

//date_default_timezone_set('America/New_York');

/*
* Import Image function
*/
function _import_photo( $post_id, $image_url) {
//$url = "http://s.wordpress.org/style/images/wp3-logo.png";
// Add Featured Image to Post
//$image_url  = 'http://s.wordpress.org/style/images/wp-header-logo.png'; // Define the image URL here
$upload_dir = wp_upload_dir(); // Set upload folder
$image_data = file_get_contents($image_url); // Get image data
$filename   = basename($image_url); // Create image file name

// Check folder permission and define file location
if( wp_mkdir_p( $upload_dir['path'] ) ) {
    $file = $upload_dir['path'] . '/' . $filename;
} else {
    $file = $upload_dir['basedir'] . '/' . $filename;
}

// Create the image  file on the server
file_put_contents( $file, $image_data );

// Check image file type
$wp_filetype = wp_check_filetype( $filename, null );

// Set attachment data
$attachment = array(
    'post_mime_type' => $wp_filetype['type'],
    'post_title'     => sanitize_file_name( $filename ),
    'post_content'   => '',
    'post_status'    => 'inherit'
);

// Create the attachment
$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

// Include image.php
require_once(ABSPATH . 'wp-admin/includes/image.php');

// Define attachment metadata
$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

// Assign metadata to attachment
wp_update_attachment_metadata( $attach_id, $attach_data );

// And finally assign featured image to post
set_post_thumbnail( $post_id, $attach_id );
}

/*
* upload photo function
*/
function _upload_photo($post_id){
	$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post_id) );
//echo $post_id." WHATS UP WHATS UP-->".$feat_image; 
		$image_id=pn_get_attachment_id_from_url($feat_image);
		$upload = array(
		'id'=> $image_id,
		'src'=>	$feat_image);

		//print_r($upload);
		update_post_meta($post_id, '_wps_deals_main_image', $upload);
		

		
}

/*
* Import into wordpress post
*/
function upload_item($title, $description,$category){
//echo "<h1>pushing Title: ".$title."</h1>";
$args = array('name'=>$category,
			'taxonomy' => 'wpsdealcategories');
$categories = get_categories($args); 
foreach ($categories as $catman) {
		if  ($catman->category_nicename == strtolower($category))
		{
			//echo "EQUALS ";
			$catnum=$catman->cat_ID;
		}
	}
$upload_post = array(
  'post_title'    => $title,
  'post_content'  => $description,
  'post_status'   => 'publish',
  'post_author'   => 1,
  'post_category' => $catnum,
  'post_type'	 => 'wpsdeals'
);
//print_r($upload_post);
wp_insert_post( $upload_post, $wp_error );
}
/*
* push deatils to DB
*/
function details_push($post_id, $title,$price, $lowestprice,$link, $maincat, $subcat)
{
	//pushing to _wps_deals_type
	update_post_meta($post_id, '_wps_deals_type', 'affiliate');
	
		
	//pushing to _wps_deals_add_to_cart
	update_post_meta($post_id, '_wps_deals_add_to_cart', 'Buy Now!');
	
	//pushing to _wps_deals_normal_price
	$price =str_replace("$","",$price);
	update_post_meta($post_id, '_wps_deals_normal_price', $price);
	
	//pushing to _wps_deals_normal_price
	$lowestprice =str_replace("$","",$lowestprice);
	update_post_meta($post_id, '_wps_deals_sale_price', $lowestprice);
	
	//pushing to _wps_deals_purchase_link
	update_post_meta($post_id, '_wps_deals_purchase_link', (string)$link);	
	
	//getting term information
	$args = array(  'fields'=> 'all');	
	$terms=get_terms( 'wpsdealcategories');
	//echo "print array of terms<br>";
	
	//print_r($terms);
	echo "<br>Main CAT: ".$maincat."<br/> Sub-Category: ".$subcat;
	$cat_array=array();
	foreach ($terms as $term) {
	echo "<br/> currently searching ".$term->name;
		//echo "<br>Comapring  CAT: ".$maincat;
		if  ($term->name == $maincat)
		{
			echo "<br/>".$maincat." EQUALS ".$term->name;
			array_push($cat_array, $term->term_id);
		}
		
		//echo "<br/>Comparing Sub-Category: ".$subcat;
		if  ((strtolower($term->name) == $subcat) || $term->name == $subcat)
		{
			echo "<br/>".$subcat." EQUALS ".$term->name;
			array_push($cat_array, $term->term_id);
		}
	}
	//print_r($cat_array);
		wp_set_post_terms( $post_id, $cat_array,  'wpsdealcategories'); 
		
	wp_set_post_terms( $post_id, $title, 'wpsdealtags' );
}


/*
* Set Date for new device
*/
function activate_items($post_id, $start_date,$end_date)
{
$startdate = strtotime($start_date);
$new_start_date = date('Y-m-d H:i:s', $startdate); 

$enddate = strtotime($end_date);
$new_end_date = date('Y-m-d H:i:s', $enddate);   

//echo "DEBUG INSERT: ".$new_start_date." ".$new_end_date;
	//pushing to _wps_deals_start_date
	//$date = date('Y-m-d H:i:s');
	//echo "DEBUG _wps_deals_start_date ".$date."<br/>";
	update_post_meta($post_id, '_wps_deals_start_date', $new_start_date);
	
	//pushing to _wps_deals_end_date
	//$newDate = date('Y-m-d H:i:s', strtotime("+15 days"));
	//echo "DEBUG _wps_deals_end_date ".$newDate;
	update_post_meta($post_id, '_wps_deals_end_date', $new_end_date);
}


/*
* Get attachment id from URL
*/
function pn_get_attachment_id_from_url( $attachment_url = '' ) {
 
	global $wpdb;
	$attachment_id = false;
 
	// If there is no url, return.
	if ( '' == $attachment_url )
		return;
 
	// Get the upload directory paths
	$upload_dir_paths = wp_upload_dir();
 
	// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
	if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
 
		// If this is the URL of an auto-generated thumbnail, get the URL of the original image
		$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
 
		// Remove the upload path base directory from the attachment URL
		$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
 
		// Finally, run a custom database query to get the attachment ID from the modified attachment URL
		$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
 
	}
 
	return $attachment_id;
}

 function meta_tag_function($PID, $title, $description)
	 {
		//meta_tag title
		update_post_meta($PID, '_amt_title', (string)$title, true); 
		
		//meta_description description
		$result = substr($description, 0, 130);
		//echo $result."...";
		update_post_meta($PID, '_amt_description', (string)$result." ...", true); 
		
		//_amt_keywords
		$keyword = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', strtolower($title));
		$keys = explode("-", $keyword);
		$final_keyword .= $keys[0];  //one
		$final_keyword .= ", ".$keyword; //two
		$final_keyword .= ", ".$keys[0]." deal"; //three
		$final_keyword .= ", ".$keys[0]." sale"; //four
		update_post_meta($PID, '_amt_keywords', (string)$final_keyword , true);
	 }
?>