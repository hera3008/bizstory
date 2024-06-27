<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include  "./header.php";
	
// 미처리접수리스트

	$receipt_no_where  = " and ri.comp_idx = '" . $code_comp . "' and ri.part_idx = '" . $code_part . "'";
	$receipt_no_where .= " and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')";
	$receipt_no_list = receipt_info_data('page', $receipt_no_where);
	$receipt_no_total = number_format($receipt_no_list['total_num']);
/*

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 총접수
	$receipt_query = "
		select
			ri.ri_idx
		from
			receipt_info ri
			left join receipt_info_detail rid on rid.del_yn = 'N' and rid.comp_idx = ri.comp_idx and rid.ri_idx = ri.ri_idx
			left join client_info ci on ci.del_yn = 'N' and ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
		where
			ri.del_yn = 'N'
			and ri.comp_idx = '" . $code_comp . "' and ri.part_idx = '" . $code_part . "'
			and (
				if (ifnull(rid.mem_idx, '') = ''
					, if (ifnull(ri.charge_mem_idx, '') = ''
						, ci.mem_idx
						, ri.charge_mem_idx)
					, rid.mem_idx) = '" . $mem_idx . "')
	";
// 미완료접수
	$receipt_ing_query = $receipt_query . "
			and ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60'
	";
	$receipt_ing = query_view($receipt_ing_query);
	$receipt_no_total = number_format($receipt_ing['total_num']);
*/
// 나의 업무
// 보류(WS80), 완료(WS90), 종료(WS99), 취소(WS50)

	$work_where = "
		and wi.comp_idx = '" . $code_comp . "'
		and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')
		and wi.work_status <> 'WS80' and wi.work_status <> 'WS90' and wi.work_status <> 'WS99' and wi.work_status <> 'WS50'";
	$my_work_list = work_info_data('page', $work_where);
	$my_work_total = number_format($my_work_list['total_num']);
/*
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 총업무
	$work_query = "
		select
			count(wi_idx)
		from
			work_info
		where
			del_yn = 'N'
			and comp_idx = '" . $code_comp . "'
			and (concat(',', charge_idx, ',') like '%," . $mem_idx . ",%' or apply_idx = '" . $mem_idx . "' or reg_id = '" . $mem_idx . "')
	";
// 보류(WS80), 완료(WS90), 종료(WS99), 취소(WS50)
	$work_ing_query = $work_query . "
			and work_status <> 'WS80' and work_status <> 'WS90' and work_status <> 'WS99' and work_status <> 'WS50'
	";
	$work_ing = query_page($work_ing_query);
	$my_work_total = number_format($work_ing['total_num']);
*/
	// 읽을 업무, 보고, 댓글
	$check_num = work_read_check('');
	$read_work = number_format($check_num['work_check']);

// 받은쪽지
	$message_where = "
		and mr.comp_idx = '" . $code_comp . "'
		and mr.mem_idx = '" . $code_mem . "'
		and if(mr.read_date = '0000-00-00', 'Y', 'N') = 'Y'";
	$message_list = message_receive_data('page', $message_where);
	$message_total = number_format($message_list['total_num']);

// 메모장
	$memo_where = "
		and mm.comp_idx = '" . $code_comp . "'
		and mm.mem_idx = '" . $code_mem . "'";
	$memo_list = member_memo_data('page', $memo_where);
	$memo_total = number_format($memo_list['total_num']);

// 나의 상담 - 안 읽은 것들
	$consult_total = 0;

// 자료실
	$bbs_total1 = 0;

// 비즈스토리 만남의 광장
	$bbs_total2 = 0;

?>

