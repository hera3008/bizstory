<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$comp_idx = $idx;

	$where = " and mi.mode_type != 'maintain' and mi.view_yn = 'Y'";
	$list = menu_info_data('list', $where, '', '', '');

	$menu_url = $local_dir . '/bizstory/maintain/company_menu.php';
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<div class="info_frame">
			<span>* 메뉴권한을 가지고 있어도 설정값이 N이면 사용할 수 없습니다.</span>
		</div>

		<form id="otherform" name="otherform" action="<?=$this_page;?>" method="post">
			<input type="hidden" id="other_comp_idx"   name="comp_idx"   value="<?=$comp_idx;?>" />
			<input type="hidden" id="other_sub_type"   name="sub_type"   value="auth_menu" />
			<input type="hidden" id="other_idx"        name="idx"        value="" />
			<input type="hidden" id="other_post_value" name="post_value" value="" />

			<table class="tinytable">
			<colgroup>
				<col width="80px" />
				<col />
			</colgroup>
			<thead>
				<tr>
					<th class="nosort"><h3>권한</h3></th>
					<th class="nosort"><h3>메뉴명</h3></th>
				</tr>
			</thead>
			<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
				<tr>
					<td colspan="2">등록된 데이타가 없습니다.</td>
				</tr>
<?
	}
	else
	{
		$i = 1;
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$sub_where = " and mac.comp_idx = '" . $comp_idx . "' and mac.mi_idx = '" . $data["mi_idx"] . "'";
				$menu_auth_data = menu_auth_company_data('view', $sub_where);
				if ($menu_auth_data['view_yn'] == 'Y') $view_yn = 'Y';
				else $view_yn = 'N';

				if ($auth_menu['mod'] == "Y")
				{
					$btn_view = "other_check_code('auth_menu', 'view_yn', '" . $data["mi_idx"] . "', '" . $view_yn . "', '" . $menu_url . "')";
				}
				else
				{
					$btn_view = "check_auth_popup('modify')";
				}
?>
				<tr>
					<td><img src="bizstory/images/icon/<?=$view_yn;?>.gif" alt="<?=$view_yn;?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
					<td>
						<div class="left depth_<?=$data["menu_depth"];?>"><?=$data["menu_name"];?></div>
					</td>
				</tr>
<?
				$i++;
			}
		}
	}
?>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<span class="btn_big_gray"><input type="button" value="닫기" onclick="popupform_close()" /></span>
				</div>
			</div>
		</form>
	</div>
</div>