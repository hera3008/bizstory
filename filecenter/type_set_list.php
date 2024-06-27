<?
/*
	생성 : 2013.01.29
	수정 : 2013.04.08
	위치 : 파일센터 > 타입설정 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

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

	$where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "'";
	$list = filecenter_code_type_data('list', $where, '', '');

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
	    $btn_auth = '<a href="javascript:void(0);" onclick="popupentrust_open(\'\')" class="btn_big_green"><span>권한 위임</span></a>';
		$btn_write = '<a href="javascript:void(0);" onclick="popupform_open(\'\')" class="btn_big_green"><span>등록</span></a>';
	}

//-------------------------------------- 폴더목록 - 펼침
	function filecenter_type_folder($comp_idx, $part_idx, $menu_depth = 1, $up_idx = '')
	{
		global $local_dir, $auth_menu;

		$common_where = " and code.comp_idx = '" . $comp_idx . "' and code.part_idx = '" . $part_idx . "'";

		$info_where = $common_where . " and code.menu_depth = '" . $menu_depth . "' and concat(',', code.up_code_idx, ',') like '%," . $up_idx . ",%'";
		$info_list = filecenter_code_type_data('list', $info_where, '', '', '');

		if ($menu_depth == 1)
		{
			$left_str = '
	<ul id="type_folder_navi">
		<li>
			<table class="typetable">
				<colgroup>
					<col width="110px" />
					<col />
					<col width="50px" />
					<col width="180px" />
				</colgroup>
				<thead>
					<tr>
						<th>지사명</th>
						<th>설정명</th>
						<th>보기</th>
						<th>관리</th>
					</tr>
				</thead>
			</table>
		</li>';
		}
		else
		{
			$left_str .= '
	<ul id="[ui_id_str]">';
		}

		$sort = 1;
		foreach ($info_list as $info_k => $info_data)
		{
			if (is_array($info_data))
			{
				$code_idx   = $info_data['code_idx'];
				$code_name  = $info_data['code_name'];
				$next_depth = $info_data['menu_depth'] + 1;
				if ($info_data['up_code_idx'] == '') $next_up = $info_data['code_idx'];
				else $next_up = $info_data['up_code_idx'] . ',' . $info_data['code_idx'];

			// 하위메뉴
				$down_where = $common_where . " and code.menu_depth = '" . $next_depth . "' and concat(',', code.up_code_idx, ',') like '%," . $next_up . ",%'";
				$down_menu = filecenter_code_type_data('view', $down_where);

				$chk_up_idx = $info_data['up_code_idx'];
				$chk_up_arr = explode(',', $chk_up_idx);
				foreach ($chk_up_arr as $chk_up_k => $chk_up_v)
				{
					if ($chk_up_k > 0)
					{
						if ($chk_up_k == 1) $chk_up = $chk_up_v;
						else $chk_up .= '_' . $chk_up_v;
					}
				}
				if ($chk_up == '') $li_id_str = 'authleft_' . $sort;
				else $li_id_str = 'authleft_' . $chk_up . '_' . $sort;
				$left_str = str_replace('[ui_id_str]', 'authsubmenu_' . $chk_up, $left_str);

				if ($auth_menu['mod'] == "Y")
				{
					$btn_view   = "check_code_data('check_yn', 'view_yn', '" . $code_idx . "', '" . $info_data["view_yn"] . "')";
					$btn_modify = "popupform_open('" . $code_idx . "')";
					$btn_auth   = "other_page_open('" . $code_idx . "', '" . $local_dir . "/bizstory/filecenter/type_set_auth.php')";
				}
				else
				{
					$btn_view   = "check_auth_popup('modify')";
					$btn_modify = "check_auth_popup('modify')";
					$btn_auth   = "check_auth_popup('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $code_idx . "')";
				else $btn_delete = "check_auth_popup('delete');";

				if ($info_data["view_yn"] == 0) $view_yn = 'N'; else $view_yn = 'Y';

				$left_str .= '
		<li id="' . $li_id_str . '">
			<table class="typetable">
				<colgroup>
					<col width="110px" />
					<col />
					<col width="50px" />
					<col width="180px" />
				</colgroup>
				<tbody>
					<tr>
						<td>' . $info_data["part_name"] . '</td>
						<td class="left"><span><strong></strong>&nbsp;<a href="javascript:void(0)"> ' . $info_data["code_name"] . '</a></span></td>
						<td><img src="' . $local_dir . 'bizstory/images/icon/' . $view_yn . '.gif" alt="' . $view_yn . '" class="pointer" onclick="' . $btn_view . '" /></td>
						<td>';

				if ($info_data['menu_depth'] > 1)
				{
					$left_str .= '
							<input type="button" value="권한설정" class="btn_con_violet" onclick="' . $btn_auth . '" />';
				}
				$left_str .= '
							<input type="button" value="수정" class="btn_con_blue" onclick="' . $btn_modify . '" />';
				if ($info_data['menu_num'] == 0)
				{
					$left_str .= '
							<input type="button" value="삭제" class="btn_con_red" onclick="' . $btn_delete . '" />';
				}
				$left_str .= '
						</td>
					</tr>
				</tbody>
			</table>';

				if ($down_menu['total_num'] > 0)
				{
					$left_str .= filecenter_type_folder($comp_idx, $part_idx, $next_depth, $next_up);
				}

				$left_str .= '
		</li>';
				$sort++;
			}
		}
		$left_str .= '
	</ul>';

		Return $left_str;
	}
?>
<div class="info_text">
	<ul>
		<li>1단계는 타입별로 구분을 하기 위한 것입니다.</li>
		<li>각 폴더별로 직원에게 권한을 설정하시면 해당폴더 생성시 직원에게 권한이 부여가 됩니다.</li>
	</ul>
</div>
<hr />

<div class="etc_bottom">
    <?=$btn_auth?>
	<?=$btn_write;?>
</div>
<hr />
<?
	$folder_str = filecenter_type_folder($code_comp, $code_part, 1, '');
	echo $folder_str;
?>
<hr />

<?
// 펼치기 위해서
	$info_where = " and code.code_idx = '" . $idx . "'";
	$info_data = filecenter_code_type_data('view', $info_where);
	$navi_up = $info_data['up_code_idx'];
	$navi_up_arr = explode(',', $navi_up);
	foreach ($navi_up_arr as $navi_up_k => $navi_up_v)
	{
		if ($navi_up_k > 0)
		{
			if ($navi_up_k == 1) $chk_up = $navi_up_v;
			else $chk_up .= '_' . $navi_up_v;
		}
	}
?>
<script type="text/javascript">
//<![CDATA[
	var now_folder_id = 'authsubmenu_<?=$chk_up;?>';

	var fsidebar = document.getElementById("type_folder_navi");
	if (fsidebar)
	{
		this.flistItem = function(li){
			if(li.getElementsByTagName("ul").length > 0)
			{
				var li_a = li.getElementsByTagName("span")[0];
				var ul = li.getElementsByTagName("ul")[0];
				var ul_id = $(ul).attr('id');

				ul.style.display = "none";
				var span = li.getElementsByTagName("span")[0];
				var strong = li.getElementsByTagName("strong")[0];
				strong.className = "collapsed";
				span.onclick = function(){
					ul.style.display = (ul.style.display == "none") ? "block" : "none";
					strong.className = (ul.style.display == "none") ? "collapsed" : "expanded";
				};

				var sub_menu_arr = now_folder_id.split('_');
				var left_str = 'authsubmenu';
				for (var left_num = 1; left_num < 10; left_num++)
				{
					if (sub_menu_arr[left_num] != undefined)
					{
						left_str = left_str + '_' + sub_menu_arr[left_num];
						$("#" + left_str).css({"display": "block"});
						if (ul_id == left_str)
						{
							strong.className = "expanded";
						}
					}
				}
				li_a.appendChild(strong);
			};
		};
		var items = fsidebar.getElementsByTagName("li");
		for(var i = 0; i < items.length; i++)
		{
			flistItem(items[i]);
		};
	};
	

//------------------------------------ 팝업등록폼 열기
    function popupentrust_open()
    {
        $("#popup_notice_view").hide();
        //$('#list_idx').val(idx);
        $.ajax({
            type: "get", dataType: 'html', url: link_entrust,
            data: $('#listform').serialize(),
            success  : function(msg) {
                $('html, body').animate({scrollTop:0}, 500);
                var maskHeight = $(document).height() + 100;
                var maskWidth  = $(window).width();
                $("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':maskHeight}).fadeIn("slow");
                $("#data_form").slideDown("slow");
                $('.popupform').css('top',  "80px");
                $('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
                $("#data_form").html(msg);
            }
        });
    }
//------------------------------------ 팝업등록폼 닫기
    function popupentrust_close()
    {
        try {
            
        } catch(e) {}
        $("#data_form").slideUp("slow");
        $("#backgroundPopup").fadeOut("slow");
    }
//]]>
</script>
