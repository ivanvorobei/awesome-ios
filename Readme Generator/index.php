<?php
DEFINE ('CURRENT_DIR', dirname( __FILE__ ));
require_once CURRENT_DIR.'/class/markdown.php';
$markdown = new MarkDown;
$markdown->generate_md();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon.png"/>
	<title>Readme</title>
	<style>
	html {
		height:100%;
	}
	body {
		height:100%;
		margin:0;
		padding:0;
		display:flex;
		justify-content:center;
		align-items:center;
	}
	body > div {
		text-align: center;
	}
	.button{
		margin: 10px auto;
		display: inline-block;
		border: 1px solid #999;
		padding: 10px 20px;
		border-radius: 3px;
		color: #000;
		background: #fafafa;
		text-decoration: none;
	}
	.button:hover{
		text-decoration: underline;
	}
	</style>
</head>
<body>
	<a class="button" href="Readme.md" download>Download</a>
</body>
</html>