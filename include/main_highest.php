					<div class="hb_contents">
						<div class="layout_frame">
							<div class="layout_box">

								<!-- Ticker -->
								<div id="main_notice" class="ticker">
									<div class="ticker_frame">
										<div id="ticker-wrapper" class="no-js">
											<ul id="js-news" class="js-hidden">
												<li class="news-item"><a href="./">업무 시스템 홈페이지 개편에 따른 일부 장애 안내 입니다.</a></li>
												<li class="news-item"><a href="./">업무 시스템 굳소프트 인증을 받고 현재 출시준비중입니다.</a></li>
												<li class="news-item"><a href="./">금일 오후 부터 시스템 업그레이드로 인한 서비스 일시중지 안내입니다.</a></li>
												<li class="news-item"><a href="./">홈빌더 홈스토리를 유비스토리에서 출시 예정입니다.</a></li>
											</ul>
										</div>
									</div>
								</div>
								<!-- //Ticker -->

								<div>
				<?
					$agent_where = " and comp.del_yn = 'N' and ci.del_yn = 'N'";
					$agent_list = agent_data_data('page', $agent_where);
				?>
									총 에이전트 수 : <?=number_format($agent_list['total_num']);?> 개
								</div>

								<ul class="comp_list">
				<?
					$comp_where = " and comp.auth_yn = 'Y' and comp.view_yn = 'Y'";
					$comp_list = company_info_data('list', $comp_where, '', '', '');

					foreach ($comp_list as $comp_k => $comp_data)
					{
						if (is_array($comp_data))
						{
							$cs_where = " and cs.comp_idx = '" . $comp_data['comp_idx'] . "'";
							$comp_set = company_set_data('view', $cs_where);

							$set_comp_idx   = $comp_data['comp_idx'];
							$set_start_date = $comp_data['start_date'];
							$set_end_date   = $comp_data['end_date'];
							$set_agent_yn   = $comp_set['agent_yn'];

						//사용자 데이터 - /data/company/comp_idx/* 값구해서
							$volume_path = $comp_path . '/' . $set_comp_idx;
							$volume_data = server_volume($volume_path);

						//거래처
							$client_where = " and ci.comp_idx = '" . $set_comp_idx . "' and ci.del_yn = 'N'";
							$client_list = client_info_data('page', $client_where);
				?>
									<li>
										<div class="service_info">
											<h4><strong><?=$comp_data['comp_name'];?></strong></h4>
											<ul>
												<li><span>서비스 시작일 </span><em>: <?=date_replace($set_start_date, 'Y.m.d');?></em></li>
												<li><span>서비스 만기일 </span><em>: <?=date_replace($set_end_date, 'Y.m.d');?></em></li>
												<li><span>데이터 사용량 </span><em>: <?=byte_replace($volume_data);?></em></li>
												<li><span>거래처수 </span><em>: <?=number_format($client_list['total_num']);?> 개</em></li>
									<?
										if ($set_agent_yn == 'Y')
										{
											$agent_where = " and ad.comp_idx = '" . $set_comp_idx . "' and ci.del_yn = 'N'";
											$agent_list = agent_data_data('page', $agent_where);
									?>
												<li><span>에이전트 갯수 </span><em>: <?=number_format($agent_list['total_num']);?> 개</em></li>
									<?
										}
										else
										{
									?>
												<li><span>에이전트 갯수 </span><em>: 사용하지 않음</em></li>
									<?
										}
									?>
												<li>
													<span>부가서비스사용 </span>
													<em class="exception">: </em>
												</li>
											</ul>
										</div>
									</li>
				<?
						}
					}
				?>
								</ul>
							</div>
						</div>
					</div>
