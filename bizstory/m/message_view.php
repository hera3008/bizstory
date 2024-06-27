<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include "./header.php";

	$send_fmode = "msg";
	$send_smode = "msg";
	
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
	$mem_idx   = $idx;
	

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$query_string = "
		select
			mr.*
			, ms.remark, ms.send_date
		from
			message_receive mr
			left join message_send ms on ms.del_yn = 'N' and ms.ms_idx = mr.ms_idx
		where
			mr.comp_idx = '" . $code_comp . "'
			and (mr.mem_idx = '" . $code_mem . "' or mr.reg_id = '" . $code_mem . "')
			and if (mr.mem_idx = '" . $code_mem . "', mr.reg_id, mr.mem_idx) = '" . $mem_idx . "'
			and if (mr.mem_idx = '" . $code_mem . "', mr.del_yn, ms.send_del) = 'N'
		order by
			if (mr.mem_idx = '" . $code_mem . "', mr.reg_date, ms.reg_date) desc
	";
	$data_sql['query_page']   = $query_page;
	$data_sql['query_string'] = $query_string;
	$data_sql['page_size']    = $page_size;
	$data_sql['page_num']     = $page_num;
	$list = query_list($data_sql);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';
	
	if ($auth_menu['del'] == "Y") // 삭제버튼
	{
		$btn_delete = '<a href="javascript:void(0);" onclick="select_delete()"><span class="btn_r">선택삭제</span></a>';
	}
	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		//$btn_write = '<a href="./message_form.php"><span class="btn_g">쪽지작성</span></a>';
		$btn_write = '<a href="javascript:" onclick="alertMsg(\'서비스 중비 중 입니다.\')"><span class="btn_g">쪽지작성</span></a>';
	}
?>

<div id="page">
	<div id="header">
	<a class="back" href="javascript:history.go(-1)">BACK</a>
<?
	include "body_header.php";
	
	$mem_name = staff_layer_form($mem_idx, '', $set_part_work_yn, $set_color_list2, 'msgviewstaff', '1', 'memlist');
//	echo "mem_name 	=>".$mem_name;
	$msg_num  = $msg_data[$k]['num'];

?>
	</div>
	<div id="content">
		<article>
			<h2><?=$mem_name?></h2>
			<div class="message_bar"> 
				<span class="msg_num">Total <span><?=$list['total_num'];?></span></span>
				<ul>
					<li><?=$btn_delete?></li>
					<li><?=$btn_write?></li>
				</ul>
			</div> 
		</article>
	
		<form id="listform" name="listform" method="post" style="margin:0">
			<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
			<input type="hidden" id="list_sub_action" name="sub_action" value="" />
			<input type="hidden" id="list_idx"        name="idx"        value="" />
			<input type="hidden" id="list_post_value" name="post_value" value="" />
			<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
			<?=$form_page;?>
			<input type="hidden" id="list_idx2"       name="idx2"         value="" />
			<input type="hidden" id="list_mem_idx"    name="list_mem_idx" value="" />
		</form>
			
		<div id="wrapper" class="message_comment">
			<div id="scroller">
				
				<ul class="list_cmt">
<?
	$i = 1;
	$num = $list["total_num"] - ($page_num - 1) * $page_size;
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
			$remark = strip_tags($data["remark"]);
			$remark = str_replace('&nbsp;', ' ', $remark);
			$remark = string_cut($remark, 70);

		// 첨부파일
			$file_where = " and msgf.ms_idx = '" . $data['ms_idx'] . "'";
			$file_list = message_file_data('page', $file_where);
			$total_file = $file_list['total_num'];
			if ($total_file > 0) $file_str = '<span class="attach" title="첨부파일">' . number_format($total_file) . '</span>';
			else $file_str = '';

		// 구분별로 보여줄 내용
			if ($data['reg_id'] == $code_mem && $data['mem_idx'] != $data['reg_id']) // 보낸경우
			{
				$msg_class  = "message_box2";
				$msg_class2 = "ico_cmt_right";
				$msg_check  = '<input type="checkbox" id="msidx_' . $i . '" name="chk_ms_idx[]" value="' . $data["ms_idx"] . '" />';
				$msg_reg    = '보낸일 : ' . $data['send_date'];
				$msg_type   = 'send';
				$msg_idx    = $data["ms_idx"];
				$mem_img    = member_img_view($code_mem, $comp_member_dir); // 등록자 이미지
				$mem_img_view = '<span class="txt_photo">' . $mem_img['img_35'] . '</span>';
				$ico_cmt = '<span class="ico_cmt ico_cmt_right"></span>';
			}
			else
			{
				$msg_class  = "message_box";
				$msg_class2 = "ico_cmt_left";
				$msg_check  = '<input type="checkbox" id="mridx_' . $i . '" name="chk_mr_idx[]" value="' . $data["mr_idx"] . '" />';
				$msg_reg    = '받은일 : ' . $data['send_date'];
				$msg_type   = 'receive';
				$msg_idx    = $data["mr_idx"];
				$mem_img    = member_img_view($mem_idx, $comp_member_dir); // 등록자 이미지
				$mem_img_view = '<span class="txt_photo">' . $mem_img['img_35'] . '</span>';
				$ico_cmt = '<span class="ico_cmt ico_cmt_left"></span>';

				if ($data['read_date'] == "" || $data['read_date'] == "0000-00-00 00:00:00")
				{
					$remark = '<span class="no_read" id="msg_view_a_' . $i . '">' . $remark . '</span>';
				}
				else
				{
					$msg_reg .= ', 읽은일 : ' . $data['read_date'];
				}
			}
?>
					<li class="<?=$msg_class;?>">
						<div class="box_cmt">
							<?=$mem_img_view;?>
							<div class="txt_area">
								<span class="txt_cmt">
									<?=$msg_check;?>
									<a href="javascript:void(0);" onclick="popupview_open('<?=$i;?>', '<?=$mem_idx;?>', '<?=$msg_idx;?>', '<?=$msg_type;?>')"><?=$remark;?></a>
									<?=$file_str;?>
									<div id="msg_view_remark_<?=$i;?>" class="none"></div>
									<?=$ico_cmt;?>
								</span>
								<span class="desc">
									<span class="time"><?=$msg_reg;?></span>
								</span>
							</div>
						</div>
					</li>
<?
			$i++;
		}
	}

	if ($list['total_num'] == 0)
	{
?>
	<li class="message_box msg_no">
		데이타가 없습니다.
	</li>
<?
	}
?>
				</ul>

			</div>
		</div>
	</div>
<script>
//------------------------------------ 팝업보기 열기
	function popupview_open(idx2, chk_idx, idx, msg_type)
	{
		$('#list_mem_idx').val(chk_idx);
		$('#list_idx2').val(idx2);
		$('#list_idx').val(idx);
		$('#list_post_value').val(msg_type);
		$.ajax({
			type: "get", dataType: 'html', url: '<?="/bizstory/msg/msg_view.php"?>',
			data: $('#listform').serialize(),
			success : function(msg) {
				$("#msg_view_remark_" + idx2).html(msg);
				$("#msg_view_remark_" + idx2).css({"display":"block"});
			}
		});
	}

//------------------------------------ 팝업보기 열기
	function popupview_close(idx)
	{
		$("#msg_view_remark_" + idx).css({"display":"none"});
	}
	
	// 라디로 체크박스 관련
	$(document).ready(
		function(){
			$('input[type=radio]').ezMark();
			$('input[type=checkbox]').ezMark();
		}
	);
</script>
<?
	include "./footer.php";
?>