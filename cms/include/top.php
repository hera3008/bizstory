<?
	include $local_path . "/cms/include/header.php";
?>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function() {

	// Clock
		$('#clock').epiclock();

	// File Style
		$(".type_basic").filestyle({
			image: "/common/upload/file_submit.gif",
			imagewidth : 82,
			imageheight : 29
		});
		$("#backgroundPopup").click(function(){popupform_close()}); // 등록폼 닫기
		//only need force for IE6
		$("#backgroundPopup").css({
			"height": document.documentElement.clientHeight
		});
	});
//]]>
</script>
<title>접수</title>
</head>

<body>
	<div id="loading">로딩중입니다...</div>
	<div id="loading2">문서 미리보기 로딩중입니다...</div>
	<div id="popup_file_preview" title="파일 미리보기"></div>
	<div id="preview_file_result" title="파일변환결과"></div>

	<div id="style-switcher">
		<div id="header">
			<a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/cms'" title="홈으로 이동 합니다." class="logo">
				<img src="<?=$local_dir;?>/bizstory/images/common/logo.jpg" width="198px" height="39px" alt="BI" />
			</a>
			<div class="work_number">
				<ul>
					<li><?=$client_data['client_name'];?></li>
					<li>&nbsp;</li>
				<?
					foreach ($cms_menu as $k => $menu_data)
					{
						$menu_name = $menu_data['0'];
						$menu_url  = $menu_data['1'];
						$menu_chk  = $menu_data['2'];

						$link_url = '';
						$link_url = $local_dir . '/cms/' . $menu_url;

						if ($link_url != '')
						{
				?>
					<li class="menu_view"><a href="javascript:void(0);" onclick="location.href='<?=$link_url;?>'"><span>|</span><?=$menu_name;?></a></li>
				<?
						}
					}
				?>
				</ul>
	<?
	// 완료, 취소, 보류는 제외
		$receipt_where = " and ri.comp_idx = '" . $code_comp . "' and code2.code_value = '99'";
		$receipt_page1 = receipt_info_data('page', $receipt_where);

	// 완료, 취소, 보류는 제외
		$receipt_where = " and ri.comp_idx = '" . $code_comp . "' and code2.code_value <> '99' and code2.code_value <> '90' and code2.code_value <> '80'";
		$receipt_page2 = receipt_info_data('page', $receipt_where);
	?>
			</div>
			<div id="etc_menu" class="animate_over">
				<ul>
					<li class="icon3"><a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/cms/logout.php'" title="<?=$member_info_data['mem_name'];?> 로그아웃">로그아웃</a></li>
				</ul>
			</div>
		</div>
		<div id="top-close" title="상단 레이어 닫기"></div>
		<div id="toggle-top" title="상단 레이어 열기"></div>
	</div>

	<div class="popupform" title="팝업등록폼">
		<div id="data_form" title="등록폼"></div>
	</div>

	<table id="layout_table">
		<tr>
			<td id="container">
				<div class="etc_frame">
					<div id="clock"></div>
				</div>
				<div class="sub_layout_box">
					<? include $local_path . "/cms/include/menu_inc.php"; ?>




