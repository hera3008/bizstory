<?
/*
	생성 : 2013.01.29
	수정 : 2013.04.23
	위치 : 파일센터 > 파일관리- 목록
*/
    require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";
	
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	if ($up_level == '') $up_level = 1;

	if ($pro_end == '')
	{
		$pro_end      = 'N';
		$send_pro_end = 'N';
		$recv_pro_end = 'N';
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;pro_end=' . $send_pro_end;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
		<input type="hidden" name="pro_end" value="' . $send_pro_end . '" />
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
	$path_data     = filecenter_folder_path($up_idx); // 현위치
	$dir_auth_page = filecenter_auth_folder($up_idx); // 권한확인 - 현위치

	$first_name = $path_data['path_up_name'][1];
    
	$form_chk = 'N';
	if ($dir_auth_page['dir_view_auth'] == 'Y') // 목록권한
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($form_chk == 'Y')
	{
		if ($first_name == 'Project') // 프로젝트일 경우 - 지사구분이 없음
		{
			if ($pro_end == 'N') $status_where .= " and 1 = (case when fi.menu_code is null then ifnull(pro.pro_status, 'PS01') != 'PS90' else 1 end ) ";

            $list_query['query_column'] = "
                    fi.*
                    , (select count(*) from filecenter_history where fi_idx=fi.fi_idx and part_idx=fi.part_idx and comp_idx=fi.comp_idx and dir_file='file' and del_yn='0') cnt
                    , fa.dir_view, fa.dir_read, fa.dir_write, fa.dir_delete
            ";
            
			$list_query['query_string'] = "
				from
					filecenter_info fi
					left join filecenter_auth fa on fa.del_yn = 'N' and fa.comp_idx = fi.comp_idx and fa.mem_idx = '" . $code_mem . "' and fa.fi_idx = fi.fi_idx
					left join project_info pro on pro.del_yn = 'N' and pro.pro_idx = fi.pro_idx
				where
					fi.del_yn = 'N'
					and fi.comp_idx = '" . $code_comp . "'
					and fi.dir_depth = '" . $up_level . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'
					and (if (fi.dir_file = 'file', 1, fa.dir_view) = '1'
						or if (fi.dir_file = 'file', 1, fa.dir_read) = '1'
						or if (fi.dir_file = 'file', 1, fa.dir_write) = '1')
					" . $status_where . "
				order by
					fi.dir_file desc, fi.file_path asc, fi.file_name asc
			";
			//echo $list_query['query_string'];
			
			$list_query['page_num']  = $page_num;
			$list_query['page_size'] = $page_size;
			$list = query_pagelist($list_query);
	
		}
		else
		{
		    $list_query['query_column'] = "
		              fi.*
                    , (select count(*) from filecenter_history where fi_idx=fi.fi_idx and part_idx=fi.part_idx and comp_idx=fi.comp_idx and dir_file='file' and reg_type in ('insert', 'update', 'file_update', 'move') and del_yn='0') cnt
                    , fa.dir_view, fa.dir_read, fa.dir_write, fa.dir_delete
		      ";
			$list_query['query_string'] = "					
				from
					filecenter_info fi
					left join filecenter_auth fa on fa.del_yn = 'N' and fa.comp_idx = fi.comp_idx and fa.mem_idx = '" . $code_mem . "' and fa.fi_idx = fi.fi_idx
				where
					fi.del_yn = 'N'
					and fi.comp_idx = '" . $code_comp . "' and fi.part_idx = '" . $code_part . "'
					and fi.dir_depth = '" . $up_level . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'
					and (if (fi.dir_file = 'file', 1, fa.dir_view) = '1'
						or if (fi.dir_file = 'file', 1, fa.dir_read) = '1'
						or if (fi.dir_file = 'file', 1, fa.dir_write) = '1')
				order by
					fi.dir_file desc, fi.file_path asc, fi.file_name asc
			";
			//echo $list_query['query_string'];
			$list_query['page_num']  = $page_num;
			$list_query['page_size'] = $page_size;
			$list = query_pagelist($list_query);
		}

?>
<div class="details">
	<span>현위치 : <?=$path_data['navi_path'];?></span>
	<div class="data_left">
	<?
		if ($up_level > 1)
		{
			$path_up_idx   = $path_data['path_up_idx'];
			$path_up_level = $path_data['path_up_level'];
			$chk_up_level  = $up_level - 2;

		// 폴더수구하기
			$chk_query = "
				select
					count(fi.fi_idx) as file_num
				from
					filecenter_info fi
					left join filecenter_auth fa on fa.del_yn = 'N' and fa.comp_idx = fi.comp_idx and fa.mem_idx = '" . $code_mem . "' and fa.fi_idx = fi.fi_idx
					left join project_info pro on pro.del_yn = 'N' and pro.pro_idx = fi.pro_idx
				where
					fi.del_yn = 'N'
					and fi.comp_idx = '" . $code_comp . "' and fi.dir_file = 'folder'
					and fi.dir_depth = '" . $up_level . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'
					and (fa.dir_view = '1' or fa.dir_read = '1' or fa.dir_write = '1')
			";
			//echo $chk_query;
			
			//echo "<br>";
			//$chk_data = query_view($chk_query);
			$up_folder_num = $chk_data['file_num'];

		// 파일수구하기
			$chk_query = "
				select
					count(fi.fi_idx) as file_num, sum(fi.file_size) as file_size
				from
					filecenter_info fi
					left join filecenter_auth fa on fa.del_yn = 'N' and fa.comp_idx = fi.comp_idx and fa.mem_idx = '" . $code_mem . "' and fa.fi_idx = fi.fi_idx
				where
					fi.del_yn = 'N'
					and fi.comp_idx = '" . $code_comp . "' and fi.dir_file = 'file'
					and fi.dir_depth = '" . $up_level . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'
			";
			
			//$chk_data = query_view($chk_query);
			$up_file_num  = $chk_data['file_num'];
			if ($chk_data['file_size'] > 0)
			{
				$up_file_size = ' (' . byte_replace1($chk_data['file_size']) . ')';
			}
	?>
		<div class="btn_default_dark pointer" onclick="file_list_move('<?=$path_up_idx[$chk_up_level];?>', '<?=$path_up_level[$chk_up_level];?>')"><span>상위폴더</span></div>
		<span class="ico01" id="folder_count">폴더 : <?=number_format($up_folder_num);?>개</span>
		<span class="ico02" id="file_count">파일 : <?=number_format($up_file_num);?>개<?=$up_file_size;?></span>
	<?
		}
	?>
	</div>
	<div class="etc_bottom">
		<?=$dir_auth_page['btn_sel_down'];?>
		<?=$dir_auth_page['btn_folder2'];?>
		<?=$dir_auth_page['btn_folder1'];?>
		<?=$dir_auth_page['btn_file_copy'];?>
		<?=$dir_auth_page['btn_file_move'];?>
		<?=$dir_auth_page['btn_file'];?>
		<?=$dir_auth_page['btn_sel_del'];?>
	</div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col />
		<col width="70px" />
		<col width="65px" />
		<col width="75px" />
		<col width="65px" />
		<col width="45px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="fiidx" onclick="check_all('fiidx', this);" /></th>
			<th class="nosort"><h3>이름</h3></th>
			<th class="nosort"><h3>크기</h3></th>
			<th class="nosort"><h3>종류</h3></th>
			<th class="nosort"><h3>올린날짜</h3></th>
			<th class="nosort"><h3>올린사람</h3></th>
			<th class="nosort"><h3>관리</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 1;
	$num = $list["total_num"];
    $folder_cnt = 0;
    $file_cnt = 0;
    $total_size = 0;
    
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
			$fi_idx     = $data['fi_idx'];
			$next_depth = $data['dir_depth'] + 1;

			if ($data['dir_file'] == 'folder') // 폴더일 경우
			{
			    $folder_cnt++;
				$checkbox_html = '';
				$icon_img   = '<img src="' . $local_dir . '/common/images/icon/folder3.png" alt="folder" /> ';
				$file_size  = '';
				$file_type  = 'folder';
				$file_url   = '<a href="javascript:void(0)" onclick="file_list_move(\'' . $data['fi_idx'] . '\', \'' . $next_depth . '\')">' . $data["file_name"] . '</a>';

				if ($data['dir_depth'] == 1)
				{
					if ($data['file_name'] == 'Project') $icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_p.png" alt="Porject" title="Porject" /> ';
					else $icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_v.png" alt="V-Center" title="V-Center" /> ';
				}
				else
				{
					if ($data['set_type'] == 'fix' && $data['file_name'] == 'Member')
					{
						$icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_m.png" alt="Member" title="Member" /> ';
					}
					else if ($data['set_type'] == 'fix' && $data['file_name'] == 'Work')
					{
						$icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_w2.png" alt="Work" title="Work" /> ';
					}
				}

				$chk_level = $up_level - 1;
				$chk_type  = $path_data['path_set_type'][$chk_level];
				$chk_name  = $path_data['path_up_name'][$chk_level];
				if (($chk_name == 'Member' && $chk_type == 'fix') || ($chk_name == 'Work' && $chk_type == 'fix'))
				{
					$mem_where = " and mem.mem_idx = '" . $data["file_sname"] . "'";
					$mem_data = member_info_data('view', $mem_where);
					if ($mem_data['total_num'] > 0)
					{
						$mem_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span> (' . $mem_data['group_name'] . ')] : ' . $data['file_name'] . '</span>';

						$file_url = '<a href="javascript:void(0)" onclick="file_list_move(\'' . $data['fi_idx'] . '\', \'' . $next_depth . '\')">' . $mem_name . '</a>';
					}
				}
			}
			else
			{
			    $file_cnt++;
				$checkbox_html = '<input type="checkbox" id="fiidx_' . $i . '" name="chk_fi_idx[]" value="' . $data['fi_idx'] . '" title="선택" />';
				$icon_img   = file_ext_img($data['file_ext']) . ' ';
				$file_size  = byte_replace($data['file_size']);
				$file_type  = $data["file_ext"];
                
                $total_size += $data['file_size'];

				if ($dir_auth_page['dir_read_auth'] == 'Y' || $dir_auth_page['dir_write_auth'] == 'Y')
				{
					$file_url = '<a href="' . $set_filecneter_url . '/file_download.php?idx=' . $data['fi_idx'] . '&amp;idx2=' . $code_mem . '" title="' . $data['file_name'] . ' 다운로드">' . $data['file_name'] . '</a>';
				}
				else $file_url = $data['file_name'];
			}

			$dir_auth = filecenter_auth($fi_idx, $data, $dir_auth_page['dir_view_auth'], $dir_auth_page['dir_read_auth'], $dir_auth_page['dir_write_auth']); // 권한
			$charge_str = staff_layer_form($data['reg_id'], '', 'N', $set_color_list2, 'fileliststtaff', $data['fi_idx'], '');
?>
		<tr>
			<td><?=$checkbox_html;?></td>
			<td><div class="left"><?=$icon_img;?><?=$file_url;?>
			    <?=$dir_auth['btn_empowerment'];?>
				<?=$dir_auth['btn_preview'];?>
				<?=$dir_auth['btn_history'];?></div></td>
			<td><div class="right"><span class="eng"><?=$file_size;?></span></div></td>
			<td><span class="eng"><?=$file_type;?></span></td>
			<td><span class="eng"><?=date('Y-m-d', $data["reg_date"]);?></span></td>
			<td><?=$charge_str;?></td>
			<td>
				<?=$dir_auth['btn_modify'];?><br />
				<?=$dir_auth['btn_delete'];?>
			</td>
		</tr>
<?
			$i++;
			$num--;
		}
	}
	if ($list["total_num"] == 0)
	{
?>
		<tr>
			<td colspan="7">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
?>
	</tbody>
</table>
<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />
<hr />

<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>
<hr />
<br /><br /><br /><br />
<?
		if ($dir_auth_page['btn_sel_down'] != '') // 다운로드권한을 가지고 있을 경우
		{
?>
<link rel="stylesheet" type="text/css" href="/bizstory/filecenter/biz/download/css/chxdownload.css" />
<script type="text/javascript" src="/bizstory/filecenter/biz/download/chxdownload_js.php"></script>
<script type="text/javascript">
    var downloader = null;
    
    function DownloadFinished (files) {
        var num = files.length;

        var el = downloader.downloadWrapper.getElementsByTagName('INPUT'), i;
        for (i = el.length-1; i >= 0; i--) {
            downloader.downloadWrapper.removeChild(el[i].parentNode.parentNode);
        }
        downloader.downloadingTotalFileSize.innerHTML = '(0 Bytes)';
        downloader.clear();
        $('.chxdownload_wrapper').hide();
    }
    
    $(function() {
        downloader = new CHXDownload();
        downloader.callme = DownloadFinished;  // 전송이 끝나면 호출될 함수
        downloader.run();
    });
        
    function select_download() {
        $('.chxdownload_wrapper').show();
        var checkbox = document.querySelectorAll('input[name="chk_fi_idx[]"]');
        var otherFiles = [];

        [].filter.call(checkbox, function (el) {
            if (el.checked) {
                otherFiles.push(el.value);
            }
        });
        downloader.setOtherFiles(otherFiles);
    }
</script>
<?
		}
	}

	db_close();
