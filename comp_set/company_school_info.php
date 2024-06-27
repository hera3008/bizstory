<?
	header("Content-Type: application/json; charset=utf-8");

	include "../common/setting.php";
	//include "../common/no_direct.php";
	
	$str['success_chk'] = 'N';
	$str['error_string'] = '';

	if($sub_type == "")
	{
		$str['success_chk'] = 'N';
		$str['error_string'] = 'sub_type 명이 필요합니다.';

		echo json_encode($str, JSON_UNESCAPED_UNICODE);
		exit;
	}
	
	if(!function_exists($sub_type))
	{
		$str['success_chk'] = 'N';
		$str['error_string'] = 'ssub_type method 가 없습니다.';

		echo json_encode($str, JSON_UNESCAPED_UNICODE);		
		exit;
	}
	call_user_func($sub_type);
	exit;



// 학교검색
	function view()
	{
		global $str;

		$sc_code = $_POST['sc_code'];
		$search_text = $_POST['search_text'];
		

        $sch_where = " and comp.schul_name like '%{$search_text}%'";
        $sch_where .= $sc_code ? " and comp.sc_code = '" . $sc_code . "'" : "";
        $sch_data = company_info_data("list", $sch_where);
       //print_r($sch_data);
        if ($sch_data['total_num'] == 0)
        {
            $str['success_chk'] = 'N';
            $str['error_string'] = '검색된 학교가 없습니다.';
        }
        else
        {	
            $i=0;
            foreach($sch_data as $key => $sch_info)
            {
                if(is_array($sch_info))
                {
                    $sch_list[$i]['comp_idx'] = $sch_info['comp_idx'];
                    $sch_list[$i]['sc_code'] = $sch_info['sc_code'];
                    $sch_list[$i]['sc_name'] = $sch_info['sc_name'];
                    $sch_list[$i]['org_code'] = $sch_info['sc_code'];
                    $sch_list[$i]['ogr_name'] = $sch_info['ogr_name'];
                    $sch_list[$i]['schul_code'] = $sch_info['schul_code'];
                    $sch_list[$i]['schul_name'] = $sch_info['schul_name'];
                    $sch_list[$i]['tel_num'] = $sch_info['tel_num'];
                    $sch_list[$i]['fax_num'] = $sch_info['fax_num'];
                    $sch_list[$i]['zip_code'] = $sch_info['fax_num'];
                    $sch_list[$i]['address'] = $sch_info['address'];
                    $i++;
                }					
            }

		}

		echo json_encode($sch_list, JSON_UNESCAPED_UNICODE);
		exit;
	}


?>