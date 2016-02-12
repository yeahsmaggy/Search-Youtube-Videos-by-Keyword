<?php
$q='aldo';


require_once 'vendor/autoload.php';
$DEVELOPER_KEY = 'YOUR DEVELOPER KEY';


$client = new Google_Client();
$client->setDeveloperKey($DEVELOPER_KEY);


$youtube = new Google_Service_YouTube($client);
try {
	$searchResponse = $youtube->search->listSearch('id,snippet', array(
		'q' => $q,
		'maxResults' => 5,
	));


	$videos = '';
	$channels = '';
	$playlists = '';


	foreach ($searchResponse['items'] as $searchResult) {
		switch ($searchResult['id']['kind']) {
		case 'youtube#video':
			$videos .= sprintf('<li>
			<a target="_blank" href="https://www.youtube.com/watch?v=%s">
			<img src="http://img.youtube.com/vi/' . $searchResult['id']['videoId'] . '/1.jpg"/><br>
			%s</a>
			</li>',
			$searchResult['id']['videoId'], $searchResult['snippet']['title']);
		break;
		}
	}

$htmlBody .= 
<<<END
<style type="text/css">
.vids {
clear: left;
width: 100%;
padding: 0px;
}
.vids li{
list-style: none;
float: left;
width: 132px;
}
</style>
<h3>Videos</h3>
<ul class="vids">$videos</ul>
END;


	} 
	catch (Google_Service_Exception $e) 
		{
			$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
			htmlspecialchars($e->getMessage()));
		} 
	catch (Google_Exception $e) {
			$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
			htmlspecialchars($e->getMessage()));
		}

?>
<!doctype html>
<html>
<head>
<title>YouTube Search</title>
</head>
<body>
<?=$htmlBody?>
</body>
</html>