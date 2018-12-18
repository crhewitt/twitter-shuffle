<!DOCTYPE html>
<html>
  <head>
  <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
  <script type="text/javascript" src="../customize-twitter.js"></script>
  <script>
    var options = {
      "url": "../style.css",
      "widget_count": 10
    };
    CustomizeTwitterWidget(options);
  </script>
  <link rel="stylesheet" type="text/css" href="../style.css"></link>
</head>
<body>
  <div class='twitter-block'>
    <?php include_once('get_tweets.php'); ?>
  </div>
</body>
</html>
