<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="imagetoolbar" content="no" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="description" content="Business application." />
<meta name="keywords" content="bizstory,biz,business" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<link href="<?=$local_dir;?>/bizstory/images/icon/favicon.ico" rel="icon" type="image/ico" />
<link href="<?=$local_dir;?>/bizstory/images/icon/favicon.png" rel="icon" type="image/png" />
<link href="<?=$local_dir;?>/bizstory/images/icon/favicon.ico" rel="shortcut icon" type="image/ico" />
<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/sole/css/common.css" media="all" />
<!--[if IE 7]>
	<style type="text/css">
		#layout_table {position:relative; z-index:2 !important;}
	</style>
<![endif]-->
<? // Javascript Files ?>
<!--[if IE 6]>
	<script type="text/javascript" src="<?=$local_dir;?>/common/js/DD_belatedPNG_0.0.8a-min.js" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		DD_belatedPNG.fix('*')
		try { document.execCommand('BackgroundImageCache', false, true); }catch(e){}
	</script>
	<style type="text/css">
		.hb_schedule .schedule_textarea textarea {
			background:none;
		}
	</style>
<![endif]-->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="<?=$local_dir;?>/sole/js/common.js" charset="utf-8"></script>
<?
	if (strlen(stristr($_SERVER["HTTP_USER_AGENT"], "Mobile")) > 0) {
?>
<!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
<link rel="apple-touch-icon-precomposed" href="<?=$local_dir;?>/bizstory/mobile/themes/img/apple-touch-icon-144x144-precomposed.png" media="screen and (resolution: 163dpi)" />
<!-- For first-generation iPad: -->
<link rel="apple-touch-icon-precomposed" href="<?=$local_dir;?>/bizstory/mobile/themes/img/apple-touch-icon-72x72-precomposed.png" media="screen and (resolution: 132dpi)" />
<link rel="apple-touch-startup-image" href="<?=$local_dir;?>/bizstory/mobile/themes/img/ipad-landscape.png" media="screen and (min-device-width: 1024px) and (max-device-width: 1024px) and (orientation:landscape)" />
<link rel="apple-touch-startup-image" href="<?=$local_dir;?>/bizstory/mobile/themes/img/ipad-portrait.png" media="screen and (min-device-width: 1024px) and (max-device-width: 1024px) and (orientation:portrait)" />
<!-- For iPhone 4 with high-resolution Retina display: -->
<link rel="apple-touch-icon-precomposed" href="<?=$local_dir;?>/bizstory/mobile/themes/img/apple-touch-icon-72x72-precomposed.png" media="screen and (resolution: 326dpi)" />
<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/common/css/ipad-portrait.css" media="only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation:portrait)" />
<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/common/css/ipad-landscape.css" media="only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation:landscape)" />
<?
	}
?>
