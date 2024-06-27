<?
	include "../common/setting.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include  "./header.php";
?>
<script type="text/javascript" src="./js/jquery.promptumenu.min.js"></script>
<script type="text/javascript">
	$(function(){
		
		$('.example_3 ul').promptumenu({
			/* 'width': 500,
			'height': 100, */
			'rows': 1,
			'columns': 3,
			'direction': 'horizontal',
			'pages': true
		});
	});
	
</script>
<style type="text/css">
html, body {background:transparent;background:none;}
.navGnb {margin:0 18px 13px 18px;}
.navGnb .navGnb_area {width:100%; overflow:hidden;border:1px solid #9d805e; background-color:rgba(210, 176, 133,0.5);-moz-border-radius:.6em;-webkit-border-radius:.6em;border-radius:.6em; -moz-box-shadow:0px 0px 2px rgba(255,255,255,1); -webkit-box-shadow:0px 0px 2px rgba(255,255,255,1); box-shadow:0px 0px 2px rgba(255,255,255,1); }
.promptumenu_nav{z-index: 5;position: absolute;bottom:7px;left: 50%;margin-right: -50%;}
	.promptumenu_nav a{cursor: pointer;width: 13px;height: 13px;text-indent: -9999px;outline: none;background: url(./images/pagelink2.png) 0 -13px no-repeat;display: block;float:left;position: relative;left: -50%;margin: 0 2px;}
	.promptumenu_nav a.active{background: url(./images/pagelink2.png) 0 0 no-repeat;}
	.promptumenu_window{margin: 0;position: relative;display:block;padding:0;top:0;height:120px !important;overflow:none !important;}
		.promptumenu_window ul{padding:0; margin:0;height:120px !important;text-align:center;}
		.promptumenu_window ul li {position:relative;clear:both;top:15px !important;height:120px !important;text-align:center;margin:0 auto;display:block;}
			.promptumenu_window ul li img {width:60px;top:0;}
			.promptumenu_window ul li span {position:absolute;top:65px;left:0;width:100%;text-align:center;margin:0 auto;text-shadow: 1px 1px 0px rgba(255,255,255,.6);}
</style>


					<section class="navGnb">
						<div class="navGnb_area">
		<div class="example_3">
			<ul>
				<li><img src="./images/nav_m1.png" alt="나의업무" /><span>나의업무</span></li>
				<li><img src="./images/nav_m2.png" alt="직원목록" /><span>직원목록</span></li>
				<li><img src="./images/nav_m5.png" alt="접수목록" /><span>접수목록</span></li>
				<li><img src="./images/nav_m4.png" alt="거래처목록" /><span>거래처목록</span></li>
				<li><img src="./images/nav_m3.png" alt="게시판" /><span>게시판</span></li>
			</ul>
<?
	//$left_list = left_menu_list($code_comp, $top_code_part, $code_mem, $set_part_yn);
	//$menu_arr = $left_list['menu'];
	
	if (is_array($menu_arr)) {
?>
			<ul>
<?
		$idx = 1;
		foreach($menu_arr[1][0] as $chk_k => $menu_data) {
				$menu_idx    = $menu_data['menu_idx'];
				$menu_up     = $menu_data['menu_up'];
				$chk_menu_up = $menu_data['chk_menu_up'];
				$menu_depth  = $menu_data['menu_depth'];
				$chk_depth   = $menu_data['chk_depth'];
				$ul_id_str   = $menu_data['ul_id_str'];
				$li_id_str   = $menu_data['li_id_str'];
				$em_str      = $menu_data['em_str'];
				$menu_name   = $menu_data['menu_name'];
				$link_url    = $menu_data['link_url'];
				$a_class     = $menu_data['a_class'];
				$li_class    = $menu_data['li_class'];
				$menu_num    = $menu_data['menu_num'];
				
?>
				<li><img src="./images/nav_m<?=$idx++?>.png" alt="<?=$menu_name?>" /><span><?=$menu_name?></span></li>
<?
				
		}
?>
				
			</ul>
<?
	}
?>
			

		</div>
						</div>
					</section>


	</body>
</html>