?>
<script>
    function setFolderInfo(folderCnt, fileCnt, totalSize) {
        $('#folder_count').html('폴더 : ' + folderCnt + '개');
        
        var fileInfo = '파일 : ' + fileCnt + '개';
        
        if (totalSize > 0) {        
            fileInfo += ' (' + byteFormat(totalSize) + ')';
        }
        
        $('#file_count').html(fileInfo);
    }
    
    function byteFormat(bytes) {
        
        bytes = parseInt(bytes);
        
        var s = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB'];
        
        var e = Math.floor(Math.log(bytes) / Math.log(1024));
        
        if (e == "-Infinity") {
            return "0 " + s[0];
        } else {
            return (bytes / Math.pow(1024, Math.floor(e))).toFixed(2) + " " + s[e];
        }
    }
    
    setFolderInfo(<?=$folder_cnt?>, <?=$file_cnt?>, <?=$total_size?>);
</script>

<!-- 다운로드 인터페이스 -->
<div class="chxdownload_wrapper" style="display:none">
    <div class="chxdownload_filelist" id="IdDownloadWrapper">
        <div id="IdDownloadFileList">
            <div class="chxdownload_filename">이름</div>
            <div class="chxdownload_filesize">크기</div>
            <div class="chxdownload_status">상태</div>
        </div>
    </div>
    <div class="chxdownload_button">
        <span class="chxdownload_remove_button" id="IdRemoveFileButton"></span>
    </div>
    <div>
        <span class="progress_label">파일</span>
        <progress max="100" value="0" id="IdProgressBar" class="progress_bar"></progress>
        <label id="IdProgressBarLabel" class="progress_label" style="display: inline-block; width: 35px">0%</label>
        <label id="IdDownloadingFileSize" class="progress_label">(0 Bytes)</label>
    </div>
    <div>
        <span class="progress_label">전체</span>
        <progress max="100" value="0" id="IdTotalProgressBar" class="progress_bar"></progress>
        <label id="IdTotalProgressBarLabel" class="progress_label" style="display: inline-block; width: 35px">0%</label>
        <label id="IdDownloadingTotalFileSize" class="progress_label">(0 Bytes)</label>
    </div>
</div>
