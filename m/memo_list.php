<?
	include "../common/setting.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include  "./header.php";
	
	$memo_where = " and mm.comp_idx = '" . $code_comp . "' and mm.mem_idx = '" . $code_mem . "'";
	$memo_list = member_memo_data('list', $memo_where, '', '', '');

?>

<div id="page">
	<div id="header">
	<a class="back" href="javascript:history.go(-1)">BACK</a>
<?
	include "body_header.php";
?>
	</div>
	<div id="content">
		<div id="wrapper">
			<div id="scroller">
				
				<ul class="list memo">
<?
	$i = 0;
	if ($memo_list["total_num"] == 0) {
?>
		<li>
			등록된 데이타가 없습니다.
		</li>
<?
	} else {
			
		foreach ($memo_list as $memo_k => $memo_data)
		{
			if (is_array($memo_data))
			{
?>
					<li>
						<a href="memo_view.php?idx=<?=$memo_data['mm_idx']?>">
							<strong class="title2 mr4l10"><?=$memo_data['remark']?></strong>
							<span class="date mr4l10"><?=$memo_data['reg_date']?></span>
							<span class="btn_more">></span>
						</a>
					</li>
<?
			}
		}
	}
?>
				</ul>

			</div>
		</div>
	</div>

<?
	include "./footer.php";
?>