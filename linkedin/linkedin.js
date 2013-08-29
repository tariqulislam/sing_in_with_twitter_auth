function onLinkedInLoad() {
 $('a[id*=li_ui_li_gen_]').html('<img src="http://testserver.bscheme.com/social_login/images/LinkedIn-Button_zps1044accd.gif" height="90" width="90" border="0" />');
$('#linkedin').css({display:'block'});
  IN.Event.on(IN, "auth", function() {onLinkedInLogin();});
  IN.Event.on(IN, "logout", function() {onLinkedInLogout();});
  
}

function onLinkedInLogout() {
  setLoginBadge(false);
}

function onLinkedInLogin() {
  // we pass field selectors as a single parameter (array of strings)
  IN.API.Profile("me")
    .fields(["id", "firstName", "lastName", "pictureUrl", "publicProfileUrl","email-address"])
    .result(function(result) {
      setLoginBadge(result.values[0]);
    })
    .error(function(err) {
      alert(err);
    });
}

// get and create the user information container//
// get and create the user information container//
function setLoginBadge(profile) {
  if (!profile) {
    profHTML = "<p>You are not logged in</p>";
  }
  else {
    var pictureUrl = profile.pictureUrl || "http://static02.linkedin.com/scds/common/u/img/icon/icon_no_photo_80x80.png";
    profHTML = "<p><a href=\"" + profile.publicProfileUrl + "\">";
    profHTML = profHTML + "<img align=\"baseline\" src=\"" + pictureUrl + "\"></a>";      
    profHTML = profHTML + "&nbsp; Welcome <a href=\"" + profile.publicProfileUrl + "\">";
    profHTML = profHTML + "&nbsp; Welcome  "+ profile.emailAddress;
    profHTML = profHTML + "&nbsp;  <a href=\"" + profile.publicProfileUrl + "\">";
    profHTML = profHTML + profile.firstName + " " + profile.lastName + "</a>! <a href=\"#\" onclick=\"IN.User.logout(); return false;\">logout</a></p>";
  }
  document.getElementById("loginbadge").innerHTML = profHTML;
}

