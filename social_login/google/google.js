 var clientId = '977321882852-lac517uf3usfaf0s3nra3ph258ie2kbv.apps.googleusercontent.com';//https://code.google.com/apis/console/#project:977321882852:access

            var apiKey = 'AIzaSyCK3KghiFf1WQVj7oI3OqQbwqZ4OH5dtsg';//https://code.google.com/apis/console/#project:977321882852:access

            // To enter one or more authentication scopes, refer to the documentation for the API.
            var scopes = 'https://www.googleapis.com/auth/plus.me https://www.googleapis.com/auth/userinfo.email';//https://developers.google.com/drive/training/drive-apps/auth/scopes

            // Use a button to handle authentication the first time.
            function handleClientLoad() {
                gapi.client.setApiKey(apiKey);
                window.setTimeout(checkAuth, 1);
            }

            function checkAuth() {
                gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: true}, handleAuthResult);
            }


            function handleAuthResult(authResult) {
                var authorizeButton = document.getElementById('authorize-button');
                if (authResult && !authResult.error) {
                    //authorizeButton.style.visibility = 'hidden';//You should use hidden after authoized
                    makeApiCall();
                } else {
                    //authorizeButton.style.visibility = '';
                    authorizeButton.onclick = handleAuthClick;
                }
            }

            function handleAuthClick(event) {
                gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: false}, handleAuthResult);
                return false;
            }
//         @see:  http://stackoverflow.com/questions/11606101/how-to-get-user-email-from-google-plus-oauth
            // Load the API and make an API call.  Display the results on the screen.
//           @see: https://developers.google.com/accounts/docs/OAuth2LoginV1
            function makeApiCall() {
                var user_id,email,firstname,lastname;
                gapi.client.load('oauth2', 'v2', function() {
                    var authrequest = gapi.client.oauth2.userinfo.get();
                    authrequest.execute(function(logResponse) {
                        //console.log(logResponse);
                        email= logResponse.email;
                        user_id=logResponse.id;
//                        var authcontent = document.createElement('h4');
//                        authcontent.appendChild(document.createTextNode(logResponse.email));
//                        authcontent.appendChild(document.createTextNode(logResponse.id));
//                        authcontent.appendChild(document.createTextNode(logResponse.verified_email));
//                        document.getElementById('authContent').appendChild(authcontent);
                    });
                });
                gapi.client.load('plus', 'v1', function() {
                    var request = gapi.client.plus.people.get({
                        'userId': 'me'
                    });
                    request.execute(function(resp) {
                        //console.log(resp);
                        firstname =resp.name.givenName;
                        lastname = resp.name.familyName;
                        //document.write('<iframe id="logoutframe" src="https://accounts.google.com/logout" style="display: none"></iframe>');
                         $.ajax({
                    type: "POST",
                    url: "ajax/social_login.php",
                    data: {SL_provider: 'google', SL_user_id:user_id , SL_user_email:email , SL_user_first_name: firstname, SL_user_last_name: lastname},
                    success: function(response) {
                        //alert(response);
                        var obj = jQuery.parseJSON(response);

                        if (obj.output_type == 0)
                        {
                           /** if the user already register **/
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
                        //console.log(email);
//                        var heading = document.createElement('h4');
//                        var image = document.createElement('img');
//                        image.src = resp.image.url;
//                        heading.appendChild(image);
//                        heading.appendChild(document.createTextNode(resp.displayName));
//                        heading.appendChild(document.createTextNode(resp.id));
//                        document.getElementById('content').appendChild(heading);
                    });
                });
                  
            }


