//--Error Detecting-----------------------
window.onerror=function(msg, url, linenumber){
	alert('Error message:\n'+msg+'\n\nURL: '+url+'\n\nLine Number: '+linenumber)
}

//--Collect Object By ID------------------
function objId(id){
	if(!document.getElementById(id)){
		//alert("Developer Message:\nID ("+id+") Not Defined on function `objId`.");
		return false;
	}
	else
	return document.getElementById(id);
}

//--Build Pop
var title="Title";
function buildPop(id, actionLink, parameters){
	
	//--Create & append Black BG DIV
	if(!document.getElementById(id+"_bg")){
		var div_bg = document.createElement("div");
		div_bg.setAttribute("style", "background: rgba(0, 0, 0, 0.8); width: 100%;	position: fixed; top: 0px; height: 100%; z-index:101;");
		div_bg.setAttribute("ondblclick", "buildMsgdivClose('"+id+"'); ");
		div_bg.setAttribute("id", id+"_bg");
		document.body.appendChild(div_bg);
	}
	
	//--Creating Main Div
	if(!document.getElementById(id)){
		var div = document.createElement("div");
		div.setAttribute("style", "background:#F9F9F9; position:fixed; right:0px; left:0px; margin:0px auto; width:90%; max-width:1000px; height:90%; top:50%; transform:scale(.5, .5) translate(0%, -100%); -moz-transition: All .3s; -webkit-transition: All .3s; transition: All .3s; border:1px solid #DDD; box-shadow: 0px 0px 3px #FFF; z-index:101;");
		div.setAttribute("id", id);
		div.setAttribute("class", "pop_show");
		div.innerHTML="<div style=\"border-bottom:1px solid #999; font-size:16px;padding: 10px 10px 3px 10px;background: #DDD;\"><span name=\"popExit\" class=\"button\" style=\"float:right; color: #fff; background-color: #f1353d; border-color: #ec111b;\" onclick=\"buildMsgdivClose('"+id+"');\"><i class=\"fa fa-times-circle\"></i> Exit</i></span><span id='title_details_"+id+"' style=\"line-height: 33px;\">"+title+" </span> : &nbsp;</div><div id='details_"+id+"' style='margin:10px; font-size:16px; overflow: auto; height: 440px;'>Loading...</div>";
		
		//--Append DIV
		document.body.appendChild(div);
		
		document.getElementById("details_"+id).style.height= (div.offsetHeight-67)+"px";
	}
	
	//--Calling Ajax
	if(typeof ajaxPageLoad == 'function')
	ajaxPageLoad("details_"+id, actionLink, parameters);
	
	//--Then Display IT (After 2ms)
	var t=setTimeout(function(){
		if(div)
		div.style.transform="scale(1, 1)  translate(0%, -50%)";
	}, 2);
	return false;
}
//--Removing Message/Popup Div
function buildMsgdivClose(id){
	if(document.getElementById(id))
	document.getElementById(id).style.transform="scale(.5, .5)";
	
	//--Then Remove IT (After 150ms)
	var t=setTimeout(function(){
		if(document.getElementById(id))
		document.getElementById(id).parentNode.removeChild(document.getElementById(id));
		
		//--Clearing div_bg
		if(document.getElementById(id+'_bg'))
		document.getElementById(id+'_bg').parentNode.removeChild(document.getElementById(id+'_bg'));
	}, 150);
	//return false;
}



//liveSearch("searchJson.php");
//<div class=\"\"><label for=\"search\">Search: </label><input id=\"search\"></div>
var cache = {};
function liveSearch(searchId, jsonLink){
	$("#"+searchId).autocomplete({
		minLength: 1,
		source: function(request, response){
			var term = request.term;
			if(term in cache){
				response(cache[term]);
				return;
			}
			
			$.getJSON(jsonLink, request, function(data, status, xhr){
				cache[term]=data;
				response(data);
			});
		}
	});
};

