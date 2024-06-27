
	$(document).ready(function() {

	// Side Bar
		window.onload = sidebar;

		$("#footer").html('<address><em>Copyright &copy;</em><strong>BIZSTORY</strong><span>All Rights Reserved.</span></address>');
		$(".engName").html('BIZSTORY');
		$(".hanName").html('비즈스토리');

	// Clock
		$('#clock').epiclock();
	// Tabs
		$(".tabs").css({"display": "block"});
		//$('#tabs').tabs({ cookie: { expires: 30 } });
		$('#tabs').tabs();
		$('#tabs_status').tabs();// - 이력

		//$(".popup_bottom").click(function(){bookmark_open();});
		$("#backgroundPopup").click(function(){popupform_close();}); // 등록폼 닫기
		//$("#backgroundPopup").click(function(){popupform_close(); bookmark_close(); popupwrite();}); // 등록폼 닫기
	//only need force for IE6
		$("#backgroundPopup").css({
			"height": document.documentElement.clientHeight
		});
/*
	// Source Plus Area
		$("#source_plus_area").hide();
		$('#source_plus').click(function(){
			if ($('#source_plus:checked').val() == 'on') {
				$("#source_plus_area").fadeIn();
				$("#loading").fadeIn('slow').fadeOut('slow');
			} else {
				$("#source_plus_area").fadeOut();
				$("#loading").fadeIn('slow').fadeOut('slow');
			}
		});
*/
	// 즐겨찾기부분
		var stageWidth = 190;
		var itemUnit = 107;
		var orderArray = [];
		var total_fav = 6;

		var defaultOrderArray = [];
		$('#psvcList li').each(function(i){
			defaultOrderArray.push($(this).html());
		});

		var reOrderHTMLArray = [];
		var reOrderSetHTMLArray = [];
		var itemLen = $('#psvcList').length;
		var count = 1;
		reLocationList();

		$('#setting').click(function(){
			$('#psvcSetList :checkbox').removeAttr('checked');
			$('#psvcSetList label').removeClass('on');
			$('#scrollSetMenuWrap').hide();
			$('#selectFavorWrap').hide().slideDown(200);

			// 쿠키 존재 시 체크박스 checked
			if($.cookie('psvcOrder') != null)
			{
				//쿠키에 저장된 순서대로 초기 6값 셋팅
				$.each($.cookie('psvcOrder').split(','),function(i){
					$('#psvcSetList :checkbox')[this].click();
				});
			}
			else
			{
				$('#settingReset').click();
			}

			return false;
		});

		$('#settingReset').click(function(){
			$('#psvcSetList :checkbox').removeAttr('checked');
			$('#psvcSetList label').removeClass('on');

			for(var i = 0 ; i < total_fav ; i++)
			{
				$('#psvcSetList :checkbox')[i].click();
			}
			return false;
		});

		$('#settingClose').click(function(){
			$('#primarySvcWrap').animate({top : '374px'},300);
			$('#scrollSetMenuWrap').show();
			$('#selectFavorWrap').slideUp(300);
			return false;
		});

		$('#settingSave').click(function(){
			
			if (window.confirm('저장하시겠습니까?') && $('#psvcSetList :checkbox:checked').length == total_fav)
			{
				//some cookie action
				orderArray = [];
				$('#psvcSetList label.on').each(function(){
					orderArray.push($(this).parent().index());
				});

				$.cookie('psvcOrder', orderArray, {expires:365});
				
				$('#settingClose').click();
				orderArray = [];
				
				count = countMax; //저장 후 슬라이드 첫번째로 이동
				//$('#btnNext').click();

				reLocationList(); //슬라이드 재 정의

			} else {
				alert('6개 메뉴를 선택하셔야 저장이 가능합니다');
			}
			return false;
		});
		$('#btnPrev,#btnNext').hover(function(){$(this).addClass('on')},function(){$(this).removeClass('on')});
		var countMax = Math.ceil($('.mask li').length*itemUnit/stageWidth);
		$('#btnPrev').click(function(){
			if(count > 1) {
				$('.mask ul').animate({left:'+='+stageWidth+'px'},350);
				count--;
			}
		});
		$('#btnNext').click(function(){
			if(count < countMax) {
				$('.mask ul').animate({left:'-'+count*stageWidth+'px'},350);
				count++;
			} else {
				$('.mask ul').animate({left:'0'},350);
				count = 1;
			}
		});

		$('#psvcSetList label').click(function(e){
			e.preventDefault();
			if($('#psvcSetList label.on').size() >= total_fav &&  !$(this).hasClass('on')){
				alert('최대 ' + total_fav + '개까지만 선택가능합니다');
			} else {
				$(this).prev().click();
			}
			
		});
		$('#psvcSetList :checkbox').click(function(){
			if($('#psvcSetList :checkbox:checked').size() > 7 &&  !$(this).next().hasClass('on')) {
				$(this).removeAttr('checked')
			} else {
				$(this).next().toggleClass('on');
			}
		});

		function reLocationList()
		{
			if($.cookie('psvcOrder') != null)
			{
				orderArray = $.cookie('psvcOrder').split(',');

				//쿠키에 저장된 순서대로 초기 6값 셋팅
				$.each(orderArray,function(i)
				{
					$('#psvcList li').eq(i).html(defaultOrderArray[this]);
					defaultOrderArray.splice(this,0);
				});

				//나머지 9개 셋팅
				var tmpCnt = total_fav;
				for(var i = 0 ; i < defaultOrderArray.length; i++){
					var tmpFlag = true; //쿠키에 존재하는 순번인지 체크
					$.each(orderArray,function(j){
						if(i == parseInt(orderArray[j])){
							tmpFlag = false
						}
					});
					if(tmpFlag){ //쿠키에 존재 하지 않는다면 추가 한다.
						$('#psvcList li').eq(tmpCnt).html(defaultOrderArray[i]);
						tmpCnt++;
					}
				}
			}
		}
	});

	jQuery(function($){
	// Top Toggle
		var switcher = $("#style-switcher");
		if(jQuery.trim($.cookie('top-frame-state')) == 'close')
		{
			switcher.css("top","-43px");
			$('#layout_table').css("top","0");
			$('#toggle-top').show();
		}
		$("#toggle-top").click(function(e){
			switcher.animate({top:0},'normal');
			$('#layout_table').animate({top:43},'normal');
			$(this).fadeOut('fast');
			$("#loading").fadeIn('fast').fadeOut('slow');
			$.cookie("top-frame-state","open",30);
		});
		$("#top-close").click(function(e){
			switcher.animate({top:-43},'normal');
			$('#layout_table').animate({top:0},'normal',function(){ $('#toggle-top').fadeIn('fast'); });
			$("#loading").fadeIn('fast').fadeOut('slow');
			$.cookie("top-frame-state","close",30);
		});

	// Sidebar Toggle
		var switcher2 = $("#sidebar");
		if(jQuery.trim($.cookie('sidebar-frame-state'))=='open')
		{
			switcher2.css("width","204px");
			$('#sidebar_width').fadeIn('slow');
			$('#sidebar-close').show();
			$("#toggle-sidebar").hide().fadeOut("slow");
		}
		$("#toggle-sidebar").click(function(e){
			switcher2.animate( { width: "204px" }, { queue: false, duration: 1000 });
			$('#sidebar_width').fadeIn('slow');
			$('#sidebar-close').show();
			$("#toggle-sidebar").hide().fadeOut("slow");
			$("#loading").fadeIn('fast').fadeOut('slow');
			$.cookie("sidebar-frame-state","open",30);
		});
		$("#sidebar-close").click(function(e){
			switcher2.animate( { width: "0" }, { queue: false, duration: 1000 });
			$('#sidebar_width').fadeOut('slow');
			$('#sidebar-close').hide();
			$('#toggle-sidebar').show();
			$("#loading").fadeIn('fast').fadeOut('slow');
			$.cookie("sidebar-frame-state","close",30);
		});
	});

