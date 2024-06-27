<?
/*
	생성 : 2012.08.30
	수정 : 2013.05.13
	위치 : 설정관리 > 통계 > 접수통계
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	if ($list_type == '')
	{
		$list_type = 'month'; $send_list_type = 'month'; $recv_list_type = 'month';
	}
	if ($syear == '')
	{
		$syear = date('Y'); $send_syear = date('Y'); $recv_syear = date('Y');
	}
	if ($smonth == '')
	{
		$smonth = date('m'); $send_smonth = date('m'); $recv_smonth = date('m');
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list = $local_dir . "/bizstory/tong/receipt_member_list.php"; // 목록
?>
<div class="tablewrapper">

	<div id="tableheader">
		<? include $local_path . "/bizstory/comp_set/part_menu_inc.php"; ?>

		<? include $local_path . "/bizstory/comp_set/member_menu_inc.php"; ?>

		<div class="tabarea" id="tong_menu">
			<p>통계구분</p>
			<?
			switch ($list_type) {
				case 'year':
					$class_str1 = ' class="select"'; 
					$class_str2 = '';
					$class_str3 = '';
				break;
				case 'month':
					$class_str1 = '';
					$class_str2 = ' class="select"';
					$class_str3 = '';
				break;
				case 'day':
					$class_str1 = '';
					$class_str2 = '';
					$class_str3 = ' class="select"';
				break;
			}
			?>
			<div class="tabarea_part">
				<!--
				<a href="javascript:void(0);" id="tong_list_year" onclick="check_search('year')"<?=$class_str1;?>>연별</a>
				<span>|</span>
				-->
				<a href="javascript:void(0);" id="tong_list_month" onclick="check_search('month')"<?=$class_str2;?>>월별</a>
				<span>|</span>
				<a href="javascript:void(0);" id="tong_list_day" onclick="check_search('day')"<?=$class_str3;?>>일별</a>
			</div>
		</div>
	</div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<input type="hidden" id="list_code_member"  name="code_member"  value="<?=$code_member;?>" />
		<input type="hidden" id="list_list_type"  name="list_type"  value="<?=$list_type;?>" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>

</div>
<script>
	function check_mem_idxs() {
		$('#mem_idxs option:selected').each(function() {
			//console.log($(this).val());
		});
	}
	function getSelectedOptions(eId) {
		var opts = [];
		$('#' + eId + ' option:selected').each(function() {
			opts.push($(this).val());
		});
		return opts.join(',');
	}
	function move_member(member_idx)
	{
		$('#list_code_member').val(member_idx);
		$('#member_menu a').removeClass('select');
		$('#member_' + member_idx).addClass('select');

		view_close();
		list_data();
	}
</script>
<script src="<?=$local_dir;?>/bizstory/add/highcharts/highcharts.js"></script>
<script src="<?=$local_dir;?>/bizstory/add/highcharts/modules/exporting.js"></script>
<script type="text/javascript">
//<![CDATA[
	var link_list = '<?=$link_list;?>';

//------------------------------------ 검색
	function check_search(list_type)
	{
		document.listform.list_type.value = list_type;

		$('#list_code_member').val(getSelectedOptions('mem_idxs'));

		switch(list_type) {
			/*
			case 'year':
				$('#tong_menu a').removeClass('select');
				$('#tong_list_year').addClass('select');
				break;
			*/
			case 'month':
				$('#tong_menu a').removeClass('select');
				$('#tong_list_month').addClass('select');
				break;
			case 'day':
				$('#tong_menu a').removeClass('select');
				$('#tong_list_day').addClass('select');
				break;
		}

		list_data();
		return false;
	}

	function down_excel(list_type) {
		var form = document.listform;
		form.list_type.value = list_type;

		$('#list_code_member').val(getSelectedOptions('mem_idxs'));

		form.target = "_blank";
		form.action = "./bizstory/tong/receipt_member_excel.php";
		form.submit();
		return false;
	}

	list_data();

//]]>
</script>