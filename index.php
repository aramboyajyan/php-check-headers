<?php

/**
 * @file
 * Simple script that checks headers of a given URL.
 *
 * This script is NOT SECURE.
 * It should be used only locally and as a tool for development.
 *
 * Created by: Topsitemakers
 * http://www.topsitemakers.com/
 */

// Prevent XSS via $_SERVER['PHP_SELF']
$php_self = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);

/**
 * Helper function
 *
 * Use regex instead of filter_var as that has some issues in older versions
 * of PHP.
 */
function is_valid_url($url) {
  return preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url);
}

if ($_POST) {
  $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
  // If 'http' ommitted, add manually
  if (substr($url, 0, 4) != 'http') $url = 'http://' . $url;
  // Check if the URL is valid and continue
  if (is_valid_url($url)) {
    // URL is OK, get the headers and build the output
    $headers = '';
    foreach (get_headers($url) as $header) {
      $headers .= $header . '<br>';
    }
  }
  else {
    $headers = 'Please enter a valid URL.';
  }

}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>URL Header check</title>
<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<link type="text/css" rel="stylesheet" href="//necolas.github.com/normalize.css/2.0.1/normalize.css" />
<style type="text/css">
#main {
  width: 600px;
  margin: 40px auto;
  text-align: center;
  background: #F9F9F9;
  padding: 30px;
}
#headers {
  text-align: left;
  padding-top: 20px;
  margin-top: 20px;
  border-top: 1px solid #DDD;
  float: left;
  width: 100%;
}
#url {
  width: 380px;
  padding: 10px;
  margin: 7px 10px 0 0;
  float: left;
}
#url:focus {
  outline: 0;
}
/* Add some fancyness */
/* http://hellohappy.org/css3-buttons/ */
#submit {
  float: right;
  background: #4162a8;
  border-top: 1px solid #38538c;
  border-right: 1px solid #1f2d4d;
  border-bottom: 1px solid #151e33;
  border-left: 1px solid #1f2d4d;
  border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 10px 1px #5c8bee, 0px 1px 0 #1d2c4d, 0 6px 0px #1f3053, 0 8px 4px 1px #111111;
  box-shadow: inset 0 1px 10px 1px #5c8bee, 0px 1px 0 #1d2c4d, 0 6px 0px #1f3053, 0 8px 4px 1px #111111;
  color: #fff;
  font: bold 20px/1 "helvetica neue", helvetica, arial, sans-serif;
  padding: 10px 0 12px 0;
  text-align: center;
  text-shadow: 0px -1px 1px #1e2d4d;
  width: 180px;
  -webkit-background-clip: padding-box;
  margin: 4px 0 10px 0;
}
#submit:hover {
  -webkit-box-shadow: inset 0 0px 20px 1px #87adff, 0px 1px 0 #1d2c4d, 0 6px 0px #1f3053, 0 8px 4px 1px #111111;
  box-shadow: inset 0 0px 20px 1px #87adff, 0px 1px 0 #1d2c4d, 0 6px 0px #1f3053, 0 8px 4px 1px #111111;
  cursor: pointer;
}
#submit:active {
  -webkit-box-shadow: inset 0 1px 10px 1px #5c8bee, 0 1px 0 #1d2c4d, 0 2px 0 #1f3053, 0 4px 3px 0 #111111;
  box-shadow: inset 0 1px 10px 1px #5c8bee, 0 1px 0 #1d2c4d, 0 2px 0 #1f3053, 0 4px 3px 0 #111111;
  margin: 8px 0 6px 0;
}
</style>
<script type="text/javascript">
// Focus the input box on page load
window.onload = function() {
  document.getElementById('url').focus();
}
</script>
</head>
<body>

<div id="main">

  <form action="<?php print $php_self; ?>" method="post">
    <input type="text" name="url" id="url" value="<?php isset($_POST['url']) ? print $_POST['url'] : ''; ?>" placeholder="Enter URL">
    <input type="submit" value="Check headers" id="submit">
  </form>

  <?php if (isset($headers)): ?>
  <div id="headers"><?php print $headers; ?></div>
  <?php endif; ?>

  <div style="display: block; clear: both;"></div>

</div>

</body>
</html>