<div id="page">
	<div id="header">
		<h1><a href="./" class="logo"><img src="./images/h_logo_x2.png" alt="비즈스토리"></a></h1>
		<?=$btn_logout;?>
	</div>
	<div id="content">
		<div id="wrapper">
			<div id="scroller">

				<article class="mt_3">
					<div class="navTab">
						<ul class="list_navTab">
							<li><a href="javascript:void(0)" onclick="window.location.href='work_list.php'" class="link_navTab"><img src="./images/icon1.png" width="124" height="124" alt="업무" />업무<em class="push"><?=$my_work_total;?></em></a></li>
							<li><a href="javascript:void(0)" onclick="window.location.href='work_list.php?sview=today'" class="link_navTab"><img src="./images/icon2.png" width="124" height="124" alt="알림" />알림<em class="push"><?=$read_work?></em></a></li>
							<li><a href="javascript:void(0)" onclick="window.location.href='message_list.php'" class="link_navTab"><img src="./images/icon3.png" width="124" height="124" alt="쪽지" />쪽지<em class="push"><?=$message_total;?></em></a></li>
							<li><a href="javascript:void(0)" onclick="window.location.href='receipt_list.php'" class="link_navTab"><img src="./images/icon4.png" width="124" height="124" alt="접수" />접수<em class="push"><?=$receipt_no_total;?></em></a></li>
						</ul>
					</div>
						
					<iframe style="width:100% !important; height:140px; background:transparents;" scrolling="no" frameborder="no" src="./gnbscroll.php"></iframe>

					<!-- 공지사항 -->
					<section class="notice">
						<strong>NOTICE</strong>
						<?
							$notice_where = "
								and ni.notice_type = '2' and ni.view_yn = 'Y'
								and (concat(ni.comp_idx, ',') like '%" . $code_comp . "%' or ni.comp_all = 'Y')
							";
							$notice_list = notice_info_data('list', $notice_where, '', '', '');
						?>
								<div>
									<marquee behavior="scroll" direction="left" scrollamount="2">
						<?
							if ($notice_list['total_num'] == 0)
							{
						?>
											<span style="padding-right:20px;">&nbsp;</span>
						<?
							}
							else
							{
								foreach ($notice_list as $notice_k => $notice_data)
								{
									if (is_array($notice_data))
									{
										$import_type = $notice_data['import_type'];
										$link_url    = $notice_data['link_url'];

									// 중요도
										if ($notice_data['import_type'] == '1') $important_span = '<span class="btn_level_1"><span>상</span></span>';
										else if ($notice_data['import_type'] == '2') $important_span = '<span class="btn_level_2"><span>중</span></span>';
										else if ($notice_data['import_type'] == '3') $important_span = '<span class="btn_level_3"><span>하</span></span>';
										else $important_span = '';

										if ($link_url == '')
										{
											$subject = $notice_data['content'];
										}
										else
										{
											$subject = '<a href="http://' . $link_url . '" target="_blank">' . $notice_data['content'] . '</a>';
										}
						?>
											<span style="padding-right:20px;">ㆍ <?=$subject;?><?=$important_span;?></span>
						<?
									}
								}
							}
						?>
										</marquee>
									</div>
					</section>
				</article>

				<div class="list_work">
					<ul>
						<li>
							<a href="javascript:" onclick="window.location.href='memo_list.php'" class="title">
								<img src="./images/icon5.png" class="ico_img" alt="메모보기" /> 메모보기
								<span class="btn"><?=$memo_total;?><img src="./images/bul_arrow.png" alt="더보기" /></span>
							</a>
						</li>
						
						<li>
							<a href="javascript:" onclick="window.location.href='consult_list.php'" class="title">
								<img src="./images/icon6.png" class="ico_img" alt="메모보기" /> 나의상담
								<span class="btn"><?=$consult_total;?><img src="./images/bul_arrow.png" alt="더보기" /></span>
							</a>
						</li>
						
						<li>
							<a href="javascript:" onclick="alert('준비중입니다')" class="title">
								<img src="./images/icon7.png" class="ico_img" alt="메모보기" /> 비즈스토리 만남의 광장
								<span class="btn"><?=$bbs_total2;?><img src="./images/bul_arrow.png" alt="더보기" /></span>
							</a>
						</li>
					</ul>
				</div>

			</div>
		</div>
	</div>


<?php
	if ($pId)
	{
?>
	<script type="text/javascript">
	
    $(function() {
        var uAgent = navigator.userAgent.toLowerCase();
            
        if (uAgent.indexOf("android") != -1)
        {
            window.android.setId("<?php echo $pId ?>", "<?php echo $pPw ?>");
        }
        else if (uAgent.indexOf("iphone") != -1 || uAgent.indexOf("ipod") != -1)
        {
            window.location = "ios://loginBizstory?<?php echo $pId ?>&<?php echo $pPw ?>";
        }
        else
        {
        //  window.location = "ios://loginBizstory";
        //  sleep(5000);
        }
        
        var isLogin = $.cookie('isLogin');
        
        if (isLogin === "" || isLogin === null) {
            $.cookie('isLogin', 'Y', { expires: 1});
            location.reload(true);
        } else {
            $.cookie('isLogin', '', { expires: -1});
        }
        
    });

		
	</script>
<?php
	}

	include  "./footer.php";
?>