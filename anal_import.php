<?
ini_set('display_errors',1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
#session_start();
#phpinfo();
include_once 'GAnalytics/GAnalytics.php';

	// Set up your Google Analytics credentials*/
	
	$gaEmail        = 'analyzit.co.il';
	$gaPassword     = '6tgb7yhn';
	$id='499429';
	/*
	$gaEmail        = 'shaman33';
	$gaPassword     = 'b@bu1n101';
	$id='47960995';
	*/
	
#AIzaSyCiDr1Xl_4M4-rrQYyIR5bEGzFuec7ZsnA
	// Set up a period of time to get data for
	#$statsStartDate = date('Y-m-d', time() - 148 * 24 * 60 *60); //one week ahead
	$statsStartDate='2013-06-01';
	#$statsEndDate   = date('Y-m-d', time() - 1 * 24 * 60 *60); //yesterday
	$statsEndDate='2013-06-30';
	// Get and store the query data code from Google Analytics Data Feed Query Explorer
	// http://code.google.com/apis/analytics/docs/gdata/gdataExplorer.html
	//
	// You will set here your own query data url
	#project number 747974991802
	#47960995
	#18656176
	/*
	$gaUrl="https://www.googleapis.com/analytics/v3/data/mcf"
  ."?ids=ga:47960995"
  ."&metrics=mcf:totalConversions,mcf:totalConversionValue"
  ."&dimensions=mcf:conversionDate,mcf:adwordsDisplayUrlPath,mcf:keywordPath,mcf:source,mcf:keyword"
  ."&start-date={$statsStartDate}"
  ."&end-date={$statsEndDate}";
*/
#query 1

	$gaUrl="https://www.googleapis.com/analytics/v2.4/data?"
	."ids=ga:499429"
	."&dimensions=ga:adwordsCampaignID,ga:campaign"
	."&metrics=ga:impressions,ga:CTR,ga:adClicks,ga:visits,ga:entranceBounceRate,ga:transactions,ga:transactionRevenue,ga:adCost"
	."&sort=-ga:impressions"
	."&filters=ga:medium%3D%3Dreferral"
#	."&segment=gaid::10 OR dynamic::ga:medium%3D%3Dreferral"
	."&dynamic::ga:medium%3D%3Dreferral"
	."&start-date={$statsStartDate}"
	."&end-date={$statsEndDate}"
#	."&start-index=10"
	."&max-results=5"
	."&prettyprint=true";
	
	## query 2
/*
	$gaUrl="https://www.googleapis.com/analytics/v2.4/data?"
	."ids=ga:".$id
	."&dimensions=ga:adGroup"
	."&metrics=ga:impressions,ga:CTR,ga:adClicks,ga:visits,ga:entranceBounceRate,ga:transactions,ga:transactionRevenue,ga:adCost"
	."&sort=-ga:impressions"
	."&filters=ga:medium%3D%3Dreferral"
#	."&segment=gaid::10 OR dynamic::ga:medium%3D%3Dreferral"
	."&dynamic::ga:medium%3D%3Dreferral"
	."&start-date={$statsStartDate}"
	."&end-date={$statsEndDate}"
#	."&start-index=10"
	."&max-results=5"
	."&prettyprint=true";
#	."&sheet-name=keywords";
	*/
	/*
		
	$gaUrl = "https://www.google.com/analytics/feeds/data?" .
			  "ids=ga:".$id."&" .
			  "dimensions=ga%3ApagePath" .
			  "&metrics=ga%3Avisits&" .
			  "filters=ga%3ApagePath%3D~anunt%5C%3Fid%3D*&" .
			  "sort=-ga%3Avisits&" .
			  "start-date={$statsStartDate}&" .
			  "end-date={$statsEndDate}&" .
		      "max-results=5";*/
		
#print "<h5>".$gaUrl."</h5>";
	// Keep your connection data into a config array
	$config = array('email'      => $gaEmail,
					'password'   => $gaPassword,
					'requestUrl' => $gaUrl,
	);

	// Create a new GAnalytics object
	$ga = new GAnalytics($config);
	
	try {

		// Call the Google Analytics API request in here
		$gaResult = $ga->call();

		// If the call was successful - do your magic in here
		// You have to parse the Atom Feed XML response and gather you stats
		// This can be achieved with a SimpleXML tree traversing
		// or with a preg_match_call() to make your life easier
		preg_match_all("@<dxp:dimension name='ga:pagePath' value='/anunt\?id=([0-9]{1,})'/>@", $gaResult, $matches);

		// A dummy data rendering here...
		die($gaResult);
		var_dump($matches, $matches[1], $gaResult);

	} catch (Exception $e) {

#print_r($e);
		// Log your error here
		echo "GAnalytics Connection error ({$e->getCode()}): {$e->getMessage()}";
	}
?>