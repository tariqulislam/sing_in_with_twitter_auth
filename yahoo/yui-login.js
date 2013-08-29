        YUI.add('login', function(Y) {

            
            /// @see http://openid.net/specs/openid-authentication-2_0.html#verification
            // Licensed under Yahoo! BSD
            Y.namespace('login');

            Y.login.cookieName = 'login_session';
            Y.login.sessionReadyEventName = 'login:sessionReady';
            
            // This function constructs an openid login url for Yahoo!, and opens a popup to this location
            // @param {string} returnUrl is the url to redirect to after the user goes through the login flow.  Defaults to document.location.href
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
                            'email':assertion['openid.ax.value.email']
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

                // Self-close login window if user hasn't completed flow in 30 sec
//                Y.later(30000, Y, function () {
//                    timer.cancel();
//                    popup.close();
//                });
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

                html = html || '<img src="images/Yahoo-Button.png" height="100px" width="100px">';

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

                if (!id) {
                    throw new Error('Y.login.renderLogoutButton - A DOM element id is a required argument');
                }

                var button = Y.one('#'+id);

                if (!button) {
                    throw new Error('Y.login.renderLogoutButton - No DOM element with id "'+id+'" found');
                }
                
                //fetch username for display to make the login/logout a bit more realistic
                var json = Y.Cookie.get(Y.login.cookieName);
                var session = Y.JSON.parse(json);
                
                html = html || 'User id: '+session['username']+',   email:'+ session['email'] +'<br><a href="#">log out</a>';

                button.set('innerHTML', html);

                var handler = button.on('click', function(e) {
                    
                    e.preventDefault();
                    
                    // remove session cookie
                    Y.Cookie.remove(Y.login.cookieName);

                    // remove logout click handler
                    Y.Event.purgeElement(button);

                    // restore login button
                    Y.login.renderLoginButton(id);

                });

            };

        }, '', {requires:['cookie', 'event', 'querystring', 'json', 'node']});