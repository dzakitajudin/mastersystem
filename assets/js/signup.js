$(function () {
    $('input, select').on('focus', function () {
        $(this).parent().find('.input-group-text').css('border-color', '#80bdff');
    });
    $('input, select').on('blur', function () {
        $(this).parent().find('.input-group-text').css('border-color', '#ced4da');
    });
});


// datepicker
$(function () {
    var bindDatePicker = function() {
        var date = new Date();
        date.setDate(date.getDate());
        $("#birthday").datepicker({
            autoclose:true,
            todayHighlight:true,
            format:'dd/mm/yyyy',
            endDate: date
        })
    }
    
    bindDatePicker();
});


// process login
function check_registration() {
    $.ajax({
        url : 'http://localhost/mastersystem/services/signUpApi.php',
        dataType : 'json',
        headers: {'Accept': 'application/json'},
        type : 'POST',
        data : JSON.stringify({
            FirstName: $('#firstName').val(),
            LastName: $('#lastName').val(),
            Email: $('#email').val(),
            PhoneNumber: $('#phoneNumber').val(),
            Gender: $('input[type=radio]:checked').val(),
            BirthDate: $('#birthday').val(),
            Password: $('#password').val(),
            ConfirmPassword: $('#passwordConfirmation').val(),
        }),
        crossDomain : true,
        xhrFields: {
            withCredentials: false
        },
        success : function (data) {
          console.log('success',data);
          
          if (data.message == 'Data saved success') {
              $('#modal-regis-save-success').modal('show');
          }
           
          if (data.message == 'First Name is required') {
              $('#modal-regis-firstname-required').modal('show');
          }
          if (data.message == 'Last Name is required') {
              $('#modal-regis-lastname-required').modal('show');
          }
          if (data.message == 'Email is required') {
              $('#modal-regis-email-required').modal('show');
          }
          if (data.message == 'Email must be unique') {
              $('#modal-regis-email-unique').modal('show');
          }
          if (data.message == 'Email is invalid') {
            $('#modal-regis-email-invalid').modal('show');
          }
          if (data.message == 'Phone Number is required') {
            $('#modal-regis-phone-required').modal('show');
          }
          if (data.message == 'Phone Number must be unique') {
            $('#modal-regis-phone-unique').modal('show');
          }
          if (data.message == 'Gender is invalid format (M / F)') {
            $('#modal-regis-gender-invalid').modal('show');
          }
          if (data.message == 'Birth Date is invalid format (dd/mm/yyyy)') {
            $('#modal-regis-birthdate-invalid').modal('show');
          }
          if (data.message == 'Password is required') {
            $('#modal-regis-password-required').modal('show');
          }
          if (data.message == 'Confirm Password is required') {
            $('#modal-regis-confirmpassword-required').modal('show');
          }
          if (data.message == 'Confirm Password is not match with password') {
            $('#modal-regis-confirmpassword-notmatch').modal('show');
          }
          if (data.message == 'Data saved failed') {
            $('#modal-regis-save-failed').modal('show');
          }     
        },
        error : function (err) {
          console.log('Error',err);
          alert('error');
        }
    });
  
    return false;
}

function redirect_login() {
    $('body').fadeOut(1000, function() {
        window.location.href = "./login.php";
    });
}