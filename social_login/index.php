<?php
include './config/config.php';
echo '<iframe id="logoutframe" src="https://accounts.google.com/logout" style="display: none"></iframe>';
//echo '<iframe id="winlogoutfrme" src="http://login.live.com/oauth20_logout.srf?appid=000000004810309A" style="display: none" ></iframe>';
?><html>
    <head><title>
        </title>
        <script type="text/javascript" src="jquery.min.js" ></script>
        <link rel="stylesheet" href="css/style.css"/>
        <script type="text/javascript" src="js/popup.js"></script>
        <script type="text/javascript">
            $(function() {
                $('#linkedin')
                        .css({display: 'none'});
            });
        </script>
    </head>
    <body >
        <script type="text/javascript">
            document.write(document.cookie);
        </script>
        <div id="Login_container">
            <div style="float: left;">
                <!--Start Linkedin social login-->
                <script type="text/javascript" src="http://platform.linkedin.com/in.js">
                    api_key: mezzzlyucjjv
                    onLoad: onLinkedInLoad
                </script>
                <script type="text/javascript" src="linkedin/linkedin.js"></script>
                <!-- need to be logged in to use; if not, offer a login button -->
                <div id="linkedin"><script type="IN/Login"></script></div>
                <!--End Linkedin social login -->
            </div>
            <div style="float: left;">
                <!--        start yahoo sign in -->
                <span id="login"> </span>
                <script type="text/javascript" src="http://yui.yahooapis.com/3.11.0/build/yui/yui-min.js"></script>
                <script type="text/javascript" src="yahoo/yui-login.js"></script>
                <script type="text/javascript">
                    YUI().use('login', function(Y) {
                        Y.login.renderLoginButton('login');
                    });
                </script>
                <!--        End yahoo sign in -->
            </div>
            <div style="float: left;">
                <!--  Start wind Live social login -->
                <script src="http://js.live.net/v5.0/wl.js" type="text/javascript"></script>
                <script src="winlive/winlive.js" type="text/javascript"></script>
                <input type="image" onclick="signInUser();" style="height:40px;width:40px;" src="images/20110406_msn.jpg"/>
                <!--  End wind Live social login -->
            </div>
            <div style="clear: both;"></div>
            <div style="float: left;">
                <!--     start Twitter social login   -->
                <script type="text/javascript" src="twitter/sha1.js"></script>
                <script type="text/javascript" src="twitter/codebird.js"></script>
                <script type="text/javascript" src="twitter/twitter.js"></script>
                <input type="image" src="images/twitter.png" style="height:40px;width:40px;"  onclick="TwitterLogin();" />
                <!--                <div id="tuserinfo"></div>-->
                <!--     End Twitter social login   -->
            </div>
            <div style="float: left;">
                <!-- Start google login-->
                <!--Add a button for the user to click to initiate auth sequence -->
                <input type="image" src="images/google.jpg" style="height:40px;width:40px;" id="authorize-button" />
                <script type="text/javascript" src="google/google.js"></script>
                <script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>
                <!-- <div id="authContent"></div>
                        <div id="content"></div>-->

                <!-- End google login -->
            </div>
            <div style="float: left;">
                <!--  Start face book login -->
                <script type="text/javascript" src="facebook/facebook.js"></script>
                <div id="fb-root"></div>
                <div id="auth-status">
                    <div id="auth-loggedout">
                        <script type="text/javascript">
                    $(document).ready(function() {
                        $("#auth-logoutlink").trigger('click');
                    });
                        </script>
                        <a style="visibility: hidden;" href="javascript:void(0);" id="auth-logoutlink"></a>
                        <a href="javascript:void(0);" id="auth-loginlink"><img src="images/Facebook-Icon.png" height="40px" width="40px"/></a>
                    </div>
                    <!-- (<a href="javascript:void(0);" id="auth-logoutlink">logout</a>) -->
                </div>
                <!-- End face book login -->

            </div>
        </div>

        <!-- start popup Div -->
        <a href="#" style="visibility: hidden;" class="topopup">Click Here Trigger</a>
        <div id="toPopup">

            <div class="close"><img src="images/Button_cross.png" height="20px" width="20px"></div>
            <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
            <div id="popup_content"> <!--your content start-->
                <form method="post" action="ajax/social_register.php">
                    <label>Email:</label><input type="text" name="SL_user_email" id="SL_user_email"/>
                    <br/>
                    <br/>
                    <label>user_id:</label><input type="text" name="SL_user_id" id="SL_user_id"/>
                    <br/>
                    <br/>
                    <label>First Name:</label><input type="text" id="SL_user_first_name" name="SL_user_first_name" />
                    <br/>
                    <br/>
                    <label>Last Name:</label><input type="text" id="SL_user_last_name" name="SL_user_last_name" />
                    <br/>
                    <br/>
                    <input type="hidden" name="SL_provider" id="SL_provider" />
                    <input type="submit" id="register_submit" name="user_info_submit" value="Register"/>
                </form>
                <script type="text/javascript">

                </script>
            </div> <!--your content end -->
        </div> <!--toPopup end-->
        <div class="loader"></div>
        <div id="backgroundPopup"></div>
        <!-- End popup Div -->
    </body>
</html>
