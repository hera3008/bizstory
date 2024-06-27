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
    $mem_idx = $_POST['mem_idx'];

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
				<ul id="type_folder_navi">
					<li>
						<table class="typetable">
						<colgroup>
							<col />
							<col width="80px" />
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
								<th>삭제</th>
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
					if ($info_data['up_fi_idx'] == '') $next_up = $info_data['fi_idx'];
					else $next_up = $info_data['up_fi_idx'] . ',' . $info_data['fi_idx'];

				// 하위메뉴
					$down_where = $common_where . " and fi.dir_depth = '" . $next_depth . "' and concat(',', fi.up_fi_idx, ',') like '%," . $next_up . ",%'";
					$down_menu = filecenter_info_data('view', $down_where);

					$chk_up_idx = $info_data['up_fi_idx'];
					$chk_up_arr = explode(',', $chk_up_idx);
					foreach ($chk_up_arr as $chk_up_k => $chk_up_v)
					{
						if ($chk_up_k == 0) $chk_up = $chk_up_v;
						else $chk_up .= '_' . $chk_up_v;
					}
					if ($chk_up == '') $li_id_str = 'authleft_' . $sort;
					else $li_id_str = 'authleft_' . $chk_up . '_' . $sort;
					$left_str = str_replace('[ui_id_str]', 'authsubmenu_' . $chk_up, $left_str);

					$icon_img = '';
					$write_auth_yn = 'Y';
					if ($info_data['dir_depth'] == 1)
					{
						if ($file_name == 'Project') $icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_p.png" alt="Porject" title="Porject" /> ';
						else $icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_v.png" alt="V-Center" title="V-Center" /> ';
					}
				// V-Drive/Member
					if ($info_data['dir_depth'] == 2 && $info_data['file_path'] == '/V-Drive' && $file_name == 'Member' && $info_data['set_type'] == 'fix')
					{
						$icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_m.png" alt="Member" title="Member" /> ';
						$write_auth_yn = 'N';
					}
				// Project/Project_code/Member
					$project_dir = '/Project/' . $info_data['project_code'];
					if ($info_data['dir_depth'] == 3 && $info_data['file_path'] == $project_dir && $file_name == 'Member' && $info_data['set_type'] == 'fix')
					{
						$icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_m.png" alt="Member" title="Member" /> ';
						$write_auth_yn = 'N';
					}

				// 권한확인
					$sub_where = " and fa.comp_idx = '" . $comp_idx . "' and fa.mem_idx = '" . $mem_idx . "' and fa.fi_idx = '" . $info_data["fi_idx"] . "'";
					$auth_data = filecenter_auth_data('view', $sub_where);
					if ($auth_data['dir_view'] == '1') $dir_view = 'Y'; else $dir_view = 'N';
					if ($auth_data['dir_read'] == '1') $dir_read = 'Y'; else $dir_read = 'N';
					if ($auth_data['dir_write'] == '1') $dir_write = 'Y'; else $dir_write = 'N';
                    if ($auth_data['dir_delete'] == '1') $dir_delete = 'Y'; else $dir_delete = 'N';

					if ($auth_menu['mod'] == "Y")
					{
						$btn_view  = "check_mem_auth('dir_view', '" . $info_data["fi_idx"] . "', '" . $auth_data['dir_view'] . "')";
						$btn_read  = "check_mem_auth('dir_read', '" . $info_data["fi_idx"] . "', '" . $auth_data['dir_read'] . "')";
						$btn_write = "check_mem_auth('dir_write', '" . $info_data["fi_idx"] . "', '" . $auth_data['dir_write'] . "')";
                        $btn_delete = "check_mem_auth('dir_delete', '" . $info_data["fi_idx"] . "', '" . $auth_data['dir_delete'] . "')";
					}
					else
					{
						$btn_view  = "check_auth_popup('modify')";
						$btn_read  = "check_auth_popup('modify')";
						$btn_write = "check_auth_popup('modify')";
                        $btn_delete= "check_auth_popup('modify')";
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
						<table class="typetable">
						<colgroup>
							<col />
							<col width="70px" />
							<col width="70px" />
							<col width="70px" />
							<col width="70px" />
						</colgroup>
						<tbody>
							<tr>
								<td class="left"><span><strong></strong>&nbsp;<a href="javascript:void(0)"> ' . $icon_img . $file_name . '</a></span></td>
								<td><img src="' . $local_dir . '/bizstory/images/icon/' . $dir_view . '.gif" alt="' . $dir_view . '" class="pointer" onclick="' . $btn_view . '" /></td>
								<td><img src="' . $local_dir . '/bizstory/images/icon/' . $dir_read . '.gif" alt="' . $dir_read . '" class="pointer" onclick="' . $btn_read . '" /></td>';

				// Member 일 경우
					if ($write_auth_yn == 'N')
					{
						$left_str .= '
								<td>&nbsp;</td>
                                <td>&nbsp;</td>';
					}
					else
					{
						$left_str .= '
								<td><img src="' . $local_dir . '/bizstory/images/icon/' . $dir_write . '.gif" alt="' . $dir_write . '" class="pointer" onclick="' . $btn_write . '" /></td>
                                <td><img src="' . $local_dir . '/bizstory/images/icon/' . $dir_delete . '.gif" alt="' . $dir_delete . '" class="pointer" onclick="' . $btn_delete . '" /></td>';
					}

					$left_str .= '
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

<script src="<?=$local_dir;?>/common/js/fileTree/jquery_.js?t=<?=time()?>" type="text/javascript"></script>
<script src="<?=$local_dir;?>/common/js/fileTree/jquery.easing.js?t=<?=time()?>" type="text/javascript"></script>
<script src="<?=$local_dir;?>/common/js/fileTree/jqueryFileAuthTree.js?t=<?=time()?>" type="text/javascript"></script>
<link href="<?=$local_dir;?>/common/js/fileTree/jqueryFileTree.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
    var E = $.noConflict();
    E(document).ready( function() {
        auth_folder_list('N');
    });
    
    function auth_folder_list(pro_end) {
        
        $("#label_complete").show();
        $("#folder_tab").removeClass('btn_big_green').addClass("btn_big_blue");
        $("#auth_tab").removeClass('btn_big_blue').addClass("btn_big_green");
        
        E('#auth_folder_list').fileTree({ script: '<?=$local_dir?>/bizstory/filecenter/ajax/staff_auth_folder_list.php' , params : '<?=$code_comp?>||<?=$code_part?>||<?=$mem_idx?>||<?=$set_part_yn?>||1||||' + pro_end, folderEvent: 'click', expandSpeed: 750, collapseSpeed: 750, expandEasing: 'easeOutBounce', collapseEasing: 'easeOutBounce', loadMessage: 'Un momento...' }, function(file) { 
        });
    }
    
    function auth_entrust() {
        
        $("#label_complete").hide();
        $("#folder_tab").removeClass('btn_big_blue').addClass("btn_big_green");
        $("#auth_tab").removeClass('btn_big_green').addClass("btn_big_blue");
        
        $.ajax({
           type: 'post', 
           url: '<?=$local_dir?>/bizstory/filecenter/staff_auth_entrust.php',
           data: $("#postform").serialize(),
           dataType: 'html',
           success: function(html_data) {
               //alert(html_data);
               $("#auth_folder_list").html(html_data);
           } 
        });
    }
    
    function check_end_project() {
        var obj_value = ($("#chk_complete").is(":checked") ? "Y" : "N");
        auth_folder_list(obj_value);
    }
    
    function check_search() {
        
        if ($.trim($("#search_text").val()) == "") {
            check_auth_popup("검색어를 입력해 주십시오.");
        } else {
        
            $.ajax({
               type: 'post', 
               url: '<?=$local_dir?>/bizstory/filecenter/ajax/staff_auth_folder_search_list.php',
               data: $("#postform").serialize(),
               dataType: 'html',
               success: function(html_data) {
                   //alert(html_data);
                   $("#auth_folder_list").html(html_data);
               }
            });
        }
        
        return false;
    }
    
</script>

<div class="ajax_write">
	<div class="upload_title">
		<strong><?=$mem_data['mem_name'];?>님 <?=$page_menu_name;?></strong> <?=$form_title;?>
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>
	<div class="info_text">
		<ul>
			<li>권한설정시 해당되는 상위폴더권한이 설정이 되어 있지 않으면 자동으로 설정이 됩니다.</li>
			<li>/Project/Project_code/Member 는 보기, 읽기만 가능합니다.</li>
			<li>/V-Drive/Member 는 보기, 읽기만 가능합니다.</li>
		</ul>
	</div>

	<div class="ajax_frame">
	    <form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_search()">
    
        <?=$form_all;?>
        <input type="hidden" id="post_comp_idx" name="comp_idx" value="<?=$code_comp;?>" />
        <input type="hidden" id="post_part_idx" name="part_idx" value="<?=$code_part;?>" />
        <input type="hidden" id="post_mem_idx"  name="mem_idx"  value="<?=$mem_idx;?>" />
        <input type="hidden" name="source_mem_idx" id="source_mem_idx" value="<?=$mem_idx;?>" />
        <input type="hidden" name="target_mem_idx" id="target_mem_idx" />
	    
	    <a href="javascript:void(0);" onclick="auth_folder_list('N')" class="btn_big_green" id="folder_tab"><span>폴더별설정</span></a>
	    <a href="javascript:void(0);" onclick="auth_entrust()" class="btn_big_green" id="auth_tab"><span>사용자권한위임</span></a>
	    <div class="tablewrapper">
    <div id="tableheader">
        <div class="search view_result" id="label_complete">
            <p>검&nbsp;&nbsp;색</p>
                <span>
                <label for="chk_complete">
                    <input type="checkbox" id="chk_complete" name="chk_complete" value="Y" onclick="check_end_project()" />완료 프로젝트 보기
                </label>
                </span>

            <!--<div class="s_area">-->

                <select id="search_swhere" name="swhere" title="<?=$search_column;?>">
                    <option value="project_code">프로젝트코드</option>
                    <option value="subject">프로젝트명</option>
                    <option value="folder">폴더명</option>
                </select>
                <input type="text" id="search_text" name="search_text" class="type_text" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />

            <!--
                <select id="search_sdatetype" name="sdatetype" title="기간을 선택하세요." onchange="search_date_chk(this.value)">
                    <option value="0"<?=selected($sdatetype, '0');?>>기간전체</option>
                    <option value="1"<?=selected($sdatetype, '1');?>>1주일</option>
                    <option value="2"<?=selected($sdatetype, '2');?>>1개월</option>
                    <option value="3"<?=selected($sdatetype, '3');?>>3개월</option>
                    <option value="4"<?=selected($sdatetype, '4');?>>6개월</option>
                    <option value="99"<?=selected($sdatetype, '99');?>>직접입력</option>
                </select>
                <input type="text" id="search_sdatestart" name="sdatestart" class="type_text" title="시작일을 입력하세요." />
                ~
                <input type="text" id="search_sdateend" name="sdateend" class="type_text" title="종료일을 입력하세요." />
                <span class="txt">(예: 2013-01-01)</span>
            -->
                <a href="javascript:void(0);" class="btn_sml" onclick="check_search()"><span>검색</span></a>
            <!--</div>-->
        </div>
    </div>
    </div>
            
			<ul id="auth_folder_list"></ul>
	<?
		//$folder_str = filecenter_staff_folder($code_comp, $code_part, $mem_idx, $set_part_yn, 1, '', '');
		//echo $folder_str;
	?>
		</form>
	</div>
</div>
<?
// 펼치기 위해서
	$info_where = " and fi.fi_idx = '" . $up_idx . "'";
	$info_data = filecenter_info_data('view', $info_where);
	$navi_up = $info_data['up_fi_idx'];
	$navi_up_arr = explode(',', $navi_up);
	foreach ($navi_up_arr as $navi_up_k => $navi_up_v)
	{
		if ($navi_up_k == 0) $chk_up = $navi_up_v;
		else $chk_up .= '_' . $navi_up_v;
	}
?>

<script type="text/javascript">

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
</script>

<?
	}
?>