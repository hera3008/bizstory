<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";
	include $mobile_path . "/header.php";

	$contents_title = '나의 업무상세';

	$where = " and wi.wi_idx = '" . $wi_idx . "'";
	$data = work_info_data('view', $where);

	$data = work_list_data($data, $wi_idx); // 작업내용

	check_work_type_status($data); // 승인대기, 읽기

	//work_WT04($data); // 읽기확인

// 파일목록
	$file_where = " and wf.wi_idx = '" . $wi_idx . "'";
	$file_list = work_file_data('list', $file_where, '', '', '');

	$section_str = work_status_view($data); // 진행상태
?>
<script type="text/javascript" src="<?=$mobile_dir;?>/js/myScroll.js" charset="utf-8"></script>

<div id="work_view" class="full sub">

	<div class="toolbar han">
		<?=$btn_back;?>
		<h1>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/work_my_view.php?wi_idx=<?=$wi_idx;?>'"><?=$contents_title;?></a>
		</h1>
		<?=$btn_logout;?>
	</div>

	<!-- Contents -->
	<div id="wrapper">
		<div id="scroller">

			<form id="viewform" name="viewform" method="post" action="<?=$this_page;?>">
				<input type="hidden" name="sub_type"    id="view_sub_type" />
				<input type="hidden" name="wi_idx"      id="view_wi_idx"      value="<?=$wi_idx;?>" />
				<input type="hidden" name="work_status" id="view_work_status" value="<?=$data['work_status'];?>" />

				<table border="1" cellspacing="0" class="board-list" summary="업무제목, 담당자, 기한, 분류, 업무내용 등이 있습니다,">
				<caption><?=$contents_title;?> 콘텐츠</caption>
				<colgroup>
					<col width="90px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th>업무제목</th>
						<td class="subject">
							<?=$data['work_img'];?>
							<?=$data['part_img'];?>
							<strong><?=$data['subject_txt'];?></strong>
							<?=$data['important_img'];?>
							<?=$data['open_img'];?>
							<?=$data['file_str'];?>
							<?=$data['report_str'];?>
							<?=$data['comment_str'];?>
							<?=$data['read_work_str'];?>
						</td>
					</tr>
					<tr>
						<th>담당자</th>
						<td><?=$data['total_charge_str'];?></td>
					</tr>
				<?
					if ($data['work_type'] == 'WT03')
					{
				?>
					<tr>
						<th>승인자</th>
						<td><?=$data['apply_name'];?></td>
					</tr>
				<?
					}
				?>
					<tr>
						<th>기한</th>
						<td>
							<strong class="date"><?=$data['deadline_date_str'];?></strong>
							- <?=$data['end_date_str'];?>
						</td>
					</tr>
					<tr>
						<th>분류</th>
						<td><?=$data['work_class_str'];?></td>
					</tr>
					<tr>
						<td colspan="2">
							<?=$data['remark'];?>
						</td>
					</tr>
					<tr>
						<th>첨부파일</th>
						<td>
			<?
				if ($file_list['total_num'] > 0) {
			?>
							<ul>
			<?
					foreach ($file_list as $file_k => $file_data)
					{
						if (is_array($file_data))
						{
							$fsize = $file_data['img_size'];
							$fsize = byte_replace($fsize);
			?>
								<li><?=$file_data['img_fname'];?> (<?=$fsize;?>)</li>
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
					<tr>
						<th>등록일</th>
						<td><strong class="date"><?=$data['reg_name'];?>(<?=$data['reg_date'];?>)<strong></td>
					</tr>
				</tbody>
				</table>



<!--//
				<div class="section">
					<div class="status_box_<?=$section_str['status_title_bg'];?>"></div>
					<div class="status_box">
						<div class="status_top">
							<p class="count">
								<?=$section_str['status_title'];?>
							</p>
						</div>
					<?
						if ($section_str['status_comment'] != '')
						{
					?>
						<div class="status">
							<div class="status_info">
								<?=$section_str['status_comment'];?>
							</div>
						</div>
					<?
						}
					?>
					</div>
				</div>
//-->





				<div class="section">
					<div class="status_box_<?=$section_str['bgimg'];?>"></div>
					<div class="status_box">
					<?
						if ($section_str['text'] != '')
						{
					?>
						<div class="status_top">
							<p class="count">
								<?=$section_str['text'];?>
							</p>
						</div>
					<?
						}
					?>
					<?
						if ($section_str['comment'] != '')
						{
					?>
						<div class="status">
							<div class="status_info">
								<?=$section_str['comment'];?>
							</div>
						</div>
					<?
						}
					?>
					</div>
				</div>
			</form>
			<br />
<?
////////////////////////////////////////////////////////////////////////////////////////
// 업무보고서
	if ($data['work_type'] == 'WT04' && $data['total_report'] == 0) // 업무알림일 경우 업무보고 나오지 않음
	{ }
	else
	{
?>
			<div id="task_report" class="report_box">
				<div class="report_top">
					<p class="count">
						<a href="javascript:void(0)" onclick="report_view()" id="report_gate" title="업무보고목록" class="btn_i_plus"><span class="empty"></span> 업무보고 <span id="report_total_value">[<?=number_format($data['total_report']);?>]</span></a>
			<?
				if ($data['read_report'] > 0)
				{
					echo '
						<span class="today_num" title="읽을 업무보고"><em>', number_format($data['read_report']), '</em></span>';
				}
			?>
					</p>
	<?
		if ($data['report_yn'] == 'Y')
		{
	?>
					<div class="new" id="report_new_btn"><img src="<?=$lcoal_dir;?>/bizstory/images/btn/btn_report.png" alt="업무보고 쓰기" class="pointer" onclick="report_insert_form('open', 'insert')" /></div>
	<?
		}
	?>
				</div>

				<div id="new_report" title="업무보고쓰기"></div>

				<form id="reportlistform" name="reportlistform" method="post" action="<?=$this_page;?>">
					<input type="hidden" id="reportlist_sub_type" name="sub_type" value="" />
					<input type="hidden" id="reportlist_wi_idx"   name="wi_idx"   value="<?=$wi_idx;?>" />
					<input type="hidden" id="reportlist_wr_idx"   name="wr_idx"   value="" />
					<?=$form_page;?>
					<div id="report_list_data"></div>
				</form>
			</div>
<?
	}

////////////////////////////////////////////////////////////////////////////////////////
// 코멘트
?>
			<div id="task_comment" class="comment_box">
				<div class="comment_top">
					<p class="count">
						<a href="javascript:void(0)" onclick="comment_view()" id="comment_gate" title="코멘트목록" class="btn_i_plus"><span class="empty"></span> 코멘트 <span id="comment_total_value">[<?=number_format($data['total_comment']);?>]</span></a>
				<?
					if ($data['read_comment'] > 0)
					{
						echo '
						<span class="today_num" title="읽을 댓글"><em>', number_format($data['read_comment']), '</em></span>';
					}
				?>
					</p>
					<div class="new" id="comment_new_btn"><img src="<?=$lcoal_dir;?>/bizstory/images/btn/btn_comment.png" alt="코멘트 쓰기" class="pointer" onclick="comment_insert_form('open')" /></div>
				</div>

				<div id="new_comment" title="코멘트쓰기"></div>

				<form id="commentlistform" name="commentlistform" method="post" action="<?=$this_page;?>">
					<input type="hidden" id="commentlist_sub_type" name="sub_type" value="" />
					<input type="hidden" id="commentlist_wi_idx"   name="wi_idx"   value="<?=$wi_idx;?>" />
					<input type="hidden" id="commentlist_wc_idx"   name="wc_idx"   value="" />
					<?=$form_page;?>
					<div id="comment_list_data"></div>
				</form>
			</div>

		</div>
	</div>
	<!-- //Contents -->
	<?
		$bottom_btn = '
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/index.php\'" class="icon4"><span>홈</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/work_my_list.php\'" class="icon2"><span>나의업무</span></a>
			<a href="javascript:void(0)" onclick="check_auth_popup(\'준비중입니다\')" class="icon2"><span>업무등록</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/set_up.php\'" class="icon3"><span>설정</span></a>
		';
	?>
	<? include $mobile_path . "/footer.php"; ?>
</div>

<script type="text/javascript">
//<![CDATA[
	var report_chk_val = 'open';
	var comment_chk_val = 'open';
//]]>
</script>
</body>
</html>