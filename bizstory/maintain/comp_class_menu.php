<?
/*
	생성 : 2012.11.09
	위치 : 설정폴더 > 업체관리 > 업체분류 - 분류별 메뉴
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_idx = $idx;

	$code_where = " and code.code_idx = '" . $code_idx . "'";
	$code_data = company_class_data('view', $code_where);

	$where = " and mi.mode_type != 'maintain'";
	$list = menu_info_data('list', $where, '', '', '');

	$menu_url = $local_dir . '/bizstory/maintain/comp_class_menu.php';
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong><?=$code_data['code_name'];?></strong> 메뉴를 설정하세요.
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>

	<div class="ajax_frame">

		<form id="otherform" name="otherform" action="<?=$this_page;?>" method="post">
			<input type="hidden" id="other_code_idx"   name="code_idx"   value="<?=$code_idx;?>" />
			<input type="hidden" id="other_sub_type"   name="sub_type"   value="auth_menu" />
			<input type="hidden" id="other_sub_action" name="sub_action" value="" />
			<input type="hidden" id="other_idx"        name="idx"        value="" />
			<input type="hidden" id="other_post_value" name="post_value" value="" />

			<table class="tinytable">
				<colgroup>
					<col width="80px" />
					<col width="80px" />
					<col />
				</colgroup>
				<thead>
					<tr>
						<th class="nosort"><h3>설정</h3></th>
						<th class="nosort"><h3>기본</h3></th>
						<th class="nosort"><h3>메뉴명</h3></th>
					</tr>
				</thead>
				<tbody>
	<?
		$i = 0;
		if ($list["total_num"] == 0) {
	?>
					<tr>
						<td colspan="3">등록된 데이타가 없습니다.</td>
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
					$sub_where = " and ccm.code_idx = '" . $code_idx . "' and ccm.mi_idx = '" . $data["mi_idx"] . "'";
					$menu_auth_data = company_class_menu_data('view', $sub_where);

					if ($menu_auth_data['view_yn'] == 'Y') $view_yn = 'Y';
					else $view_yn = 'N';

					if ($menu_auth_data['default_yn'] == 'Y') $default_yn = 'Y';
					else $default_yn = 'N';

					if ($auth_menu['mod'] == "Y")
					{
						$btn_view    = "other_check_code('auth_menu', 'view_yn', '" . $data["mi_idx"] . "', '" . $view_yn . "', '" . $menu_url . "')";
						$btn_default = "other_check_code('auth_menu', 'default_yn', '" . $data["mi_idx"] . "', '" . $default_yn . "', '" . $menu_url . "')";
					}
					else
					{
						$btn_view    = "check_auth_popup('modify')";
						$btn_default = "check_auth_popup('modify')";
					}
	?>
					<tr>
						<td><img src="bizstory/images/icon/<?=$view_yn;?>.gif" alt="<?=$view_yn;?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
						<td><img src="bizstory/images/icon/<?=$default_yn;?>.gif" alt="<?=$default_yn;?>" class="pointer" onclick="<?=$btn_default;?>" /></td>
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
