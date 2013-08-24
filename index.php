<html>
    <head><title>Twitter Login</title>
        <script type="text/javascript" src="sha1.js"></script>
        <script type="text/javascript" src="codebird.js"></script>
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="twitter.js"></script>
        <script type="text/javascript">
            //get the user by new autho_varifire with call back url
         function getTwitterUserByAutneticate(authoTocken)
         {
             cb.__call(
                "oauth_accessToken",
                {oauth_verifier:authoTocken},
                function (reply) {
                    // store the authenticated token, which may be different from the request token (!)
                    cb.setToken(reply.oauth_token, reply.oauth_token_secret);

                                   cb.__call(
                                         "account_verifyCredentials",
                                         {},
                                         function (user_replay) {
                                             console.log(user_replay);
                                         }
                                     );
                            }
                        );
         }
         </script>
    </head>
    <body >
      
        <button type="submit" onclick="TwitterLogin()">Twitter Login</button>
        <input type="text" id="pinbox" name="pinbox"/><button type="submit" onclick="getUserTwitter();"></button>
        
       
    </body>
</html>