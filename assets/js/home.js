// process logout
$('a').click(function() {
	// remove token
	$.cookie('Token', null);
    $.cookie('FirstName', null);
    $.cookie('LastName', null);
	// redirect to login page with animation
	$(function(){
		$('body').fadeOut(1000, function() {
		    window.location.href = "./index.php";
		})
	});
});

// redirect to index page
function redirect_index() {
	$(function(){
		$('body').fadeOut(1000, function() {
		    window.location.href = "./index.php";
		})
	});
}

// refresh page and token expired
if ($.cookie('Token') == null) {
	$('#modal-login-token-expired').modal('show');
}