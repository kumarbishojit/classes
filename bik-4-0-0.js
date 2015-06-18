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
//liveSearch("searchJson.php");
//<div class=\"\"><label for=\"search\">Search: </label><input id=\"search\"></div>
