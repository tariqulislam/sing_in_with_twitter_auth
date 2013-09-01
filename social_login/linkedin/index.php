<!--<html>
    <head><title></title>
<script type="text/javascript" src="http://platform.linkedin.com/in.js">
      api_key: mezzzlyucjjv
      authorize: true
</script>
</head>
<body>
  <script type="IN/Login"></script>
</body>
</html>-->
<script type="text/javascript" src="http://platform.linkedin.com/in.js">
  api_key: mezzzlyucjjv
  onLoad: onLinkedInLoad

</script>


<p>This example demonstrates the use of a login button to control what's displayed.  It also demonstrates how to use the LinkedIn auth events in a user flow.</p>

<!-- NOTE: be sure to set onLoad: onLinkedInLoad -->
<script type="text/javascript">
function onLinkedInLoad() {
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
</script>

<!-- need to be logged in to use; if not, offer a login button -->
<script type="IN/Login"></script>

<div id="loginbadge">
  <p>Login badge renders here if the current user is authorized.</p>
</div>