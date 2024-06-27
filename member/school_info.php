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



// 이메일중복확인 함수
	function view()
	{
		global $str;

		$sc_code = $_POST['sc_code'];
		$org_code = $_POST['org_code'];
		$schul_knd_sc_nm = $_POST['schul_knd_sc_nm'];	
		$sc_code = string_input($sc_code);
		
		if ($sc_code == '')
		{
			$str['success_chk'] = 'N';
			$str['error_string'] = '도교육청을 선택하세요.';
		}
		else
		{
			// 지원청
			$where = " and sch.JU_ORG_NM!='교육부' AND sch.SD_SCHUL_CODE != 0";
			$where .= $sc_code ? " and sch.ATPT_OFCDC_SC_CODE = '" . $sc_code . "'" : "";

			$org_where = $where;
			$org_where .= " and sch.ATPT_OFCDC_SC_CODE != sch.JU_ORG_NM";			
			$query_string = "select DISTINCT SUBSTR(sch.SD_SCHUL_CODE, 1, 4) as ORG_CODE, sch.JU_ORG_NM from school_info sch where 1 " . $org_where. " order by sch.SD_SCHUL_CODE";
			$data_sql['query_string'] = $query_string;
			$org_data = query_list($data_sql);

			$i=0;
			foreach($org_data as $key => $org_info)
			{
				if(is_array($org_info))	
				{
					$org_list[$i]['org_code'] = $org_info['ORG_CODE'];
					$org_list[$i]['ogr_name'] = $org_info['JU_ORG_NM'];
					$i++;
				}					
			}

			$sch_where = $where;
			$sch_where .= $org_code ? " and SUBSTR(sch.SD_SCHUL_CODE, 1, 4) = '" . $org_code . "'" : "";
			$sch_where .= $schul_knd_sc_nm ? " and sch.SCHUL_KND_SC_NM = '" . $schul_knd_sc_nm . "'" : "";
			$sch_data = school_info_data('list', $sch_where, 'sch.SCHUL_NM');
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
						$sch_list[$i]['sc_code'] = $sch_info['ATPT_OFCDC_SC_CODE'];
						$sch_list[$i]['sc_name'] = $sch_info['ATPT_OFCDC_SC_NM'];
						$sch_list[$i]['org_code'] = substr($sch_info['SD_SCHUL_CODE'], 0, 4);
						$sch_list[$i]['ogr_name'] = $sch_info['JU_ORG_NM'];
						$sch_list[$i]['schul_code'] = $sch_info['SD_SCHUL_CODE'];
						$sch_list[$i]['schul_name'] = $sch_info['SCHUL_NM'];
						$sch_list[$i]['tel_num'] = $sch_info['ORG_TELNO'];
						$sch_list[$i]['fax_num'] = $sch_info['ORG_FAXNO'];
						$sch_list[$i]['zip_code'] = $sch_info['ORG_RDNZC'];
						$sch_list[$i]['address1'] = $sch_info['ORG_RDNMA'];
						$sch_list[$i]['address2'] = str_replace('/', '', $sch_info['ORG_RDNDA']);
						$sch_list[$i]['home_page'] = $sch_info['HMPG_ADRES'];
						$i++;
					}					
				}
				
				$str['success_chk'] = 'Y';
				$str['error_string'] = '';
				$str['data'] = array($org_list, $sch_list);
			}
		}
		echo json_encode($str, JSON_UNESCAPED_UNICODE);
		exit;
	}


?>