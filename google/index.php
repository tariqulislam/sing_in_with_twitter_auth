<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8' />
    </head>
    <body>
        
        <!--Add a button for the user to click to initiate auth sequence -->
        <input type="image" src="../images/google.jpg" style="height:90px;width:90px;" id="authorize-button" <!--style="visibility: hidden;"--> />
        <script type="text/javascript" src="google.js"></script>
        <script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>
        <div id="authContent"></div>
        <div id="content"></div>
        <p>Retrieves your profile name using the Google Plus API.</p>
    </body>
</html>