// Sub Navi
	this.sidebar = function(){
		var sidebar = document.getElementById("sub_navi")
		if(sidebar)
		{
			this.listItem = function(li){
				if(li.getElementsByTagName("ul").length > 0)
				{
					var ul = li.getElementsByTagName("ul")[0];
					var ul_id = $(ul).attr('id');
					var ul_id_arr = ul_id.split('_');
					var chk_ul_id = ul_id_arr[0] + '_' +  ul_id_arr[1];

					ul.style.display = "none";
					var span = document.createElement("span");
					span.className = "collapsed";
					span.onclick = function(){
						ul.style.display = (ul.style.display == "none") ? "block" : "none";
						this.className = (ul.style.display == "none") ? "collapsed" : "expanded";
					};

					var sub_menu_arr = now_sub_menu_id.split('_');
					var left_str = 'submenu';
					for (var left_num = 1; left_num < 10; left_num++)
					{
						if (sub_menu_arr[left_num] != undefined)
						{
							left_str = left_str + '_' + sub_menu_arr[left_num];
							$("#" + left_str).css({"display": "block"});
							if (ul_id == left_str)
							{
								span.className = "expanded";
							}
						}
					}
					li.appendChild(span);
				};
			};
			var items = sidebar.getElementsByTagName("li");
			for(var i = 0; i < items.length; i++)
			{
				listItem(items[i]);
			};
		};
	};
