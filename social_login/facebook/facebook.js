// Load the SDK Asynchronously
(function(d) {
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "https://connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
}(document));

// Init the SDK upon load
window.fbAsyncInit = function() {
    FB.init({
        appId: '156540967883799', // Your App ID
        channelUrl: 'http://testserver.bscheme.com/social_login/facebook/channel.html', // Path to your Channel File
        status: true, // check login status
        cookie: true, // enable cookies to allow the server to access the session
        xfbml: true  // parse XFBML
    });

    // listen for and handle auth.statusChange events
    FB.Event.subscribe('auth.statusChange', function(response) {
        if (response.authResponse) {
              
            // user has auth'd your app and is logged into Facebook
            FB.api('/me', function(me) {
                //console.log(me);
                /** if the user already register **/
                  var id= me.id;
                  var email= me.email;
                  var first_name= me.first_name; 
                  var last_name= me.last_name; 
                           FB.logout(function() {
                                        FB.Auth.setAuthResponse(null, 'unknown');

                            });
                $.ajax({
                    type: "POST",
                    url: "ajax/social_login.php",
                    data: {SL_provider: 'facebook', SL_user_id: id, SL_user_email: email, SL_user_first_name: first_name, SL_user_last_name: last_name},
                    success: function(response) {
                        //alert(response);
                        var obj = jQuery.parseJSON(response);

                        if (obj.output_type == 0)
                        {
                         
                           window.location.replace("http://testserver.bscheme.com/social_login/logininfo.php");
                        }
                        else
                        {
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
                //document.getElementById('auth-displayname').innerHTML = me.name;
                // document.getElementById('autho-email').innerHTML= me.email;
            });
          
        } else {
            // user has not auth'd your app, or is not logged into Facebook
           
        }
    });
    // respond to clicks on the login and logout links
    document.getElementById('auth-loginlink').addEventListener('click', function() {
        FB.login(function() {
        }, {scope: "email"});
    });
    document.getElementById('auth-logoutlink').addEventListener('click', function() {
        FB.logout();
    });
};
      