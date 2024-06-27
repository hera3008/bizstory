
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
		$('#tabs').tabs(); // 내용
		$('#tabs_status').tabs(); // 이력

		$("#backgroundPopup").click(function(){popupform_close(); popupform_close2();}); // 등록폼 닫기
	//only need force for IE6
		$("#backgroundPopup").css({
			"height": document.documentElement.clientHeight
		});
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
