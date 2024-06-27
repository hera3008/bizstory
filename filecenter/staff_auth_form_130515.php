<?
/*
	생성 : 2013.02.04
	수정 : 2013.04.08
	위치 : 파일센터 > 권한설정 - 권한
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp   = $_SESSION[$sess_str . '_comp_idx'];
	$code_part   = search_company_part($code_part);
	$code_mem    = $_SESSION[$sess_str . '_mem_idx'];
	$set_part_yn = $comp_set_data['part_yn'];
	$chk_fi_idx  = $idx;

	$form_chk = 'N';
	if ($auth_menu['mod'] == 'Y' && $mem_idx != '') // 수정권한
	{
		$form_chk = 'Y';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>
		';
	}

	if ($form_chk == 'Y')
	{
	//-------------------------------------- 폴더목록 - 펼침
		function filecenter_staff_folder($comp_idx, $part_idx, $mem_idx, $part_yn, $dir_depth = 1, $up_idx = '', $pro_end)
		{
			global $local_dir, $auth_menu;

			$common_where = " and fi.comp_idx = '" . $comp_idx . "' and fi.part_idx = '" . $part_idx . "' and fi.dir_file = 'folder' and fi.file_name != ''";
			if ($pro_end == 'N')
			{
				$common_where .= " and ifnull(pro.pro_status, '') != 'PS90'";
			}

			$where = $common_where . " and fi.dir_depth = '" . $dir_depth . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'";
			$order = "fi.file_path asc, fi.file_name asc";
			$info_list = filecenter_info_data('list', $where, $order, '', '');

			if ($dir_depth == 1)
			{
				$left_str = '
				<ul id="auth_folder_navi">
					<li>
						<table class="authtable">
						<colgroup>
							<col />
							<col width="80px" />
							<col width="80px" />
							<col width="80px" />
						</colgroup>
						<thead>
							<tr>
								<th>폴더명</th>
								<th>보기</th>
								<th>읽기</th>
								<th>쓰기</th>
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
					$file_name  = $info_data['file_name'];
					$next_depth = $info_data['dir_depth'] + 1;
					if ($info_data['up_fi_idx'] == '')
					{
						$next_up = $info_data['fi_idx'];
					}
					else
					{
						$next_up = $info_data['up_fi_idx'] . ',' . $info_data['fi_idx'];
					}

				// 하위메뉴
					$down_where = $common_where . " and fi.dir_depth = '" . $next_depth . "' and concat(',', fi.up_fi_idx, ',') like '%," . $next_up . ",%'";
					$down_menu = filecenter_info_data('view', $down_where);

					$chk_up_idx = $info_data['up_fi_idx'];
					$chk_up_arr = explode(',', $chk_up_idx);
					foreach ($chk_up_arr as $chk_up_k => $chk_up_v)
					{
						if ($chk_up_k == 0)
						{
							$chk_up = $chk_up_v;
						}
						else
						{
							$chk_up .= '_' . $chk_up_v;
						}
					}
					if ($chk_up == '')
					{
						$li_id_str = 'authleft_' . $sort;
					}
					else
					{
						$li_id_str = 'authleft_' . $chk_up . '_' . $sort;
					}
					$left_str = str_replace('[ui_id_str]', 'authsubmenu_' . $chk_up, $left_str);

					$icon_img = '';
					if ($info_data['dir_depth'] == 1)
					{
						if ($file_name == 'Project') $icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_p.png" alt="Porject" title="Porject" /> ';
						else $icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_v.png" alt="V-Center" title="V-Center" /> ';
					}

				// 권한확인
					$sub_where = " and fa.comp_idx = '" . $comp_idx . "' and fa.mem_idx = '" . $mem_idx . "' and fa.fi_idx = '" . $info_data["fi_idx"] . "'";
					$auth_data = filecenter_auth_data('view', $sub_where);
					if ($auth_data['dir_view'] == '1') $dir_view = 'Y'; else $dir_view = 'N';
					if ($auth_data['dir_read'] == '1') $dir_read = 'Y'; else $dir_read = 'N';
					if ($auth_data['dir_write'] == '1') $dir_write = 'Y'; else $dir_write = 'N';

					if ($auth_menu['mod'] == "Y")
					{
						$btn_view  = "check_mem_auth('dir_view', '" . $info_data["fi_idx"] . "', '" . $auth_data['dir_view'] . "')";
						$btn_read  = "check_mem_auth('dir_read', '" . $info_data["fi_idx"] . "', '" . $auth_data['dir_read'] . "')";
						$btn_write = "check_mem_auth('dir_write', '" . $info_data["fi_idx"] . "', '" . $auth_data['dir_write'] . "')";
					}
					else
					{
						$btn_view  = "check_auth_popup('modify')";
						$btn_read  = "check_auth_popup('modify')";
						$btn_write = "check_auth_popup('modify')";
					}

				// 쓰기권한일 경우
					if ($dir_write == 'Y')
					{
						$btn_view  = "";
						$btn_read  = "";
					}
				// 읽기권한일 경우
					else if ($dir_read == 'Y')
					{
						$btn_view  = "";
					}

					$left_str .= '
					<li id="' . $li_id_str . '">
						<table class="authtable">
						<colgroup>
							<col />
							<col width="80px" />
							<col width="80px" />
							<col width="80px" />
						</colgroup>
						<tbody>
							<tr>
								<td class="left"><span><a href="javascript:void(0)"> ' . $icon_img . $file_name . '</a></span></td>
								<td><img src="' . $local_dir . '/bizstory/images/icon/' . $dir_view . '.gif" alt="' . $dir_view . '" class="pointer" onclick="' . $btn_view . '" /></td>
								<td><img src="' . $local_dir . '/bizstory/images/icon/' . $dir_read . '.gif" alt="' . $dir_read . '" class="pointer" onclick="' . $btn_read . '" /></td>
								<td><img src="' . $local_dir . '/bizstory/images/icon/' . $dir_write . '.gif" alt="' . $dir_write . '" class="pointer" onclick="' . $btn_write . '" /></td>
							</tr>
						</tbody>
						</table>';

					if ($down_menu['total_num'] > 0)
					{
						$left_str .= filecenter_staff_folder($comp_idx, $part_idx, $mem_idx, $part_yn, $next_depth, $next_up, $pro_end);
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

		$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
		$mem_data = member_info_data('view', $mem_where);
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong><?=$mem_data['mem_name'];?>님 <?=$page_menu_name;?></strong> <?=$form_title;?>
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>
	<div class="info_text">
		<ul>
			<li>권한설정시 해당되는 상위폴더권한이 설정이 되어 있지 않으면 자동으로 설정이 됩니다.</li>
		</ul>
	</div>

	<div class="ajax_frame">
		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" id="post_comp_idx" name="comp_idx" value="<?=$code_comp;?>" />
			<input type="hidden" id="post_part_idx" name="part_idx" value="<?=$code_part;?>" />
			<input type="hidden" id="post_mem_idx"  name="mem_idx"  value="<?=$mem_idx;?>" />
	<?
		$folder_str = filecenter_staff_folder($code_comp, $code_part, $mem_idx, $set_part_yn, 1, '', '');
		echo $folder_str;
	?>
		</form>
	</div>
</div>

<?
// 펼치기 위해서
	$chk_where = "and fi.fi_idx = '" . $chk_fi_idx . "'";
	$chk_folder = filecenter_info_data('view', $chk_where);
	if ($chk_folder['up_fi_idx'] == '')
	{
		$navi_up = $chk_folder['fi_idx'];
	}
	else
	{
		$navi_up = $chk_folder['up_fi_idx'] . ',' . $chk_folder['fi_idx'];
	}
	$navi_up_arr = explode(',', $navi_up);
	$navi_up_len = count($navi_up_arr) - 1;
	foreach ($navi_up_arr as $navi_up_k => $navi_up_v)
	{
		if ($navi_up_k == 0)
		{
			$chk_up = $navi_up_v;
		}
		else if ($navi_up_k < $navi_up_len)
		{
			$chk_up .= '_' . $navi_up_v;
		}
	}
?>

<script type="text/javascript">
//<![CDATA[
	var now_folder_id = 'authleft_<?=$chk_up;?>';

	var fsidebar = document.getElementById("auth_folder_navi");
	if (fsidebar)
	{
		this.flistItem = function(li){
			if(li.getElementsByTagName("ul").length > 0)
			{
				var li_a  = li.getElementsByTagName("span")[0];
				var ul    = li.getElementsByTagName("ul")[0];
				var ul_id = $(ul).attr('id');

				ul.style.display = "none";
				var span = li.getElementsByTagName("span")[0];
				var strong = document.createElement("strong");
				strong.className = "collapsed";
				span.onclick = function(){
					ul.style.display = (ul.style.display == "none") ? "block" : "none";
					strong.className = (ul.style.display == "none") ? "collapsed" : "expanded";
				};

				var file_menu_arr = now_folder_id.split('_');
				var left_str = 'authsubmenu';
				for (var left_num = 1; left_num < 10; left_num++)
				{
					if (file_menu_arr[left_num] != undefined && file_menu_arr[left_num] != '')
					{
						left_str = left_str + '_' + file_menu_arr[left_num];
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
//]]>
</script>

<?
	}
?>