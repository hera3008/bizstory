<?
	include "../common/setting.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include  "./header.php";
	
	$memo_where = " and mm.comp_idx = '" . $code_comp . "' and mm.mm_idx = '" . $idx . "'";
	$data = member_memo_data('view', $memo_where, '', '', '');
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
				
				<div class="memo_view">
					<strong class="top_date"><?=$data['reg_date']?></strong>
					<p>
						<?=$data['remark']?>
					</p>
				</div>
				<ul class="memo_ul">
					<li><span class="btn_b">수정</span></li>
					<li><span class="btn_r2">삭제</span></li>
				</ul>

			</div>
		</div>
	</div>

<?
	include "./footer.php";
?>