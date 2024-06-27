<?
	require_once "../bizstory/common/setting.php";
	require_once $local_path . "/cms/include/client_chk.php";
	require_once $local_path . "/cms/include/no_direct.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and rc.ri_idx = '" . $ri_idx . "'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$orderby = "rc.gno asc, rc.tgno asc";
	$list = receipt_comment_data('list', $where, $orderby, $m_page_num, $m_page_size);
	$m_page_num = $list['page_num'];
?>
<!-- 댓글 보기 -->
	<div class="cb_lstcomment">
		<ul>
			<li><strong><?=$list["total_num"];?></strong> 개의 댓글이 있습니다.</li>
<?
	$list_i = 0;
	if ($list["total_num"] == 0) {
?>
			<li>등록된 데이타가 없습니다.</li>
<?
	}
	else
	{
		$list_i = 1;
		$num = $list["total_num"] - ($m_page_num - 1) * $m_page_size;
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$sub_where = " and mem.mem_idx = '" . $data['mem_idx'] . "'";
				$sub_data = member_info_data('view', $sub_where);

				$reply_start = ''; $reply_end = '';
				if ($data['tgno'] > 0)
				{
					$reply_start = '<div class="reply' . $data['tgno'] . '">';
					$reply_end = '</div>';
				}
?>
			<li>
				<div class="cb_section">
					<?=$reply_start;?>

					<span class="cb_nick_name"><?=$data['writer'];?></span>
					<? if ($sub_data['mem_id'] != '') { ?> <span class="cb_usr_id">(<?=$sub_data['mem_id'];?>)</span><? } ?>
					<span class="cb_date"><?=$data['reg_date'];?></span>
					<div class="btn_area">
						<a href="javascript:void(0);" onclick="comment_reply('<?=$data['rc_idx'];?>', '<?=$data['gno'];?>', '<?=$data['tgno'];?>', '<?=$list_i;?>')" class="btn_close" title="답변">답변</a>
					</div>
					<p>
						<div class="cb_section_remark">
							<?=$data['remark'];?>
						</div>
					</p>
					<div id="comment_view_<?=$list_i;?>"></div>

					<?=$reply_end;?>
				</div>
			</li>
<?
				$num--;
				$list_i++;
			}
		}
	}
?>
		</ul>
<?
// 페이지부분
	$m_total_page = $list['total_page'];
	$m_block_page = (int)(($m_page_num - 1) / $m_block_size) * $m_block_size + 1;
	$old_m_block_page = $m_block_page;
?>
		<div class="pagination">
<?
	if ($old_m_block_page != 1)
	{
		$page_str = $old_m_block_page - $m_block_size;
?>
			<a href="javascript:void(0);" onclick="comment_page_move('<?=$page_str;?>')" class="prev">이전<?=$m_block_size;?></a>
<?
	}

	$i = 1;
	while ($i <= $m_block_size && $m_block_page <= $m_total_page)
	{
		if ($m_block_page == $m_page_num)
		{
?>
			<span class="current"><?=$m_block_page;?></span>
<?
		}
		else
		{
?>
			<a href="javascript:void(0);" onclick="comment_page_move('<?=$m_block_page;?>')"><?=$m_block_page;?></a>
<?
		}

		$i++;
		$m_block_page++;
	}

	if ($m_block_page <= $m_total_page)
	{
		$page_str = $m_block_page;
?>
			<a href="javascript:void(0);" onclick="comment_page_move('<?=$page_str;?>')" class="next">다음<?=$m_block_size;?></a>
<?
	}
?>
		</div>
	</div>
	<input type="hidden" id="memolist_m_list_num"   name="m_list_num"   value="<?=$list_i;?>" />
	<input type="hidden" id="memolist_m_total_page" name="m_total_page" value="<?=$list['total_page'];?>" />
<!-- //댓글 보기 -->
