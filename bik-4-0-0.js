var cache = {};
function liveSearch(jsonLink){
	$("#birds").autocomplete({
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
liveSearch("searchJson.php");
