/**
 * This is the js manipulate most of the event.
 */
$(document).ready(function(){
//	$("body").on('click', ".folder", function(){
//		var id = $(this).attr("id");
//		$(".wrap").remove();
//		$.get("./index.php/home/fileList/"+id, function(data){
//			$("body").html(data);
//		});
//	});
	
	$("body").on('click',".edit_doc", function(){
		var link = $(this).data('destination');
		window.open(link);
	});
	
	$("body").on('click', ".delete_doc", function(){
		var etag = $(this).data("etag");
		var id = $(this).data("id");
		var url = $(this).data("url");
		$.post(url, {
			id:id,
			etag:etag
		}, function(response){
			if (response.status == "success"){
				$("#"+id).remove();
			} else {
				alert(response.error);
			}
		}, 'json');
	});
});