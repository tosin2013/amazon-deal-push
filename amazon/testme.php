<?php
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'bootstrap.php';
require_once 'Config.php';

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\ApaiIO;

$conf = new GenericConfiguration();
$conf
    ->setCountry('com')
    ->setAccessKey(AWS_API_KEY)
    ->setSecretKey(AWS_API_SECRET_KEY)
    ->setAssociateTag(AWS_ASSOCIATE_TAG)
	->setResponseTransformer('ApaiIO\ResponseTransformer\XmlToSimpleXmlObject');
 
$apaiIO = new ApaiIO($conf);

$search = new Search();
$search->setCategory('Automotive');
//$search->setActor('Bruce Willis');
$search->setKeywords('BMW');
$search->setResponsegroup(array('Large', 'Images'));

$formattedResponse = $apaiIO->runOperation($search);
print_r($formattedResponse);


foreach($formattedResponse->Items->Item as $current)
{
$price = $current->ItemAttributes->ListPrice->FormattedPrice; //price
$dept = $current->ItemAttributes->Department; //sub department
$link = $current->DetailPageURL; //aff link
$title = $current->ItemAttributes->Title; //Title
$description = $current->EditorialReviews->EditorialReview->Content; // Description
$category = $current->Promotions; 
$AmaThumb = $current->LargeImage->URL;  // image
$lowestprice = $current->OfferSummary->LowestNewPrice->FormattedPrice; //num of items
$AmaStock = $current->ItemAttributes->NumberOfItems; // Products Stocks
$AmaAsin = $current->ASIN;  // ASIN
$shipping = $current->Offers->Offer->OfferListing->IsEligibleForSuperSaverShipping;
Echo "testing shipping? ".$shipping."<br/>";
echo $AmaAsin."<br/> TITLE ".$title."<br/> Department: ".$dept."</br> PRICE: ".$price;
echo "<br/>Lowest Price:".$lowestprice."<br/> IN STOCK: ".$AmaStock ."<br/>";
echo "LINK: <a href= ".$link.">link</a><br/> cat:".$category."<br/><br/>"; 
}
