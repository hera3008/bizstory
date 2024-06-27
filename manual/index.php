<?
	include "../bizstory/common/setting.php";
	include $local_path . "/manual/common/func_manual.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = $_SESSION[$sess_str . '_part_idx'];
	$code_mem = $_SESSION[$sess_str . '_mem_idx'];
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
<meta name="description" content="Business application." />
<meta name="keywords" content="bizstory,biz,business,ubstory" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<link href="<?=$local_dir;?>/bizstory/images/icon/favicon.ico" rel="icon" type="image/ico" />
<link href="<?=$local_dir;?>/bizstory/images/icon/favicon.png" rel="icon" type="image/png" />
<link href="<?=$local_dir;?>/bizstory/images/icon/favicon.ico" rel="shortcut icon" type="image/ico" />
<link href="<?=$local_dir;?>/manual/css/common.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?=$local_dir;?>/manual/js/common.js" charset="utf-8"></script>
<title> Bizstory Manual </title>

<!--[if IE 6]>
	<script type="text/javascript" src="<?=$local_dir;?>/manual/js/DD_belatedPNG_0.0.8a-min.js" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		DD_belatedPNG.fix('*')
		try { document.execCommand('BackgroundImageCache', false, true); }catch(e){}
	</script>
<![endif]-->
<!--[if IE 7]>
	<style type="text/css">
		#layout_table {position:relative; z-index:2 !important;}
	</style>
<![endif]-->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<style>
/**/
	html, body, div, span, applet, object, iframe,
	h1, h2, h3, h4, h5, h6, p, blockquote, pre,
	a, abbr, acronym, address, big, cite, code,
	del, dfn, em, font, img, ins, kbd, q, s, samp,
	small, strike, strong, sub, sup, tt, var,
	b, u, i, center,
	dl, dt, dd, ol, ul, li,
	fieldset, form, label, legend,
	table, caption, tbody, tfoot, thead, tr, th, td {
		margin:0; padding:0; border:0; outline:0;
		vertical-align:baseline; background:transparent;
	}

	html, body {height:100%;}
	html {overflow-y:scroll;}
	body {
		margin:0; padding:0; position:relative;
		min-width:1003px; height:100%;
		background:url("/manual/images/common/pattern.gif") repeat; background-position:0 0; background-color:#fff;
		font:12px "NanumGothicBoldWeb", "NanumGothicWeb", "Malgun Gothic", "Dotum", "돋움", "sans-serif", "Arial";
	}

	img {vertical-align:middle; border:0;}

	a:link {color:#777; text-decoration:none;}
	a:visited {color:#777; text-decoration:none;}
	a:active {color:#777; text-decoration:none;}
	a:hover {color:#000; text-decoration:none;}
	table {
		border-collapse:collapse; border-spacing:0; empty-cells:show;
	}
	ol, ul { list-style:none; }

/* Loading */
	#loading {
		position:absolute; z-index:100000; width:100%; height:100%;
		overflow:hidden; display:none;
		text-indent:-9999px;
		background:url("/manual/images/common/loading2.gif") no-repeat; background-position:50% 50%;
		filter:alpha(opacity=60); opacity:0.6;
	}
	#loading:after{
		display:block; clear:both; content:'';
	}

/* Top Button */
	.top_btn {
		position:fixed; z-index:1000; right:10px; bottom:10px;
	}
	.top_btn a {
		display:block; width:20px; height:20px;
		background:transparent url("/manual/images/btn/topbtn.png") no-repeat;
	}
	.top_btn a:hover {
		background-position:0px -27px;
	}

/* Header */
	#header {
		height:43px; position:relative; z-index:6;
		background:url("/manual/images/common/header_img.png") repeat-x; background-position:0 -200px;
	}
	#header a.logo {
		left:0; top:0; width:204px; height:39px;
		background-position:0 0;
	}
	#header a.logo img {
		width:196px; height:38px; margin:1px 0 0 1px;
	}
	#footer {
		clear:both; width:100%; display:block;
		margin:0 auto; padding:10px 0;
		font-size:60%; text-align:center; line-height:14px;
	}
/* Footer */
	#footer address {
		font-style:normal;
	}
	#footer address span {
		padding-left:2px;
	}
	#footer address em {
		padding-left:6px; font-style:normal;
	}

/* backgroundPopup */
	#backgroundPopup {
		display:none; position:absolute; z-index:15;
		width:100%; height:100%; min-height:100%;
		top:0px; left:0px; padding-bottom:20px;
		background:#31323a;
	}

