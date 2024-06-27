<?
/*
	생성 : 2012.12.20
	수정 : 2013.04.23
	위치 : 업무폴더 > 프로젝트관리
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'pro.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list         = $local_dir . "/bizstory/project/project_list.php";       // 목록
	$link_form         = $local_dir . "/bizstory/project/project_form.php";       // 등록
	$link_view         = $local_dir . "/bizstory/project/project_view.php";       // 보기
	$link_ok           = $local_dir . "/bizstory/project/project_ok.php";         // 저장
	$link_excel        = $local_dir . "/bizstory/project/project_excel.php";      // 액셀
	$link_print        = $local_dir . "/bizstory/project/project_print.php";      // 인쇄
	$link_print_detail = $local_dir . "/bizstory/project/project_print_sel.php";  // 상세인쇄
	$link_print_view   = $local_dir . "/bizstory/project/project_view_print.php"; // 보기인쇄

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';
	$search_charge  = '담당자 입력';

	if ($auth_menu['down'] == "Y") // 다운로드버튼
	{
		//$btn_down = '<a href="javascript:void(0);" class="btn_sml" onclick="list_excel()"><span>엑셀</span></a>';
	}
	if ($auth_menu['print'] == "Y") // 인쇄버튼
	{
		//$btn_print = '<a href="javascript:void(0);" class="btn_sml" onclick="list_print()"><span><em class="print"></em>인쇄</span></a>';
	}
?>
<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/bizstory/css/charge_bar.css" media="all" />
<div class="info_text">
	<ul>
		<li>프로젝트 수정중입니다.</li>
		<li>프로젝트 제대로 작동이 안됩니다.</li>
	</ul>
</div>
<hr />

<div class="tablewrapper">
	<div id="tableheader">
		<? include $local_path . "/bizstory/comp_set/part_menu_inc.php"; ?>

		<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
			<?=$form_default;?>
			<div class="search">
				<p>검&nbsp;&nbsp;&nbsp;색</p>
				<input type="checkbox" id="search_sproject" name="sproject" value="Y" <?=checked('Y', $sproject);?> /> 완료 프로젝트 보기
				<input type="text" id="search_scharge" name="scharge" class="type_text" value="<?=$search_charge;?>" title="<?=$search_charge;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />

				<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
					<option value="pro.project_code"<?=selected($swhere, 'pro.project_code');?>>프로젝트코드</option>
					<option value="pro.subject"<?=selected($swhere, 'pro.subject');?>>프로젝트명</option>
				</select>
				<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />

				<select id="search_sdatetype" name="sdatetype" title="기간을 선택하세요." onchange="search_date_chk(this.value)">
					<option value="0"<?=selected($sdatetype, '0');?>>기간전체</option>
					<option value="1"<?=selected($sdatetype, '1');?>>1주일</option>
					<option value="2"<?=selected($sdatetype, '2');?>>1개월</option>
					<option value="3"<?=selected($sdatetype, '3');?>>3개월</option>
					<option value="4"<?=selected($sdatetype, '4');?>>6개월</option>
					<option value="99"<?=selected($sdatetype, '99');?>>직접입력</option>
				</select>
				<input type="text" id="search_sdatestart" name="sdatestart" class="type_text" value="<?=$sdatestart;?>" title="시작일을 입력하세요." />
				~
				<input type="text" id="search_sdateend" name="sdateend" class="type_text" value="<?=$sdateend;?>" title="종료일을 입력하세요." />
				(예: 2013-01-01)

				<a href="javascript:void(0);" class="btn_sml" onclick="check_search()"><span>검색</span></a>
				<?=$btn_down;?>
				<?=$btn_print;?>
				<?=$btn_print_sel;?>
			</div>
		</form>
	</div>

	<div id="data_view" title="상세보기 / 등록, 수정폼"></div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<input type="hidden" id="list_org_idx"    name="org_idx"    value="" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
	var link_list         = '<?=$link_list;?>';
	var link_form         = '<?=$link_form;?>';
	var link_view         = '<?=$link_view;?>';
	var link_ok           = '<?=$link_ok;?>';
	var link_excel        = '<?=$link_excel;?>';
	var link_print        = '<?=$link_print;?>';
	var link_print_detail = '<?=$link_print_detail;?>';
	var link_print_view   = '<?=$link_print_view;?>';

//------------------------------------ 검색
	function check_search()
	{
		var stext       = $('#search_stext').val();
		var stext_title = $('#search_stext').attr('title');
		if (stext == stext_title) stext = '';

		document.listform.swhere.value = $('#search_swhere').val();
		document.listform.stext.value  = stext;

		view_close();
		list_data();
		return false;
	}
<?
	if ($pro_idx > 0)
	{
		echo "view_open('" . $pro_idx . "');";
	}
?>

//------------------------------------ 기한일표시
	function search_date_chk(idx)
	{
		var now = new Date();
		var now_time = Math.floor(now.getTime() / 1000);
		var add_time = 0;
		var after_time = 0;
		var after_date = '';

		var now_year  = 1900 + now.getYear(); // 년
		var now_month = now.getMonth() + 1; // 월
			if (now_month.length == 1)
			{
				now_month = '0' + String(now_month);
			}
		var now_day   = now.getDate();  // 일
		var now_week  = now.getDay();  // 요일
		var now_date  = now_year + '-' + now_month + '-' + now_day;

		if (idx == '1') // 1주일
		{
			add_time   = 7 * 24 * 60 * 60;
			after_time = now_time + add_time;

			after_date = parseInt(after_time.toString().substring(0, 10))

			$('#search_sdatestart').val(now_date);
			$('#search_sdateend').val(after_date);
		}
		else if (idx == '2') // 1개월
		{
			add_time = 30 * 24 * 60 * 60;
			after_time = now_time + add_time;

			after_date = parseInt(after_time.toString().substring(0, 10))

			$('#search_sdatestart').val('');
			$('#search_sdateend').val('');
		}
		else
		{
			$('#search_sdatestart').val('');
			$('#search_sdateend').val('');
		}

		//alert(now + '\n\n' + now_time + '\n\n' + after_time + '\n\n' + now_year + '\n\n' + now_month + '\n\n' + now_day + '\n\n' + now_week + '\n\n' + after_date);
		//parseInt(s) 나 parseFloat(s)
	}
	list_data();
	<?
		if ($code_mem == '8')
		{
			echo 'open_data_form(\'\')';
		}
	?>
//]]>
</script>
<? include $local_path . "/bizstory/js/filecenter_js.php"; ?>