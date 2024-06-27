<?
/*
	수정 : 2024.03.15
	위치 : 통계 > 접속 통계
*/

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	
	$comp_idx = $code_comp;
	$where = " and comp.comp_idx = '" . $comp_idx . "'";
	$comp_into = company_info_data("view", $where);
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;sclass=' . $send_sclass;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 업체 정보

	$where = " and code.comp_idx = '" . $comp_idx . "'";
	$list = code_receipt_class_data('list', $where, '', '', '');

	$where = " and comp.comp_idx = ".$code_comp;
	$comp_info = company_info_data('view', $where);

	// 분류리스트
	$ci_where = " and receipt_code != '' and import_yn='N' ";
	if($comp_info['sc_code'] && !$comp_info['org_code'] && !$comp_info['schul_code']) 
		$ci_where .= " and (code.sc_code = '" . $comp_info['sc_code'] . "' and  code.org_code = '' and code.schul_code = '')";

	else if($comp_info['org_code'] && $comp_info['org_code'] && !$comp_info['schul_code']) 
		$ci_where .= " and (code.sc_code = '" . $comp_info['sc_code'] . "' or  code.org_code = '" . $comp_info['org_code'] . "') and code.schul_code = ''";

	else if($comp_info['schul_code'] && $comp_info['org_code'] && $comp_info['schul_code']) 
		$ci_where .= " and ( code.sc_code = '" . $comp_info['sc_code'] . "' or  code.org_code = '" . $comp_info['org_code'] . "' or code.schul_code = '" . $comp_info['schul_code'] . "' )";
	
	$receipt_class_data = code_receipt_class_data('list', $ci_where, '', '', '');
	

	
// 초기화
	$statistics_class = $statistics_class ? $statistics_class : 'day'; 
	$sclass = explode('||', $sclass);
	$last_depth = $sclass[1];
	$class = $sclass[0];	
	$mdepth = !$class ? 2 : ($last_depth == 'last' ? strlen($class) : strlen($class)*2); 
	$syear = $syear ? $syear : date('Y');
	$smonth = $smonth ? $smonth : date('m');
	
	if($comp_info['comp_class'] == 1){ 
		$where = " 1 and (";
		if($comp_info['sc_code']) $where .= " sc_code = '" . $comp_info['sc_code'] . "' ";
		if($org_code) $where .= "  or org_code = '" . $org_code . "' ";
		if($schul_code) $where .= "  or schul_code = '" . $schul_code . "' ";
		$where .=")";
		$where .= " and LENGTH(receipt_code) = " . $mdepth;
		$where .= $class ? " and substr(receipt_code, 1, " . strlen($class) . ") = '".$class."'" : "";
		$query_string = "select receipt_code, code_name from code_receipt_class where " . $where ." ORDER BY receipt_code";
		//echo $query_string;
		$data_sql['query_string'] = $query_string;
		$data_info = query_list($data_sql);
	}
	else
	{
	}

