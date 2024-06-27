<!--  Sidebar -->
<div id="kt_app_sidebar" class="app-sidebar  flex-column " data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--  Logo -->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--  Logo image -->
        <a href="/main.php">
        <?
            // 총판로고
                $sole_logo_where = " and solef.sole_idx = '" . $company_info_data['sole_idx'] . "' and solef.sort = '1'";
                $sole_logo_data  = sole_file_data('view', $sole_logo_where);
                if ($sole_logo_data['img_sname'] == '')
                {
                    $sole_logo_img = $local_dir . '/assets/media/logos/default-dark.svg';
                }
                else
                {
                    $sole_logo_path = $local_path . "/data/sole/" . $sole_logo_data['img_sname'];
                    $sole_logo_img  = $local_dir . "/data/sole/" . $sole_logo_data['img_sname'];

                    if (file_exists($sole_logo_path) == true)
                    {}
                    else $sole_logo_img = $local_dir . '/assets/media/logos/default-dark.svg';
                }

                $site_url1  = $_SERVER['SERVER_NAME'];
                $site_url1 = str_replace('www.', '', $site_url1);
                if ($site_url1 == 'on-trade.co.kr')
                {
                    echo '<img src="' . $local_dir . '/bizstory/images/ontrade/logo_ontrade.jpg" width="131px" height="28px" alt="Logo" />'; // On Trade Logo
                }
                else if ($site_url1 == 'v-center.co.kr' || $site_url1 == 'vcenter.ubstory.co.kr')
                {
                    echo '<img src="' . $local_dir . '/bizstory/images/vcenter/logo_vcenter.jpg" width="131px" height="25px" alt="Logo" />'; // V Center Logo
                }
                else if ($site_url1 == 'gw.neoarena.co.kr' || $site_url1 == 'gw.neoarena.com' || $site_url1 == 'neoarena.ubstory.co.kr')
                {
                    echo '<img src="' . $local_dir . '/bizstory/images/neoarena/logo_neoarena.jpg" width="131px" height="25px" alt="home" />'; // Neoarena Logo
                }
                else
                {
                    echo '<img src="' . $sole_logo_img . '" alt="Logo" class="h-25px app-sidebar-logo-default" />'; // Bizstory Logo
                }
            ?>
            <img alt="Logo" src="<?=$local_dir?>/assets/media/logos/default-small.svg" class="h-20px app-sidebar-logo-minimize">
        </a>
        <!--// Logo image -->

        <!--  Sidebar toggle -->
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate "
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-double-left fs-2 rotate-180"><span class="path1"></span><span
                    class="path2"></span></i>
        </div>
        <!--// Sidebar toggle -->
    </div>
    <!--// Logo -->

    <!--  sidebar menu -->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--  Menu wrapper -->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <!--  Menu -->
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">
                <!--  마이페이지 -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link text-hover-gray-300 text-active-gray-300"><span class="menu-icon"><i
                                class="ki-duotone ki-profile-circle fs-2 text-gray-600"><span class="path1"></span><span
                                    class="path2"></span><span class="path3"></span></i></span><span
                            class="menu-title">마이페이지</span><span class="menu-arrow"></span></span>
                    <!--  Menu sub -->
                    <div class="menu-sub menu-sub-accordion">
                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/mypage/modify.php"
                                class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">정보수정</span></a>
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/mypage/favorites.php"
                                class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">즐겨찾기</span></a>
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/mypage/note.php" class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">쪽지함</span></a>
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/mypage/sms.php" class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">보낸문자내역</span></a>
                        </div>
                        <!--// Menu item -->
                    </div>
                    <!--// Menu sub -->
                </div>
                <!--// 마이페이지 -->


                <!--  접수관리 -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link text-hover-gray-300 text-active-gray-300"><span class="menu-icon"><i
                                class="ki-duotone ki-file-right fs-2 text-gray-600"><span class="path1"></span><span
                                    class="path2"></span></i></span><span class="menu-title">접수관리</span><span
                            class="menu-arrow"></span></span>
                    <!--  Menu sub -->
                    <div class="menu-sub menu-sub-accordion">
                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/receipt/list.php" class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">접수목록</span></a>
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/receipt/write.php"
                                class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">접수등록</span></a>
                        </div>
                        <!--// Menu item -->
                    </div>
                    <!--// Menu sub -->
                </div>
                <!--// 접수관리 -->

                <!--  거래처목록 -->
                <div class="menu-item">
                    <a href="/customer/list.php" class="menu-link text-hover-gray-300 text-active-gray-300">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-office-bag fs-2 text-gray-600"><span class="path1"></span><span
                                    class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        </span>
                        <span class="menu-title">거래처목록</span>
                    </a>
                </div>
                <!--// 거래처목록 -->

                <!--  직원관리 -->
                <div class="menu-item">
                    <a href="/employee/list.php" class="menu-link text-hover-gray-300 text-active-gray-300">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-profile-user fs-2 text-gray-600"><span class="path1"></span><span
                                    class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        </span>
                        <span class="menu-title">직원관리</span>
                    </a>
                </div>
                <!--// 직원관리 -->

                <!--  게시판 -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link text-hover-gray-300 text-active-gray-300"><span class="menu-icon"><i
                                class="ki-duotone ki-message-text fs-2 text-gray-600"><span class="path1"></span><span
                                    class="path2"></span><span class="path3"></span></i></span><span
                            class="menu-title">게시판</span><span class="menu-arrow"></span></span>
                    <!--  Menu sub -->
                    <div class="menu-sub menu-sub-accordion">
                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/bbs/list.php" class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">마케팅자료</span></a>
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/bbs/list.php" class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">NAS자료</span></a>
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/bbs/list.php" class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">도큐먼트자료</span></a>
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/bbs/list.php" class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">기술자료</span></a>
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/bbs/list.php" class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">개발자료</span></a>
                        </div>
                        <!--// Menu item -->
                    </div>
                    <!--// Menu sub -->
                </div>
                <!--// 게시판 -->

                <!--  점검보고서 -->
                <div class="menu-item">
                    <a href="/inspect/list.php" class="menu-link text-hover-gray-300 text-active-gray-300">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-document fs-2 text-gray-600"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </span>
                        <span class="menu-title">점검보고서</span>
                    </a>
                </div>
                <!--// 점검보고서 -->

                <!--  통계관리 -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link text-hover-gray-300 text-active-gray-300"><span class="menu-icon"><i
                                class="ki-duotone ki-chart-pie-3 fs-2 text-gray-600"><span class="path1"></span><span
                                    class="path2"></span><span class="path3"></span></i></span><span
                            class="menu-title">통계관리</span><span class="menu-arrow"></span></span>
                    <!--  Menu sub -->
                    <div class="menu-sub menu-sub-accordion">
                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/statistics/reception.php"
                                class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">접수통계</span></a>
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/statistics/partner.php"
                                class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">거래처별통계</span></a>
                        </div>
                        <!--// Menu item -->
                    </div>
                    <!--// Menu sub -->
                </div>
                <!--// 통계관리 -->


                <!--  설정관리 (show 선택된 페이지 열람 예) -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion show">
                    <span class="menu-link text-hover-gray-300 text-active-gray-300"><span class="menu-icon"><i
                                class="ki-duotone ki-setting-3 fs-2 text-gray-600"><span class="path1"></span><span
                                    class="path2"></span><span class="path3"></span><span class="path4"></span><span
                                    class="path5"></span></i></span><span class="menu-title">설정관리</span><span
                            class="menu-arrow"></span></span>
                    <!--  Menu sub -->
                    <div class="menu-sub menu-sub-accordion">
                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/setting/schoolinfo.php"
                                class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">학교정보</span></a>
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="/setting/menuname.php"
                                class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">메뉴명관리</span></a>
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item  (show 선택된 페이지 열람 예) -->
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion show">
                            <span class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">교직원관리</span><span class="menu-arrow"></span></span>
                            <!--  Menu sub -->
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <!--  Menu item  (active 선택된 페이지 on 예) -->
                                <div class="menu-item">
                                    <a href="/setting/position.php"
                                        class="menu-link text-hover-gray-400 text-active-gray-400 active"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">직책관리</span></a>
                                </div>
                                <!--// Menu item -->

                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="javascript:void(0);"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">부서관리</span></a>
                                </div>
                                <!--// Menu item -->

                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="/setting/registration.php"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">직원등록/수정</span></a>
                                </div>
                                <!--// Menu item -->

                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="javascript:void(0);"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">퇴사직원</span></a>
                                </div>
                                <!--// Menu item -->
                            </div>
                            <!--// Menu sub -->
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">접수관리</span><span class="menu-arrow"></span></span>
                            <!--  Menu sub -->
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="/setting/classification.php"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">접수분류</span></a>
                                </div>
                                <!--// Menu item -->

                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="/setting/state.php"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">접수상태</span></a>
                                </div>
                                <!--// Menu item -->

                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="javascript:void(0);"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">접수알림설정</span></a>
                                </div>
                                <!--// Menu item -->

                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="javascript:void(0);"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">점검항목</span></a>
                                </div>
                                <!--// Menu item -->
                            </div>
                            <!--// Menu sub -->
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">거래처관리</span><span class="menu-arrow"></span></span>
                            <!--  Menu sub -->
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="javascript:void(0);"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">거래처분류</span></a>
                                </div>
                                <!--// Menu item -->

                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="javascript:void(0);"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">거래처등록/수정</span></a>
                                </div>
                                <!--// Menu item -->

                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="javascript:void(0);"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">삭제거래처</span></a>
                                </div>
                                <!--// Menu item -->
                            </div>
                            <!--// Menu sub -->
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">에이전트관리</span><span class="menu-arrow"></span></span>
                            <!--  Menu sub -->
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="javascript:void(0);"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">타입관리</span></a>
                                </div>
                                <!--// Menu item -->

                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="javascript:void(0);"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">아이콘관리</span></a>
                                </div>
                                <!--// Menu item -->

                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="javascript:void(0);"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">배너관리</span></a>
                                </div>
                                <!--// Menu item -->

                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="javascript:void(0);"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">알림관리</span></a>
                                </div>
                                <!--// Menu item -->

                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="javascript:void(0);"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">상담관리</span></a>
                                </div>
                                <!--// Menu item -->

                                <!--  Menu item -->
                                <div class="menu-item">
                                    <a href="javascript:void(0);"
                                        class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                            class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                            class="menu-title">에이전트현황</span></a>
                                </div>
                                <!--// Menu item -->
                            </div>
                            <!--// Menu sub -->
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="javascript:void(0);"
                                class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">팝업관리</span></a>
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="javascript:void(0);"
                                class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">게시판관리</span></a>
                        </div>
                        <!--// Menu item -->

                        <!--  Menu item -->
                        <div class="menu-item">
                            <a href="javascript:void(0);"
                                class="menu-link text-hover-gray-400 text-active-gray-400"><span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span><span
                                    class="menu-title">일정종류</span></a>
                        </div>
                        <!--// Menu item -->
                    </div>
                    <!--// Menu sub -->
                </div>
                <!--// 설정관리 -->



                <!--  스토리지 사용량 -->
                <div class="menu-item menu-title my-5">
                    <div class="menu-content"><span class="fw-semibold text-uppercase text-gray-600 fs-7">스토리지
                            사용량</span></div>
                    <div class="d-flex align-items-center flex-column w-100 px-4">
                        <div class="w-100 h-15px bg-gray-500 rounded mb-2">
                            <div class="bg-warning rounded h-15px" role="progressbar" style="width: 37%"
                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="fw-semibold fs-8 text-gray-700 w-100 mt-auto">
                            <span>15GB 중 <strong class="text-warning fw-bold">2.54GB</strong> 사용</span>
                        </div>
                    </div>
                </div>
                <!--// 스토리지 사용량 -->
            </div>
            <!--// Menu -->
        </div>
        <!--// Menu wrapper -->
    </div>
    <!--// sidebar menu -->

    <!--  Footer -->
    <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
        <a href="javascript:void(0);"
            class="btn btn-sm btn-flex flex-center btn-warning overflow-hidden text-nowrap px-0 h-40px w-100"
            data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click"
            title="개선사항이나 질문이 있을 경우 언제든지 문의주세요.">
            <i class="ki-duotone ki-notepad-edit fs-2 me-2"><span class="path1"></span><span
                    class="path2"></span></i>비즈스토리 건의사항
        </a>
    </div>
    <!--// Footer -->
</div>
<!--// Sidebar -->