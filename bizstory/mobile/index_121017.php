<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";
	include $mobile_path . "/header.php";

// 미처리접수리스트
	$receipt_no_where  = " and ri.comp_idx = '" . $code_comp . "' and ri.part_idx = '" . $code_part . "'";
	$receipt_no_where .= " and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')";
	$receipt_no_list = receipt_info_data('page', $receipt_no_where);
	$receipt_no_total = number_format($receipt_no_list['total_num']);

// 나의 업무
// 보류(WS80), 완료(WS90), 종료(WS99), 취소(WS50)
	$work_where = "
		and wi.comp_idx = '" . $code_comp . "'
		and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')
		and wi.work_status <> 'WS80' and wi.work_status <> 'WS90' and wi.work_status <> 'WS99' and wi.work_status <> 'WS50'";
	$my_work_list = work_info_data('page', $work_where);
	$my_work_total = number_format($my_work_list['total_num']);

// 받은쪽지
	$message_where = "
		and mr.comp_idx = '" . $code_comp . "'
		and mr.mem_idx = '" . $code_mem . "'
		and if(mr.read_date = '0000-00-00', 'Y', 'N') = 'Y'";
	$message_list = message_receive_data('page', $message_where);
	$message_total = number_format($message_list['total_num']);

// 메모장
	$memo_where = "
		and mm.comp_idx = '" . $code_comp . "'
		and mm.mem_idx = '" . $code_mem . "'";
	$memo_list = member_memo_data('page', $memo_where);
	$memo_total = number_format($memo_list['total_num']);

// 자료실
	$bbs_total1 = 0;

// 비즈스토리 만남의 광장
	$bbs_total2 = 0;

	$isReload = false;

	if (isset($_COOKIE['isLogin'])) {

		$isLogin = $_COOKIE['isLogin'];
		if ($isLogin == "Y") {
			setcookie("isLogin", "", 0, "/");
		} else {
			setcookie("isLogin", "Y", time()+60*60*$hour, "/");
			$isReload = true;
		}

	} else {
		setcookie("isLogin", "Y", time()+60*60*$hour, "/");
		$isReload = true;
	}

?>
<!-- home -->
<div id="home" class="homebox full">

	<!-- Toolbar -->
	<div class="toolbar">
		<h1>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/index.php'"><?=$mobile_eng;?></a>
		</h1>
		<?=$btn_logout;?>
	</div>
	<!-- //Toolbar -->

	<!-- Contents -->
	<div id="wrapper">
		<div id="scroller">
			<div class="around_icon">
				<ul class="slides">
					<li>
						<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/receipt_list.php'" class="icon01">
							<em class="on_push"><small><?=$receipt_no_total;?></small></em>
							<span><strong>접수목록</strong></span>
						</a>
						<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/work_my_list.php'" class="icon02">
							<em class="on_push"><small><?=$my_work_total;?></small></em>
							<span><strong>나의업무</strong></span>
						</a>
						<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/message_list.php'" class="icon03">
							<em class="on_push"><small><?=$message_total;?></small></em>
							<span><strong>받은쪽지</strong></span>
						</a>
					</li>
					<li>
						<a href="javascript:void(0)" onclick="check_auth_popup('준비중입니다')" class="icon06">
							<span><strong>프로젝트관리</strong></span>
						</a>
						<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/partner_list.php'" class="icon04">
							<span><strong>거래처목록</strong></span>
						</a>
						<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/staff_list.php'" class="icon05">
							<span><strong>직원목록</strong></span>
						</a>
					</li>
				</ul>
			</div>
			<div id="barmenu" class="barmenu">
				<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/memo_list.php'" class="loop">
					메모장보기
					<em class="push"><?=$memo_total;?></em>
				</a>
				<a href="javascript:void(0)" onclick="check_auth_popup('준비중입니다')" class="loop">
					자료실
					<em class="push"><?=$bbs_total1;?></em>
				</a>
				<a href="javascript:void(0)" onclick="check_auth_popup('준비중입니다')" class="loop">
					비즈스토리 만남의 광장
					<em class="push"><?=$bbs_total2;?></em>
				</a>
			</div>
		</div>
	</div>
	<!-- //Contents -->
	<?
		$bottom_btn = '
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/index.php\'" class="icon4"><span>홈</span></a>
			<a href="javascript:void(0)" onclick="check_auth_popup(\'준비중입니다\');" class="icon2"><span>일정</span></a>
			<a href="javascript:void(0)" onclick="check_auth_popup(\'준비중입니다\');" class="icon1"><span class="leave_type">나의정보</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/set_up.php\'" class="icon3"><span>설정</span></a>
		';
	?>
	<? include $mobile_path . "/footer.php"; ?>
</div>
<!-- //home -->

<?php
	if ($pId)
	{
?>
	<script type="text/javascript">
	//<![CDATA[

	var uAgent = navigator.userAgent.toLowerCase();
		console.log("agent = "+uAgent);
		if (uAgent.indexOf("android") != -1)
		{
		//	window.android.setId("<?php echo $pId ?>", "<?php echo $pPw ?>");
		}
		else if (uAgent.indexOf("iphone") != -1 || uAgent.indexOf("ipod") != -1)
		{
			window.location = "ios://loginBizstory?<?php echo $pId ?>&<?php echo $pPw ?>";
		}
		else
		{
		//	window.location = "ios://loginBizstory";
		//	sleep(5000);
		}
<?
		if ($isReload  == true) {
?>
		//alert("<?=$isReload . ""?>");
		location.reload(true);
<?
		} else {
?>
		//alert("first");
<?
		}
?>
	//]]>
	</script>
<?php
	}
?>