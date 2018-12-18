<?php
require_once('../api.php');

function tostr($n) {
  return rtrim(rtrim(sprintf('%.8F', $n), '0'), ".");
}

if (empty($_GET)) {
  header('Location: ../index.php');
  die();
}

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => "937501697085075456-hNAJAuLlBpk63Okafso2OinGIz8kKuz",
    'oauth_access_token_secret' => "1TpP7Ac3x7PKeX4QRMjbRCwavZo7BhdNOSNhA9YturdLB",
    'consumer_key' => "8wz0ckffsgnthyivSnfdm5jZJ",
    'consumer_secret' => "gUT0Px29Zc492prMvw3BW7w2WdPb1dz1LZZjnIgGeFjdQjo9fZ"
);

$twitter = new TwitterAPIExchange($settings);

$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
$requestMethod = "GET";
$user = $_GET['user'];
$count = 200;
$getfield = "?screen_name={$user}&count={$count}";

$all_ids = array();

if (!file_exists("data/{$user}.txt")) {
  do {
    $pre_count = count($all_ids);

    $tweets = json_decode($twitter->setGetfield($getfield)
                 ->buildOauth($url, $requestMethod)
                 ->performRequest(),$assoc = TRUE);

    if(array_key_exists("errors", $tweets)) {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$tweets[errors][0]["message"]."</em></p>";exit();}

    foreach($tweets as $items) {
      $id = $items["id_str"];
      if (!in_array($id, $all_ids)) {
        array_push($all_ids, $id);
      }
    }

    $max_id = min($all_ids);
    $getfield = "?screen_name={$user}&count={$count}&max_id={$max_id}";

    $post_count = count($all_ids);
  } while ($post_count > $pre_count);
  $ids_serialized = serialize($all_ids);
  file_put_contents("data/{$user}.txt", $ids_serialized);
} else {
  $all_ids = unserialize(file_get_contents("data/{$user}.txt"));
  do {
    $pre_count = count($all_ids);

    $tweets = json_decode($twitter->setGetfield($getfield)
                 ->buildOauth($url, $requestMethod)
                 ->performRequest(),$assoc = TRUE);

    if(array_key_exists("errors", $tweets)) {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$tweets[errors][0]["message"]."</em></p>";exit();}

    foreach($tweets as $items) {
      $id = $items["id_str"];
      if (!in_array($id, $all_ids)) {
        array_push($all_ids, $id);
      }
    }

    $max_id = min($all_ids);
    $getfield = "?screen_name={$user}&count=200&max_id={$max_id}";

    $post_count = count($all_ids);
  } while ($post_count > $pre_count);
  $ids_serialized = serialize($all_ids);
  file_put_contents("data/{$user}.txt", $ids_serialized);
}

$rand_ids = array_rand($all_ids, 10);
shuffle($rand_ids);

foreach ($rand_ids as $key) {
  $this_id = $all_ids[$key];
  echo "
  <blockquote class='twitter-tweet'>
    <a href='https://twitter.com/{$user}/status/{$this_id}'></a>
  </blockquote>
  ";
}
?>
