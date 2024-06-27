<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include "./process/no_direct.php";
	include "./header.php";
	
	$send_fmode = "consult";
	$send_smode = "consult";
	

	$cons_idx   = $idx;
	$form_chk = 'N';
	if ($auth_menu['view'] == 'Y' && $cons_idx != '') // 보기권한
	{
		$form_chk   = 'Y';
		$form_title = '보기';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>';
		exit;
	}

	if ($form_chk == 'Y')
	{
		$chk_where = " and cons.cons_idx = '" . $cons_idx . "'";
		$chk_data = consult_info_data('view', $chk_where);

		$data = consult_list_data($cons_idx, $chk_data);

	// 파일목록
		$file_where = " and consf.cons_idx = '" . $cons_idx . "'";
		$file_list = consult_file_data('list', $file_where, '', '', '');
?>
<!-- <script type="text/javascript" src="<?=$mobile_dir;?>/js/_myScroll.js" charset="utf-8"></script> -->
<div id="page">
	<div id="header">
	<a class="back" href="javascript:history.go(-1)">BACK</a>
<?
	include "body_header.php";
?>
	</div>
	<div id="content">
		<article class="mt_4">
			<h2>상담내역</h2>
		</article>
		<div id="wrapper" class="receipt">
			<div id="scroller">
				<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
				<input type="hidden" name="sub_type" id="view_sub_type" />
				<input type="hidden" name="part_idx" id="view_part_idx" value="<?=$data['part_idx'];?>" />
				<input type="hidden" name="cons_idx" id="view_cons_idx" value="<?=$cons_idx;?>" />

				<div class="work_area">
					<div class="work_inner">
						
						<div class="title">
							<strong><?=$data['subject'];?></strong>
							
							<strong class="regist"><?=$data['writer'];?><span class="data">(<a href="tel:<?=$data['tel_num'];?>"><?=$data['tel_num'];?></a>) <?=$data['reg_date'];?> </span><strong>
						</div>
						<table border="1" cellspacing="0" summary="업무내용" class="table02">
							<tr>
								<th class="w100">거래처명</th>
								<td><?=$data['client_name'];?></td>
							</tr>
							<tr>
								<th class="w100">지사</th>
								<td><?=$data['part_name'];?></td>
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
								<td>
									<?=$data['total_charge_str'];?>
								</td>
							</tr>							
							<tr>
								<td colspan="2" class="ptb10l5">
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
				
										$btn_str = preview_file($comp_receipt_dir, $file_data['rf_idx'], 'receipt');
				?>
									<li>
									<?=$btn_str;?>
									<a href="<?=$local_dir;?>/bizstory/receipt/receipt_download.php?rf_idx=<?=$file_data['rf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
									</li>
				<?
									}
								}
								$btn_img = preview_images($ri_idx, 'receipt');
								if ($btn_img != '')
								{
									echo '
											<li>' . $btn_img . '</li>
										';
								}
				?>
								</ul>
				<?
							}
				?>
								</td>
							</tr>
						</table>

					</div>
				</div>

				<!-- 접수등록 상태 -->
				<!-- 접수상태, 내역 -->
				<div class="status_box">
					<div id="receipt_section" class="receipt_section"></div>
					<div class="status_info" id="status_history_info"></div>
				</div>
				</form>

				
				<!-- 코멘트 -->
				<div id="task_comment" class="comment_box1">
					<div class="comment_top">
						<p class="count"><a href="javascript:void(0)" onclick="comment_view()" id="comment_gate" title="코멘트목록" class="ui-link btn_i_minus"><span class="empty"></span> 코멘트 <span id="comment_total_value">[<?=number_format($data['total_comment']);?>]</span></a></p>
						<div class="new" id="comment_new_btn"><img src="/bizstory/images/btn/btn_comment.png" alt="코멘트 쓰기" class="pointer" onclick="comment_insert_form('open')"></div>
					</div>

					<div id="new_comment" title="코멘트쓰기"></div>

					<form id="commentlistform" name="commentlistform" method="post" action="<?=$this_page;?>">
					<input type="hidden" id="commentlist_sub_type" name="sub_type" value="" />
					<input type="hidden" id="commentlist_code_part" name="code_part" value="<?=$code_part;?>" />
					<input type="hidden" id="commentlist_cons_idx"  name="cons_idx"  value="<?=$cons_idx;?>" />
					<input type="hidden" id="commentlist_consc_idx" name="consc_idx" value="" />
					<?=$form_page;?>
					<div id="comment_list_data"></div>
					</form>
				</div>
				<!-- //코멘트 -->

			</div>
		</div>
	</div>
	

<script type="text/javascript">
//<![CDATA[
	//receipt_change();
	//receipt_history();
	//receipt_comment();
	
//------------------------------------ 댓글 관련
	var comment_list = './consult_view_comment_list.php';
	var comment_form = './consult_view_comment_form.php';
	var comment_ok   = '/bizstory/consult/consult_view_comment_ok.php';
	
//------------------------------------ 댓글 열기/닫기
	var comment_chk_val = 'close';
	function comment_view()
	{
		if (comment_chk_val == 'close')
		{
			comment_chk_val = 'open';
			$('#comment_list_data').html('');
			$("#comment_gate").removeClass('btn_i_minus');
			$("#comment_gate").addClass('btn_i_plus');
		}
		else
		{
			comment_chk_val = 'close';
			comment_list_data();
			$("#comment_gate").removeClass('btn_i_plus');
			$("#comment_gate").addClass('btn_i_minus');
		}
	}
	comment_view();
	
	function comment_list_data()
	{
		$.ajax({
			async : false,
			type: "get", dataType: 'html', url: comment_list,
			data: $('#commentlistform').serialize(),
			success: function(msg) {
				$('#comment_list_data').html(msg);
			},
			complete: function() {
				myScroll.refresh();
			}
		});
	}
	
	function comment_insert_form(form_type)
	{
		if (form_type == 'close')
		{
			$("#new_comment").slideUp("slow");
			$("#new_comment").html('');
			$('#comment_new_btn').css({'display':'block'});
		}
		else
		{
			$.ajax({
				async : false,
				type: "post", dataType: 'html', url: comment_form,
				data: $('#viewform').serialize(),
				success: function(msg) {
					$('#comment_new_btn').css({'display':'none'});
					$("#new_comment").slideDown("slow");
					$("#new_comment").html(msg);
				},
				complete: function() {
					myScroll.refresh();
				}
			});
		}
	}
//]]>
</script>
<?
	}
	include "./footer.php";
?>
