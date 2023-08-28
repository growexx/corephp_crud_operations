var global_hostname = 'localhost/phpsetup';  // decleared once and can use it anywhere in project
var authcred = JSON.parse(window.localStorage.getItem('authdata'));

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (re.test(email) == false) {
        toastr.error('Please Provide Valid Email ID', 'Error');
    }
}

function chkLogin() {
    var authdata = window.localStorage.getItem("authdata");
    if (!authdata) {
        document.location.href = "login.html";
    }
}

function register() {

    var email = $("#user_email").val();
    var password = $("#user_password").val();

    var registerObj = {
        'user_email': $("#user_email").val(),
        'user_password': $("#user_password").val(),
        'hobbies': $("#hobbies").val(),
        'userImg': $("#userImg").val(),
    };


    if (registerObj.user_email!=='' && registerObj.user_password!=='' && registerObj.hobbies!=='' && registerObj.userImg!=='') {

        var registerData = JSON.stringify(registerObj);

        var URL = 'http://' + global_hostname + '/model/services/authService.php?cmd=REGISTER&registerData=' + registerData;

        $("#save-register-file").attr('action', URL);
        $("#save-register-file").submit(function(e) {
            var formObj = $(this);
            var formURL = formObj.attr("action");
            var formData = new FormData(this);
            $.ajax({
                url: formURL,
                type: 'POST',
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    var result = jQuery.parseJSON(res);
                    if (result.status == 'Success') {

                        toastr.success(result.response_text, 'Success');

                        var loginObj = {
                            'email': email,
                            'password': password,
                        };

                        var URL = 'http://' + global_hostname + '/model/services/authService.php?cmd=LOGIN';

                        $.ajax({
                            type: "POST",
                            url: URL,
                            data: loginObj,
                            success: function (res) {
                                var result = jQuery.parseJSON(res);
                                if (result.status == 'Success') {
                                    // toastr.success(result.response_text, 'Success');
                                    window.localStorage.setItem("authdata", JSON.stringify(result.login[0]));
                                    setTimeout(function () {
                                        document.location.href = "index.html";
                                    }, 1000);
                                }
                            },
                            error: function (result) {
                            }
                        });
                    }
                    else {
                        toastr.error(result.response_text, 'Error');
                    }
                },
                error: function (res) {
                }
            });
            e.preventDefault();
        });
        $("#save-register-file").submit();
    } else {
        toastr.error('All fields are mandatory', 'Error');
    }
}

function login() {
    var loginObj = {
        'email': $("#email").val(),
        'password': $("#password").val(),
    };

    if (loginObj.email !='' && loginObj.password != '') {

        var URL = 'http://' + global_hostname + '/model/services/authService.php?cmd=LOGIN';

        $.ajax({
            type: "POST",
            url: URL,
            data: loginObj,
            success: function (res) {
                var result = jQuery.parseJSON(res);
                if (result.status == 'Success') {
                    toastr.success(result.response_text, 'Success');
                    window.localStorage.setItem("authdata", JSON.stringify(result.login[0]));
                    setTimeout(function () {
                        document.location.href = "index.html";
                    }, 1000);
                }
                else{
                    toastr.error(result.response_text, 'Error');
                }
            },
            error: function (result) {
            }
        });
    }
    else {
        toastr.error('Required Mobile Number', 'Error');
    }
}

function logout(val){
    window.localStorage.removeItem('authdata');
        document.location.href = "login.html";
}

function authCredential(val){
    if (!authcred && authcred == null) {
            document.location.href = "login.html";
        }
}

function getUser() {

    var URL = 'http://' + global_hostname + '/model/services/authService.php?cmd=GET_USER';

    $.ajax({
        type: "POST",
        url: URL,
        success: function (res) {
            var result = jQuery.parseJSON(res);
            if (result.status == 'Success') {

                var list = '';
                for (i = 0; i < result.userList.length; i++) {

                    list = list + '<tr>'+
                                '<td>' + result.userList[i].Mail + '</td>'+
                                '<td>' + result.userList[i].Hobbies + '</td>'+
                                '<td style="cursor:pointer;" onclick="userDetails('+result.userList[i].UserID+');"><b><u> View </u></b></td>'+
                                '<td style="cursor:pointer;" onclick="deleteUser('+result.userList[i].UserID+');"><b><u> Delete </u></b></td>'+
                            '</tr>';

                }
                $('#JobsList').html(list);
            }
            else{
                toastr.error(result.response_text, 'Error');
            }
        },
        error: function (result) {
        }
    });
}

function deleteUser(UserID) {

    var deleteUserObj = {
        'UserID': UserID,
    };

    var URL = 'http://' + global_hostname + '/model/services/authService.php?cmd=DELETE_USER';

    $.ajax({
        type: "POST",
        url: URL,
        data: deleteUserObj,
        success: function (res) {
            var result = jQuery.parseJSON(res);
            if (result.status == 'Success') {
                toastr.success(result.response_text, 'Success');
                getUser();
            }
            else{
                toastr.error(result.response_text, 'Error');
            }
        },
        error: function (result) {
        }
    });
}