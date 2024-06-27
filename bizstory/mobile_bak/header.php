<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title><?=$mobile_eng;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-touch-fullscreen" content="yes" />
<meta http-equiv="cache-control" content="no-cache" />
<meta name="format-detection" content="telephone=no" />
<meta name="publisher" content="HomeBox | Woo Deok seong" />
<meta name="keywords" content="UBStory,BizStory,HomeStory" />
<link rel="apple-touch-icon-precomposed" href="icon.png" />
<link rel="apple-touch-startup-image" href="startup.jpg" />
<link rel="stylesheet" type="text/css" href="<?=$mobile_dir;?>/themes/themes.css" media="all" />
<link rel="stylesheet" type="text/css" href="<?=$mobile_dir;?>/themes/landscape.css" media="all and (orientation:landscape)">
<link rel="stylesheet" type="text/css" href="<?=$mobile_dir;?>/themes/jquery-ui-1.8.16.custom.css" media="all" />
<script type="text/javascript" src="<?=$mobile_dir;?>/js/common.js" charset="utf-8"></script>
</head>

<body>
<!-- div id="loading"></div -->
<div id="homebox"></div>
<form id="moreform" name="moreform" method="post" action="#">
	<input type="hidden" id="moretype" name="moretype" value="<?=$moretype;?>" />
	<input type="hidden" id="morenum"  name="morenum"  value="<?=$page_num;?>" />
	<input type="hidden" id="moresize" name="moresize" value="<?=$page_size;?>" />
	<input type="hidden" id="morelist_type" name="list_type" value="<?=$list_type;?>" />
</form>
