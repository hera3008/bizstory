<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";

	$moretype = 'consult_view';
	include $mobile_path . "/header.php";

	$contents_title = '나의상담상세보기';

	$chk_where = " and cons.cons_idx = '" . $cons_idx . "'";
	$chk_data = consult_info_data('view', $chk_where);

	$data = consult_list_data($cons_idx, $chk_data);

// 파일목록
	$file_where = " and consf.cons_idx = '" . $cons_idx . "'";
	$file_list = consult_file_data('list', $file_where, '', '', '');
?>
<script type="text/javascript" src="<?=$mobile_dir;?>/js/myScroll.js" charset="utf-8"></script>

<div id="consult_view" class="full sub view">

	<div class="toolbar han">
		<?=$btn_back;?>
		<h1>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/consult_view.php?cons_idx=<?=$cons_idx;?>'"><?=$contents_title;?></a>
		</h1>
		<?=$btn_logout;?>
	</div>

	<!-- Contents -->
	<div id="wrapper">
		<div id="scroller">

			<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
				<input type="hidden" name="sub_type" id="view_sub_type" />
				<input type="hidden" name="cons_idx" id="view_cons_idx" value="<?=$cons_idx;?>" />

				<table border="1" cellspacing="0" class="board-list" summary="제목, 작성일, 거래처명, 담당직원 등">
				<caption><?=$contents_title;?> 콘텐츠</caption>
				<colgroup>
					<col width="100px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th>거래처명</th>
						<td><?=$data['client_name'];?></td>
					</tr>
					<tr>
						<th>제목</th>
						<td class="subject">
							<?=$data['subject'];?>
							<?=$data['important_img'];?>
							<?=$data['total_file_str'];?>
							<?=$data['total_comment_str'];?>
						</td>
					</tr>
					<tr>
						<th>등록자</th>
						<td>
							<?=$data['writer'];?>(<a href="tel:<?=$data['tel_num'];?>" class="tel"><?=$data['tel_num'];?></a>)
							- <strong class="date"><?=date_replace($data['reg_date'], 'Y.m.d H:i');?></strong>
						</td>
					</tr>
					<tr>
						<th>분류</th>
						<td>
					<?
						$consult_class = $data['class_str']['code_name'];
						if (is_array($consult_class))
						{
							foreach ($consult_class as $k => $v)
							{
								if ($k == 1) echo $v;
								else echo ' &gt; ', $v;
							}
						}
					?>
						</td>
					</tr>
					<tr>
						<th>담당자</th>
						<td><?=$data['total_charge_str'];?></td>
					</tr>
					<tr>
						<td colspan="2">
							<p>
								<?=$data['remark'];?>
							</p>
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
				</tbody>
				</table>

			</form>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 코멘트
?>
			<div id="task_comment" class="comment_box">
				<div class="comment_top">
					<p class="count">
						코멘트 <span id="comment_total_value">[<?=number_format($data['total_comment']);?>]</span>
					</p>
					<div class="new" id="comment_new_btn"><!--//<img src="<?=$lcoal_dir;?>/bizstory/images/btn/btn_comment.png" alt="코멘트 쓰기" class="pointer" onclick="consult_comment_insert('open')" />//--></div>
				</div>

				<div id="new_comment" title="코멘트쓰기"></div>

				<form id="commentlistform" name="commentlistform" method="post" action="<?=$this_page;?>">
					<input type="hidden" id="commentlist_sub_type"  name="sub_type"  value="" />
					<input type="hidden" id="commentlist_cons_idx"  name="cons_idx"  value="<?=$cons_idx;?>" />
					<input type="hidden" id="commentlist_consc_idx" name="consc_idx" value="" />
					<?=$form_page;?>
					<div id="comment_list_data"></div>
				</form>
			</div>
			<br /><br /><br />
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
<script type="text/javascript">
//<![CDATA[
	consult_comment();
//]]>
</script>
</body>
</html>