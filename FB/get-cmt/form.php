<html>
<head>
<title>Read All Comments and Replies</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<h1>Facebook Comments Reader</h1>
<hr>
<?php
	ini_set('max_execution_time', 0);
	$idpost = $_POST['inputPostid'];
	$temp = explode("/", $idpost);
	$idpost = $temp[count($temp)-2];
	$limit = "500";
	$token = $_POST['inputToken'];
	$aHtml = json_decode(file_get_contents("https://graph.facebook.com/$idpost/comments?limit=$limit&access_token=$token"),JSON_UNESCAPED_UNICODE); //return array if true, else object
//Trinh bay cho de nhin thoi
	$dem=0;
	$output = "<ul>";
	foreach ($aHtml["data"] as $comment) {
		$dem +=1;
		// Lay tung cmt
		$cmt_id = $comment["id"];
		$output .= '<h4><p class="bg-info"><a href="https://fb.com/'.$comment["from"]["id"].'" target="_blank">'.$comment["from"]["name"]."</a> : ".$comment["message"]."</p></h4>";
		// Lay tung reply
		$aHtmlrep = json_decode(file_get_contents("https://graph.facebook.com/$cmt_id/comments?limit=$limit&access_token=$token"),JSON_UNESCAPED_UNICODE);
		$output .= "<blockquote>";
			foreach ($aHtmlrep["data"] as $reply) 
			{
				if (isset($reply["message"]))
				{
				$output .= '<p><a href="https://fb.com/'.$reply["from"]["id"].'" target="_blank">'.$reply["from"]["name"]."</a> : ".$reply["message"]."</p>";
				$dem +=1;
			}
			}
		$output .= "</blockquote>";
	}
	$output .= "</ul>";
	echo '<h3>Tổng số bình luận : '.$dem.'</h3>';
	echo($output);	
?>
<hr>
<h4>Copyright Minh Dat @ 2017</h4>
</body>
</html>