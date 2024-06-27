				<!-- Header -->
				<div id="kt_app_header" class="app-header">
					<!-- Header container -->
					<div class="app-container  container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
						<!-- Sidebar mobile toggle -->
						<div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
							<div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
								<i class="ki-duotone ki-abstract-14 fs-2 fs-md-1"><span class="path1"></span><span class="path2"></span></i>
							</div>
						</div>
						<!--// Sidebar mobile toggle -->
						<!-- Mobile logo -->
						<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
							<a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/'" title="홈으로 이동 합니다." class="d-lg-none">
								
							</a>
						</div>
						
						<!--// Mobile logo -->
						<!-- Header wrapper -->
						<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
							<!-- Menu wrapper -->
							<div class="app-header-menu app-header-mobile-drawer align-items-stretch"></div>
							<!--// Menu wrapper -->
							<!-- Navbar -->
							<div class="app-navbar flex-shrink-0">
								<!-- Search -->
								<div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1 ms-1 ms-md-3">
									<!-- Search -->
									<div id="kt_header_search" class="header-search d-flex align-items-stretch" data-kt-search-keypress="true" data-kt-search-min-length="2" data-kt-search-enter="enter" data-kt-search-layout="menu" data-kt-menu-trigger="auto" data-kt-menu-overflow="false" data-kt-menu-permanent="true" data-kt-menu-placement="bottom-end">
										<!-- Search toggle -->
										<div class="d-flex align-items-center" data-kt-search-element="toggle" id="kt_header_search_toggle">
											<div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px">
												<i class="ki-duotone ki-magnifier fs-2 fs-lg-1"><span class="path1"></span><span class="path2"></span></i>
											</div>
										</div>
										<!--// Search toggle -->

										<div data-kt-search-element="content" class="menu menu-sub menu-sub-dropdown p-7 w-325px w-md-375px">
											<!-- Wrapper -->
											<div data-kt-search-element="wrapper">
												<!-- Form -->
												<form data-kt-search-element="form" class="w-100 position-relative mb-3" autocomplete="off">
													<!-- Icon -->
													<i class="ki-duotone ki-magnifier fs-2 text-gray-500 position-absolute top-50 translate-middle-y ms-0"><span class="path1"></span><span class="path2"></span></i>    <!--// Icon -->
													<!-- Input -->
													<input type="text" class="search-input  form-control form-control-flush ps-10" name="search"  placeholder="키워드를 입력하세요." data-kt-search-element="input">
													<!--// Input -->
												</form>
												<!--// Form -->
												<!-- Separator -->
												<div class="separator border-gray-200 mb-6"></div>
												<!--// Separator -->

												<!-- Recently viewed -->
												<div data-kt-search-element="main">
													<!-- Heading -->
													<div class="d-flex flex-stack fw-semibold mb-4">
														<!-- Label -->
														<span class="text-muted fs-6 me-2">최근 검색어</span>
														<!--// Label -->
													</div>
													<!--// Heading -->
													<!-- Items -->
													<div class="scroll-y mh-150px mh-lg-175px">
														<!-- Item -->
														<div class="d-flex align-items-center mb-5">
															<div class="d-flex flex-column">
																<a href="javascript:void(0);" class="fs-6 text-gray-800 text-hover-primary ellipsis-1 fw-semibold">소프트웨어사업자</a>
															</div>
														</div>
														<!--// Item -->
														<!-- Item -->
														<div class="d-flex align-items-center mb-5">
															<div class="d-flex flex-column">
																<a href="javascript:void(0);" class="fs-6 text-gray-800 text-hover-primary ellipsis-1 fw-semibold">직접생산증명서 </a>
															</div>
														</div>
														<!--// Item -->
														<!-- Item -->
														<div class="d-flex align-items-center mb-5">
															<div class="d-flex flex-column">
																<a href="javascript:void(0);" class="fs-6 text-gray-800 text-hover-primary ellipsis-1 fw-semibold">조직관리시스템구축사업</a>
															</div>
														</div>
														<!--// Item -->
														<!-- Item -->
														<div class="d-flex align-items-center mb-5">
															<div class="d-flex flex-column">
																<a href="javascript:void(0);" class="fs-6 text-gray-800 text-hover-primary ellipsis-1 fw-semibold">벤처기업확인서이노비즈</a>
															</div>
														</div>
														<!--// Item -->
														<!-- Item -->
														<div class="d-flex align-items-center mb-5">
															<div class="d-flex flex-column">
																<a href="javascript:void(0);" class="fs-6 text-gray-800 text-hover-primary ellipsis-1 fw-semibold">리엑트</a>
															</div>
														</div>
														<!--// Item -->
													</div>
													<!--// Items -->
												</div>
												<!--// Recently viewed -->
											</div>
											<!--// Wrapper -->
										</div>

									</div>
									<!--// Search -->
								</div>
								<!--// Search -->

								<!-- Notifications -->
								<div class="app-navbar-item ms-1 ms-md-3">
									<?
									$top_chk = member_chk_data($code_mem);
									
									// 접수메뉴만 있는 사람만 보이도록
									$receipt_str = 'N';
									$menu_where = "
										and mam.comp_idx = '" . $code_comp . "' and mam.mem_idx = '" . $code_mem . "' and mam.yn_list = 'Y'
										and mac.view_yn = 'Y' and mi.del_yn = 'N' and mi.mode_folder = 'receipt' and mi.mode_file = 'receipt'";
									$menu_data = menu_auth_member_data('page', $menu_where);
									if ($menu_data['total_num'] > 0)
									{
										$receipt_str = 'Y';

									// 접수 - 완료, 보류, 취소는 제외
										$receipt_where = " and ri.comp_idx = '" . $code_comp . "'";
										if ($set_part_yn == 'N') $receipt_where .= " and ri.part_idx = '" . $top_code_part . "'";
										$receipt_where .= " and ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60'";
										$receipt_page = receipt_info_data('page', $receipt_where);
									}else{
										$receipt_page['total_num']=0;
										$receipt_page['receipt_ing']=0;
									}

									?>
									<!-- Menu- wrapper -->
									<div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px position-relative" data-kt-menu-trigger="{default: 'click', lg: 'click'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" id="kt_menu_item_wow">
										<i class="ki-duotone ki-notification-on fs-2 fs-lg-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
										<?if($receipt_page['receipt_ing']>0){?>
										<span class="position-absolute top-0 start-100 translate-middle  badge badge-circle badge-danger w-15px h-15px ms-n4 mt-3"><?=number_format($receipt_page['receipt_ing']);?></span>
										<?}?>
									</div>

									<div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-400px" data-kt-menu="true" id="kt_menu_notifications">
										<!-- Heading -->
										<div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('<?=$local_dir;?>/bizstory/assets/media/misc/menu-header-bg.jpg')">
											<!-- Title -->
											<h3 class="text-white fw-semibold px-9 mt-10 mb-6">
												읽지 않은 알림 <?if($top_chk['total_num']>0){?><span class="fs-8 opacity-75 ps-3"><?=number_format($top_chk['total_num']);?></span><?}?>
											</h3>
											<!--// Title -->
											<!-- Tabs -->
											<ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
												<li class="nav-item">
													<a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab" href="#kt_topbar_notifications_1">알림</a>
												</li>
												<li class="nav-item">
													<a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#kt_topbar_notifications_2">접수</a>
												</li>
												<li class="nav-item">
													<a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#kt_topbar_notifications_3">쪽지</a>
												</li>
											</ul>
											<!--// Tabs -->
										</div>
										<!--// Heading -->
										<!-- Tab content -->
										<div class="tab-content">
											<!-- Tab panel -->
											<div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">
												<!-- Items -->
												<div class="scroll-y mh-250px my-4 px-8">
													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="javascript:void(0);" class="text-gray-800 text-hover-primary ellipsis-1">솔루션 납품 및 계약 총괄</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->

													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="javascript:void(0);" class="text-gray-800 text-hover-primary ellipsis-1">경기도청_조직관리시스템구축사업</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->

													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="javascript:void(0);" class="text-gray-800 text-hover-primary ellipsis-1">유비스토리 홈페이지 리뉴얼</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->

													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="javascript:void(0);" class="text-gray-800 text-hover-primary ellipsis-1">[인증서갱신]직접생산증명서</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->

													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="javascript:void(0);" class="text-gray-800 text-hover-primary ellipsis-1">홈스토리(ePlat) 리엑트 버전 개발업무</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->

													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="javascript:void(0);" class="text-gray-800 text-hover-primary ellipsis-1">한국예탁결제원_전자투표 시스템 재구축</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->

													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="javascript:void(0);" class="text-gray-800 text-hover-primary ellipsis-1">소프트웨어사업자 신고확인서</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->

													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="javascript:void(0);" class="text-gray-800 text-hover-primary ellipsis-1">[인증서갱신]기업신용평가 신청</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->
												</div>
												<!--// Items -->

												<!-- View more -->
												<div class="py-3 text-center border-top">
													<a href="javascript:void(0);" class="btn btn-color-gray-600 btn-active-color-primary">
														모두보기
														<i class="ki-duotone ki-arrow-right fs-5"><span class="path1"></span><span class="path2"></span></i>
													</a>
												</div>
												<!--// View more -->
											</div>
											<!--// Tab panel -->

											<!-- Tab panel -->
											<div class="tab-pane fade" id="kt_topbar_notifications_2" role="tabpanel">

												<!-- Items -->
												<div class="scroll-y mh-250px my-4 px-8">
													<?
													if ($receipt_str == 'Y')
													{
														$receipt_where = " and ri.comp_idx = '" . $code_comp . "'";
														if ($set_part_yn == 'N') $receipt_where .= " and ri.part_idx = '" . $top_code_part . "'";
														$receipt_where .= " and ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60'";
														$receipt_list = receipt_info_data('list', $receipt_where, '', 1, 6);


														if ($receipt_list['total_num'] > 0)
														{
															$chk_num = 1;
															foreach ($receipt_list as $receipt_k => $receipt_data)
															{
																if (is_array($receipt_data))
																{
																// 댓글수
																	$sub_where = " and rc.ri_idx='" . $receipt_data['ri_idx'] . "'";
																	$sub_data = receipt_comment_data('page', $sub_where);
																	$receipt_data['total_tail'] = $sub_data['total_num'];
																// 분류
																	$receipt_class = receipt_class_view($receipt_data['receipt_class']);
													?>
													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="javascript:void(0)" onclick="location.href='<?=$this_page;?>?fmode=receipt&smode=receipt&ri_idx=<?=$receipt_data['ri_idx'];?>'" class="text-gray-800 text-hover-primary ellipsis-1">
																<?=$receipt_data['subject'];?>
															</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8"><?=date_replace($receipt_data['reg_date'], 'y.m.d');?></span>
														<!--// Label -->
													</div>
													<!--// Item -->
													<?
																}
															}
														}
													}
													?>
												</div>
												<!-- View more -->
												<div class="py-3 text-center border-top">
													<a href="javascript:void(0)" onclick="location.href='<?=$this_page;?>?fmode=receipt&smode=receipt&list_type=all_no'" class="btn btn-color-gray-600 btn-active-color-primary">
														모두보기
														<i class="ki-duotone ki-arrow-right fs-5"><span class="path1"></span><span class="path2"></span></i>
													</a>
												</div>
												<!--// View more -->
											</div>
											<!--// Tab panel -->

											<!-- Tab panel -->
											<div class="tab-pane fade" id="kt_topbar_notifications_3" role="tabpanel">
												<!-- Items -->
												<div class="scroll-y mh-250px my-4 px-8">
													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="/mypage/note.php" class="text-gray-800 text-hover-primary ellipsis-1">	아이폰 이전에 많는 어플에는 푸쉬 전송이 되네요.</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->

													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="/mypage/note.php" class="text-gray-800 text-hover-primary ellipsis-1">대표님 중랑숲어린이도서관 14세 미만가입처리완료했습니다 완료처리해주세요</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->

													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="/mypage/note.php" class="text-gray-800 text-hover-primary ellipsis-1">비즈스토리 라이센스(소프트웨어 사용증서) 수정본 입니다. </a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->

													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="/mypage/note.php" class="text-gray-800 text-hover-primary ellipsis-1">브로셔 수정사항 반영한 안은 시간이 좀더 걸린다고 연락왔습니다.</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->

													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="/mypage/note.php" class="text-gray-800 text-hover-primary ellipsis-1">오늘까지 작업된거 보내드려요~</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->

													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="/mypage/note.php" class="text-gray-800 text-hover-primary ellipsis-1">파일센터에서 문서에 관한 아이콘은 나타나나, 캐드 데이터에 관한 아이콘은 현재 나오지 않고 있습니다.</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->

													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="/mypage/note.php" class="text-gray-800 text-hover-primary ellipsis-1">홈스토리 라이센스는 살짝 수정된 수정본이구요.</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->

													<!-- Item -->
													<div class="d-flex flex-stack py-2">
														<!-- Section -->
														<div class="d-flex align-items-center me-2">
															<!-- Title -->
															<a href="/mypage/note.php" class="text-gray-800 text-hover-primary ellipsis-1">아님 제가 업무 담당자에 등록이 안되어서 그런가요?</a>
															<!--// Title -->
														</div>
														<!--// Section -->
														<!-- Label -->
														<span class="badge badge-light fw-normal fs-8">09.30 18:34</span>
														<!--// Label -->
													</div>
													<!--// Item -->
												</div>
												<!--// Items -->

												<!-- View more -->
												<div class="py-3 text-center border-top">
													<a href="/mypage/note.php" class="btn btn-color-gray-600 btn-active-color-primary">
														모두보기
														<i class="ki-outline ki-arrow-right fs-5"></i>
													</a>
												</div>
												<!--// View more -->
											</div>
											<!--// Tab panel -->
										</div>
										<!--// Tab content -->
									</div>
									<!--// Menu wrapper -->
								</div>
								<!--// Notifications -->

								<!-- Chat -->
								<div class="app-navbar-item ms-1 ms-md-3">
									<!-- Menu wrapper -->
									<div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px position-relative" id="kt_drawer_chat_toggle">
										<i class="ki-duotone ki-notification-status fs-2 fs-lg-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
										<span class="bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle top-0 start-50 animation-blink">
										</span>
									</div>
									<!--// Menu wrapper -->
								</div>
								<!--// Chat -->

								<!-- My apps links -->
								<div class="app-navbar-item app-navbar-item-apps ms-1 ms-md-3">
									<!-- Menu wrapper -->
									<div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'click'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<i class="ki-duotone ki-element-11 fs-2 fs-lg-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
									</div>
									<!-- My apps -->
									<div class="menu menu-sub menu-sub-dropdown menu-column w-100 w-sm-400px" data-kt-menu="true">
										<!-- Card -->
										<div class="card">
											<!-- Card header -->
											<div class="card-header">
												<!-- Card title -->
												<div class="card-title">즐겨찾기</div>
												<!--// Card title -->
												<!-- Card toolbar -->
												<div class="card-toolbar">
													<a href="javascript:void(0)" onclick="location.href='<?=$local_dir;?>/index.php?fmode=myinfo&amp;smode=bookmark'" class="btn btn-sm btn-icon btn-active-light-primary me-n3">
														<i class="ki-duotone ki-setting-3 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
													</a>
												</div>
												<!--// Card toolbar -->
											</div>
											<!--// Card header -->
											<!-- Card body -->
											<div class="card-body py-5">
												<!-- Scroll - 하위 메뉴는 상위 1차 메뉴의 아이콘과 동일하게 -->
												<div class="mh-450px scroll-y me-n5 pe-5">
													<!-- Row -->
													<div class="row g-2">
														<?
														$where = " and mb.comp_idx = '" . $code_comp . "' and mb.mem_idx = '" . $code_mem . "'and mb.view_yn = 'Y' and mac.view_yn = 'Y' and mi.menu_num = 0";
														$order = "mi.sort asc";
														$bookmark_list = member_bookmark_data('list', $where, $order, '', '');

														if ($bookmark_list["total_num"] > 0) {
															foreach($bookmark_list as $k => $bookmark)
															{
																if (is_array($bookmark))
																{
														?>

														<!-- Col -->
														<div class="col-4">
															<a href="javascript:void(0)" onclick="location.href='<?=$local_dir;?>/index.php?fmode=<?=$bookmark['mode_folder'];?>&smode=<?=$bookmark['mode_file'];?>'"class="d-flex flex-column flex-center text-center text-gray-800 text-hover-primary bg-hover-light rounded py-4 px-3">
																<i class="ki-duotone ki-profile-circle fs-2tx mb-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
																<span class="fw-semibold"><?=$bookmark['menu_name'];?></span>
															</a>
														</div>
														<!--// Col -->
														<?
																}
															}
														}
														?>
													</div>
													<!--// Row -->
												</div>
												<!--// Scroll -->
											</div>
											<!--// Card body -->
										</div>
										<!--// Card -->
									</div>
									<!--// My apps -->
									<!--// Menu wrapper -->
								</div>
								<!--// My apps links -->

								<!-- User menu -->
								<div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
									<?
									$where = " and mem.mem_idx = '" . $code_mem . "'";
									$mem_data  = member_info_data('view', $where);									
									$mem_img = member_img_view($code_mem, $comp_member_dir);
									?>
									<!-- Menu wrapper -->
									<div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<!--img src="<?=$local_dir;?>/bizstory/assets/media/avatars/300-1.png" alt="user"-->
										<?=$mem_img['img_26'];?>
									</div>
									<!-- User account menu -->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color py-4 fs-7 w-275px" data-kt-menu="true">
										<!-- Menu item -->
										<div class="menu-item px-3">
											<div class="menu-content d-flex align-items-center px-3">
												<!-- Avatar -->
												<div class="symbol symbol-50px me-5">
													<!--img alt="Logo" src="<?=$local_dir;?>/bizstory/assets/media/avatars/300-1.png"-->
													<?=$mem_img['img_26'];?>
												</div>
												<!--// Avatar -->
												<!-- Username -->
												<div class="d-flex flex-column">
													<div class="fw-semibold d-flex align-items-center fs-5">
														<?=$code_mem_name;?> <?if($mem_data['ubstory_yn']=='Y'){?><span class="badge badge-light-danger fs-8 px-2 py-1 ms-2">Admin</span><?}?>
													</div>
													<a href="javascript:void(0);" class="text-muted text-hover-primary fs-8 kt_user_button">
														<?=$mem_data['mem_email']?>
													</a>
												</div>
												<!--// Username -->
											</div>
										</div>
										<!--// Menu item -->
										<!-- Menu separator -->
										<div class="separator my-2"></div>
										<!--// Menu separator -->

										<!-- Menu item -->
										<div class="menu-item px-5">
											<a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/index.php?fmode=myinfo&amp;smode=myinfo'" class="menu-link px-5">
												정보수정
											</a>
										</div>
										<!--// Menu item -->

										<!-- Menu item -->
										<div class="menu-item px-5">
											<a href="javascript:void(0);" onclick="location.href='<?=$this_page;?>?fmode=receipt&smode=receipt'" class="menu-link px-5">
												접수목록
											</a>
										</div>
										<!--// Menu item -->

										<!-- Menu item -->
										<div class="menu-item px-5">
											<a href="javascript:void(0);" onclick="login_out()" class="menu-link px-5">
												로그아웃
											</a>
										</div>
										<!--// Menu item -->
									</div>
									<!--// User account menu -->
									<!--// Menu wrapper -->
								</div>
								<!--// User menu -->
							</div>
							<!--// Navbar -->
						</div>
						<!--// Header wrapper -->
					</div>
					<!--// Header container -->
				</div>
				<!--// Header -->