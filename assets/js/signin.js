// login page
// page animation and remove token every redirect to login page
$(function(){
    // remove token
	$.cookie('Token', null);
    $.cookie('FirstName', null);
    $.cookie('LastName', null);
 });

// process login
function check_login() {
    $.ajax({
      url : 'http://localhost/mastersystem/services/signInApi.php',
      dataType : 'json',
      headers: {'Accept': 'application/json'},
      type : 'POST',
      data : JSON.stringify({
          Email: $('#email').val(),
          Password: $('#password').val()
      }),
      crossDomain : true,
      xhrFields: {
          withCredentials: false
      },
      success : function (data) {
        console.log('success',data);

        if (data.message == 'Login success') {
            token = data.data[0].token;
            // set expire token
			var date= new Date();
			var minute= 60;
			date.setTime(date.getTime() + (minute * 60 * 1000));
            // set token and save to cookies
            $.cookie('Token', token, { expires: date });
            $.cookie('FirstName', data.data[0].firstname, { expires: date });
            $.cookie('LastName', data.data[0].lastname, { expires: date });
            
            $(function(){
                $('body').fadeOut(1000, function() {
                    window.location.href = "./home.php";
                })
            });
        }
         
        if (data.message == 'Email is required') {
            $('#modal-login-email-required').modal('show');
        }
        if (data.message == 'Password is required') {
            $('#modal-login-password-required').modal('show');
        }
        if (data.message == 'email and password not match') {
            $('#modal-login-notmatch').modal('show');
        }     
      },
      error : function (err) {
        console.log('Error',err);
        alert('error');
      }
    });

    return false;
}