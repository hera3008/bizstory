<?
/*
	생성 : 2012.10.11
	수정 : 2012.10.12
	위치 : 즐겨찾기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$where = " and mb.comp_idx = '" . $code_comp . "' and mb.mem_idx = '" . $code_mem . "'and mb.view_yn = 'Y' and mac.view_yn = 'Y' and mi.menu_num = 0";
	$order = "mi.sort asc";
	$list = member_bookmark_data('list', $where, $order, '', '');
?>

<div class="bookmark_frame">
	<div class="bookmark_top">
		<p class="title">즐겨찾기</p>
		<ul class="bookmark_btn">
			<li class="bookmark_settilng"><a href="javascript:void(0)" onclick="location.href='<?=$local_dir;?>/index.php?fmode=myinfo&amp;smode=bookmark'">즐겨찾기 셋팅</a></li>
			<li class="bookmark_close"><a href="javascript:void(0);" onclick="bookmark_close()">즐겨찾기 닫기</a></li>
		</ul>
	</div>
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
</div>