<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

//-------------------------------------- 폴더목록 - 펼침
	function my_bookmark($comp_idx, $mem_idx, $menu_depth = 1, $up_idx = '')
	{
		global $local_dir, $auth_menu;

		$common_where = " and mam.comp_idx = '" . $comp_idx . "' and mam.mem_idx = '" . $mem_idx . "' and mam.yn_list = 'Y' and mac.view_yn = 'Y'";

		$info_where = $common_where . " and mi.menu_depth = '" . $menu_depth . "' and concat(',', mi.up_mi_idx, ',') like '%," . $up_idx . ",%'";
		$info_order = "mc.sort asc";
		$info_list = menu_auth_member_data('list', $info_where, $info_order, '', '');

		if ($menu_depth == 1)
		{
			$left_str = '
	<ul id="type_folder_navi">
		<li>
			<table class="typetable">
				<colgroup>
					<col width="80px" />
					<col />
				</colgroup>
				<thead>
					<tr>
						<th class="nosort"><h3>즐겨찾기</h3></th>
						<th class="nosort"><h3>메뉴명</h3></th>
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
				$next_depth = $info_data['menu_depth'] + 1;
				if ($info_data['up_mi_idx'] == '') $next_up = $info_data['mi_idx'];
				else $next_up = $info_data['up_mi_idx'] . ',' . $info_data['mi_idx'];

			// 하위메뉴
				$down_where = $common_where . " and mi.menu_depth = '" . $next_depth . "' and concat(',', mi.up_mi_idx, ',') like '%," . $next_up . ",%'";
				$down_menu = menu_auth_member_data('view', $down_where);

				$chk_up_idx = $info_data['up_mi_idx'];
				$chk_up_arr = explode(',', $chk_up_idx);
				foreach ($chk_up_arr as $chk_up_k => $chk_up_v)
				{
					if ($chk_up_k == 0) $chk_up = $chk_up_v;
					else $chk_up .= '_' . $chk_up_v;
				}
				if ($chk_up == '') $li_id_str = 'authleft_' . $sort;
				else $li_id_str = 'authleft_' . $chk_up . '_' . $sort;
				$left_str = str_replace('[ui_id_str]', 'authsubmenu_' . $chk_up, $left_str);

			// 즐겨찾기
				$book_where = " and mb.comp_idx = '" . $info_data['comp_idx'] . "' and mb.mi_idx = '" . $info_data['mi_idx'] . "' and mb.mem_idx = '" . $mem_idx . "'";
				$book_data = member_bookmark_data('view', $book_where);

				$view_yn = $book_data["view_yn"];
				if ($view_yn == '') $view_yn = 'N';

			// 메뉴명
				$menu_name = $info_data['part_menu_name'];
				if ($menu_name == '') $menu_name = $data['menu_name'];

				if ($view_yn == 'Y') $menu_name = '<a href="javascript:void(0)" style="font-size:15px;color:#3300ff;font-weight:700;">' . $menu_name . '</a>';
				else $menu_name = '<a href="javascript:void(0)">' . $menu_name . '</a>';

				if ($auth_menu['mod'] == "Y")
				{
					$btn_view = "check_code_data('check_yn', 'view_yn', '" . $info_data['mi_idx'] . "', '" . $view_yn . "')";
				}
				else
				{
					$btn_view = "check_auth_popup('modify')";
				}

				if ($info_data['menu_num'] == 0)
				{
					$btn_chk = '<img src="' . $local_dir . '/bizstory/images/icon/' . $view_yn . '.gif" alt="' . $view_yn . '" class="pointer" onclick="' . $btn_view . '" />';
				}
				else $btn_chk = '&nbsp;';

				unset($menu_data);
				unset($book_data);

				$left_str .= '
		<li id="' . $li_id_str . '">
			<table class="typetable">
				<colgroup>
					<col width="80px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<td>' . $btn_chk . '</td>
						<td class="left"><span><strong></strong>' . $menu_name . '</span></td>
					</tr>
				</tbody>
			</table>';

				if ($down_menu['total_num'] > 0)
				{
					$left_str .= my_bookmark($comp_idx, $mem_idx, $next_depth, $next_up);
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

	$folder_str = my_bookmark($code_comp, $code_mem, 1, '');
	echo $folder_str;
?>

<script type="text/javascript">
//<![CDATA[
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
				li_a.appendChild(strong);
			};
		};
		var items = fsidebar.getElementsByTagName("li");
		for(var i = 0; i < items.length; i++)
		{
			flistItem(items[i]);
		};
	};
//]]>
</script>
