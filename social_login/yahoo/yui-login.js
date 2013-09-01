        YUI.add('login', function(Y) {
            /// @see http://openid.net/specs/openid-authentication-2_0.html#verification
            /// Licensed under Yahoo! BSD
            Y.namespace('login');
            Y.login.cookieName = 'login_session';
            Y.login.sessionReadyEventName = 'login:sessionReady';
            
            /// This function constructs an openid login url for Yahoo!, and opens a popup to this location
            /// @param {string} returnUrl is the url to redirect to after the user goes through the login flow.  Defaults to document.location.href
            Y.login.popup = function (returnUrl) {

                returnUrl = returnUrl || document.location.href;
                
                // @see http://openid.net/specs/openid-authentication-2_0.html#realms
                var realm = returnUrl.match(/(http[s]?:\/\/[^\/?]+)/)[0];

                // Load openid login flow in popup window
                // @see http://developer.yahoo.com/openid/
                var url = 'https://open.login.yahooapis.com/openid/op/auth?' + Y.QueryString.stringify({
                    'openid.claimed_id': 'http://specs.openid.net/auth/2.0/identifier_select',
                    'openid.return_to': returnUrl,
                    'openid.mode': 'checkid_setup',
                    'openid.identity': 'http://specs.openid.net/auth/2.0/identifier_select',
                    'openid.ns': 'http://specs.openid.net/auth/2.0',
                    'openid.realm': realm,
                    'openid.ns.oauth':'http://specs.openid.net/extensions/oauth/1.0',
                    //'openid.oauth.consumer':'##Consumer Key##',
                    // @see: https://developer.apps.yahoo.com/dashboard/createKey.html
                    'openid.ns.ax':'http://openid.net/srv/ax/1.0',
                    'openid.ax.mode':'fetch_request',
                    'openid.ax.required':'email,fullname,nickname',
                    'openid.ax.type.email':'http://axschema.org/contact/email',
                    'openid.ax.type.fullname':'http://axschema.org/namePerson',
                    'openid.ax.type.nickname':'http://axschema.org/namePerson/friendly'
                });
                var name = 'login';
                var params = 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=0,resizable=1, width=500,height=500,left=200,top=200';
                var popup = window.open(url, name, params);
                // Poll popup every 300 msec to see if it's redirected back to this domain
                // @see http://developer.yahoo.com/yui/3/api/YUI.html#method_later
                var timer = Y.later(300, Y, function() {
                    // If user's closed window, stop timer
                    if (popup.closed) {
                        timer.cancel();
                        return;
                    }
                    // Wrap in try/catch to avoid fatal cross-domain exceptions
                    try {
                        // Openid's response is called the "assertion"
                        // @see http://openid.net/specs/openid-authentication-2_0.html#positive_assertions
                        var assertion = popup.location.href.split('?')[1];
                        assertion = Y.QueryString.parse(assertion);
                        console.log(assertion);
                        // Extract the user-specific info
                        var session = {
                            'username': assertion['openid.identity'],
                            'email':assertion['openid.ax.value.email'],
                            'firstname':assertion['openid.ax.value.fullname'],
                            'lastname':assertion['openid.ax.value.nickname']
                            
                        };
                        
                        var json = Y.JSON.stringify(session);

                        // Cache the session in a cookie
                        Y.Cookie.set(Y.login.cookieName, json);
                        
                        // Notify anyone who's listening in this yui sandbox that the session's ready
                        Y.fire(Y.login.sessionReadyEventName);
                        
                        // Stop polling popup & close it
                        timer.cancel();
                        popup.close();

                    } catch(e) {
                        Y.log(e);
                    }

                }, '', true);
            };
            // This function creates markup and event handling for a login button
     
            Y.login.renderLoginButton = function (id, html) {
                
                if (!id) {
                    throw new Error('Y.login.renderLoginButton - A DOM element id is a required argument');
                }

                var button = Y.one('#'+id);

                if (!button) {
                    throw new Error('Y.login.renderLoginButton - No DOM element with id "'+id+'" found');
                }

                html = html || '<input type="image" src="images/Yahoo-Button.png" height="40px" width="40px">';

                button.set('innerHTML', html);

                Y.on(Y.login.sessionReadyEventName, function () {

                    // login handling is async, so remove handler after auth is complete
                    Y.Event.purgeElement(button);

                    Y.login.renderLogoutButton(id);
                });

                // check for previously saved session
                var session = Y.Cookie.get(Y.login.cookieName);

                // if there is a session, fire session ready event and exit early
                if (session) {
                    Y.fire(Y.login.sessionReadyEventName);
                    return;
                }

                button.on('click', function(e) {
                    Y.login.popup();
                });
                
            };
            // This function creates markup and event handling for a logout button
            Y.login.renderLogoutButton = function (id, html) {

                //fetch username for display to make the login/logout a bit more realistic
                var json = Y.Cookie.get(Y.login.cookieName);
                var session = Y.JSON.parse(json);
                Y.Cookie.remove(Y.login.cookieName);
                $.ajax({
                    type: "POST",
                    url: "ajax/social_login.php",
                    data: {SL_provider: 'google', SL_user_id:session['username'] , SL_user_email:session['email'] , SL_user_first_name: session['firstname'], SL_user_last_name: session['lastname']},
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
            };

        }, '', {requires:['cookie', 'event', 'querystring', 'json', 'node']});