<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include "./header.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
//	echo "code_part 1	=>".$code_part;
	$search_part = $_POST["search_part"]; 

	if ($search_part != ''){
		$code_part = $search_part;
	}
	$code_comp     = $_SESSION[$sess_str . '_comp_idx'];
	$where = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $code_part . "'";
	
	if ($shgroup != '' && $shgroup != 'all') // 직원그룹
	{
		$where .= " and mem.csg_idx = '" . $shgroup . "'";
	}
	if ($stext != '' && $swhere != '')
	{
		if ($swhere == 'mem.tel_num')
		{
			$stext = str_replace('-', '', $stext);
			$stext = str_replace('.', '', $stext);
			$where .= " and (
				replace(mem.tel_num, '-', '') like '%" . $stext . "%' or
				replace(mem.tel_num, '.', '') like '%" . $stext . "%' or
				replace(mem.hp_num, '-', '') like '%" . $stext . "%' or
				replace(mem.hp_num, '.', '') like '%" . $stext . "%'
			)";
		}
		else $where .= " and " . $swhere . " like '%" . $stext . "%'";
	}
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'mem.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = 'mem.login_yn asc, ' . $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 페이지관련
	$list = member_info_data('list', $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shgroup=' . $send_shgroup;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"   value="' . $send_swhere . '" />
		<input type="hidden" name="stext"    value="' . $send_stext . '" />
		<input type="hidden" name="shgroup"  value="' . $send_shgroup . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1"   value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2"   value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';
?>

<script type="text/javascript">

	function search_member(part){
	
		//var code_part = part;
		$('#search_part').val(part);
	//	searchform.action = "member_list.php";
		$("#searchform").submit();

	}
	
	

</script>
<div id="page">
	<div id="header">
	<a class="back" href="javascript:history.go(-1)">BACK</a>
<?
	include "body_header.php";
?>
	</div>
	<div id="content">
		<article>
			<h2>직원목록</h2>
			<form id="searchform" method="POST" action="">
				<fieldset>
				<input type="hidden" name="fmode" value="<?=$send_fmode?>" />
				<input type="hidden" name="smode" value="<?=$send_smode?>" />
				<input type="hidden" name="swhere"    value="<?=$send_swhere?>" />
				<input type="hidden" name="stext"     value="<?=$send_stext?>" />
				<input type="hidden" name="swtype"    value="<?=$send_swtype?>" />
				<input type="hidden" name="shwstatus" value="<?=$send_shwstatus?>" />
				<input type="hidden" name="smember"   value="<?=$send_smember?>" />
				<input type="hidden" name="search_part" id="search_part" value="" />
				<legend>컨텐츠 검색</legend>
					<div class="search_bar"> 
						<div class="search_area"> 
							<div class="inpwp"><input type="search" title="검색어 입력" id="inpSearch" autocomplete="off" autocorrect="off" name="keyword" value="검색할 단어 입력" class="" maxlength="40" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" /></div>
							<i class="spr_tm mag"></i> 
							<button type="button" class="del" id="btnDelete"><i class="spr_tm">검색어 삭제</i></button> 
						</div> 
						<button type="button" class="go" id="btnSearch"><i class="spr_tm">검색하기</i></button>
					</div> 
				</fieldset> 
			</form>
<?
	$part_where = "and part.comp_idx = '" . $code_comp . "'";
	$part_list     = company_part_data('list', $part_where, '', '', '');
//	echo "part_list". print_r($part_list);
	if($part_list['total_num'] > 0) {
?>
			<div class="message_bar">
				<select class="ngb_select" id="MemberGroupList" onchange="search_member(this.value);">
<?
		foreach ($part_list as $k => $part_data){
			if(is_array($part_data)){
				//echo "code_part: " . $code_part;
				if ($code_part == $part_data['part_idx']) $class_str = ' class="select" selected="selected"';
					else $class_str = '';
?>					
					<option value="<?=$part_data['part_idx'];?>" <?=$class_str?>><?=$part_data['part_name'];?></option>
					
<?
			}
		}
?>
				</select>
			</div>
<?
	}
?>
		</article>
		<div id="wrapper" class="member_section">
			<div id="scroller">

				<ul class="member_list">
<?
//echo $list["total_num"];
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<li>
			등록된 데이타가 없습니다.
		</li>
<?
	}
	else
	{
		$i = 1;
		$num = $list["total_num"] - ($page_num - 1) * $page_size;
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
			// 직책명
				$sub_where = "and cpd.cpd_idx = '" . $data['cpd_idx'] . "'";
				$duty_data = company_part_duty_data('view', $sub_where);

			// 직책그룹
				$sub_where = "and csg.csg_idx = '" . $data['csg_idx'] . "'";
				$group_data = company_staff_group_data('view', $sub_where);

				if ($auth_menu['view'] == "Y") $btn_view = "view_open('" . $data['mem_idx'] . "')";
				else $btn_view = "check_auth_popup('view')";

				$mem_idx  = $data['mem_idx'];
				//$charge_str = staff_layer_form2($data['mem_idx'], '', 'N', $set_color_list2, 'stafflist', $data['mem_idx'], '');
				$mem_string = staff_layer_form2($mem_idx, '', $set_part_work_yn, $set_color_list2, 'msgstaff', $i, 'memlist');
				
				$mem_idx  = $data['mem_idx'];
				$mem_img = member_img_view($mem_idx, $comp_member_dir); // 등록자 이미지
?>

					<li class="<?if ($i==1) {?>first <?}?> small pop1" data-bpopup='{"transition":"slideDown","speed":850,"easing":"easeOutBack"}' onclick="popupMemInfo(<?=$mem_idx?>)">
						<p class="name"><?=$mem_string;?> <?=$duty_data["duty_name"];?></p>
						<?=$mem_img['img_22'];?>
						<ul class="member_info">
							<li class="email"><?=$data['mem_email']?></li>
							<li class="tel"><?=$data["hp_num"];?></li>
						</ul>
					</li>
<?
				$num--;
				$i++;
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