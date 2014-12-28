<?

Class GA_Custom
{
var $analytics;
var $account;
var $webproperty;
var $profile;
var $segment;
var $samplinglevel;
var $apitype;

function GA_Custom($analytics)
	{
	$this->analytics=$analytics;
	
	if(isset($_GET['account']))$this->account=$_GET['account'];
	if(isset($_GET['webproperty']))$this->webproperty=$_GET['webproperty'];
	if(isset($_GET['profile']))$this->profile=$_GET['profile'];
	if(isset($_GET['apitype']))$this->apitype=$_GET['apitype'];
	if(isset($_GET['segment']))$this->segment=$_GET['segment'];
	if(isset($_GET['samplinglevel']))$this->samplinglevel=$_GET['samplinglevel'];
	}
function header()
{
return '<!DOCTYPE html><html>
<head>
<meta charset="UTF-8">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
 <script src="lib.js"></script>
 <style>
 label{
 cursor:pointer;
 }
 </style>
</head>
<body>';
}
function footer()
{
return '</body>
 <script>
$(function() {

$("#start-date" ).datepicker({ dateFormat: \'yy-mm-dd\' });
$("#end-date" ).datepicker({ dateFormat: \'yy-mm-dd\' });
});
</script>
</html>';
}

function draw_accounts_select()
{
#<input type="text" id="account" name="account" readonly="readonly">
$accounts = $this->analytics->management_accounts->listManagementAccounts();
foreach($accounts->items as $item)
	{
	#pre($item);
	$data[$item->name]=$item;
	}
ksort($data);
#pre($data);
$out='<select name="account" id="account" onchange="goto(\''.$this->goto_lnk('account').'\'+this.value)">
<option>Select...</option>';
foreach($data as $k=>$v)
	$out.='<option value="'.$v->id.'" '.(($v->id==$this->account)?'selected':'').'>'.$v->name.'</option>';
$out.='</select>';	
return $out;
}

//Webproperty

function draw_webproperty()
{
#<input type="text" id="webproperty" name="webproperty" readonly="readonly">
$out='<select name="webproperty" id="webproperty" onchange="goto(\''.$this->goto_lnk('webproperty').'\'+this.value)">
<option value="0">Select...</option>';
if($this->account)
	{
	$webproperties = $this->analytics->management_webproperties
        ->listManagementWebproperties($this->account);
	foreach($webproperties->items as $item)
		{
		$out.='<option value="'.$item->id.'" '.(($item->id==$this->webproperty)?'selected':'').'>'.$item->name.'</option>';
		#pre($item);
		}
	
	#pre($data[$this->account]->childLink->href);
	}
$out.='</select>';
return $out;
}
function draw_profile()
{
if($this->account&&$this->webproperty)
{
$profiles = $this->analytics->management_profiles->listManagementProfiles($this->account, $this->webproperty);

$out='<select name="profile" id="profile" onchange="goto(\''.$this->goto_lnk('profile').'\'+this.value)">
<option>Select...</option>';
if($profiles->items)
foreach($profiles->items as $item)
	{
	$out.='<option value="'.$item->id.'" '.(($item->id==$this->profile)?'selected':'').'>'.$item->name.'</option>';
	}
$out.='</select>';
return $out;


}
}
function draw_dimensions_segment($code)
{
if(empty($code))return null;
return '<li><input type="checkbox" name="dimensions['.$code.']" id="'.$code.'" '.((isset($_POST['dimensions'][$code])&&($_POST['dimensions'][$code]=='on'))?'checked':'').'><label for="'.$code.'">'.$code.'</label></li>';
}

function draw_metrics_segment($code)
{
if(empty($code))return null;
return '<li><input type="checkbox" name="metrics['.$code.']" id="'.$code.'"'.((isset($_POST['metrics'][$code])&&($_POST['metrics'][$code]=='on'))?'checked':'').'><label for="'.$code.'">'.$code.'</label></li>';
}
function get_dimensions_mcf($type)
{
$hid='dimensions'.str_replace('/','_',str_replace(' ','_',$type));
$out='<label onclick="open_close(\'#'.$hid.'\')">'.$type.'</label>
<ul id="'.$hid.'" style="display:none;">';
#$out=$type.'<ul>';
	switch($type)
	{
	case 'Conversion Paths':
	$out.=$this->draw_dimensions_segment('mcf:basicChannelGroupingPath');
	$out.=$this->draw_dimensions_segment('mcf:sourcePath');
	$out.=$this->draw_dimensions_segment('mcf:mediumPath');
	$out.=$this->draw_dimensions_segment('mcf:sourceMediumPath');
	$out.=$this->draw_dimensions_segment('mcf:campaignPath');
	$out.=$this->draw_dimensions_segment('mcf:keywordPath');
	$out.=$this->draw_dimensions_segment('mcf:adwordsAdContentPath');
	$out.=$this->draw_dimensions_segment('mcf:adwordsAdGroupPath');
	$out.=$this->draw_dimensions_segment('mcf:adwordsCampaignPath');
	$out.=$this->draw_dimensions_segment('mcf:adwordsDisplayUrlPath');
	$out.=$this->draw_dimensions_segment('mcf:adwordsKeywordPath');
	$out.=$this->draw_dimensions_segment('mcf:adwordsMatchedSearchQueryPath');
	$out.=$this->draw_dimensions_segment('mcf:adwordsPlacementDomainPath');
	$out.=$this->draw_dimensions_segment('mcf:adwordsPlacementUrlPath');
	$out.=$this->draw_dimensions_segment('mcf:conversionDate');
	$out.=$this->draw_dimensions_segment('mcf:conversionGoalNumber');
	$out.=$this->draw_dimensions_segment('mcf:conversionType');
	$out.=$this->draw_dimensions_segment('mcf:pathLengthInInteractionsHistogram');
	$out.=$this->draw_dimensions_segment('mcf:timeLagInDaysHistogram');
	
	break;
	case 'Interactions':
	$out.=$this->draw_dimensions_segment('mcf:basicChannelGrouping');
	$out.=$this->draw_dimensions_segment('mcf:source');
	$out.=$this->draw_dimensions_segment('mcf:medium');
	$out.=$this->draw_dimensions_segment('mcf:sourceMedium');
	$out.=$this->draw_dimensions_segment('mcf:campaignName');
	$out.=$this->draw_dimensions_segment('mcf:keyword');
	$out.=$this->draw_dimensions_segment('mcf:adwordsAdContent');
	$out.=$this->draw_dimensions_segment('mcf:adwordsAdGroup');
	$out.=$this->draw_dimensions_segment('mcf:adwordsAdNetworkType');
	$out.=$this->draw_dimensions_segment('mcf:adwordsCampaign');
	$out.=$this->draw_dimensions_segment('mcf:adwordsDestinationUrl');
	$out.=$this->draw_dimensions_segment('mcf:adwordsDisplayUrl');
	$out.=$this->draw_dimensions_segment('mcf:adwordsKeyword');
	$out.=$this->draw_dimensions_segment('mcf:adwordsMatchedSearchQuery');
	$out.=$this->draw_dimensions_segment('mcf:adwordsMatchType');
	$out.=$this->draw_dimensions_segment('mcf:adwordsPlacementDomain');
	$out.=$this->draw_dimensions_segment('mcf:adwordsPlacementType');
	$out.=$this->draw_dimensions_segment('mcf:adwordsPlacementUrl');
	$out.=$this->draw_dimensions_segment('mcf:adwordsTargetingType');
	
	break;
	case 'Time':
	$out.=$this->draw_dimensions_segment('mcf:nthDay');
	break;
	}
	$out.='</ul>';
	return $out;

}

function get_dimensions($type)
{
$hid='dimensions'.str_replace('/','_',str_replace(' ','_',$type));
$out='<label onclick="open_close(\'#'.$hid.'\')">'.$type.'</label>
<ul id="'.$hid.'" style="display:none;">';
	switch($type)
	{
	case 'Visitor':
	$out.=$this->draw_dimensions_segment('ga:visitorType');
	$out.=$this->draw_dimensions_segment('ga:visitCount');
	$out.=$this->draw_dimensions_segment('ga:daysSinceLastVisit');
	$out.=$this->draw_dimensions_segment('ga:userDefinedValue');
	break;
	case 'Session':
	$out.=$this->draw_dimensions_segment('ga:visitLength');
	break;
	case 'Traffic Sources':
	$out.=$this->draw_dimensions_segment('ga:referralPath');
	$out.=$this->draw_dimensions_segment('ga:fullReferrer');
	$out.=$this->draw_dimensions_segment('ga:campaign');
	$out.=$this->draw_dimensions_segment('ga:source');
	$out.=$this->draw_dimensions_segment('ga:medium');
	$out.=$this->draw_dimensions_segment('ga:sourceMedium');
	$out.=$this->draw_dimensions_segment('ga:keyword');
	$out.=$this->draw_dimensions_segment('ga:adContent');
	$out.=$this->draw_dimensions_segment('ga:socialNetwork');
	$out.=$this->draw_dimensions_segment('ga:hasSocialSourceReferral');
	break;
	case 'AdWords':
	$out.=$this->draw_dimensions_segment('ga:adGroup');
	$out.=$this->draw_dimensions_segment('ga:adSlot');
	$out.=$this->draw_dimensions_segment('ga:adSlotPosition');
	$out.=$this->draw_dimensions_segment('ga:adDistributionNetwork');
	$out.=$this->draw_dimensions_segment('ga:adMatchType');
	$out.=$this->draw_dimensions_segment('ga:adMatchedQuery');
	$out.=$this->draw_dimensions_segment('ga:adPlacementDomain');
	$out.=$this->draw_dimensions_segment('ga:adPlacementUrl');
	$out.=$this->draw_dimensions_segment('ga:adFormat');
	$out.=$this->draw_dimensions_segment('ga:adTargetingType');
	$out.=$this->draw_dimensions_segment('ga:adTargetingOption');
	$out.=$this->draw_dimensions_segment('ga:adDisplayUrl');
	$out.=$this->draw_dimensions_segment('ga:adDestinationUrl');
	$out.=$this->draw_dimensions_segment('ga:adwordsCustomerID');
	$out.=$this->draw_dimensions_segment('ga:adwordsCampaignID');
	$out.=$this->draw_dimensions_segment('ga:adwordsAdGroupID');
	$out.=$this->draw_dimensions_segment('ga:adwordsCreativeID');
	$out.=$this->draw_dimensions_segment('ga:adwordsCriteriaID');
	break;
	case 'Goal Conversions':
	$out.=$this->draw_dimensions_segment('ga:goalCompletionLocation');
	$out.=$this->draw_dimensions_segment('ga:goalPreviousStep1');
	$out.=$this->draw_dimensions_segment('ga:goalPreviousStep2');
	$out.=$this->draw_dimensions_segment('ga:goalPreviousStep3');
	break;
	case 'Platform / Device':
	$out.=$this->draw_dimensions_segment('ga:browser');
	$out.=$this->draw_dimensions_segment('ga:browserVersion');
	$out.=$this->draw_dimensions_segment('ga:operatingSystem');
	$out.=$this->draw_dimensions_segment('ga:operatingSystemVersion');
	$out.=$this->draw_dimensions_segment('ga:deviceCategory');
	$out.=$this->draw_dimensions_segment('ga:isMobile');
	$out.=$this->draw_dimensions_segment('ga:isTablet');
	$out.=$this->draw_dimensions_segment('ga:mobileDeviceBranding');
	$out.=$this->draw_dimensions_segment('ga:mobileDeviceMarketingName');
	$out.=$this->draw_dimensions_segment('ga:mobileDeviceModel');
	$out.=$this->draw_dimensions_segment('ga:mobileInputSelector');
	$out.=$this->draw_dimensions_segment('ga:mobileDeviceInfo');
	break;
	case 'Geo / Network':
	$out.=$this->draw_dimensions_segment('ga:continent');
	$out.=$this->draw_dimensions_segment('ga:subContinent');
	$out.=$this->draw_dimensions_segment('ga:country');
	$out.=$this->draw_dimensions_segment('ga:region');
	$out.=$this->draw_dimensions_segment('ga:metro');
	$out.=$this->draw_dimensions_segment('ga:city');
	$out.=$this->draw_dimensions_segment('ga:latitude');
	$out.=$this->draw_dimensions_segment('ga:longitude');
	$out.=$this->draw_dimensions_segment('ga:networkDomain');
	$out.=$this->draw_dimensions_segment('ga:networkLocation');
	break;
	case 'System':
	$out.=$this->draw_dimensions_segment('ga:flashVersion');
	$out.=$this->draw_dimensions_segment('ga:javaEnabled');
	$out.=$this->draw_dimensions_segment('ga:language');
	$out.=$this->draw_dimensions_segment('ga:screenColors');
	$out.=$this->draw_dimensions_segment('ga:screenResolution');
	break;
	case 'Social Activities':
	$out.=$this->draw_dimensions_segment('ga:socialActivityEndorsingUrl');
	$out.=$this->draw_dimensions_segment('ga:socialActivityDisplayName');
	$out.=$this->draw_dimensions_segment('ga:socialActivityPost');
	$out.=$this->draw_dimensions_segment('ga:socialActivityTimestamp');
	$out.=$this->draw_dimensions_segment('ga:socialActivityUserHandle');
	$out.=$this->draw_dimensions_segment('ga:socialActivityUserPhotoUrl');
	$out.=$this->draw_dimensions_segment('ga:socialActivityUserProfileUrl');
	$out.=$this->draw_dimensions_segment('ga:socialActivityContentUrl');
	$out.=$this->draw_dimensions_segment('ga:socialActivityTagsSummary');
	$out.=$this->draw_dimensions_segment('ga:socialActivityAction');
	$out.=$this->draw_dimensions_segment('ga:socialActivityNetworkAction');
	break;
	case 'Page Tracking':
	$out.=$this->draw_dimensions_segment('ga:hostname');
	$out.=$this->draw_dimensions_segment('ga:pagePath');
	$out.=$this->draw_dimensions_segment('ga:pagePathLevel1');
	$out.=$this->draw_dimensions_segment('ga:pagePathLevel2');
	$out.=$this->draw_dimensions_segment('ga:pagePathLevel3');
	$out.=$this->draw_dimensions_segment('ga:pagePathLevel4');
	$out.=$this->draw_dimensions_segment('ga:pageTitle');
	$out.=$this->draw_dimensions_segment('ga:landingPagePath');
	$out.=$this->draw_dimensions_segment('ga:secondPagePath');
	$out.=$this->draw_dimensions_segment('ga:exitPagePath');
	$out.=$this->draw_dimensions_segment('ga:previousPagePath');
	$out.=$this->draw_dimensions_segment('ga:nextPagePath');
	$out.=$this->draw_dimensions_segment('ga:pageDepth');
	break;
	case 'Internal Search':
	$out.=$this->draw_dimensions_segment('ga:searchUsed');
	$out.=$this->draw_dimensions_segment('ga:searchKeyword');
	$out.=$this->draw_dimensions_segment('ga:searchKeywordRefinement');
	$out.=$this->draw_dimensions_segment('ga:searchCategory');
	$out.=$this->draw_dimensions_segment('ga:searchStartPage');
	$out.=$this->draw_dimensions_segment('ga:searchDestinationPage');
	break;
	case 'App Tracking':
	$out.=$this->draw_dimensions_segment('ga:appName');
	$out.=$this->draw_dimensions_segment('ga:appId');
	$out.=$this->draw_dimensions_segment('ga:appVersion');
	$out.=$this->draw_dimensions_segment('ga:appInstallerId');
	$out.=$this->draw_dimensions_segment('ga:landingScreenName');
	$out.=$this->draw_dimensions_segment('ga:screenName');
	$out.=$this->draw_dimensions_segment('ga:exitScreenName');
	$out.=$this->draw_dimensions_segment('ga:screenDepth');
	break;
	case 'Event Tracking':
	$out.=$this->draw_dimensions_segment('ga:eventCategory');
	$out.=$this->draw_dimensions_segment('ga:eventAction');
	$out.=$this->draw_dimensions_segment('ga:eventLabel');
	break;
	case 'Ecommerce':
	$out.=$this->draw_dimensions_segment('ga:transactionId');
	$out.=$this->draw_dimensions_segment('ga:affiliation');
	$out.=$this->draw_dimensions_segment('ga:visitsToTransaction');
	$out.=$this->draw_dimensions_segment('ga:daysToTransaction');
	$out.=$this->draw_dimensions_segment('ga:productSku');
	$out.=$this->draw_dimensions_segment('ga:productName');
	$out.=$this->draw_dimensions_segment('ga:productCategory');
	$out.=$this->draw_dimensions_segment('ga:currencyCode');
	break;
	case 'Social Interactions':
	$out.=$this->draw_dimensions_segment('ga:socialInteractionNetwork');
	$out.=$this->draw_dimensions_segment('ga:socialInteractionAction');
	$out.=$this->draw_dimensions_segment('ga:socialInteractionNetworkAction');
	$out.=$this->draw_dimensions_segment('ga:socialInteractionTarget');
	$out.=$this->draw_dimensions_segment('ga:socialEngagementType');
	break;
	case 'User Timings':
	$out.=$this->draw_dimensions_segment('ga:userTimingCategory');
	$out.=$this->draw_dimensions_segment('ga:userTimingLabel');
	$out.=$this->draw_dimensions_segment('ga:userTimingVariable');
	break;
	case 'Exception Tracking':
	$out.=$this->draw_dimensions_segment('ga:exceptionDescription');
	break;
	case 'Experiments':
	$out.=$this->draw_dimensions_segment('ga:experimentId');
	$out.=$this->draw_dimensions_segment('ga:experimentVariant');
	break;
	case 'Custom Variables or Columns':
	$out.=$this->draw_dimensions_segment('ga:customVarNameXX');
	$out.=$this->draw_dimensions_segment('ga:customVarValueXX');
	$out.=$this->draw_dimensions_segment('ga:dimensionXX');
	break;
	case 'Time':
	$out.=$this->draw_dimensions_segment('ga:date');
	$out.=$this->draw_dimensions_segment('ga:year');
	$out.=$this->draw_dimensions_segment('ga:month');
	$out.=$this->draw_dimensions_segment('ga:week');
	$out.=$this->draw_dimensions_segment('ga:day');
	$out.=$this->draw_dimensions_segment('ga:hour');
	$out.=$this->draw_dimensions_segment('ga:yearMonth');
	$out.=$this->draw_dimensions_segment('ga:yearWeek');
	$out.=$this->draw_dimensions_segment('ga:dateHour');
	$out.=$this->draw_dimensions_segment('ga:nthMonth');
	$out.=$this->draw_dimensions_segment('ga:nthWeek');
	$out.=$this->draw_dimensions_segment('ga:nthDay');
	$out.=$this->draw_dimensions_segment('ga:isoWeek');
	$out.=$this->draw_dimensions_segment('ga:dayOfWeek');
	$out.=$this->draw_dimensions_segment('ga:dayOfWeekName');
	break;
	}
$out.='</ul>';
return $out;
}
function get_metrics_mcf($type)
{
$hid='metrics'.str_replace('/','_',str_replace(' ','_',$type));
$out='<label onclick="open_close(\'#'.$hid.'\')">'.$type.'</label>
<ul id="'.$hid.'" style="display:none;">';
#@$out=$type.'<ul>';
	switch($type)
	{
	case 'Conversion Paths':
	$out.=$this->draw_metrics_segment('mcf:totalConversions');
	$out.=$this->draw_metrics_segment('mcf:totalConversionValue');
	
	break;
	case 'Interactions':
	$out.=$this->draw_metrics_segment('mcf:assistedConversions');
	$out.=$this->draw_metrics_segment('mcf:assistedValue');
	$out.=$this->draw_metrics_segment('mcf:firstInteractionConversions');
	$out.=$this->draw_metrics_segment('mcf:firstInteractionValue');
	$out.=$this->draw_metrics_segment('mcf:lastInteractionConversions');
	$out.=$this->draw_metrics_segment('mcf:lastInteractionValue');
	
	break;
	}
$out.='</ul>';
return $out;
}
function get_metrics($type)
{
$hid='metrics'.str_replace('/','_',str_replace(' ','_',$type));
$out='<label onclick="open_close(\'#'.$hid.'\')">'.$type.'</label>
<ul id="'.$hid.'" style="display:none;">';
#$out=$type.'<ul>';
	switch($type)
	{
	case 'Visitor':
	$out.=$this->draw_metrics_segment('ga:visitors');
	$out.=$this->draw_metrics_segment('ga:newVisits');
	$out.=$this->draw_metrics_segment('ga:percentNewVisits');
	break;
	case 'Session':
	$out.=$this->draw_metrics_segment('ga:visits');
	$out.=$this->draw_metrics_segment('ga:bounces');
	$out.=$this->draw_metrics_segment('ga:timeOnSite');
	$out.=$this->draw_metrics_segment('ga:entranceBounceRate');
	$out.=$this->draw_metrics_segment('ga:visitBounceRate');
	$out.=$this->draw_metrics_segment('ga:avgTimeOnSite');
	break;
	case 'Traffic Sources':
	$out.=$this->draw_metrics_segment('ga:organicSearches');
	break;
	case 'AdWords':
	$out.=$this->draw_metrics_segment('ga:impressions');
	$out.=$this->draw_metrics_segment('ga:adClicks');
	$out.=$this->draw_metrics_segment('ga:adCost');
	$out.=$this->draw_metrics_segment('ga:CPM');
	$out.=$this->draw_metrics_segment('ga:CPC');
	$out.=$this->draw_metrics_segment('ga:CTR');
	$out.=$this->draw_metrics_segment('ga:costPerTransaction');
	$out.=$this->draw_metrics_segment('ga:costPerGoalConversion');
	$out.=$this->draw_metrics_segment('ga:costPerConversion');
	$out.=$this->draw_metrics_segment('ga:RPC');
	$out.=$this->draw_metrics_segment('ga:ROI');
	$out.=$this->draw_metrics_segment('ga:margin');
	break;
	case 'Goal Conversions':
	$out.=$this->draw_metrics_segment('ga:goalXXStarts');
	$out.=$this->draw_metrics_segment('ga:goalStartsAll');
	$out.=$this->draw_metrics_segment('ga:goalXXCompletions');
	$out.=$this->draw_metrics_segment('ga:goalCompletionsAll');
	$out.=$this->draw_metrics_segment('ga:goalXXValue');
	$out.=$this->draw_metrics_segment('ga:goalValueAll');
	$out.=$this->draw_metrics_segment('ga:goalValuePerVisit');
	$out.=$this->draw_metrics_segment('ga:goalXXConversionRate');
	$out.=$this->draw_metrics_segment('ga:goalConversionRateAll');
	$out.=$this->draw_metrics_segment('ga:goalXXAbandons');
	$out.=$this->draw_metrics_segment('ga:goalAbandonsAll');
	$out.=$this->draw_metrics_segment('ga:goalXXAbandonRate');
	$out.=$this->draw_metrics_segment('ga:goalAbandonRateAll');
	break;
	case 'Social Activities':
	$out.=$this->draw_metrics_segment('ga:socialActivities');
	break;
	case 'Page Tracking':
	$out.=$this->draw_metrics_segment('ga:entrances');
	$out.=$this->draw_metrics_segment('ga:pageviews');
	$out.=$this->draw_metrics_segment('ga:uniquePageviews');
	$out.=$this->draw_metrics_segment('ga:timeOnPage');
	$out.=$this->draw_metrics_segment('ga:exits');
	$out.=$this->draw_metrics_segment('ga:entranceRate');
	$out.=$this->draw_metrics_segment('ga:pageviewsPerVisit');
	$out.=$this->draw_metrics_segment('ga:pageValue');
	$out.=$this->draw_metrics_segment('ga:avgTimeOnPage');
	$out.=$this->draw_metrics_segment('ga:exitRate');
	break;
	case 'Internal Search':
	$out.=$this->draw_metrics_segment('ga:searchResultViews');
	$out.=$this->draw_metrics_segment('ga:searchUniques');
	$out.=$this->draw_metrics_segment('ga:searchVisits');
	$out.=$this->draw_metrics_segment('ga:searchDepth');
	$out.=$this->draw_metrics_segment('ga:searchRefinements');
	$out.=$this->draw_metrics_segment('ga:searchDuration');
	$out.=$this->draw_metrics_segment('ga:searchExits');
	$out.=$this->draw_metrics_segment('ga:avgSearchResultViews');
	$out.=$this->draw_metrics_segment('ga:percentVisitsWithSearch');
	$out.=$this->draw_metrics_segment('ga:avgSearchDepth');
	$out.=$this->draw_metrics_segment('ga:percentSearchRefinements');
	$out.=$this->draw_metrics_segment('ga:avgSearchDuration');
	$out.=$this->draw_metrics_segment('ga:searchExitRate');
	$out.=$this->draw_metrics_segment('ga:searchGoalXXConversionRate');
	$out.=$this->draw_metrics_segment('ga:searchGoalConversionRateAll');
	$out.=$this->draw_metrics_segment('ga:goalValueAllPerSearch');
	break;
	case 'Site Speed':
	$out.=$this->draw_metrics_segment('ga:pageLoadTime');
	$out.=$this->draw_metrics_segment('ga:pageLoadSample');
	$out.=$this->draw_metrics_segment('ga:domainLookupTime');
	$out.=$this->draw_metrics_segment('ga:pageDownloadTime');
	$out.=$this->draw_metrics_segment('ga:redirectionTime');
	$out.=$this->draw_metrics_segment('ga:serverConnectionTime');
	$out.=$this->draw_metrics_segment('ga:serverResponseTime');
	$out.=$this->draw_metrics_segment('ga:speedMetricsSample');
	$out.=$this->draw_metrics_segment('ga:domInteractiveTime');
	$out.=$this->draw_metrics_segment('ga:domContentLoadedTime');
	$out.=$this->draw_metrics_segment('ga:domLatencyMetricsSample');
	$out.=$this->draw_metrics_segment('ga:avgPageLoadTime');
	$out.=$this->draw_metrics_segment('ga:avgDomainLookupTime');
	$out.=$this->draw_metrics_segment('ga:avgPageDownloadTime');
	$out.=$this->draw_metrics_segment('ga:avgRedirectionTime');
	$out.=$this->draw_metrics_segment('ga:avgServerConnectionTime');
	$out.=$this->draw_metrics_segment('ga:avgServerResponseTime');
	$out.=$this->draw_metrics_segment('ga:avgDomInteractiveTime');
	$out.=$this->draw_metrics_segment('ga:avgDomContentLoadedTime');
	break;
	case 'App Tracking':
	$out.=$this->draw_metrics_segment('ga:screenviews');
	$out.=$this->draw_metrics_segment('ga:uniqueScreenviews');
	$out.=$this->draw_metrics_segment('ga:timeOnScreen');
	$out.=$this->draw_metrics_segment('ga:avgScreenviewDuration');
	$out.=$this->draw_metrics_segment('ga:screenviewsPerSession');
	break;
	case 'Event Tracking':
	$out.=$this->draw_metrics_segment('ga:totalEvents');
	$out.=$this->draw_metrics_segment('ga:uniqueEvents');
	$out.=$this->draw_metrics_segment('ga:eventValue');
	$out.=$this->draw_metrics_segment('ga:visitsWithEvent');
	$out.=$this->draw_metrics_segment('ga:avgEventValue');
	$out.=$this->draw_metrics_segment('ga:eventsPerVisitWithEvent');
	break;
	case 'Ecommerce':
	$out.=$this->draw_metrics_segment('ga:transactions');
	$out.=$this->draw_metrics_segment('ga:transactionRevenue');
	$out.=$this->draw_metrics_segment('ga:transactionShipping');
	$out.=$this->draw_metrics_segment('ga:transactionTax');
	$out.=$this->draw_metrics_segment('ga:itemQuantity');
	$out.=$this->draw_metrics_segment('ga:uniquePurchases');
	$out.=$this->draw_metrics_segment('ga:itemRevenue');
	$out.=$this->draw_metrics_segment('ga:localItemRevenue');
	$out.=$this->draw_metrics_segment('ga:localTransactionRevenue');
	$out.=$this->draw_metrics_segment('ga:localTransactionTax');
	$out.=$this->draw_metrics_segment('ga:localTransactionShipping');
	$out.=$this->draw_metrics_segment('ga:transactionsPerVisit');
	$out.=$this->draw_metrics_segment('ga:revenuePerTransaction');
	$out.=$this->draw_metrics_segment('ga:transactionRevenuePerVisit');
	$out.=$this->draw_metrics_segment('ga:totalValue');
	$out.=$this->draw_metrics_segment('ga:revenuePerItem');
	$out.=$this->draw_metrics_segment('ga:itemsPerPurchase');
	break;
	case 'Social Interactions':
	$out.=$this->draw_metrics_segment('ga:socialInteractions');
	$out.=$this->draw_metrics_segment('ga:uniqueSocialInteractions');
	$out.=$this->draw_metrics_segment('ga:socialInteractionsPerVisit');
	break;
	case 'User Timings':
	$out.=$this->draw_metrics_segment('ga:userTimingValue');
	$out.=$this->draw_metrics_segment('ga:userTimingSample');
	$out.=$this->draw_metrics_segment('ga:avgUserTimingValue');
	break;
	case 'Exception Tracking':
	$out.=$this->draw_metrics_segment('ga:exceptions');
	$out.=$this->draw_metrics_segment('ga:fatalExceptions');
	$out.=$this->draw_metrics_segment('ga:exceptionsPerScreenview');
	$out.=$this->draw_metrics_segment('ga:fatalExceptionsPerScreenview');
	break;
	
	case 'Custom Variables or Columns':
	$out.=$this->draw_metrics_segment('ga:metricXX');
	break;
	}
$out.='</ul>';
return $out;
}
function draw_dimensions()
{
$out='<div style="height:200px;overflow-y:scroll;">';
$out.='<ul>';
$out.='<li>'.$this->get_dimensions('Visitor').'</li>';
$out.='<li>'.$this->get_dimensions('Session').'</li>';
$out.='<li>'.$this->get_dimensions('Traffic Sources').'</li>';
$out.='<li>'.$this->get_dimensions('AdWords').'</li>';
$out.='<li>'.$this->get_dimensions('Goal Conversions').'</li>';
$out.='<li>'.$this->get_dimensions('Platform / Device').'</li>';
$out.='<li>'.$this->get_dimensions('Geo / Network').'</li>';
$out.='<li>'.$this->get_dimensions('System').'</li>';
$out.='<li>'.$this->get_dimensions('Social Activities').'</li>';
$out.='<li>'.$this->get_dimensions('Page Tracking').'</li>';
$out.='<li>'.$this->get_dimensions('Internal Search').'</li>';
#$out.='<li>'.$this->get_dimensions('Site Speed').'</li>';
$out.='<li>'.$this->get_dimensions('App Tracking').'</li>';
$out.='<li>'.$this->get_dimensions('Event Tracking').'</li>';
$out.='<li>'.$this->get_dimensions('Ecommerce').'</li>';
$out.='<li>'.$this->get_dimensions('Social Interactions').'</li>';
$out.='<li>'.$this->get_dimensions('User Timings').'</li>';
$out.='<li>'.$this->get_dimensions('Exception Tracking').'</li>';
$out.='<li>'.$this->get_dimensions('Experiments').'</li>';
$out.='<li>'.$this->get_dimensions('Custom Variables or Columns').'</li>';
$out.='<li>'.$this->get_dimensions('Time').'</li>';
$out.='</ul>';
$out.='</div>';
return $out;
}
function draw_dimensions_mcf()
{
$out='<div style="height:200px;overflow-y:scroll;">';
$out.='<ul>';
$out.='<li>'.$this->get_dimensions_mcf('Conversion Paths').'</li>';
$out.='<li>'.$this->get_dimensions_mcf('Interactions').'</li>';
$out.='<li>'.$this->get_dimensions_mcf('Time').'</li>';
$out.='</ul>';
$out.='</div>';
return $out;
}
function draw_metrics()
{
$out='<div  class="metrics" style="height:200px;overflow-y:scroll;">';
$out.='<ul>';
$out.='<li>'.$this->get_metrics('Visitor').'</li>';
$out.='<li>'.$this->get_metrics('Session').'</li>';
$out.='<li>'.$this->get_metrics('Traffic Sources').'</li>';
$out.='<li>'.$this->get_metrics('AdWords').'</li>';
$out.='<li>'.$this->get_metrics('Goal Conversions').'</li>';
#$out.='<li>'.$this->get_metrics('Platform / Device').'</li>';
#$out.='<li>'.$this->get_metrics('Geo / Network').'</li>';
#$out.='<li>'.$this->get_metrics('System').'</li>';
$out.='<li>'.$this->get_metrics('Social Activities').'</li>';
$out.='<li>'.$this->get_metrics('Page Tracking').'</li>';
$out.='<li>'.$this->get_metrics('Internal Search').'</li>';
$out.='<li>'.$this->get_metrics('Site Speed').'</li>';
$out.='<li>'.$this->get_metrics('App Tracking').'</li>';
$out.='<li>'.$this->get_metrics('Event Tracking').'</li>';
$out.='<li>'.$this->get_metrics('Ecommerce').'</li>';
$out.='<li>'.$this->get_metrics('Social Interactions').'</li>';
$out.='<li>'.$this->get_metrics('User Timings').'</li>';
$out.='<li>'.$this->get_metrics('Exception Tracking').'</li>';
#$out.='<li>'.$this->get_metrics('Experiments').'</li>';
$out.='<li>'.$this->get_metrics('Custom Variables or Columns').'</li>';
#$out.='<li>'.$this->get_metrics('Time').'</li>';
$out.='</ul>';
$out.='</div>';
return $out;
}
function draw_metrics_mcf()
{
$out='<div  class="metrics" style="height:200px;overflow-y:scroll;">';
$out.='<ul>';
$out.='<li>'.$this->get_metrics_mcf('Conversion Paths').'</li>';
$out.='<li>'.$this->get_metrics_mcf('Interactions').'</li>';
$out.='</ul>';
$out.='</div>';
return $out;
}
function draw_segments()
{
#analytics.management.segments.list

$segments=$this->analytics->management_segments->listManagementSegments();
$out='<select name="segment" id="segment"  onchange="goto(\''.$this->goto_lnk('segment').'\'+this.value)">
<option>Select...</option>';
foreach($segments->items as $s)
	{
	$out.='<option value="'.$s->segmentId.'" '.(($s->segmentId==$this->segment)?'selected':'').'>'.$s->name.'</option>';	
	}
$out.='</select>';	
return $out;
}
function get_results()
{
if(isset($_POST['ids']))
	{
	$optParams=array();
	
	if(isset($_POST['dimensions']))
		{
		$optParams['dimensions']=implode(',',$this->extract_post_vars($_POST['dimensions']));
		}
	if(!empty($_POST['sort']))$optParams['sort']=$_POST['sort'];
	#$filters_value=mb_substr($_POST['filters'],mb_strpos($_POST['filters'],'=')-1);
	
	
	if(!empty($_POST['filters']))$optParams['filters']=$_POST['filters'];
	if(!empty($_POST['max-results']))$optParams['max-results']=$_POST['max-results'];
	$metrics=(isset($_POST['metrics'])?implode(',',$this->extract_post_vars($_POST['metrics'])):'ga:visits');
	if($_POST['apitype']=='mcf')
	{
	#if(!empty($_POST['samplinglevel']))$optParams['samplinglevel']=$_POST['samplinglevel'];
	if(!empty($_POST['']))$optParams['fields']=$_POST['fields'];

	return $this->analytics->data_mcf->get(
       $_POST['ids'],
       $_POST['start-date'],
       $_POST['end-date'],
       $metrics,$optParams);
	
	}else{
	return $this->analytics->data_ga->get(
       $_POST['ids'],
       $_POST['start-date'],
       $_POST['end-date'],
       $metrics,$optParams);
	
	}
     

  
	
	}
}
function extract_post_vars($post)
{
foreach($post as $k=>$v)
	{
	if($v=='on')$results[]=$k;
	}
if(count($results))return $results;
else return null;	
}
function print_results($headers,$rows)
{
$out='<table border=1>
	<tr>';
	foreach($headers as $header)
		{
		$out.='<th>'.$header->getName().'</th>';
		}
	$out.='</tr>';	
	#pre($rows);
	foreach($rows as $row)
		{
		$out.='<tr>';
		foreach($row as $cell)$out.='<td>'.htmlspecialchars($cell, ENT_NOQUOTES).'</td>';
		$out.='</tr>';
		}
	$out.='</table>';
return $out;
}
function export_xls($results)
{
if($this->apitype=='mcf')
	{
	$headers=$results->getColumnHeaders();
	/*
	foreach($results->getColumnHeaders() as $header)	
		{
		if($header->columnType=='METRIC')$headers[]=$header;
		}*/
	$rowso=$results->getRows();
	$rows=$this->preprowso($rowso);
	}else{
	$rows = $results->getRows();
	$headers = $results->getColumnHeaders();
	}
	$xl = new xls();
	$i = 1;
	foreach ($headers as $header) {
		    $xl->add_cell($i . ":1", $header->name);
		    $i++;
	}
	$rn=2;
	foreach($rows as $row)
	{
	$c=1;
	foreach($row as $r)
		{
		$xl->add_cell($c . ":" . $rn, $r);
		#pre($r);
		$c++;
		}
	$rn++;
	
	}
	
//force download the file with specified name
	$xl->execute("export.xls");
	die();	
}
function preprowso($rowso)
{
$i=0;
foreach($rowso as $r)
		{
		
		foreach($r as $k=>$v)
			{
			if(is_numeric($k))
				{
				if(isset($v['primitiveValue']))$rows[$i][]=$v['primitiveValue'];
				elseif(isset($v['conversionPathValue']))
					{
					
					foreach($v['conversionPathValue'] as $item)
						$nodeValueA[]=$item["nodeValue"];
					$rows[$i][]=implode('|',$nodeValueA);
					unset($nodeValueA);
					}
				}
			}
		$i++;
		}
return $rows;
}
function draw_results($results)
{
$out='';
if(!$results)return null;
if($this->apitype=='mcf')
{
if($results->totalResults>0)
	{
	$headers=$results->getColumnHeaders();
	/*
	foreach($results->getColumnHeaders() as $header)	
		{
		$headers[]=$header;
		}
		*/
	$rowso=$results->getRows();
	$rows=$this->preprowso($rowso);
	$out.=$this->print_results($headers,$rows);
	}else{
	  $out.= '<p>No results found.</p>';
	}

}else{


if (count($results->getRows()) > 0) {
  
    $rows = $results->getRows();
	$headers = $results->getColumnHeaders();
	
	$out.=$this->print_results($headers,$rows);
  } else {
     $out.= '<p>No results found.</p>';
  }
}
  return $out;
}

function draw_apitype()
{
$out='<select name="apitype" id="apitype" onchange="goto(\''.$this->goto_lnk('apitype').'\'+this.value)">
<option>Select...</option>';

$out.='<option value="core" '.(($this->apitype=='core')?'selected':'').'>Core Reporting API v3</option>';
$out.='<option value="mcf" '.(($this->apitype=='mcf')?'selected':'').'>MCF Reporting API v3</option>';	
$out.='</select>';
return $out;
}
function goto_lnk($pname)
{
$url="?";
if($this->account&&($pname!='account'))$params[]='account='.$this->account;
if($this->webproperty&&($pname!='webproperty')&&($pname!='account'))$params[]='webproperty='.$this->webproperty;
if($this->profile&&($pname!='profile')&&($pname!='account'))$params[]='profile='.$this->profile;
if($this->segment&&($pname!='segment')&&($pname!='account'))$params[]='segment='.$this->segment;
if($this->apitype&&($pname!='apitype'))$params[]='apitype='.$this->apitype;
if(isset($params))$url.=implode('&',$params);
$url.='&'.$pname."=";
return $url;
}
function draw_samplingLevel()
{
$out='<select name="samplinglevel"  onchange="goto(\''.$this->goto_lnk('samplinglevel').'\'+this.value)">';
$out.='<option value="0">select</option>';
$out.='<option value="DEFAULT"'.(($this->samplinglevel=='DEFAULT')?' selected':'').'>DEFAULT</option>';
$out.='<option value="FASTER"'.(($this->samplinglevel=='FASTER')?' selected':'').'>FASTER</option>';
$out.='<option value="HIGHER_PRECISION"'.(($this->samplinglevel=='HIGHER_PRECISION')?' selected':'').'>HIGHER_PRECISION</option>';
$out.='</select>';
return $out;
}
function draw_fields()
{
$out='<input type="text" name="fields" id="fields" style="width:300px;" value="'.(isset($_POST['fields'])?$_POST['fields']:'').'"> <a href=javascript:// onclick=open_fields_editor()>open editor</a>
<div id="dialog-modal"></div>';
return $out;
}
//Main Request Form

function draw_form()
{
$out='
<form action="" method="POST">
<table border=1>

<tr><th>Account</th><td>'.$this->draw_accounts_select().'</td></tr>
<tr><th>Web Property</th><td>'.$this->draw_webproperty().'</td></tr>
<tr><th>Profile</th><td>'.$this->draw_profile().'</td></tr>
<tr><th>API Type</th><td>'.$this->draw_apitype().'</td></tr>
</table>

<table border=1>
<tr><th>ids</th><td><input type="text" id="ids" name="ids" value="'.(($this->profile)?'ga:'.$this->profile:'').'" readonly="readonly"></td></tr>';
switch($this->apitype)
{
case 'core':
$out.='<tr><th>segment</th><td>'.$this->draw_segments().'</td></tr>
<tr><th>dimensions</th><td>'.$this->draw_dimensions().'</td></tr>
<tr><th>metrics</th><td>'.$this->draw_metrics().'</td></tr>';
break;
case 'mcf':
$out.='<tr><th>samplingLevel</th><td>'.$this->draw_samplingLevel().'</td></tr>
<tr><th>fields</th><td>'.$this->draw_fields().'</td></tr>
<tr><th>dimensions</th><td>'.$this->draw_dimensions_mcf().'</td></tr>
<tr><th>metrics</th><td>'.$this->draw_metrics_mcf().'</td></tr>';
break;
}


$out.='<tr><th>filters</th><td><input type="text" id="filters" name="filters" value="'.(isset($_POST['filters'])?$_POST['filters']:'').'"><br><small>(Equals: ga:country==Israel<br>
Regular Expression: ga:city=~^New.*)</small></td></tr>
<tr><th>sort</th><td><input type="text" id="sort" name="sort"></td></tr>
<tr><th>start-date<small>(Format: 2013-12-31)</small></th><td><input type="text" id="start-date" name="start-date" value="'.date('Y-m-d',strtotime("-1 month")).'" readonly="readonly"></td></tr>
<tr><th>end-date<small>(Format: 2013-12-31)</small></th><td><input type="text" id="end-date" name="end-date" value="'.date('Y-m-d').'" readonly="readonly"></td></tr>
<tr><th>start-index</th><td><input type="text" id="start-index" name="start-index"></td></tr>
<tr><th>max-results</th><td><input type="text" id="max-results" name="max-results" value="50000"></td></tr>
<tr><td colspan=2><input type="submit" name="op" value="Print Results" onclick="return chk_form();">&nbsp;<input type="submit" name="op" value="Export XLS" onclick="return chk_form();"></td></tr>
</table>
</form>
';
return $out;
}
function draw_error($e)
{
return "<div style='width:800px;word-wrap: break-word;'><h4 style='color:red;'>".$e."</h4></div>";
}
}
?>