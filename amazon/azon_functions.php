<?php 

require_once 'tests/bootstrap.php';
require_once 'Config.php';

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\ApaiIO;

function get_azon_list($category, $keyword, $page, $start_date,$end_date){

//echo "Current Category: ".$category."<br>";
//echo "Current Keyword: ".$keyword."<br>";
$conf = new GenericConfiguration();
$conf
    ->setCountry('com')
    ->setAccessKey(AWS_API_KEY)
    ->setSecretKey(AWS_API_SECRET_KEY)
    ->setAssociateTag(AWS_ASSOCIATE_TAG)
	->setResponseTransformer('ApaiIO\ResponseTransformer\XmlToSimpleXmlObject');
 
$apaiIO = new ApaiIO($conf);
$nodeal=0;
$deal=0;
for ($i = 1; $i <= $page; $i++) {
$search = new Search();
if (strpos($category,'-') !== false) {
$cat_src=explode ("-", $category);
$catone=ucfirst ($cat_src[0]);
$cattwo=ucfirst ($cat_src[1]);
$final_cat= $catone."".$cattwo;
$compare_Cat = $catone." ".$cattwo;
$search->setCategory($final_cat);
//echo "Current CAt: ".$final_cat."<br>";
$category = $final_cat;
}else
{
$search->setCategory($category);
$compare_Cat = $category;
}
//$search->setActor('Bruce Willis');
$search->setKeywords($keyword);
$search->setResponsegroup(array('Large', 'Images'));
$search->setItemPage($i);

$formattedResponse = $apaiIO->runOperation($search);
//print_r($formattedResponse);


foreach($formattedResponse->Items->Item as $current)
{
$price = $current->ItemAttributes->ListPrice->FormattedPrice; //price
switch ($compare_Cat) {
  case "Apparel":
		$subdept= $current->ItemAttributes->Department; //sub department
    break;
  case "Video Games":
		$subdept= $current->ItemAttributes->Platform; 
    break;
  default:
    $subdept="FAILED";
}
$link = $current->DetailPageURL; //aff link
$title = $current->ItemAttributes->Title; //Title
$description = $current->EditorialReviews->EditorialReview->Content; // Description
//echo "<h1>Current Subcat ".$subdept."</h1>";
$AmaThumb = $current->LargeImage->URL;  // image
$lowestprice = $current->OfferSummary->LowestNewPrice->FormattedPrice; //num of items
$AmaStock = $current->ItemAttributes->NumberOfItems; // Products Stocks
$AmaAsin = $current->ASIN;  // ASIN
$shipping = $current->Offers->Offer->OfferListing->IsEligibleForSuperSaverShipping;
if (($price!='') && ($lowestprice!=''))
{
	//Echo "testing shipping? ".$shipping."<br/>";
	//echo $AmaAsin."<br/> TITLE ".$title."<br/> Department: ".$dept."</br> PRICE: ".$price;
	//echo "<br/>Lowest Price:".$lowestprice."<br/> IN STOCK: ".$AmaStock ."<br/>";
	//echo "LINK: <a href= ".$link.">link</a><br/> cat:".$category."<br/><br/>";
	$page = get_page_by_title( $title, OBJECT, 'wpsdeals' );
		$PID = (string)$page->ID;
		//echo "current page ID: ".$PID;
	if (!empty($PID))
	{
	 echo "<table border='1'>";
	echo "<th>Deal?</th>";
	echo "<th>Title</th>";
	echo "<th>Price</th>";
	echo "<th>Low Price</th>";
	echo "<th>Stock</th>";
	echo "<th>Link</th>";
	echo "<tr><td><font color='yellow'>All Ready Exists</font></td>";
	echo "<td>".$title."</td>";
	echo "<td>".$price."</td>";
	echo "<td>".$lowestprice."</td>";
	echo "<td>".$AmaStock."</td>";
	echo "<td><a href= ".$link.">link</a></td></tr>";
	echo "</table>";
	
			// _upload_photo($PID);
			 details_push($PID,$title,$price, $lowestprice,$link, $compare_Cat,  $subdept);
	 }
	 else
	 {
		echo "<table border='1'>";
		echo "<th>Deal?</th>";
		echo "<th>Title</th>";
		echo "<th>Price</th>";
		echo "<th>Low Price</th>";
		echo "<th>Stock</th>";
		echo "<th>Link</th>";
		echo "<th>Push Status</th>";
		echo "<tr><td><font color='green'>FOUND!!!</font></td>";
		echo "<td>".$title."</td>";
		echo "<td>".$price."</td>";
		echo "<td>".$lowestprice."</td>";
		echo "<td>".$AmaStock."</td>";
		echo "<td><a href= ".$link.">link</a></td>";

		$cp = (int)str_replace("$","",$price);
	$lp = (int)str_replace("$","",$lowestprice);
		if ($cp > $lp)
		{
			//echo "GREAT $cp and $lp";
			upload_item($title, $description, $category);
		$page = get_page_by_title( $title, OBJECT, 'wpsdeals' );
		$PID = (string)$page->ID;
		 _import_photo($PID, $AmaThumb);
		 _upload_photo($PID);
		 details_push($PID,$title,$price, $lowestprice,$link, $compare_Cat,  $subdept);
		 activate_items($PID,$start_date,$end_date);
		 meta_tag_function($PID, $title,  $description);
		 echo "<td><font color='green'>Pushed to Table!!!</font></td></tr>";
		}else
		{
			 echo "<td><font color='red'>Did not Push. Bad Data.</font></td></tr>";
		}
		echo "</table>";
	 }
	echo "<hr>";
	$deal++;
}else
{

	//echo "<br/>NO DEAL TITLE ".$title." PRICE: ".$price." Low Price ".$lowestprice."LINK: <a href= ".$link.">link</a><br/>";
	echo "<table border='1'>";
	echo "<th>Deal?</th>";
	echo "<th>Title</th>";
	echo "<th>Price</th>";
	echo "<th>Low Price</th>";
	echo "<th>Link</th>";
	echo "<tr><td><font color='red'>NOT FOUND!!!</font></td>";
	echo "<td>".$title."</td>";
	echo "<td>".$price."</td>";
	echo "<td>".$lowestprice."</td>";
	echo "<td><a href= ".$link.">link</a></td></tr>";
	echo "</table>";
	echo "<hr>";
	$nodeal=$nodeal+1;
}
}
echo "<h3>Current Page: ".$i." of ".$page.". Number of Deals Found: $deal . Number of Deals Not Found: $nodeal.</h3>";
$nodeal=0;
$deal=0;
echo "<hr>";
}
}
?>