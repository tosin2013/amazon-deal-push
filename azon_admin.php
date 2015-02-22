<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<?
define('WP_DEBUG', true); // enable debugging mode


define('KV_PLUGIN_URL', plugin_dir_url( __FILE__ ));

function kv_date_time_js() { //function to add date and time functionaly to wordpress.
	wp_register_style('kv_js_time_style' , KV_PLUGIN_URL. 'css/jquery-ui-timepicker-addon.css');
	wp_enqueue_style('kv_js_time_style');	
	wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
	wp_enqueue_script('jquery-script', 'http://code.jquery.com/ui/1.10.4/jquery-ui.js');

	wp_enqueue_script('jquery-time-picker' ,  KV_PLUGIN_URL. 'js/jquery-ui-timepicker-addon.js',  array('jquery' ));	

	}

	
/** Step 2 (from text above). */
//add_action( 'admin_menu', 'my_plugin_menu' );
add_action( 'admin_menu', 'pull_azon_items' );
	add_action('admin_head', 'kv_date_time_js');
	
/** Step 1. */
function my_plugin_menu() {
	add_options_page( 'Amazon Deals', 'Amazon Deals', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
}

function pull_azon_items(){
	add_options_page("Amazon Deals", "Amazon Pull Beta", 1, "my-unique-1", "pull_amazon_products");
}

/** Step 3. */
function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}


	echo '<div class="wrap">';
	echo '<h1>Amazon Deals Plugin</h1>';
	echo '</div>';
	//$photo_link = "http://ecx.images-amazon.com/images/I/51Wz9tvl9uL.jpg";
	//echo "<img src='".$photo_link."'>";
	 //_import_photo( '30', $photo_link);
	 //_upload_photo('30');
}

function pull_amazon_products(){
if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
kv_date_time_js() ;
	$cur_page = $_SERVER['REQUEST_URI'];
	echo '<div class="wrap">';
	echo '<h1>Get Amazon Items</h1>';
	if(!(isset($_POST['Keywords']) && isset($_POST['Category']))){
	echo '<form method="post" action="'.$cur_page.'">';
	echo '<h3>Enter Amazon Search Request:</h3>';
	echo 'Keyword: <input type="text" name="Keywords"><br>';
	echo "Category: <select name='Category'>";
	echo "<option value=''> </option>";
	//echo "<option value=''>esc_attr(__('Select Event')</option>";
	$args = array('taxonomy' => 'wpsdealcategories');
	$categories = get_categories($args); 
	echo "printing cats";	
	print_r($categories);
	foreach ($categories as $category) {
		$option = '<option value="'.$category->category_nicename.'"'.$category->category_nicename.'>';
		$option .= $category->cat_name;
		$option .= ' ('.$category->category_count.')';
		$option .= '</option>';
		echo $option;
	}
	echo "</select>";
	echo "<br/>Start Date: <input type='text' id='kv_demo_time_picker' name='start_date' value=''/>";
	echo "<br/>End Date: <input type='text' id='kv_demo_time_picker_end' name='end_date' value=''/>";
	echo "<br/>Search Depth: <select name=depth> (number of pages to search)";
	echo  "<option value='1'>1</option>";
	echo  "<option value='2'>2</option>";
	echo  "<option value='3'>3</option>";
	echo  "<option value='4'>4</option>";
	echo  "<option value='5'>5</option>";
	echo "</select>";
	submit_button( 'Submit' );
	echo '</form>';

	}
	else
	{
		$keyword = $_POST['Keywords'];
		$category =  $_POST['Category'];
		$pages = $_POST['depth'];
		$start_date =$_POST['start_date'];
		$end_date = $_POST['end_date'];
		$category=ucfirst($category) ;
		echo "<h1>Locating  $keyword deals in the $category Category.</h1>";
		get_azon_list($category, $keyword,$pages,$start_date,$end_date);
	}
	//echo "current link: ".$_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];

	echo '</div>';
	
}
?>
<script>  
    jQuery(document).ready(function() {    
        jQuery('#kv_demo_time_picker').datetimepicker();
		jQuery('#kv_demo_time_picker_end').datetimepicker();
    });
</script>