/* Layout Table */
	#layout_table {
		/*top:43px; */
		width:100%; height:auto 100%;
		position:relative; table-layout:fixed;
		word-wrap:break-word;
	}
	#layout_table td#sidebar {
		width:198px;
		height:100%;
		vertical-align:top;
	}
	#layout_table td#sidebar #sidebar_width {
		width:198px; vertical-align:top;
	}
	#layout_table td#container {
		width:100%; height:100%;
		vertical-align:top;z-index:12;
		display:block;
	}

/* 사이드 메뉴 */
	#sidebar {
		height:100%; padding:0px; margin:0px;
		background:url("/manual/images/common/sidebar_bg.gif") repeat-y;
		background-position:0 0;
	}
	#sub_navi {
		clear:both; width:198px; height:100%; position:relative; top:2px;
		letter-spacing:-.05em; *letter-spacing:-.05em;
	}
	#sub_navi ul {
		display:none;
	}
	#sub_navi li {
		position:relative; width:100%;
	}
/* IE leaves a blank space where span is added so this is to avoid that */
	* html #sub_navi li{ float:left; display:inline; }
	#sub_navi li a {
		display:block;
		margin-top:-3px; height:32px;
		line-height:32px; *line-height:34px;
		padding-left:34px;
		border-top:1px solid #464954;
		text-shadow:#4f5366 1px 1px 0; color:#c1c5ce; font-weight:700;
		background:url("/manual/images/common/sub_navi_bg.gif") repeat-x; background-position:0 0;
	}
	#sub_navi li a:active { background-position:0 -32px; }
	#sub_navi li a em {
		float:left; position:absolute; top:9px; *top:6px; left:14px; width:15px; height:15px;
		cursor:auto; cursor:pointer; *cursor:hand;
		font-size:0;
		background:url("/manual/images/icon/navi_icon.png") no-repeat;
	}
	#sub_navi li a.icon01 em { background-position:0 -75px; }
	#sub_navi li a.icon02 em { background-position:0 -167px; }
	#sub_navi li a.icon03 em { background-position:0 -91px; }
	#sub_navi li a.icon04 em { background-position:0 -105px; }
	#sub_navi li a.icon05 em { background-position:0 -152px; }
	#sub_navi li a.icon06 em { background-position:0 -75px; }
	#sub_navi li a.icon07 em { background-position:0 -90px; }
	#sub_navi li span {
		float:right; position:absolute; top:2px; *top:0; right:0; width:100%; height:32px;
		cursor:auto; cursor:pointer; *cursor:hand;
		font-size:0;
	}
	#sub_navi li span, #sub_navi li span.collapsed { background:url("/manual/images/icon/collapsed.gif") no-repeat; background-position:95% 38%; }
	#sub_navi li span.expanded { background:url("/manual/images/icon/expanded.gif") no-repeat; background-position:95% 38%; }

	/* 2단계 */
	#sub_navi li li { background:#545765 url("/manual/images/icon/st1.png") 11px 4px no-repeat; }
	#sub_navi li li.frist { margin-top:8px; }
	#sub_navi li li.end { margin-bottom:8px; }
	#sub_navi li li a {
		display:block; height:25px; padding-left:32px;
		border-top:none; background:none; background-position:30px 50%;
		font-weight:400; color:#e8e8e8; line-height:27px;
	}
	#sub_navi li li a:hover { color:#f1f1f1; background-position:30px 50%; }
	/* 3단계 */
	#sub_navi li li li { background:#fff; background:#545765 url("/manual/images/icon/st.png") 20px 0px no-repeat; }
	#sub_navi li li li.frist { margin-top:5px; }
	#sub_navi li li li.end { margin-bottom:7px; }
	#sub_navi li li li a {
		color:#aaa; line-height:23px;
		display:block; height:21px;
		padding-left:42px; padding-top:2px; padding-bottom:2px;
		border-top:none; background:none;
	}
	#sub_navi li li li a:hover { color:#c0c0c0; background:none; text-decoration:underline; }
	/* 4단계 */
	#sub_navi li li li li {
		background:#fff; background:#545765 url("/manual/images/icon/st.png") 28px 0px no-repeat;
	}
	#sub_navi li li li li a {
		color:#aaa;
		display:block;
		height:21px;
		line-height:23px;
		padding-left:50px;
		border-top:none;
		background:none;
	}
	/* 5단계 */
	#sub_navi li li li li li {
		background:#fff; background:#545765 url("/manual/images/icon/st.png") 36px 0px no-repeat;
	}
	#sub_navi li li li li li a {
		color:#aaa;
		display:block;
		height:21px;
		line-height:23px;
		padding-left:58px;
		border-top:none;
		background:none;
	}
	/* 6단계 */
	#sub_navi li li li li li li {
		background:#fff; background:#545765 url("/manual/images/icon/st.png") 44px 0px no-repeat;
	}
	#sub_navi li li li li li li a {
		color:#aaa;
		display:block;
		height:21px;
		line-height:23px;
		padding-left:66px;
		border-top:none;
		background:none;
	}
	/* 7단계 */
	#sub_navi li li li li li li li {
		background:#fff; background:#545765 url("/manual/images/icon/st.png") 52px 0px no-repeat;
	}
	#sub_navi li li li li li li li a {
		color:#aaa;
		display:block;
		height:21px;
		line-height:23px;
		padding-left:74px;
		border-top:none;
		background:none;
	}
	/* 8단계 */
	#sub_navi li li li li li li li li {
		background:#fff; background:#545765 url("/manual/images/icon/st.png") 60px 0px no-repeat;
	}
	#sub_navi li li li li li li li li a {
		color:#aaa;
		display:block;
		height:21px;
		line-height:23px;
		padding-left:82px;
		border-top:none;
		background:none;
	}
	/* 9단계 */
	#sub_navi li li li li li li li li li {
		background:#fff; background:#545765 url("/manual/images/icon/st.png") 68px 0px no-repeat;
	}
	#sub_navi li li li li li li li li li a {
		display:block; height:21px; padding-left:90px; border-top:none; background:none;
		line-height:23px; color:#aaa;
	}
	/* 10단계 */
	#sub_navi li li li li li li li li li li {
		background:#fff;
		background:#545765 url("/manual/images/icon/st.png") 76px 0px no-repeat;
	}
	#sub_navi li li li li li li li li li li a {
		display:block; height:21px; padding-left:98px;
		border-top:none; background:none;
		line-height:23px; color:#aaa;
	}

	#sub_navi li li span {
		float:right; position:absolute;
		top:3px; *top:1px; right:4px; width:100%; height:21px;
		cursor:auto; cursor:pointer; *cursor:hand;
		font-size:0;
	}
	#sub_navi li li span, #sub_navi li li span.collapsed {
		background:url("/manual/images/icon/collapsed2.gif") no-repeat; background-position:95% 50%;
	}
	#sub_navi li li span.expanded {
		background:url("/manual/images/icon/expanded2.gif") no-repeat; background-position:95% 50%;
	}

