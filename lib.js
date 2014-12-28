function goto(url)
{

document.location.href=url;
}
function chk_form()
{
if(jQuery("#ids").val().length==0)
{
alert("Error : Need select Account,Web Property and Profile first!");return false;
}
var mertics_accepted=0;
jQuery(".metrics ul li ul li input").each(function (id,input){

if(jQuery(input).is(":checked")){mertics_accepted=1;}
});
if(mertics_accepted==0){alert("Error : Need select some metrics...");return false;}



return true;
}
function open_fields_editor()
{
$.get("fields_edit.html", function( data ) {
$( "#dialog-modal" ).html( data );

});
$( "#dialog-modal" ).dialog({
height: 600,
width: 600,
modal: true
});
if($('#fields').val().length>0)popup_fields($('#fields').val());
}

function popup_fields(val)
{

}
function close_fields_editor()
{
var selected = new Array();
var i=0
$('#dialog-modal input').each(function (){

if($(this).is(':checked')&&($(this).attr('type')=='checkbox')&&($(this).attr('id')!='gwt-uid-19'))
	{
	var label=$('label[for="'+$(this).attr('id')+'"]');
	selected[i]=label.text();
	i++;
	}

});
selected=prepare_fields(selected);

$('#fields').val(selected.join(","));
 $( "#dialog-modal" ).dialog('close'); 	
}

function select_all(el,id)
{


$('.'+id+' span input').each(function (){
//$(this).attr('checked',$(el).is(':checked'));
this.checked=$(el).is(':checked');
});

}
function select_alll(el){
$('.all span input').each(function (){
//$(this).attr('checked',$(el).is(':checked'));
this.checked=$(el).is(':checked');
});
}
function prepare_fields_help(headername,ar,selected)
{
var x=0;
for(var i=0;i<selected.length;i++)
		{
		if($.inArray(selected[i],ar)>-1)
			{
			x++;
			}
		}
if(x==ar.length)
	{
	for(var i=0;i<selected.length;i++)
		if($.inArray(selected[i],ar)>-1)delete selected[i];
			
		
	selected.push(headername);
	}else if(x==1)
	{
	for(var i=0;i<selected.length;i++)
		if($.inArray(selected[i],ar)>-1){var saved=selected[i];delete selected[i];}
	selected.push(headername+"/"+saved);	
	}else if(x>1)
	{
	var saved=new Array;
	for(var i=0;i<selected.length;i++)
		if($.inArray(selected[i],ar)>-1){saved.push(selected[i]);delete selected[i];}
	selected.push(headername+"("+saved.join(',')+")");
	}

selected = selected.filter(function(v){return v!==''});	
return selected;
}
function prepare_fields(selected)
{
var headers=['query','columnHeaders','profileInfo'];
for(var i=0;i<selected.length;i++)
		if($.inArray(selected[i],headers)>-1){delete selected[i];}


var columnHeaders=['columnType','dataType','name'];
var profileInfo=['accountId','internalWebPropertyId','profileId','profileName','tableId','webPropertyId'];
var query=['dimensions','end-date','filters','ids','max-results','metrics','samplingLevel','segment','sort','start-date','start-index'];
selected=prepare_fields_help('columnHeaders',columnHeaders,selected);
selected=prepare_fields_help('profileInfo',profileInfo,selected);
selected=prepare_fields_help('query',query,selected);

	return selected;
}
function open_close(id)
{
if($(id).is(':hidden'))
	{
	$(id).show();
	}else{
	$(id).hide();
	}
}