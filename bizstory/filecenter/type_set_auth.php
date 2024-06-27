<?
/*
	생성 : 2013.01.29
	수정 : 2013.02.05
	위치 : 파일센터 > 타입설정 - 권한설정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_idx  = $idx;

	$code_where = " and code.code_idx = '" . $code_idx . "'";
	$code_data = filecenter_code_type_data('view', $code_where);

	$where = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $code_part . "'";
	$orderby = " csg.group_name asc, mem.mem_name asc";
    
	$list = member_info_data('list', $where, $orderby, '', '');

	$auth_url = $local_dir . '/bizstory/filecenter/type_set_auth.php';
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong><?=$page_menu_name;?></strong> <?=$form_title;?>
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>

	<div class="info_text">
		<ul>
			<li><strong><?=$code_data['code_name'];?></strong> 폴더에 직원별로 권한을 설정하세요.</li>
			<li>쓰기권한을 가지고 있을 경우 보기, 읽기권한은 자동으로 권한을 가지게 됩니다.</li>
		</ul>
	</div>

	<div class="ajax_frame">
		<form id="otherform" name="otherform" action="<?=$this_page;?>" method="post">
			<input type="hidden" id="other_fmode" name="fmode" value="<?=$fmode;?>" />
			<input type="hidden" id="other_smode" name="smode" value="<?=$smode;?>" />

			<input type="hidden" id="other_comp_idx"   name="comp_idx"   value="<?=$code_comp;?>" />
			<input type="hidden" id="other_part_idx"   name="part_idx"   value="<?=$code_part;?>" />
			<input type="hidden" id="other_code_idx"   name="code_idx"   value="<?=$code_idx;?>" />
			<input type="hidden" id="other_sub_type"   name="sub_type"   value="auth_dir" />
			<input type="hidden" id="other_sub_action" name="sub_action" value="" />
			<input type="hidden" id="other_idx"        name="idx"        value="" />
			<input type="hidden" id="other_post_value" name="post_value" value="" />

			<table class="tinytable view">
			<colgroup>
				<col />
				<col width="80px" />
				<col width="80px" />
				<col width="80px" />
				<col width="80px" />
			</colgroup>
			<thead>
				<tr>
					<th class="nosort"><h3>직원명</h3></th>
					<th class="nosort"><h3>보기</h3></th>
					<th class="nosort"><h3>읽기</h3></th>
					<th class="nosort"><h3>쓰기</h3></th>
					<th class="nosort"><h3>삭제</h3></th>
				</tr>
			</thead>
			<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
				<tr>
					<td colspan="4">등록된 데이타가 없습니다.</td>
				</tr>
<?
	}
	else
	{
		$i = 1;
        $comp_part = "";
        
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$sub_where = " and codea.comp_idx = '" . $code_comp . "' and codea.mem_idx = '" . $data["mem_idx"] . "' and codea.code_idx = '" . $code_idx . "'";
				$auth_data = filecenter_code_type_auth_data('view', $sub_where);

				if ($auth_data['dir_view'] == '1') $dir_view = 'Y'; else { $dir_view = 'N'; $auth_data['dir_view'] = '0'; }
				if ($auth_data['dir_read'] == '1') $dir_read = 'Y'; else { $dir_read = 'N'; $auth_data['dir_read'] = '0'; }
				if ($auth_data['dir_write'] == '1') $dir_write = 'Y'; else { $dir_write = 'N'; $auth_data['dir_write'] = '0'; }
                if ($auth_data['dir_delete'] == '1') $dir_delete = 'Y'; else { $dir_delete = 'N'; $auth_data['dir_delete'] = '0'; }

				if ($auth_menu['mod'] == "Y")
				{
					$btn_view  = "check_type_auth(this, 'dir_view', '" . $data["mem_idx"] . "', '" . $auth_url . "')";
					$btn_read  = "check_type_auth(this, 'dir_read', '" . $data["mem_idx"] . "', '" . $auth_url . "')";
					$btn_write = "check_type_auth(this, 'dir_write', '" . $data["mem_idx"] . "', '" . $auth_url . "')";
                    $btn_delete = "check_type_auth(this, 'dir_delete', '" . $data["mem_idx"] . "', '" . $auth_url . "')";
				}
				else
				{
					$btn_view  = "check_auth_popup('modify')";
					$btn_read  = "check_auth_popup('modify')";
					$btn_write = "check_auth_popup('modify')";
                    $btn_delete = "check_auth_popup('modify')";
				}
/*
			// 쓰기는 최고권한
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
*/
				$charge_str = staff_layer_form($data['mem_idx'], '', 'Y', $set_color_list2, 'typelist', $data['mem_idx']);                
?>
				<tr>
					<td>
						<div class="left"><?=$charge_str?></div>
					</td>
					<td><img src="bizstory/images/icon/<?=$dir_view;?>.gif" alt="<?=$dir_view;?>" class="pointer" onclick="<?=$btn_view;?>" val="<?=$auth_data['dir_view']?>" /></td>
					<td><img src="bizstory/images/icon/<?=$dir_read;?>.gif" alt="<?=$dir_read;?>" class="pointer" onclick="<?=$btn_read;?>" val="<?=$auth_data['dir_read']?>" /></td>
					<td><img src="bizstory/images/icon/<?=$dir_write;?>.gif" alt="<?=$dir_write;?>" class="pointer" onclick="<?=$btn_write;?>" val="<?=$auth_data['dir_write']?>" /></td>
					<td><img src="bizstory/images/icon/<?=$dir_delete;?>.gif" alt="<?=$dir_delete;?>" class="pointer" onclick="<?=$btn_delete;?>" val="<?=$auth_data['dir_delete']?>" /></td>
				</tr>
<?
				$i++;
			}
		}
	}
?>
			</tbody>
			</table>

		</form>
	</div>
</div>