/* container */
	#layout_table td#container .sub_layout_box {
		margin-left:9px; margin-right:5px;
		overflow:hidden;
	}
/* 사이트 타이틀 및 경로 */
	.home_pagenavi {
		clear:both;
		width:100%;
		height:30px;
		margin-bottom:10px;
		letter-spacing:-.05em;
		*letter-spacing:-.05em;
		background:url("/manual/images/common/dotline.gif") repeat-x;
		background-position:0 100%;
		overflow:hidden;
	}
	.home_pagenavi h2 {
		float:left;
		height:24px;
		display:block;
		overflow:hidden;
		color:#000;
		font-size:120%;
		font-weight:700;
		line-height:25px;
		text-shadow:#eeeeee 1px 1px 0;
		filter:DropShadow(Color=#eeeeee, OffX=0, OffY=1, Positive=1);
		padding-left:21px;
		background:url("/manual/images/icon/title_icon.gif") no-repeat 0 50%;
	}

	.contents_body { font-size:13px; line-height:150%; }
	.contents_body ul { padding:5px; }
	.contents_body li { padding:5px 5px 0 5px; }
	.manual_img {vertical-align:middle; border:2px solid #cc6600;}
</style>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function() {

	// Loading
		$("#loading").fadeIn(400);
		$("#loading").fadeOut(1400);
		$(window).resize(function(){
			$('#loading').css({
				position:'absolute',
				left: ($(window).width() - $('#loading').outerWidth())/2,
				top: ($(window).height() - $('#loading').outerHeight())/2
			});

			$('#container_body').css({
				'min-height': $(window).height() - 100
			});
		});
		$(window).resize();

	// Top of Page
		$('.top_btn').hide();
		$(window).scroll(function () {
			if( $(this).scrollTop() > 100 ) {
				$('.top_btn').fadeIn(300);
			}
			else {
				$('.top_btn').fadeOut(300);
			}
		});
		$('.top_btn a').click(function(){
			$('html, body').animate({scrollTop:0}, 500 );
			return false;
		});

	// Ajax 설정
		$.ajaxSetup(
		{
			async       : true, // async : true, // 비동기
			processData : true,
			cache       : true,
			timeout     : 50000,
			dataType    : "json",
			type        : "post",
			contentType : "application/x-www-form-urlencoded;charset=UTF-8",
			beforeSubmit: function(){ $("#loading").fadeIn('slow'); },
			error       : function(xhr, status, error)
			{
				var error_msg = xhr + "<br />" + status + "<br />" + error + "<br />";
				return false;
			},
			complete    : function(){
				$("#loading").fadeOut('slow');
			}
		});

		window.onload = sidebar;
	});
//]]>
</script>
</head>

<body>
	<div id="loading">로딩중입니다.</div>
	<div id="header">
		<a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/manual/'" title="홈으로 이동 합니다." class="logo"><img src="<?=$local_dir;?>/manual/images/common/logo.jpg" width="198px" height="39px" alt="Bizstory Logo" /></a>
	</div>

	<table id="layout_table">
		<tr>
			<td id="sidebar">
				<div id="sidebar_width">
			<?
				$left_list = manual_menu_list($code_comp, $code_part, $set_part_yn);

				$menu_chk = $left_list['menu'];
				$chk_sort = $left_list['sort'];

				$left_str = manual_menu_view($menu_chk, 1, 0, 'sub_navi', $chk_sort);
				echo $left_str;
			?>
				</div>
			</td>
			<td id="container">
				<div id="container_body">
			<?
				if ($fmode == '' || $smode == '')
				{
					$link_file = 'main.php';
				}
				else
				{
					$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
					$code_part  = $_SESSION[$sess_str . '_part_idx'];

					$navi_name = manual_menu_name($code_comp, $code_part, $fmode, $smode);
			?>
					<div class="sub_layout_box">
						<div class="home_pagenavi">
							<h2>
								<?=$navi_name;?>
							</h2>
						</div>
			<?
						$link_file = $fmode . '_' . $smode . '.php';
			?>
					</div>
			<?
				}

				if (is_file($link_file)) include $link_file;
				else echo $link_file . "파일이 없습니다.";
			?>
				</div>
				<div id="footer"><address><em>Copyright &copy;</em><strong>BIZSTORY</strong><span>All Rights Reserved.</span></address></div>
			</td>
		</tr>
	</table>

	<div id="backgroundPopup"></div>
	<div class="top_btn"><a href="javascript:void(0);" title="Scroll to top"></a></div>
<?
// 서브메뉴 펼치기 위해서
	if ($fmode != '' && $smode != '')
	{
		$navi_where = " and mi.mode_folder = '" . $fmode . "' and mi.mode_file = '" . $smode . "'";
		$navi_data = menu_info_data("view", $navi_where);
		$navi_up = $navi_data['up_mi_idx'];
		$navi_up_arr = explode(',', $navi_up);
		foreach ($navi_up_arr as $navi_up_k => $navi_up_v)
		{
			if ($navi_up_k > 0)
			{
				if ($navi_up_k == 1) $chk_up = $navi_up_v;
				else $chk_up .= '_' . $navi_up_v;
			}
		}
	}
?>
<script type="text/javascript">
//<![CDATA[
	var now_sub_menu_id = 'submenu_<?=$chk_up;?>';

// Sub Navi
	this.sidebar = function(){
		var sidebar = document.getElementById("sub_navi")
		if(sidebar)
		{
			this.listItem = function(li){
				if(li.getElementsByTagName("ul").length > 0)
				{
					var ul = li.getElementsByTagName("ul")[0];
					var ul_id = $(ul).attr('id');
					var ul_id_arr = ul_id.split('_');
					var chk_ul_id = ul_id_arr[0] + '_' +  ul_id_arr[1];

					ul.style.display = "none";
					var span = document.createElement("span");
					span.className = "collapsed";
					span.onclick = function(){
						ul.style.display = (ul.style.display == "none") ? "block" : "none";
						this.className = (ul.style.display == "none") ? "collapsed" : "expanded";
					};

					var sub_menu_arr = now_sub_menu_id.split('_');
					var left_str = 'submenu';
					for (var left_num = 1; left_num < 10; left_num++)
					{
						if (sub_menu_arr[left_num] != undefined)
						{
							left_str = left_str + '_' + sub_menu_arr[left_num];
							$("#" + left_str).css({"display": "block"});
							if (ul_id == left_str)
							{
								span.className = "expanded";
							}
						}
					}

					li.appendChild(span);
				};
			};

			var items = sidebar.getElementsByTagName("li");
			for(var i = 0; i < items.length; i++)
			{
				listItem(items[i]);
			};
		};
	};

//]]>
</script>

</body>
</html>