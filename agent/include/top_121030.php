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
				<li><?=$client_name;?></li>
				<li>&nbsp;</li>
				<li class="menu_view"><a href="javascript:void(0);" onclick="location.href='receipt.php'"><span>|</span>접수</a></li>
<?
	$button_where = " and abu.comp_idx = '" . $client_comp . "' and abu.part_idx = '" . $client_part . "' and abu.agent_type = '" . $client_agent . "'";
	$button_list = agent_button_data('list', $button_where, '', '', '');
	foreach ($button_list as $button_k => $button_data)
	{
		if (is_array($button_data))
		{
			$btn_name = $button_data['btn_name'];
			$btn_type = $button_data['btn_type'];

			if ($btn_type == '2') // 알림게시판
			{
				echo '
				<li class="menu_view"><a href="javascript:void(0);" onclick="location.href=\'bnotice.php\'"><span>|</span>' . $btn_name . '</a></li>';
			}
			if ($btn_type == '3') // 상담게시판
			{
				if ($page_base == 'consult.php')
				{
					echo '
				<li class="menu_view"><a href="javascript:void(0);" onclick="location.href=\'consult.php\'"><span>|</span>' . $btn_name . '</a></li>';
				}
				else
				{
					echo '
				<li class="menu_view"><a href="javascript:void(0);" onclick="location.href=\'consult.php\'"><span>|</span>' . $btn_name . '</a></li>';
				}
			}
			if ($btn_type == '4') // 일반게시판
			{
				echo '
				<li class="menu_view"><a href="javascript:void(0);" onclick="location.href=\'board.php\'"><span>|</span>' . $btn_name . '</a></li>';
			}
		}
	}
?>
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