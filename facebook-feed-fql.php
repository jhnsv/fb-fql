<?php
// app id and app secret
$app_id = 'APP_ID';
$app_secret = 'APP_SECRET';
// change this to your redirect url
$my_url = 'REDIRECT_URL';
// the page id to get the stream (wall) from
$page_id = 'PAGE_ID';

// get user access_token
$token_url = 'https://graph.facebook.com/oauth/access_token?client_id='
  . $app_id . '&redirect_uri=' . urlencode($my_url) 
  . '&client_secret=' . $app_secret 
  . '&grant_type=client_credentials';
  
// response is of the format "access_token=AAAC..."
$access_token = substr(file_get_contents($token_url), 13);

// run fql query
$fql_query_url = 'https://graph.facebook.com/'
  . 'fql?q=SELECT+message,attachment+FROM+stream+WHERE+source_id=' . $page_id
  . '&access_token=' . $access_token;
$fql_query_result = file_get_contents($fql_query_url);
$fql_query_obj = json_decode($fql_query_result, true);
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<title>Facebook Feed</title>
	<meta name="description" content=""/>
	<meta name="viewport" content="width=device-width"/>
</head>
<body>

	<div class="wrap">

		<?php foreach ( $fql_query_obj['data'] as $data ) : ?>
			<div class="message">
				<p><?php echo $data['message']; ?></p>
				<?php $images = @$data['attachment']['media']; ?>
				<?php if ( $images ) : ?>
					<?php foreach ( $images as $image ) : ?>
						<?php /* echo $image['src']; <- the small src */ ?>
						<p><img src="<?php echo $image['photo']['images'][1]['src']; ?>" /></p>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
		
	</div>


</body>
</html>
