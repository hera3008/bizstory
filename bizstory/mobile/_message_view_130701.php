<?
	include $local_path . "header.php";
?>

<div data-role="page" data-add-back-btn="ture">
	<div data-role="header">
		<h1><a href="./" class="logo"><img src="./images/h_logo_x2.png" width="123" height="50" alt="비즈스토리"></a></h1>
		<p class="logout">
			<a href="#"><img src="./images/ico_logoutx2.png" alt="로그아웃" height="18px"></a>
		</p>
	</div>
	<div data-role="content">
		<div id="wrapper">
			<div id="scroller">
				<!-- ul class="massage">
					<li><strong>보낸사람</strong> 비즈스토리</li>
					<li><strong>보낸일</strong> 2013-05-23 13:00:00</li>
					<li><strong>읽은일</strong> 2013-05-23 13:00:00</li>
					<li><strong>내용</strong>쪽지내용이 노출됩니다.으하하하하하하하하하하하하하하하하하~~~허거덩--;;</li>
					<li><strong>첨부파일</strong> aaaa</li>
				</ul -->
				
				<table border="1" cellspacing="0" summary="쪽지내용">
					<caption>쪽지내용</caption>
					<colgroup>
						<col width="20px" />
						<col  />
					</colgroup>
					<tbody>
						<tr>
							<th>보낸사람</th>
							<td>비즈스토리</td>
						</tr>
						<tr>
							<th>보낸일</th>
							<td>2013-05-23 13:00:00</td>
						</tr>
						<tr>
							<th>읽은일</th>
							<td>2013-05-24 13:00:00</td>
						</tr>
						<tr>
							<td colspan="2">쪽지내용이 노출됩니다.으하하하하하하하하하하하하하하하하하~~~허거덩--;;</td>
						</tr>
						<tr>
							<th>첨부파일</th>
							<td>aaaa</td>
						</tr>
					</tbody>
				</table>
				
				
				<!-- ul class="list_cmt">
					<li class="message_box2">
						<div class="box_cmt">
							<span class="txt_photo"><img class="photo" src="/data/company/1/member/7/member_7_1.jpg" alt="" height="35px" width="35px"></span>			<div class="txt_area">
								<span class="txt_cmt">
									<input id="msidx_15" name="chk_ms_idx[]" value="569" type="checkbox">					<a href="javascript:void(0);" onclick="popupview_open('15', '8', '569', 'send')">나에요~~ 음하하~~ 코리아콘서트 오케스트라 홈페이지 도메인이랑 알려드려요~~http://www.kocon.kr/작업위치는  ..</a>
														<div id="msg_view_remark_15" class="none"></div>
									<span class="ico_cmt ico_cmt_right"></span>
								</span>
								<span class="desc">
									<span class="time">보낸일 : 2013-01-04 15:28:36</span>
								</span>
							</div>
						</div>
					</li>
					<li class="message_box">
						<div class="box_cmt">
							<span class="txt_photo"><img class="photo" src="/data/company/1/member/8/member_8_1.png" alt="" height="35px" width="35px"></span>			<div class="txt_area">
								<span class="txt_cmt">
									<input id="mridx_16" name="chk_mr_idx[]" value="713" type="checkbox">					<a href="javascript:void(0);" onclick="popupview_open('16', '8', '713', 'receive')">테스트 쪽지지롱~~~~</a>
														<div id="msg_view_remark_16" class="none"></div>
									<span class="ico_cmt ico_cmt_left"></span>
								</span>
								<span class="desc">
									<span class="time">받은일 : 2012-12-24 17:04:16, 읽은일 : 2012-12-26 09:55:28</span>
								</span>
							</div>
						</div>
					</li>
				</ul -->

			</div>
		</div>
	</div>

<?
	include $local_path . "footer.php";
?>