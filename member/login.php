<?
/*
	위치 : 로그인	
	생성 : 2013.05.20 - 첫 개발
	수정 : 2024.01.02 - 리뉴얼_김소령
*/


	include "../common/setting.php";
	include $local_path . "/include/header.php";

	$move_url = urldecode($move_url);
	$move_url_arr = explode('&', $move_url);
	$total_url = '';
	foreach ($move_url_arr as $k => $v)
	{
		if ($k == 0)
		{
			$total_url = $v;
		}
		else
		{
			$total_url .= '&' . $v;
		}
	}
?>
	<!-- Body -->
	<body id="kt_body" class="app-blank">
		<!-- Root -->
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<!-- Authentication - Sign-in  -->
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<!-- Body -->
				<div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-3 p-lg-8 order-2 order-lg-1">
					<!-- Form -->
					<div class="d-flex flex-center flex-column flex-lg-row-fluid">
						<!-- Wrapper -->
						<div class="w-lg-400px p-3 p-lg-8">
							<!-- Form -->
							<form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="/index.php" action="#">
								<input type="hidden" name="sub_type" id="login_sub_type" value="check_login" />
								<input type="hidden" name="move_url" id="login_move_url" value="<?=$move_url;?>" />
								<!-- Heading -->
								<div class="text-center mb-11 ls-n3">
									<h1 class="text-dark fw-bolder mb-3">
										비즈스토리 <span class="fw-normal">로그인</span>
									</h1>
									<div class="text-gray-500 fs-7">
										사람의 가능성을 현실의 가치로 만드는 스마트 업무공간
									</div>
								</div>
								<!-- Heading -->

								<!-- Separator -->
								<div class="d-none d-lg-block">
									<div class="separator separator-content my-14">
										<img alt="Logo" src="<?=$local_dir;?>/bizstory/assets/media/logos/default.svg" class="h-15px h-lg-20px">
									</div>
								</div>
								<!--// Separator -->

								<!-- Input group- -->
								<div class="fv-row mb-8">
									<input type="text" placeholder="아이디" name="param[mem_id]" id="login_mem_id" autocomplete="off" class="form-control bg-transparent">
								</div>

								<div class="fv-row mb-3">
									<input type="password" placeholder="패스워드" name="param[mem_pwd]" id="login_mem_pwd" autocomplete="off" class="form-control bg-transparent">
								</div>
								<!--// Input group- -->

								<!-- Wrapper -->
								<div class="d-flex flex-stack flex-wrap gap-3 fs-base mb-8">
									<div></div>
									<div>
										<a href="#" class="text-gray-700">
											<strong class="fw-semibold">아이디</strong> 찾기
										</a>
										<a href="#" class="text-gray-700 ms-2">
											<strong class="fw-semibold">비밀번호</strong> 찾기
										</a>
									</div>
								</div>								
								<!--// Wrapper -->

								<!-- Submit button -->
								<div class="d-grid mb-10">
									<button type="submit" id="kt_sign_in_submit" class="btn btn-warning">
										<span class="indicator-label fw-bold">LOGIN</span>
										<span class="indicator-progress">
											기다리세요...<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
										</span>
									</button>
								</div>
								<!--// Submit button -->

								<!-- 데모신청 -->
								<div class="text-gray-500 text-center fs-7 ls-n3">
									언제 어디서나 스마트 업무 체험하기!
									<a href="javascript:void(0);" class="link-danger fw-semibold kt_demo_button ms-2">
										서비스 신청하기
									</a>
								</div>
								<!--// 데모신청 -->

								<!-- CareCon 다운로드 받기 -->
								<div class="text-gray-500 text-center fs-7 ls-n3">									
									<a href="javascript:void(0);">
										CareCon 다운로드 받기
									</a>
								</div>
								<!--// CareCon 다운로드 받기 -->

							</form>
							<!--// Form -->
						</div>
						<!--// Wrapper -->
					</div>
					<!--// Form -->
				</div>
				<!--// Body -->

				<!-- Aside -->
				<div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url(<?=$local_dir;?>/bizstory/assets/media/misc/auth-bg.png)">
					<!-- Content -->
					<div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-10 w-100">
						<!-- Logo -->
						<img alt="Logo" src="<?=$local_dir;?>/bizstory/assets/media/logos/default-dark.svg" class="h-25px h-lg-35px mb-0 mb-lg-12">
						<!--// Logo -->

						<!-- Image -->
						<img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-15" src="<?=$local_dir;?>/bizstory/assets/media/misc/auth-screens.png" alt="">
						<!--// Image -->

						<!-- Title -->
						<h1 class="d-none d-lg-block text-white fs-2 fw-bolder text-center text-uppercase mb-4 mb-lg-6">
							Today, start your business!
						</h1>
						<!--// Title -->

						<!-- Text -->
						<div class="d-none d-lg-block text-white fs-base text-center">
							강소기업의 <span class="text-warning fw-semibold">승승장구 비법</span>을 담은 업무 중심 협업 솔루션입니다.<br />
							중소기업의 힘은 빠르고 열린 의사결정과 효율적인 업무 소통에 있습니다.<br />
							조직원들의 가지고 있는 <span class="text-warning fw-semibold">힘을 한곳에 모아</span> 최고의 팀 성과 도출
						</div>
						<!--// Text -->
					</div>
					<!--// Content -->
				</div>
				<!--// Aside -->
			</div>
			<!--// Authentication - Sign-in -->
		</div>
		<!--// Root -->

		<!-- 데모 신청하기 -->
		<div id="kt_demo" class="bg-white drawer drawer-end" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-toggle=".kt_demo_button" data-kt-drawer-close="#kt_demo_close, #kt_demo_cancel" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'94%', 'sm': '650px', 'lg': '450px', 'xl': '550px'}">
			<div class="card w-100 rounded-0">
				<div class="card-header pe-5">
					<div class="card-title">
						<strong class="fs-4 fw-bold text-gray-900 me-1 lh-1 ls-n3">사용신청 신청하기</strong>
					</div>
					<div class="card-toolbar">
						<div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_demo_close">
							<i class="ki-outline ki-cross fs-1"></i>
						</div>
					</div>
				</div>
				<div class="card-body hover-scroll-overlay-y text-start pt-5">
					<div class="d-grid mb-5">
						<ul class="nav nav-tabs flex-nowrap text-nowrap">
							<li class="nav-item">
								<a class="nav-link btn btn-active-light-warning btn-color-gray-600 btn-active-color-warning rounded-bottom-0 active" data-bs-toggle="tab" href="javascript:void(0);" onclick="company_joinform(1)">
									<i class="ki-duotone ki-teacher fs-4 mt-1"><span class="path1"></span><span class="path2"></span></i>학교
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link btn btn-active-light-warning btn-color-gray-600 btn-active-color-warning rounded-bottom-0" data-bs-toggle="tab" href="javascript:void(0);" onclick="company_joinform(2)">
									<i class="ki-duotone ki-brifecase-tick fs-4 mt-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> 기업
								</a>
							</li>
						</ul>
					</div>
					<div class="tab-content" id="myTabContent">
						<!-- 데모 신청 form -->
					</div>
				</div>
			</div>
		</div>
		
		<?php
			include_once("{$local_path}/bizstory/include/modal.php");
		?>

		<!--// 데모 신청하기 -->
		<!-- Custom Javascript (used for this page only) -->
		<script src="<?=$local_dir;?>/bizstory/js/custom/authentication/sign-in/general.js"></script>
		<script src="<?=$local_dir;?>/bizstory/js/script_member.js?<?=date('ymdhis')?>"></script>
		<script src="<?=$local_dir;?>/bizstory/js/script_login.js?<?=date('ymdhis')?>"></script>		
		<script>
			company_joinform(1);
			var link_ok = "<?=$local_dir;?>/bizstory/member/regist_ok.php";
		</script>
		
	</body>
	<!--// Body -->
</html>