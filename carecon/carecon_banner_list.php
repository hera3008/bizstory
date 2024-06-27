<?
/*
    위치 : 설정관리 > 접수관리 > 에이전트관리 > 배너관리 - 목록
	생성 : 2012.07.02
	수정 : 2024. 04. 03 김소령 케어콘으로 명칭 변경 디자인 변경
*/
	//require_once "../common/setting.php";
	//require_once "../common/no_direct.php";
	//require_once "../common/member_chk.php";
    echo 'ddd';
  

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	//$code_part = search_company_part($code_part);
    $code_part = "";

    $where = " and comp.comp_idx = '" . $code_comp . "'";
	$comp_set_data = company_set_data("view", $where);
	$set_banner_cnt = $comp_set_data['banner_cnt'];
   
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

	$where = " and cbn.comp_idx = '" . $code_comp . "' and cbn.part_idx = '" . $code_part . "'";
	$list = carecon_banner_data('list', $where, '', '', '');

/*
	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		if ($set_banner_cnt <= $list['total_num'])
		{
			$btn_write = '<a href="javascript:void(0);" onclick="check_auth_popup(\'더이상 배너를 등록할 수 없습니다.<br />최대 ' . $set_banner_cnt . '개까지 가능합니다.\')" class="btn_big_green"><span>배너등록</span></a>';
		}
		else
		{
			$btn_write = '<a href="javascript:void(0);" onclick="popupform_open(\'\')" class="btn_big_green"><span>등록</span></a>';
		}
	}
*/
?>
										<div class="d-flex flex-stack flex-wrap mb-4">
                                            <div class="d-flex align-items-center py-1">
                                                <!--h6 class="fs-6 mt-3 text-primary">(주)유비스토리</h6-->
                                            </div>
                                            <div class="d-flex align-items-center py-1">
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-idx="" data-bs-toggle="modal" data-bs-target="#kt_modal_banner">
                                                    <i class="ki-outline ki-pencil fs-6"></i> 등록</button>
                                            </div>
                                        </div>

                                        <form id="form-position" name="form-position" method="post" action="#">
                                            <table class="table align-middle table-row-bordered ls-n2 fs-7 text-gray-700 gy-3 gx-0" id="kt_table">
                                                <thead>
                                                    <tr class="text-gray-900 fw-semibold text-uppercase bg-gray-100 border-top-2 border-secondary">
                                                        <th class="w-60px text-center">순서</th>
                                                        <th class="w-md-250px w-300px text-center">이미지</th>
                                                        <th class="text-center d-none d-lg-table-cell">제목</th>
                                                        <th class="w-300px text-center d-none d-xxl-table-cell">링크</th>
                                                        <th class="w-70px text-center">보기</th>
                                                        <th class="w-125px text-center">관리</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center">
                                                <?
                                                    $i = 0;
                                                    if ($list["total_num"] == 0) {
                                                ?>
                                                    <tr>
                                                        <td colspan="5">등록된 데이타가 없습니다.</td>
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
                                                                $sort_data = query_view("
                                                                    select min(sort) as min_sort, max(sort) as max_sort
                                                                    from agent_banner
                                                                    where del_yn = 'N' and comp_idx ='" . $data['comp_idx'] . "' and part_idx ='" . $data['part_idx'] . "'");

                                                                if ($auth_menu['mod'] == "Y")
                                                                {
                                                                    $btn_up     = "check_code_data('sort_up', '', '" . $data['ab_idx'] . "', '')";
                                                                    $btn_down   = "check_code_data('sort_down', '', '" . $data['ab_idx'] . "', '')";
                                                                    $btn_view   = "check_code_data('check_yn', 'view_yn', '" . $data['ab_idx'] . "', '" . $data["view_yn"] . "')";
                                                                    $btn_modify = "popupform_open('" . $data['ab_idx'] . "')";
                                                                }
                                                                else
                                                                {
                                                                    $btn_up     = "check_auth_popup('modify')";
                                                                    $btn_down   = "check_auth_popup('modify')";
                                                                    $btn_modify = "check_auth_popup('modify')";
                                                                }

                                                                if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $data['ab_idx'] . "')";
                                                                else $btn_delete = "check_auth_popup('delete');";

                                                                if ($data["img_sname"] != '')
                                                                {
                                                                    $img_str = '<img src="' . $comp_banner_dir . '/' . $data["img_sname"] . '" alt="' . $data["content"] . '" class="w-100 border" />';
                                                                }
                                                                else
                                                                {
                                                                    $img_str = '';
                                                                }

                                                                if ($data['link_url'] == '') $img_str = $img_str;
                                                                else $img_str = '<a href="http://' . $data['link_url'] . '" target="_blank">' . $img_str . '</a>';
                                                ?>
                                                    <tr>
                                                        <td class="sorter"></td>
                                                        <td class="p-2"><?=$img_str?></td>
                                                        <td class="text-start ps-5 d-none d-lg-table-cell"><?=$data["content"];?></td>
                                                        <td class="text-start ps-5 d-none d-xxl-table-cell">
                                                            <?if($data['link_url']){?>
                                                            <a href="<?=$data['link_url']?>" target="_blank" title="새 창으로 이동"><?=$data['link_url']?></a>
                                                            <?}?>
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-switch form-check-custom form-check-sm d-block form-check-sm">
                                                                <input class="form-check-input w-25px h-15px w-xl-30px h-xl-20px" type="checkbox" id="view_Switch_<?=$data['ab_idx']?>" <?=$data['view_yn'] == 'Y' ? 'checked="checked"' : ""?> onclick="<?=$btn_view;?>" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button" data-bs-toggle="modal" data-bs-target="#kt_modal_banner" class="btn btn-sm btn-primary px-3 py-1 fs-8" onclick="<?=$btn_modify;?>">
                                                                수정
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger px-3 py-1 fs-8" onclick="<?=$btn_delete;?>">
                                                                삭제
                                                            </button>
                                                        </td>
                                                    </tr>
                                                 <?
                                                                $i++;
                                                            }
                                                        }
                                                    }
                                                ?>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </form>