// 통계데이타 정보
	if($statistics_class == 'month')
	{	
		$cate = array('01월', '02월', '03월', '04월', '05월', '06월', '07월', '08월', '09월', '10월', '11월', '12월'); 
		/*
		$where = " comp_idx= " .$comp_idx;
		$where .= " and LENGTH(receipt_code) = " . $mdepth;
		$where .= $class ? " and substr(receipt_code, 1, " . strlen($class) . ") = '".$class."'" : "";
		$query_string = "select receipt_code, code_name from code_receipt_class where " . $where ." ORDER BY receipt_code";
		$data_sql['query_string'] = $query_string;
		$data_info = query_list($data_sql);
		*/
		
		foreach($data_info as $val => $info)
		{
			if(is_array($info))
			{
				//$stat_where = "";
				//$stat_where = " comp_idx= " .$comp_idx;
				$stat_where = " 1 and (";
				if($comp_info['sc_code']) $stat_where .= " sc_code = '" . $comp_info['sc_code'] . "' ";
				if($org_code) $stat_where .= "  or org_code = '" . $org_code . "' ";
				if($schul_code) $stat_where .= "  or schul_code = '" . $schul_code . "' ";
				$stat_where .=")";
				$stat_where .= " and receipt_code='" . $info['receipt_code'] . "'";
				$stat_where .= " and reg_yy = '". $syear ."'";
				$query_string = "select * from statistics_total_data where " . $stat_where ." ORDER BY receipt_code";
				$data_sql['query_string'] = $query_string;
				//echo $query_string."<br>";
				
				$stat_info = query_list($data_sql);
				
				$cname = $info['code_name'];
				$basic['name'] = $info['code_name'];
				
				if($stat_info['total_num'] == 0)
				{
					$series[$cname] = array(0,0,0,0,0,0,0,0,0,0,0,0);
				}
				else
				{
					foreach($stat_info as $sta => $data)
					{
						if(is_array($data))
						{
							$series[$cname] = array($data['reg_mm_01'], $data['reg_mm_02'], $data['reg_mm_03'], $data['reg_mm_04'], $data['reg_mm_05'], $data['reg_mm_06'], $data['reg_mm_07'], $data['reg_mm_08'], $data['reg_mm_09'], $data['reg_mm_10'], $data['reg_mm_11'], $data['reg_mm_12']);
						}
					}
				}
				
			}
		}

	}
	else
	{
		foreach($data_info as $val => $info)
		{
			if(is_array($info))
			{
				//$stat_where = "";
				//$stat_where = " comp_idx= " .$comp_idx;

				$stat_where = " 1 and (";
				if($comp_info['sc_code']) $stat_where .= " sc_code = '" . $comp_info['sc_code'] . "' ";
				if($org_code) $stat_where .= "  and org_code = '" . $org_code . "' ";
				if($schul_code) $stat_where .= "  and schul_code = '" . $schul_code . "' ";
				$stat_where .=")";
				$stat_where .= " and  DATE_FORMAT(reg_date, '%Y-%m') = '" . $syear . "-" . $smonth . "'";
				$stat_where .= " and receipt_code='" . $info['receipt_code'] . "'";
				$query_string = "select * from statistics_data where " . $stat_where ." ORDER BY receipt_code";
				$data_sql['query_string'] = $query_string;
				//echo $query_string."<br>";
				
				$stat_info = query_list($data_sql);
				
				$cname = $info['code_name'];
				$basic['name'][] = $info['code_name'];

				
				if($stat_info['total_num'] == 0)
				{
					$basic[$cname] = array_fill(0, $day_count, 0);
				}
				else
				{
					foreach($stat_info as $sta => $data)
					{
						if(is_array($data))
						{
							$rdate = $data['reg_date'];
							$basic[$cname][$rdate] = $data['st_count'];
							
						}
					}

				}
				
			}
		}
		
		//$day_count = date('t', strtotime("{$syear}-{$smonth}-01"));
		$day_count = date('d');

		$series = array();
		foreach($basic['name'] as $key => $name)
		{
			for($ii=1; $ii<=$day_count; $ii++)
			{
				$rdate = $syear . "-" . $smonth . "-" .sprintf('%02d',$ii);
				$cate[$ii] = $smonth . "-" .$ii;

				if($basic[$name][$rdate]) $series[$name][$rdate] = $basic[$name][$rdate];
				else $series[$name][$rdate] = 0;

			}
		}

	}


