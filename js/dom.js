function $(id){
	return document.getElementById(id);
}

function fillDiv(id,data){
	var el = $(id);
	if(el){
		el.innerHTML = data;
	}
}