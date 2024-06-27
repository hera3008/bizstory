<?
	if ($portrait == "") $portrait = "false";
?>
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
<link href="<?=$local_dir;?>/bizstory/images/icon/favicon.ico" rel="icon" type="image/ico" />
<link href="<?=$local_dir;?>/bizstory/images/icon/favicon.png" rel="icon" type="image/png" />
<link href="<?=$local_dir;?>/bizstory/images/icon/favicon.ico" rel="shortcut icon" type="image/ico" />
<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/bizstory/css/common.css" media="all" />
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
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/common.js" charset="utf-8"></script>
<title><?=$print_title;?></title>
<style media="print">
	.noprint { display: none }
</style>
<style type="text/css">
	body#print_body {background:#fff;}
</style>
</head>

<body id="print_body">
<div class="section noprint">
	<div class="fl">
		<a href="javascript:void(0);" onclick="window.print();" class="btn_big_violet"><span>인쇄</span></a>
		<a href="javascript:void(0);" onclick="window.close();" class="btn_big_violet"><span>닫기</span></a>
	</div>
</div>
<div class="info_text noprint">
	<ul>
		<li>인쇄여백을 위, 아래는 10mm, 오른쪽, 왼쪽 5mm로 해주세요.</li>
		<li>머리글, 바닥글 삭제해주세요.</li>
	</ul>
</div>
