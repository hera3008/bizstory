<?
/*
	생성 : 2013.01.23
	수정 : 2013.01.23
	위치 : 고객관리 > 접수목록 - 상세인쇄
*/
	include "../common/setting.php";
	include "../common/member_chk.php";

	$navi_where = " and mi.mode_folder = '" . $fmode . "' and mi.mode_file = '" . $smode . "'";
	$navi_data = menu_info_data("view", $navi_where);
	$navi_view = menu_navigation_view($navi_data["mi_idx"]);

	$print_title  = $navi_data['menu_name'] . ' 인쇄페이지';
	$print_header = '';
	$portrait     = 'true';

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = '';
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shclass=' . $send_shclass . '&amp;shstatus=' . $send_shstatus;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"    value="' . $send_swhere . '" />
		<input type="hidden" name="stext"     value="' . $send_stext . '" />
		<input type="hidden" name="shclass"   value="' . $send_shclass . '" />
		<input type="hidden" name="shstatus"  value="' . $send_shstatus . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1"   value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2"   value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if ($auth_menu['print'] == 'Y') // 인쇄권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth_popup('');
		//]]>
		</script>
<?
	}

	include $local_path . "/include/header_print.php";
?>
	<div id="loading">로딩중입니다...</div>

	<div class="sub_layout_box">
<?
	if ($form_chk == 'Y')
	{
		$receipt_info = new receipt_info();

		$i = 1;
		$chk_ri_idx = $_POST['chk_ri_idx'];
		if (is_array($chk_ri_idx))
		{
			foreach ($chk_ri_idx as $k => $v)
			{
				$receipt_info->ri_idx = $v;
				$receipt_info->data_path = $comp_receipt_path;
				$receipt_info->data_dir = $comp_receipt_dir;

				$data      = $receipt_info->receipt_info_view();
				$file_list = $receipt_info->receipt_file();
?>
		<strong>No. <?=$i;?></strong>
		<fieldset>
			<legend class="blind">접수정보 인쇄</legend>
			<table class="tinytable view" summary="접수된 상세내용을 인쇄합니다.">
			<caption>접수상세내용</caption>
			<colgroup>
				<col width="100px" />
				<col />
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th>거래처명</th>
					<td>
						<div class="left"><?=$data['client_name'];?></div>
					</td>
					<th>지사</th>
					<td>
						<div class="left"><?=$data['part_name'];?>-<?=$data['charge_mem_name'];?></div>
					</td>
				</tr>
				<tr>
					<th>제목</th>
					<td colspan="3">
						<div class="left">
							<strong><?=$data['subject'];?></strong>
							<?=$data['important_img'];?>
			<?
				if ($data['total_file'] > 0)
				{
					echo '
							F:', number_format($data['total_file']);
				}
				if ($data['total_comment'] > 0)
				{
					echo '
							C:', number_format($data['total_comment']);
				}
			?>
						</div>
					</td>
				</tr>
				<tr>
					<th>접수분류</th>
					<td>
						<div class="left">
				<?
					$receipt_class = $data['receipt_class_str'];
					foreach ($receipt_class as $k => $v)
					{
						if ($k == 1) echo $v;
						else echo ' &gt; ', $v;
					}
				?>
						</div>
					</td>
					<th>작성자</th>
					<td>
						<div class="left1">
							<?=$data['writer'];?> (Tel:<?=$data['tel_num'];?>)
							- <?=$data['reg_date'];?>
						</div>
					</td>
				</tr>
				<tr>
					<th>내용</th>
					<td colspan="3">
						<div class="left">
							<p class="memo">
								<?=$data['remark'];?>
							</p>
						</div>
					</td>
				</tr>
				<tr>
					<th>첨부파일</th>
					<td colspan="3">
						<div class="left file">
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
						</div>
					</td>
				</tr>
			</tbody>
			</table>

		</fieldset>

<!-- 댓글 -->
<?
				$comment_where = " and rc.ri_idx = '" . $data['ri_idx'] . "'";
				$comment_order = "rc.gno asc, rc.tgno asc";
				$comment_list = receipt_comment_data('list', $comment_where, $comment_order, '', '');

				if ($comment_list["total_num"] > 0)
				{
?>
		<div class="dotted"></div>

		<div class="cb_module">
			<div class="cb_lstcomment">
				<ul>
					<li><strong><?=$comment_list["total_num"];?></strong> 개의 댓글이 있습니다.</li>
<?
					$comment_data_i = 1;
					foreach($comment_list as $comment_k => $comment_data)
					{
						if (is_array($comment_data))
						{
							$sub_where = " and mem.mem_idx = '" . $comment_data['mem_idx'] . "'";
							$sub_data = member_info_data('view', $sub_where);

							$reply_start = ''; $reply_end = '';
							if ($comment_data['tgno'] > 0)
							{
								$reply_start = '<div class="reply' . $comment_data['tgno'] . '">';
								$reply_end = '</div>';
							}
?>
					<li>
						<div class="cb_section">
							<?=$reply_start;?>
							<span class="cb_nick_name"><?=$comment_data['writer'];?></span>
							<? if ($comment_data['mem_idx'] != '') { ?> <span class="cb_usr_id">(<?=$sub_data['mem_id'];?>)</span><? } ?>
							<span class="cb_date"><?=$comment_data['reg_date'];?></span>
							<p>
								<div class="cb_section_remark"><?=$comment_data['remark'];?></div>
							</p>
							<?=$reply_end;?>
						</div>
					</li>
<?
							$comment_data_i++;
						}
					}
?>
				</ul>
			</div>
		</div>
		<br />
<?
				}
				$i++;
			}
		}
		else
		{
			echo '인쇄하고자 하는 접수를 선택하세요.';
		}
	}
?>
