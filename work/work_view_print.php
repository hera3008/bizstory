<?
/*
	생성 : 2012.07.20
	수정 : 2012.11.01
	위치 : 업무폴더 > 나의업무 > 업무 - 보기 - 인쇄
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
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

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
	<div class="home_pagenavi">
		<h2><?=$navi_data['menu_name'];?></h2>
	</div>
<?
	if ($form_chk == 'Y')
	{
		$where = " and wi.wi_idx = '" . $wi_idx . "'";
		$data = work_info_data('view', $where);

		$data = work_list_data($data, $wi_idx); // 작업내용

	// 파일목록
		$file_where = " and wf.wi_idx = '" . $wi_idx . "'";
		$file_list = work_file_data('list', $file_where, '', '', '');

		$work_report_yn = $data['report_yn'];
		$work_report_yn = $data['report_yn'];
		$read_work      = $data['read_work'];
		$read_report    = $data['read_report'];
		$read_comment   = $data['read_comment'];

		$contents_view = 'N';
		$report_view   = 'N';
		$comment_view  = 'N';

		if ($print_type == '1') // 모두
		{
			$contents_view = 'Y';
			$report_view   = 'Y';
			$comment_view  = 'Y';
		}
		else if ($print_type == '2') // 업무내용
		{
			$contents_view = 'Y';
		}
		else if ($print_type == '3') // 업무내용+업무보고
		{
			$contents_view = 'Y';
			$report_view   = 'Y';
		}
		else if ($print_type == '4') // 업무내용+코멘트
		{
			$contents_view = 'Y';
			$comment_view  = 'Y';
		}
		else if ($print_type == '5') // 업무보고+코멘트
		{
			$report_view   = 'Y';
			$comment_view  = 'Y';
		}
?>
	<fieldset>
		<legend class="blind">업무정보</legend>

		<table class="tinytable view" summary="등록한 업무에 대한 상세정보입니다.">
			<caption>업무정보</caption>
			<colgroup>
				<col width="80px" />
				<col width="200px" />
				<col width="80px" />
				<col />
				<col width="80px" />
				<col width="100px" />
			</colgroup>
			<tbody>
				<tr>
					<th>업무제목</th>
					<td colspan="5">
						<div class="left">
							<?=$data['work_img'];?>
							<?=$data['part_img'];?>
							<strong><?=$data['subject'];?></strong>
							<?=$data['important_img'];?>
							<?=$data['open_img'];?>
							<?=$data['file_str'];?>
							<?=$data['report_str'];?>
							<?=$data['comment_str'];?>
							<?=$data['read_work_str'];?>
						</div>
					</td>
				</tr>
				<tr>
					<th>등록자</th>
					<td>
						<div class="left"><?=$data['reg_name_view'];?>(<?=$data['reg_date'];?>)</div>
					</td>
					<th>담당자</th>
					<td>
						<div class="left"><?=$data['total_charge_str'];?></div>
					</td>
					<th>승인자</th>
					<td>
						<div class="left"><?=$data['apply_name'];?></div>
					</td>
				</tr>
				<tr>
					<th>기한</th>
					<td colspan="5">
						<div class="left"><?=$data['deadline_date_str'];?></div>
					</td>
				</tr>
				<tr>
					<th>분류</th>
					<td colspan="5">
						<div class="left"><?=$data['work_class_str'];?></div>
					</td>
				</tr>
<?
	if ($contents_view == 'Y')
	{
?>
				<tr>
					<th>내용</th>
					<td colspan="5">
						<div class="left">
							<p class="memo">
								<?=$data['remark'];?>
							</p>
						</div>
					</td>
				</tr>
				<tr>
					<th>첨부파일</th>
					<td colspan="5">
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
								<li>
									<?=$file_data['img_fname'];?> (<?=$fsize;?>)
								</li>
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
<?
	}
?>
			</tbody>
		</table>
	</fieldset>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 업무보고서
	if ($report_view == 'Y')
	{
		$report_where = " and wr.wi_idx = '" . $wi_idx . "'";
		$report_list = work_report_data('list', $report_where, '', '', '');
?>
	<div class="dotted"></div>
	<div class="report_box">
		<div class="report_top">
			<p class="count">
				업무보고 [<?=number_format($report_list['total_num']);?>]
			</p>
		</div>
<?
		foreach($report_list as $k => $data)
		{
			if (is_array($data))
			{
				$mem_img = member_img_view($data['mem_idx'], $comp_member_dir); // 등록자 이미지
?>
		<div class="report">
			<div class="report_info">
				<span class="mem"><?=$mem_img['img_26'];?></span>
				<span class="user"><?=$data['writer'];?></span>
				<span class="date">
<?
				$chk_date = date_replace($data['reg_date'], 'Y-m-d');
				if ($chk_date == date('Y-m-d'))
				{
					echo '<strong>', $data['reg_date'] , '</strong>';
				}
				else
				{
					echo $data['reg_date'];
				}
?>
				</span>
			</div>

			<div class="report_wrap">
				<div class="report_data">
					<div class="user_edit">
						<?=$data['remark'];?>
					</div>
					<div class="file">
<?
				$file_where = " and wrf.wr_idx = '" . $data['wr_idx'] . "'";
				$file_list = work_report_file_data('list', $file_where, '', '', '');

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
							<li>
								<?=$file_data['img_fname'];?> (<?=$fsize;?>)
							</li>
<?
						}
					}
?>
						</ul>
<?
				}
?>
					</div>
				</div>
			</div>
		</div>
<?
				$num--;
			}
		}
?>
	</div>
<?
	}
////////////////////////////////////////////////////////////////////////////////////////
// 코멘트
	if ($comment_view == 'Y')
	{
		$comment_where = " and wc.wi_idx = '" . $wi_idx . "'";
		$comment_list = work_comment_data('list', $comment_where, '', '', '');
?>
	<div class="dotted2"></div>
	<div id="task_comment" class="comment_box">
		<div class="comment_top">
			<p class="count">
				코멘트 [<?=number_format($comment_list['total_num']);?>]
			</p>
		</div>
<?
		$num = $comment_list["total_num"];
		foreach($comment_list as $k => $data)
		{
			if (is_array($data))
			{
				$mem_img = member_img_view($data['mem_idx'], $comp_member_dir); // 등록자 이미지
?>
		<div class="comment">
			<div class="comment_info">
				<span class="mem"><?=$mem_img['img_26'];?></span>
				<span class="user"><?=$data['writer'];?></span>
				<span class="date">
<?
				$chk_date = date_replace($data['reg_date'], 'Y-m-d');
				if ($chk_date == date('Y-m-d'))
				{
					echo '<strong>', $data['reg_date'] , '</strong>';
				}
				else
				{
					echo $data['reg_date'];
				}
?>
				</span>
			</div>

			<div class="comment_wrap">
				<div class="comment_data">
					<div class="user_edit">
						<?=$data['remark'];?>
					</div>
				</div>
			</div>
		</div>
<?
				$num--;
			}
		}
?>
	</div>
<?
	}
?>
<?
	}
?>