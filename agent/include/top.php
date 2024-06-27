<?
	$page_base = basename($this_page);
	if ($page_base == 'agent_A.php' || $page_base == 'agent_B.php' || $page_base == 'agent_C.php' || $page_base == 'agent_error.php') $str_id = 'agent_wrap';
	else $str_id = 'wrap';
?>
<div id="<?=$str_id;?>">
	<!-- 헤더영역 -->
	<div id="header">
<?
	if ($comp_logo_img == '')
	{
		echo '<div class="logo_no">' . $comp_logo_img . '</div>';
	}
	else
	{
		echo '<div class="logo">' . $comp_logo_img . '</div>';
	}

	if ($page_base == 'agent_A.php' || $page_base == 'agent_B.php' || $page_base == 'agent_C.php' || $page_base == 'agent_error.php')
	{
	}
	else
	{
?>
		<div class="work_number">
			<ul>
				<li class="client"><em>거래처명</em><span class="client_name"><em  class="client_name2"><?=$client_name;?></em></span></li>
				<li>
					<ul>
		<?
			$button_where = " and abu.comp_idx = '" . $client_comp . "' and abu.part_idx = '" . $client_part . "' and abu.agent_type = '" . $client_agent . "'";
			$button_list = agent_button_data('list', $button_where, '', '', '');
			foreach ($button_list as $button_k => $button_data)
			{
				if (is_array($button_data))
				{
					$btn_sort = $button_data['sort'];
					$btn_name = $button_data['btn_name'];
					$btn_type = $button_data['btn_type'];

					if ($btn_sort == '1') // 접수
					{
						echo '
						<li class="menu_view li_first">
							<a href="javascript:void(0);" onclick="location.href=\'receipt.php\'" class="on">', $btn_name, '</a>
							<span class="today_num"><em>', number_format($receipt_notice), '</em></span>
						</li>';
					}
					else
					{
						if ($btn_type == '2') // 알림게시판
						{
							$cc_data['chk_comp'] = $client_comp;
							$cc_data['chk_part'] = $client_part;
							$cc_data['chk_ci']   = $client_idx;
							$cc_data['chk_ccg']  = $client_ccg_idx;
							$cc_data['chk_mac']  = $macaddress;
							$read_chk = agent_bnotice_read_check($cc_data);

							echo '
						<li class="menu_view">
							<a href="javascript:void(0);" onclick="location.href=\'bnotice.php\'" class="on2">', $btn_name, '</a>
							<span class="today_num"><em>', number_format($read_chk['bnotice_check']), '</em></span>
						</li>';
							unset($cc_data);
							unset($read_chk);
						}
						if ($btn_type == '3') // 상담게시판
						{
							$cc_data['chk_ci']  = $client_idx;
							$cc_data['chk_mac'] = $macaddress;
							$read_chk = consult_read_check($cc_data);

							echo '
						<li class="menu_view">
							<a href="javascript:void(0);" onclick="location.href=\'consult.php\'" class="on3">', $btn_name, '</a>
							<span class="today_num"><em>', number_format($read_chk['read_comment']), '</em></span>
						</li>';
							unset($cc_data);
							unset($read_chk);
						}
						if ($btn_type == '4') // 일반게시판
						{
							echo '
						<li class="menu_view">
							<a href="javascript:void(0);" onclick="location.href=\'board.php\'" class="on4">' . $btn_name . '</a>
						</li>';
						}
					}
				}
			}
		?>
					</ul>
				</li>
			</ul>
		</div>
<?
	}
?>
		<div class="header_title">
			<p>비즈스토리 솔루션</p>
			<p>언제든지 신청하시면 신속정확한 비즈스토리</p>
		</div>
	</div>
	<!-- //헤더영역 -->
	<div id="container">