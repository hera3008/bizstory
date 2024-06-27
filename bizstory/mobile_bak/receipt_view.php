<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";

	$moretype = 'receipt_view';
	include $mobile_path . "/header.php";

	$contents_title = '접수목록상세보기';

	$receipt_info = new receipt_info();
	$receipt_info->ri_idx = $ri_idx;
	$receipt_info->data_path = $comp_receipt_path;
	$receipt_info->data_dir = $comp_receipt_dir;

	$data      = $receipt_info->receipt_info_view();
	$file_list = $receipt_info->receipt_file();

	$list_data = receipt_list_data($ri_idx, $data);
?>
<script type="text/javascript" src="<?=$mobile_dir;?>/js/myScroll.js" charset="utf-8"></script>

<div id="receipt_view" class="full sub view">

	<div class="toolbar han">
		<?=$btn_back;?>
		<h1>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/receipt_view.php?ri_idx=<?=$ri_idx;?>'"><?=$contents_title;?></a>
		</h1>
		<?=$btn_logout;?>
	</div>

	<!-- Contents -->
	<div id="wrapper">
		<div id="scroller">

			<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
				<input type="hidden" name="sub_type" id="view_sub_type" />
				<input type="hidden" name="ri_idx"   id="view_ri_idx"  value="<?=$ri_idx;?>" />
				<input type="hidden" name="rid_idx"  id="view_rid_idx" value="" />

				<table border="1" cellspacing="0" class="board-list" summary="제목, 작성일, 거래처명, 담당직원, 거래처 등">
				<caption><?=$contents_title;?> 콘텐츠</caption>
				<colgroup>
					<col width="90px" />
					<col />
					<col width="90px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th>거래처명</th>
						<td colspan="3"><?=$data['client_name'];?></td>
					</tr>
					<tr>
						<th>제목</th>
						<td class="subject" colspan="3">
							<?=$data['subject'];?>
						</td>
					</tr>
					<tr>
						<th>접수자</th>
						<td colspan="3">
							<?=$data['writer'];?>(<a href="tel:<?=$data['tel_num'];?>" class="tel"><?=$data['tel_num'];?></a>)
							- <strong class="date"><?=date_replace($data['reg_date'], 'Y.m.d H:i');?></strong>
						</td>
					</tr>
					<tr>
						<th>분류</th>
						<td colspan="3">
					<?
						$receipt_class = $data['receipt_class_str'];
						foreach ($receipt_class as $k => $v)
						{
							if ($k == 1) echo $v;
							else echo ' &gt; ', $v;
						}
					?>
						</td>
					</tr>
					<tr>
						<th>처리상태</th>
						<td id="receipt_status_check"><?=$list_data['receipt_status_str'];?></td>
						<th>담당직원</th>
						<td><?=$data['mem_name'];?></td>
					</tr>
					<tr>
						<td colspan="4">
							<p>
								<?=$data['remark'];?>
							</p>
						</td>
					</tr>
					<tr>
						<th>첨부파일</th>
						<td colspan="3">
			<?
				if ($file_list['total_num'] > 0) {
			?>
							<ul>
			<?
					foreach ($file_list as $file_k => $file_data)
					{
						if (is_array($file_data))
						{
			?>
								<li><?=$file_data['img_fname'];?></li>
			<?
						}
					}
			?>
							</ul>
			<?
				}
			?>
						</td>
					</tr>
				</tbody>
				</table>

			<!-- 접수상태, 내역 -->
				<div class="status_box">
					<div id="receipt_section" class="receipt_section"></div>
					<div class="status_info" id="status_history_info"></div>
				</div>

			</form>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 코멘트
?>
			<div id="task_comment" class="comment_box">
				<div class="comment_top">
					<p class="count">
						코멘트 <span id="comment_total_value">[<?=number_format($list_data['total_comment']);?>]</span>
					</p>
					<div class="new" id="comment_new_btn"><img src="<?=$lcoal_dir;?>/bizstory/images/btn/btn_comment.png" alt="코멘트 쓰기" class="pointer" onclick="receipt_comment_insert('open')" /></div>
				</div>

				<div id="new_comment" title="코멘트쓰기"></div>

				<form id="commentlistform" name="commentlistform" method="post" action="<?=$this_page;?>">
					<input type="hidden" id="commentlist_sub_type" name="sub_type" value="" />
					<input type="hidden" id="commentlist_ri_idx"   name="ri_idx"   value="<?=$ri_idx;?>" />
					<input type="hidden" id="commentlist_rc_idx"   name="rc_idx"   value="" />
					<?=$form_page;?>
					<div id="comment_list_data"></div>
				</form>
			</div>

			<br /><br /><br /><br /><br />

		</div>
	</div>
	<!-- //Contents -->
	<?
		$bottom_btn = '
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/index.php\'" class="icon4"><span>홈</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/receipt_list.php\'" class="icon2"><span>접수목록</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/receipt_list.php?list_type=my_no\'" class="icon2"><span>나의접수</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/set_up.php\'" class="icon3"><span>설정</span></a>
		';
	?>
	<? include $mobile_path . "/footer.php"; ?>
</div>
<script type="text/javascript">
//<![CDATA[
	receipt_change();
	receipt_history();
	receipt_comment();
//]]>
</script>
</body>
</html>