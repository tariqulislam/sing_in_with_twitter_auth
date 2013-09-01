var APPLICATION_CLIENT_ID = "000000004810309A",
        REDIRECT_URL = "http://testserver.bscheme.com/social_login/index.php";
WL.Event.subscribe("auth.login", signInUser);
WL.init({
    client_id: APPLICATION_CLIENT_ID,
    redirect_uri: REDIRECT_URL,
    //scope: ["wl.signin", "wl.basic", "wl.birthday", "wl.emails"],
    response_type: "token"
});

function signInUser() {
    WL.login({
        scope: ["wl.signin", "wl.basic", "wl.birthday", "wl.emails"]
    }).then(
            function(response) {
                var session = WL.getSession();
                if (session)
                {
                    WL.api({
                        path: "me",
                        method: "GET"
                    }).then(
                            function(response) {
                                console.log(response);
                                var id=response.id;
                                var email =response.emails.preferred;
                                var first_name = response.first_name;
                                var last_name =response.last_name;
                                //session = null;
                                //WL.logout();
                                $.ajax({
                                    type: "POST",
                                    url: "ajax/social_login.php",
                                    data: {SL_provider: 'winlive', SL_user_id: id , SL_user_email:email, SL_user_first_name:first_name , SL_user_last_name:last_name },
                                    success: function(response) {
                                        //alert(response);
                                        var obj = jQuery.parseJSON(response);

                                        if (obj.output_type == 0)
                                        {
                                            /** if the user already register **/
                                            //WL.logout();
                                            window.location.replace("http://testserver.bscheme.com/social_login/logininfo.php");
                                        }
                                        else
                                        {
                                             //WL.logout();
                                            /**  if the user not register **/
                                            $(document).ready(function() {
                                                $('.topopup').trigger('click');
                                                $('#SL_user_email').val(obj.user_email);
                                                $('#SL_user_id').val(obj.SL_user_id);
                                                $("#SL_user_first_name").val(obj.SL_user_first_name);
                                                $("#SL_user_last_name").val(obj.SL_user_last_name);
                                                $("#SL_provider").val(obj.provider);
                                            });
                                        }
                                    }
                                });
//                                        document.getElementById("first_name").innerHTML = response.first_name;
//                                        document.getElementById("last_name").innerHTML = response.last_name;
//                                        document.getElementById("email").innerHTML = response.emails.preferred;
//                                        document.getElementById("gender").innerHTML = response.gender;
//                                        document.getElementById("birthday").innerHTML =
//                                                response.birth_month + " " + response.birth_day + " " + response.birth_year;
                            },
                            function(responseFailed) {
                                alert(responseFailed.error.message);
//                                        document.getElementById("infoArea").innerText =
//                                                "Error calling API: " + responseFailed.error.message;
                            }
                    );
                }
            },
            function(responseFailed)
            {
                alert(responseFailed.error_description);
//                            document.getElementById("infoArea").innerText =
//                                    "Error signing in: " + responseFailed.error_description;
            }

    );
}


