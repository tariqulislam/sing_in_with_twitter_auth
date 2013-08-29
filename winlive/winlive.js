// @see: http://msdn.microsoft.com/en-us/library/live/hh243643.aspx
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
                         var session =WL.getSession();
                       if(session)
                           {
                            WL.api({
                                path: "me",
                                method: "GET"
                            }).then(
                                    function(response) {
                                        console.log(response)
                                        document.getElementById("first_name").innerHTML = response.first_name;
                                        document.getElementById("last_name").innerHTML = response.last_name;
                                        document.getElementById("email").innerHTML = response.emails.preferred;
                                        document.getElementById("gender").innerHTML = response.gender;
                                        document.getElementById("birthday").innerHTML =
                                                response.birth_month + " " + response.birth_day + " " + response.birth_year;
                                    },
                                    function(responseFailed) {
                                        document.getElementById("infoArea").innerText =
                                                "Error calling API: " + responseFailed.error.message;
                                    }
                            );
                           }
                        },
                        function(responseFailed)
                        {
                            document.getElementById("infoArea").innerText =
                                    "Error signing in: " + responseFailed.error_description;
                        }
                        
                );
            }


