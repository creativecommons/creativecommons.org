/* parameters passed to a channel entry page from Kazaa
str ver - version number (no decimals eg 1.72 = 172)
str client - always 'kmd'
str country - ISO code of user's country as defined in the Tools/Options dialog box 
*/

var ver = ""	;	
var client = "";
var country = "";


var isKazaa = false;

/*
This function only supports KMD 2.00 and above - for previous versions I recommend providing a normal download link
This function triggers a smartlink and records a stat on Altnet
If stat recording is not required please take out the parameter or provide 0 for no stat

stat:
1 - bde , 0 - no.
filetitle, filecampaign, filename, filesize, filehash, filedirectory, fileauthor, filecategory:
data provided by Altnet used in stat recording and display in KMD

*/

function smartLink(filetitle, filecampaign, filename, filesize, filehash, 
			filedirectory, fileauthor, filecategory, stat)
		{
			if (ver = getCookie("ver")) {
			//will only work if the version cookie is set ie: if viewved in Kazaa
				if (ver<="172") {
					
					if (confirm("To use this feature you need to upgrade your version of KMD"))
					{
						location.href = "http://kazaa.com/us/products/downloadKMD.htm";
						return true;
					} 
				
				} else {
	
					if (ver>="250" )
					{
						
						
							channel_id = "1000";
							
							//get file_id (32 chars)
							if (filehash.substr(0,32)!="") {
								file_id = filehash.substr(0,32)
							} else {
								file_id = filehash
							}
							
							location.href = "http://localhost/KazaaSearchQuery?query=" + filehash + 
			"&tsi=true&hash=true&action=download&confirm=true&cat=" + filecategory +
			"&filename=" + escape(filename) + "&url=" + escape(filedirectory) + 
			"&size=" + filesize + "&author=" + escape(fileauthor) + 
			"&title=" + escape(filetitle) + "&signer=1&campaign=" + filecampaign + "&topsearch=channel_id[" + channel_id + "]file_id[0x" + file_id +"]";
	
					} else {
							location.href = "http://localhost/KazaaSearchQuery?query=" + filehash + 
							"&tsi=true&hash=true&action=download&confirm=true&cat=" + filecategory +
							"&filename=" + escape(filename) + "&url=" + escape(filedirectory) + 
							"&size=" + filesize + "&author=" + escape(fileauthor) + 
							"&title=" + escape(filetitle) + "&signer=1";
							
							var statimg = new Image();
			
			if (stat == 1) {
				statimg.src = "http://www.altnet.com/IsaAds/IsaTop.dll?DoStat&" + 
				"query=state=1%26pcode=KaZaA-Weblink%26percent=100%26file1=" + filename + 
				"%26key1=Weblink%26camp1=" + filecampaign;
			}			
				}
			} 
		}

	}	
		//cookie & parameters functions
function getCookie(Name) {
var search = Name + "="
if (document.cookie.length > 0) { // if there are any cookies
	offset = document.cookie.indexOf(search)
		if (offset != -1) { // if cookie exists
		offset += search.length
		// set index of beginning of value
		end = document.cookie.indexOf(";", offset)
		// set index of end of cookie value
		if (end == -1)
		end = document.cookie.length
		return unescape(document.cookie.substring(offset, end))
		}
	}
}

function getParamValue (paramName)
{
    var strFieldValue;
    var objRegExp = new RegExp(paramName + "=([^&]+)","gi");

    if (objRegExp.test(location.search))
        strFieldValue = unescape(RegExp.$1);
    else strFieldValue="";

    return strFieldValue;
}


//get current parameters

ver = getParamValue("ver");
client = getParamValue("client");
country = getParamValue("country");

/*set them as session cookies (if retrieved). These params should be set as session cookies (to expire at the end of visit). Otherwise code has to be added to handle people who might have upgraded to new versions of Kazaa
*/
if (ver!=0) document.cookie = "ver=" + ver + "; path=/; domain=creativecommons.org";

if (country!="") document.cookie = "country=" + country + "; path=/; domain=creativecommons.org";

if (client!="") document.cookie = "client=" + client + "; path=/; domain=creativecommons.org";


//test the values - if parameters have been passed and saved assume it is Kazaa
/*
if (getCookie("ver")) {
	document.write("You are using " + getCookie("client") + " ver " + getCookie("ver"));
	document.write("<br>");
	document.write("You are from " + getCookie("country"));
} else {
	document.write("You are using an ordinary browser");
}
*/
