<?
/*
	생성 : 2013.05.13
	수정 : 2013.05.13
	위치 : 설정관리 > 통계 > 에이전트
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
	$link_list = $local_dir . "/bizstory/tong/agent_list.php"; // 목록
?>
<div class="tablewrapper">

	<div id="tableheader">
		<? include $local_path . "/bizstory/comp_set/part_menu_inc.php"; ?>

		<div class="tabarea" id="tong_menu">
			<p>통계구분</p>
			<?
				if ($list_type == 'month')
				{
					$class_str1 = ' class="select"'; $class_str2 = '';
				}
				else
				{
					$class_str1 = ''; $class_str2 = ' class="select"';
				}
			?>
			<div class="tabarea_part">
				<a href="javascript:void(0);" id="tong_list_month" onclick="check_search('month')"<?=$class_str1;?>>월별</a>
				<span>|</span>
				<a href="javascript:void(0);" id="tong_list_day" onclick="check_search('day')"<?=$class_str2;?>>일별</a>
			</div>
		</div>
	</div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<input type="hidden" id="list_list_type"  name="list_type"  value="<?=$list_type;?>" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>

</div>

<script src="<?=$local_dir;?>/bizstory/add/highcharts/highcharts.js"></script>
<script src="<?=$local_dir;?>/bizstory/add/highcharts/modules/exporting.js"></script>
<script type="text/javascript">
//<![CDATA[
	var link_list = '<?=$link_list;?>';

//------------------------------------ 검색
	function check_search(list_type)
	{
		document.listform.list_type.value = list_type;

		if (list_type == 'month')
		{
			$('#tong_menu a').removeClass('select');
			$('#tong_list_month').addClass('select');
		}
		else if (list_type == 'day')
		{
			$('#tong_menu a').removeClass('select');
			$('#tong_list_day').addClass('select');
		}

		list_data();
		return false;
	}

	list_data();

//]]>
</script>