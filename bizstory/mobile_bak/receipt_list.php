<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";

	$moretype = 'receipt';
	include $mobile_path . "/header.php";

	$contents_title = '접수목록';

	$where = " and ri.comp_idx = '" . $code_comp . "' and ri.part_idx = '" . $code_part . "'";
	if ($list_type == 'my_no') // 나의 미처리
	{
		$where .= "
			and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')
			and (rid.mem_idx = '" . $code_mem . "' or ri.charge_mem_idx = '" . $code_mem . "')
		";

		$query_page = "
			select
				count(ri.ri_idx)
			from
				receipt_info ri
				left join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
				left join member_info mem on mem.comp_idx = ci.comp_idx and mem.mem_idx = ci.mem_idx
				left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.code_idx = ri.receipt_class
				left join code_receipt_status code2 on code2.comp_idx = ri.comp_idx and code2.code_idx = ri.receipt_status
				left join (select ri_idx, mem_idx from receipt_info_detail where del_yn = 'N' and mem_idx = '" . $code_mem . "' group by ri_idx) rid on rid.ri_idx = ri.ri_idx
			where
				ri.del_yn = 'N'
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ri.*
				, ci.client_name, ci.del_yn as client_del_yn, ci.link_url
				, mem.mem_name, mem.del_yn as member_del_yn
				, code.del_yn as class_del_yn
				, code2.code_name as receipt_status_str, code2.code_bold as receipt_status_bold, code2.code_color as receipt_status_color, code2.code_value as status_value
			from
				receipt_info ri
				left join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
				left join member_info mem on mem.comp_idx = ci.comp_idx and mem.mem_idx = ci.mem_idx
				left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.part_idx = ri.part_idx and code.code_idx = ri.receipt_class
				left join code_receipt_status code2 on code2.comp_idx = ri.comp_idx and code2.part_idx = ri.part_idx and code2.code_value = ri.receipt_status
				left join (select ri_idx, mem_idx from receipt_info_detail where del_yn = 'N' and mem_idx = '" . $code_mem . "' group by ri_idx) rid on rid.ri_idx = ri.ri_idx
			where
				ri.del_yn = 'N'
				" . $where . "
			order by
				ri.reg_date desc
		";
		//echo "<pre>" . $query_string . "</pre><br />";
		$data_sql['query_page']   = $query_page;
		$data_sql['query_string'] = $query_string;
		$data_sql['page_size']    = $page_size;
		$data_sql['page_num']     = $page_num;

		$list = query_list($data_sql);
	}
	else
	{
		$list = receipt_info_data('list', $where, '', $page_num, $page_size);
	}
?>
<script type="text/javascript" src="<?=$mobile_dir;?>/js/myScroll.js" charset="utf-8"></script>

<div id="receipt_list" class="full sub list">

	<div class="toolbar han">
		<?=$btn_back;?>
		<h1>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/index.php'"><?=$contents_title;?></a>
		</h1>
		<?=$btn_logout;?>
	</div>

	<!-- Contents -->
	<div id="wrapper">
		<div id="scroller">

			<ul id="thelist" class="bbs">
<?
	foreach ($list as $k => $data)
	{
		if (is_array($data))
		{
			$list_data = receipt_list_data($data['ri_idx'], $data);
?>
				<li class="barmenu2 loop">
						<strong class="date"><span><?=date_replace($data['reg_date'], 'm-d');?></span></strong>
						<strong class="date"><?=$list_data['receipt_status_str'];?></strong>
						<strong class="gubun">[<?=$data['client_name'];?>]</strong>
						<?=$list_data['subject'];?>
		<?
			if ($list_data['total_file'] > 0)
			{
				echo '
						<span class="attach" title="첨부파일">', number_format($list_data['total_file']), '</span>';
			}
			if ($list_data['total_comment'] > 0)
			{
				echo '
						<span class="cmt" title="코멘트">', number_format($list_data['total_comment']), '</span>';
			}

			if ($read_work > 0)
			{
				echo '
						<span class="today_num" title="읽을 코멘트"><em>', number_format($read_work), '</em></span>';
			}
?>
					<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/receipt_view.php?ri_idx=<?=$data['ri_idx'];?>'" class="arrow" target="_webapp">
						<em class="push"></em>
					</a>
				</li>
<?
		}
	}
?>
			</ul>

			<div id="pullUp">
				<span class="pullUpIcon"></span>
				<span class="pullUpLabel">Pull up to refresh...</span>
			</div>

		</div>
	</div>
	<!-- //Contents -->
	<?
		$bottom_btn = '
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/index.php\'" class="icon4"><span>홈</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/receipt_list.php\'" class="icon2"><span>접수목록</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/receipt_list.php?list_type=my_no\'" class="icon2"><span>나의접수</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/set_up.php\'" class="icon3"><span>설정</span></a>
		';
	?>
	<? include $mobile_path . "/footer.php"; ?>
</div>
</body>
</html>