?>	
					<!-- Content wrapper -->
					<div class="d-flex flex-column flex-column-fluid">
						<!-- Content -->
						<div id="kt_app_content" class="app-content app-content-fit-mobile flex-column-fluid">
							<!-- Content container -->
							<div id="kt_content_container" class="app-container app-container-fit-mobile container-fluid">
								<div class="card card-flush">
									<div class="card-header align-items-center min-h-50px mt-4 mt-lg-5 ls-n2 py-0 px-6 px-lg-8 gap-2 gap-md-4">
										<div class="card-title">
											<h4 class="fs-1">접수통계</h4>
										</div>
										<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
											<ol class="breadcrumb breadcrumb-dot text-muted fs-8 fs-md-7 d-none d-md-inline-flex">
												<li class="breadcrumb-item">홈</li>
												<li class="breadcrumb-item">통계관리</li>
												<li class="breadcrumb-item text-gray-700">접수통계</li>
											</ol>
										</div>
									</div>
									<div class="card-body px-6 px-lg-9 py-2 py-lg-3">



										<!-- 접수통계 -->
										<?php
											// 카테고리
											echo part_cate_tab($code_comp);
										?>

										<form id="searchform" name="searchform" method="get" action="<?=$this_page;?>" class="form">
											<?=$form_default?>
                                            <div class="p-4 border bg-gray-100 rounded-2 mb-6 mb-lg-8">
                                                <div class="row gx-2">
                                                    <div class="col-md-5 mb-3 mb-md-0">
                                                        <div class="form-check-inline">통계구분</div>
                                                        <div class="form-check form-check-custom form-check-sm form-check-inline mt-2">
                                                            <input class="form-check-input" type="radio" name="statistics_class" id="statistics_class_1" value="day" onclick="$(form).submit();" <?=$statistics_class == 'day' ? 'checked="checked"' : ''?>/>
                                                            <label class="form-check-label" for="statistics_class_2">
                                                                일별
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-custom form-check-sm form-check-inline mt-2">
                                                            <input class="form-check-input" type="radio" name="statistics_class" id="statistics_class_2" value="month"  onclick="$(form).submit();" <?=$statistics_class == 'month' ? 'checked="checked"' : ''?>/>
                                                            <label class="form-check-label" for="statistics_class_1">
                                                                월별
                                                            </label>
                                                            
                                                        </div>
                                                    </div>
                                                    

                                                    

                                                    <div class="col-6 col-md-3">
                                                        <select class="form-select form-select-sm" name="sclass" id="search_sclass" data-control="select2" data-hide-search="true" aria-label="분류선택">
                                                            <option value="" >분류선택</option>
                                                            <?
                                                            $depth = 1;
                                                            $last_str='';
                                                            foreach($receipt_class_data as $k => $data)
                                                            {
                                                                    if (is_array($data))
                                                                    {
                                                                        if($depth < $data['menu_depth']) $last_str = 'last';
                                                                        else if($depth > $data['menu_depth']) $last_str = '';

                                                                        $depth = $data['menu_depth'];
                                                                        $emp_str = str_repeat('&nbsp;', 4 * ($data['menu_depth'] - 1));
                                                            ?>
                                                            <option value="<?=$data['receipt_code']?>||<?=$last_str?>" <?=$class == $data['receipt_code'] ? 'selected ': ''?>><?=$emp_str?><?=$data['code_name']?></option>
                                                            <?		}
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-6 col-md-2">
                                                        <select class="form-select form-select-sm" name="syear" id="search_syear" data-control="select2" data-hide-search="true" aria-label="년도 선택">
                                                            <option value="">년도 선택</option>
                                                            <?for($yy=2020; $yy <= date('Y'); $yy++){?>
                                                            <option value="<?=$yy?>" <?=$syear == $yy ? 'selected="selected"':''?>><?=$yy?></option>
                                                            <?}?>
                                                        </select>
                                                    </div>
                                                    <div class="col-6 col-md-2">
                                                        <div class="position-relative d-flex align-items-center pe-14">
                                                            <select class="form-select form-select-sm" name="smonth" id="search_month" data-control="select2" data-hide-search="true" aria-label="월별 선택">
                                                                <option value="">월 선택</option>
                                                                <?for($mm=1; $mm <= 12; $mm++){?>
                                                                <option value="<?=sprintf('%02d', $mm)?>" <?=$smonth == $mm ? 'selected="selected"':''?>><?=$mm?>월</option>
                                                                <?}?>
                                                            </select>
                                                            <button type="submit" class="btn btn-sm btn-icon btn-dark position-absolute end-0 px-6" aria-label="검색"><i class="ki-outline ki-magnifier fs-3"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            
                                            

											
                                            
                                            <div class="p-4 border bg-gray-100 rounded-2 mb-6 mb-lg-8">
                                                <div class="col-md-3 mb-3 mb-md-0">
                                                <? if($comp_info['comp_class_sub'] == 3){?>									
                                                        <select class="form-select form-select-sm" name="org_code" id="search_org_code" data-control="select2" data-hide-search="true" aria-label="지역교육지원청">
                                                            <option value="" >지역교육지원청</option>
                                                            <?
                                                            $query_string = " select comp.* from company_info comp where comp.sc_code = '" . $comp_info['sc_code'] . "' and comp.org_code != ''  and comp.schul_code = ''";
                                                            $data_sql['query_string'] = $query_string;
                                                            $list = query_list($data_sql);
                                                            //print_r($list);
                                                            foreach($list as $k => $data)
                                                            {
                                                                    if (is_array($data))
                                                                    {
                                                            ?>
                                                            <option value="<?=$data['org_code']?>" <?=$org_code == $data['org_code'] ? 'selected ': ''?>><?=$data['org_name']?></option>
                                                            <?		}
                                                            }
                                                            ?>
                                                        </select>
                                                <?}?>
                                                </div>
                                                
                                                <div class="row">
                                                    <? if($comp_info['comp_class_sub'] == 3 || $comp_info['comp_class_sub'] == 4){?>
                                                    <div class="col-6 col-md-6">
                                                        <!--  													
                                                        <select class="form-select form-select-sm" name="schul_code" id="search_schul_code" data-control="select2" data-hide-search="true" aria-label="학교선택">
                                                            <option value="" >학교선택</option>
                                                        -->
                                                            <?
                                                            $where = " comp.del_yn='N' and comp.sc_code = '" . $comp_info['sc_code'] . "' and comp.schul_code != ''";
                                                            //$list = company_info_data('list', $where, 'comp.schul_name');

                                                            $query_string = " select comp.* from company_info comp where ".$where;
                                                            $data_sql['query_string'] = $query_string;
                                                            $list = query_list($data_sql);
                                                            
                                                            foreach($list as $k => $data)
                                                            {
                                                                    if (is_array($data))
                                                                    {
                                                            ?>
                                                            <div class="form-check form-check-custom form-check-sm form-check-inline mt-2">
                                                                <input class="form-check-input" type="checkbox" name="schul_code" id="search_schul_code_<?=$data['schul_code']?>" value="<?=$data['schul_code']?>" onclick="$(form).submit();" <?=$schul_code == $data['schul_code'] ? 'checked="checked"' : ''?>/>
                                                                <label class="form-check-label" for="search_schul_code_<?=$data['schul_code']?>"><?=$data['schul_name']?></label>
                                                            </div>

                                                            <!--option value="<?=$data['schul_code']?>" <?=$schul_code == $data['schul_code'] ? 'selected ': ''?>><?=$data['schul_name']?></option-->
                                                            <?		}
                                                            }
                                                            ?>
                                                        <!--/select-->
                                                    </div>
                                                    <?}?>
                                                </div>

                                            </div>
										</form>

										<div class="card card-dashed mb-8 mb-lg-10">
											<div class="card-header bg-gray-100i min-h-45px py-2 px-6 px-lg-8">
												<h3 class="card-title fs-5 fw-semibold">접수 <?=$statistics_class == 'day' ? '일별' : '월별'?></h3>
											</div>
											<div class="card-body px-6 px-lg-8">
												<div id="KTChartsWidgetMonth" class="h-400px w-100 d-none d-md-block"></div>
												<div id="KTChartsWidgetMonthMobile" class="h-600px w-100 d-md-none"></div>
											</div>
										</div>
										<!-- //접수통계 -->



									</div>
								</div>
							</div>
							<!--// Content container -->
						</div>
						<!--// Content -->
					</div>
					<!--// Content wrapper -->



	<script>
// Class definition
var KTWidgets = function () {
    var initChartsWidgetMonth = function() {
        var element = document.getElementById("KTChartsWidgetMonth");

        if ( !element ) {
            return;
        }

        var chart = {
            self: null,
            rendered: false
        };

        var initChart = function() {
            var height = parseInt(KTUtil.css(element, 'height'));
            var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
            var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
            var websiteColor = KTUtil.getCssVariableValue('--bs-primary');
            var inquiryColor = KTUtil.getCssVariableValue('--bs-success');
            var hardwareColor = KTUtil.getCssVariableValue('--bs-info');

            var options = {
                series: [
				<? foreach($series as $name => $data){?>				
					{

						name: '<?=$name?>',
						data: [<? foreach($data as $val) echo "'{$val}', "; ?>]
					}, 
				<?}?>
				],
                chart: {
                    fontFamily: 'inherit',
                    type: 'bar',
                    height: height,
                    toolbar: {
                        show: true
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: ['50%'],
                        borderRadius: 4
                    },
                },
                legend: {
                    show: true
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: [<? foreach($cate as $val) echo "'{$val}', "; ?>],
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '11px'
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: '12px'
                    },
                    y: {
                        formatter: function (val) {
                            return val
                        }
                    }
                },
                colors: [websiteColor, '#dd3333', inquiryColor, '#32dcd3', hardwareColor, '#dd3333', '#dc6a32', '#dcc532', '#32dcd3', '#32dc8f', '#d632dc'],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;
        }

        // Init chart
        initChart();

        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function() {
            if (chart.rendered) {
                chart.self.destroy();
            }

            initChart();
        });
    }


    var initChartsWidgetMonthMobile = function() {
        var element = document.getElementById("KTChartsWidgetMonthMobile");

        if ( !element ) {
            return;
        }

        var chart = {
            self: null,
            rendered: false
        };

        var initChart = function() {
            var height = parseInt(KTUtil.css(element, 'height'));
            var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
            var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
            var websiteColor = KTUtil.getCssVariableValue('--bs-primary');
            var inquiryColor = KTUtil.getCssVariableValue('--bs-success');
            var hardwareColor = KTUtil.getCssVariableValue('--bs-info');

            var options = {
                series: [
					<? foreach($series as $name => $data){?>				
					{

						name: '<?=$name?>',
						data: [<? foreach($data as $val) echo "'{$val}', "; ?>]
					}, 
					<?}?>
					],
                chart: {
                    fontFamily: 'inherit',
                    type: 'bar',
                    height: height,
                    toolbar: {
                        show: true
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        columnWidth: ['50%'],
                        borderRadius: 4
                    },
                },
                legend: {
                    show: true
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: [<? foreach($cate as $val) echo "'{$val}', "; ?>],
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '11px'
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                tooltip: {
                    style: {
                        fontSize: '12px'
                    },
                    y: {
                        formatter: function (val) {
                            return val
                        }
                    }
                },
                colors: [websiteColor, inquiryColor, hardwareColor],
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                }
            };

            chart.self = new ApexCharts(element, options);
            chart.self.render();
            chart.rendered = true;
        }

        // Init chart
        initChart();

        // Update chart on theme mode change
        KTThemeMode.on("kt.thememode.change", function() {
            if (chart.rendered) {
                chart.self.destroy();
            }

            initChart();
        });
    }

    // Public methods
    return {
        init: function () {
            initChartsWidgetMonth();
				initChartsWidgetMonthMobile();
        }
    }
}();

// Webpack support
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = KTWidgets;
}

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTWidgets.init();
});
	</script>