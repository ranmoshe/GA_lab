<?
 
ini_set('display_errors',1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
session_start();
include_once 'xls.class.php';
include_once 'ga_class.inc.php';
#include_once 'GAnalytics/GAnalytics.php';

function pre($array)
{
print "<pre>";
print_r($array);
print "</pre>";
}
/*
function runMainDemo(&$analytics) {
  try {

    // Step 2. Get the user's first view (profile) ID.
    $profileId = getFirstProfileId($analytics);

    if (isset($profileId)) {

      // Step 3. Query the Core Reporting API.
      $results = getResults($analytics, $profileId);

      // Step 4. Output the results.
      printResults($results);
    }

  } catch (apiServiceException $e) {
    // Error from the API.
    print 'There was an API error : ' . $e->getCode() . ' : ' . $e->getMessage();

  } catch (Exception $e) {
    print 'There wan a general error : ' . $e->getMessage();
  }
}
function getFirstprofileId(&$analytics) {
  $accounts = $analytics->management_accounts->listManagementAccounts();

  if (count($accounts->getItems()) > 0) {
    $items = $accounts->getItems();
    $firstAccountId = $items[0]->getId();

    $webproperties = $analytics->management_webproperties
        ->listManagementWebproperties($firstAccountId);

    if (count($webproperties->getItems()) > 0) {
      $items = $webproperties->getItems();
      $firstWebpropertyId = $items[0]->getId();

      $profiles = $analytics->management_profiles
          ->listManagementProfiles($firstAccountId, $firstWebpropertyId);

      if (count($profiles->getItems()) > 0) {
        $items = $profiles->getItems();
        return $items[0]->getId();

      } else {
        throw new Exception('No views (profiles) found for this user.');
      }
    } else {
      throw new Exception('No webproperties found for this user.');
    }
  } else {
    throw new Exception('No accounts found for this user.');
  }
}
function getResults(&$analytics, $profileId) {
   return $analytics->data_ga->get(
       'ga:' . $profileId,
       '2012-03-03',
       '2012-03-03',
       'ga:visits');
}
function printResults(&$results) {
  if (count($results->getRows()) > 0) {
    $profileName = $results->getProfileInfo()->getProfileName();
    $rows = $results->getRows();
	#print_r($rows);
    $visits = $rows[0][0];

    print "<p>First view (profile) found: $profileName</p>";
    print "<p>Total visits: $visits</p>";

  } else {
    print '<p>No results found.</p>';
  }
}*/
	// Include the Google Analytics data import class
#

require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_AnalyticsService.php';

$client = new Google_Client();
$client->setApplicationName('Hello Analytics API Sample');

// Visit //code.google.com/apis/console?api=analytics to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId('556890562009.apps.googleusercontent.com');
$client->setClientSecret('pIV1GF_eaMZCe319XQsNaxc4');
$client->setRedirectUri('http://analyzit.co.il/lab/import.php');
$client->setDeveloperKey('AIzaSyDcIslSfYeswdZpKXWNotqYtE7_YGS2Dvw');
$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));

// Magic. Returns objects from the Analytics Service instead of associative arrays.
$client->setUseObjects(true);
if (isset($_GET['code'])) {
  $client->authenticate();
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}
if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}
if (!$client->getAccessToken()) {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";

} else {
  // Create analytics service object. See next step below.
  $analytics = new Google_AnalyticsService($client);
  #print_r($analytics);
#$profileId = getFirstProfileId($analytics);
$gac=new GA_Custom($analytics); 

if(isset($_POST['op']))
{

try{
$results=$gac->get_results();

}catch(Exception $e){
$error=$e->getMessage();
}
 #$rows = $results->getRows();
#$headers = $results->getColumnHeaders();
if($_POST['op']=='Export XLS')
	{
		$gac->export_xls($results);
		exit();
	}
}
echo $gac->header();
if(isset($error))print $gac->draw_error($error);
echo $gac->draw_form();

if(isset($results))echo $gac->draw_results($results);
echo $gac->footer();
#print $profileId."<hr>";
#runMainDemo($analytics);
}


?>