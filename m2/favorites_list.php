<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include "./header.php";
	
	$page_chk = 'html';
	
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$where = " and mb.comp_idx = '" . $code_comp . "' and mb.mem_idx = '" . $code_mem . "'and mb.view_yn = 'Y' and mac.view_yn = 'Y' and mi.menu_num = 0";
    $order = "mi.sort asc";
    $list = member_bookmark_data('list', $where, $order, '', '');
	
	//echo print_r($list);
?>

<div id="page">
	<div id="header">
		<a class="back" href="javascript:history.go(-1)">BACK</a>
		<h1><a href="./" class="logo"><img src="./images/h_logo_x2.png" width="123" height="50" alt="비즈스토리"></a></h1>
		<p class="logout">
			<a href="#"><img src="./images/ico_logoutx2.png" alt="로그아웃" height="18px"></a>
		</p>
	</div>
	<div id="content">
		<div id="wrapper">
			<div id="scroller">
				
				<p class="alarm">즐겨찾기는 비즈스토리 홈페이지 - 즐겨찾기 열기를 통해 설정가능합니다.</p>

				<section class="setting_area">

					<!-- 비즈스토리 즐겨찾기 소스 그대로 출력 : 링크는 변경되어야함 모바일에 맞게 -->
					<div class="bookmark_view">
<?
        if ($list["total_num"] > 0) {
?>
						<ul id="bookmarkList">
<?
                foreach($list as $k => $data)
                {
                        if (is_array($data))
                        {
							// 링크값 확인필요 

?>
							<li><a href="javascript:void(0)" onclick="location.href='<?=$local_dir;?>/index.php?fmode=<?=$data['mode_folder'];?>&smode=<?=$data['mode_file'];?>'"><span id="bookmark_<?=$k;?>"><?=$data['menu_name'];?></span></a></li>
							
<?
                        }
                }
?>
						</ul>
<?
        }
        unset($data);
        unset($list);
?>

					</div>

				</section>

			</div>
		</div>
	</div>

<?
//echo "local_path 	:".$local_path;
	include "./footer.php";
?>