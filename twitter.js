 var cb = new Codebird;
             var cc;
              cb.setConsumerKey("Sj10FqsLbJnSLX0wZPbnVw", "wKQLj7Tis1XG54f6Ly7DOnMdXAsrFwD29ROEr9CaIM");
              cb.setToken("1682482861-ePeBQToeRVhomKbe3XjzrPfOIynWg7f62mfYSfk", "yC5hMn1kudFTbXuQINPmPHdvtMJ9gSBaJYeggiljeAE");
            function TwitterLogin()
            {
                // gets a request token
                cb.__call(
                    "oauth_requestToken",
                    {oauth_callback: "http://localhost/sign_in_with_twitter_auth/callback.html"},// change the call back url for for extarting the data
                    // http://serveraddress/[twitter_project]/callback url //
                    function (reply) {
                        
                        cb.setToken(reply.oauth_token, reply.oauth_token_secret);
                        cb.__call(
                            "oauth_authorize",
                            {oauth_verifier: reply.oauth_verifier},
                            function (auth_url) {
                             cc = window.open(auth_url,"Twitter Auth","height=500,width=500,menubar=no,location=yes,resizable=yes,scrollbars=yes,status=yes");
                                  //alert(reply.oauth_verifier); 
                                //alert(reply.oauth_verifier);
                            }
                        );
                        
                    }
                );
                
            }
            /// get the url parameter value by this method
            function getQueryParams(qs) {
                qs = qs.split("+").join(" ");

                var params = {}, tokens,
                    re = /[?&]?([^=]+)=([^&]*)/g;

                while (tokens = re.exec(qs)) {
                    params[decodeURIComponent(tokens[1])]
                        = decodeURIComponent(tokens[2]);
                }

                return params;
            }
            
            //close the popup window and get the auth_varifier value //
            function closeTwitterPopup(){
                var current_url = window.location.toString();
                var query  = getQueryParams(current_url);
                window.onbeforeunload = function() {
                     window.opener.getTwitterUserByAutneticate(query.oauth_verifier);
                    };
                window.close();
                
            
            }
            
            