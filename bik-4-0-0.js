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


//##Build Pop-Start########################################################################
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
		div.innerHTML="<div style=\"border-bottom:1px solid #999; font-size:16px;padding: 10px 10px 3px 10px;background: #DDD;\"><button name=\"popExit\" class=\"btn red\" style=\"float:right; padding: 5px 10px;\" onclick=\"buildMsgdivClose('"+id+"');\"><i class=\"fa fa-times-circle\"></i> Exit</i></button><span id='title_details_"+id+"' style=\"line-height: 33px;\">"+title+" </span> : &nbsp;</div><div id='details_"+id+"' style='margin:10px; font-size:16px; overflow: auto; height: 440px;'>Loading...</div>";
		
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
//--Remove Popup On ESC Press
function escPopClose(){
	unicode =event.keyCode? event.keyCode : event.charCode;
	if(unicode==27){// U=85, C=67, I=73, 47=0, 48=1, 49=2, ........, 13=Enter, 27=ESC;
		objLength=document.getElementsByName('popExit').length;
		
		if(document.getElementsByName("popExit")[objLength-1])
		document.getElementsByName("popExit")[objLength-1].click();
	}
}
//--Installation (Placed on Index)
//window.onkeyup=function(){
//	escPopClose();
//	//--Code Goes Here
//	//--Code Goes Here
//}
//##Build Pop-End########################################################################

//--AJAX Pageload
function ajaxPageLoad(dispId, actionLink, parameters){
	parameters +="&dispId="+dispId;
	parameters +="&actionLink="+actionLink;
	parameters +="&bik=1";
	
	//--Creating Progress DIV
	if(!document.getElementById('progress_div')){
		var divProg = document.createElement("div");
		divProg.setAttribute("id", "progress_div");
		divProg.setAttribute("style", "position: fixed; bottom:0px; height:5px; width:100%; overflow:hidden; background:#F90; transition: All 1s;  z-index:20001;");
		divProg.innerHTML="<div id='progress_st' style='background:#090; width:0%; transition: All .5s;'>&nbsp;</div>";
		document.body.appendChild(divProg);
	}
	
	var xhr = new XMLHttpRequest();
	if (xhr.upload) {
		xhr.addEventListener("progress", function(e){
			if (e.lengthComputable) {
				var percentComplete = e.loaded * 100 / e.total;
				var t=setTimeout(function(){
					if(document.getElementById('progress_st'))
					document.getElementById('progress_st').style.width=percentComplete+"%";
				}, 1000);
			} else {
				// Unable to compute progress information since the total size is unknown
			}
		}, false);
		
		xhr.onreadystatechange = function(ev){
			if (xhr.readyState == 4) {
				if(xhr.status == 200){//Yes
					//--Response Text Output
					var respAr=xhr.responseText.split(";///;");
					if(respAr[0] && document.getElementById(dispId))
					document.getElementById(dispId).innerHTML=respAr[0];
					if(respAr[1])
					eval(respAr[1]);
					
					if(divProg && divProg.parentNode){
						var t=setTimeout(function(){
							//--Clearing divProg
							divProg.parentNode.removeChild(divProg);
						}, 2000);
					}
				}
				else{//No
					if(divProg && divProg.parentNode){
						divProg.style.backgroundColor="#F00";
						var t=setTimeout(function(){
							//--Clearing divProg
							divProg.parentNode.removeChild(divProg);
						}, 2000);
					}
				}
			}
		};
 
		xhr.open('POST', actionLink, true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(parameters);
	}
	return false;
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

//--Tools Function CK Editor
function ckEditorFull(id){
	CKEDITOR.plugins.addExternal( 'onchange', 'onchange/' );
	var config = { extraPlugins: 'onchange'};
	CKEDITOR.on('instanceCreated', function (e) {
		if (!e.editor.id) 
		alert('Unknown Textarea ID');
		
		e.editor.on('change', function (ev) {
			if(document.getElementById(id))
			document.getElementById(id).value=ev.editor.getData()//
		});
	});
	CKEDITOR.replace(id);
}
//--Calling CK Editor
//ckEditorFull('editor1');
