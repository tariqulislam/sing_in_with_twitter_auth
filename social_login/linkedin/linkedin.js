function onLinkedInLoad() {
 $('a[id*=li_ui_li_gen_]').html('<img src="http://testserver.bscheme.com/social_login/images/LinkedIn-Button_zps1044accd.gif" height="40px" width="40px" border="0" />');
$('#linkedin').css({display:'block'});
  IN.Event.on(IN, "auth", function() {onLinkedInLogin();});
  IN.Event.on(IN, "logout", function() {onLinkedInLogout();});
  
}

function onLinkedInLogout() {
  
  setLoginBadge(false);
  $('a[id*=li_ui_li_gen_]').html('<img src="http://testserver.bscheme.com/social_login/images/LinkedIn-Button_zps1044accd.gif" height="40px" width="40px" border="0" />');
}

function onLinkedInLogin() {
  // we pass field selectors as a single parameter (array of strings)
  IN.API.Profile("me")
    .fields(["id", "firstName", "lastName", "pictureUrl", "publicProfileUrl","email-address"])
    .result(function(result) {
      var myProfile =result.values[0];
      //IN.User.logout();
      setLoginBadge(myProfile);
    })
    .error(function(err) {
      alert(err);
    });
}

// get and create the user information container//
// get and create the user information container//
function setLoginBadge(profile) {
         $.ajax({
                    type: "POST",
                    url: "ajax/social_login.php",
                    data: {SL_provider: 'linedin', SL_user_id: profile.id, SL_user_email: profile.emailAddress, SL_user_first_name: profile.firstName, SL_user_last_name: profile.lastName},
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
//    var pictureUrl = profile.pictureUrl || "http://static02.linkedin.com/scds/common/u/img/icon/icon_no_photo_80x80.png";
//    profHTML = "<p><a href=\"" + profile.publicProfileUrl + "\">";
//    profHTML = profHTML + "<img align=\"baseline\" src=\"" + pictureUrl + "\"></a>";      
//    profHTML = profHTML + "&nbsp; Welcome <a href=\"" + profile.publicProfileUrl + "\">";
//    profHTML = profHTML + "&nbsp; Welcome  "+ profile.emailAddress;
//    profHTML = profHTML + "&nbsp;  <a href=\"" + profile.publicProfileUrl + "\">";
//    profHTML = profHTML + profile.firstName + " " + profile.lastName + "</a>! <a href=\"#\" onclick=\"IN.User.logout(); return false;\">logout</a></p>";
  
  //document.getElementById("loginbadge").innerHTML = profHTML;
}

