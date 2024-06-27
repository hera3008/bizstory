	<div id="footer">
			
		<ul class="navTab navTab_footer col5">
			<li><a class="home" href="javascript:void(0)" onclick="window.location.href='index.php'"><img src="./images/f_icon1.png" width="124px" height="124px" alt="메인" />메인</a></li>
			<li><a class="member" href="javascript:void(0)" onclick="window.location.href='member_list.php'"><img src="./images/f_icon2.png" width="124px" height="124px" alt="멤버" />멤버</a></li>
			<li><a class="favorites" href="javascript:void(0)" onclick="window.location.href='client_list.php'"><img src="./images/f_icon6.png" width="124px" height="124px" alt="고객목록" />고객목록</a></li>
			<li><a class="vdrive" href="javascript:void(0)" onclick="window.location.href='vdrive_list.php'"><img src="./images/f_icon3.png" width="124px" height="124px" alt="V-CENTER" />V-CENTER</a></li>
			<li><a class="setting" href="javascript:void(0)" onclick="window.location.href='set_up.php'"><img src="./images/f_icon5.png" width="124px" height="124px" alt="설정" />설정</a></li>
		</ul>

	</div>
</div>

<!-- 팝업 내용 -->
<div class="md-modal md-effect" id="modal">
	<div class="md-content">
		<h3></h3>
		<div>
			<img class="photo" src="" alt="" />
			<ul>

			</ul>
			<button class="md-close"><img src="./images/btn_close.png" alt="닫기" /></button>
		</div>
	</div>
</div>
<!-- //팝업 내용 -->

<!-- 팝업 내용 -->
<div class="md-modal md-effect" id="alert-modal">
	<div class="md-content">
		<h3>[TITLE]</h3>
		<ul style="padding: 10px;">
			<li>
				[MESSAGE]
			</li>
			<li>
				<a href="javascript:" onclick="" class="md-close btn07">닫기</a>
			</li>
		</ul>
		
	</div>
</div>
<!-- //팝업 내용 -->

<!-- 팝업 내용 -->
<div class="md-modal md-effect" id="confirm-modal">
	<div class="md-content">
		<h3>[TITLE]</h3>
		<ul style="padding: 10px;">
			[MESSAGE]
		</ul>			
	</div>
</div>
<!-- //팝업 내용 -->

<div class="md-overlay"></div>
<div id="popup">

	&nbsp;

</div>
<script>
	var modal = null;

	function alertMsg(msg) {
		//var title = "비즈스토리";
		//$("#alertmodal .md-content h3").html( title );
		//$("#alertmodal .md-content div ul").html( msg );
		var message = $("#alert-modal").html();
		message = message.replace("[TITLE]", "BIZSTORY");
		message = message.replace("[MESSAGE]", msg);
		
		$("#alert-modal").html( message );
		
		var overlay = document.querySelector('.md-overlay');
		
		modal = document.querySelector("#alert-modal");		
	
		function removeModalHandler() {
			removeModal( classie.has( document.querySelector("#alert-modal"), 'md-setperspective' ) ); 
		}
	
		function removeModal( hasPerspective ) {
			classie.remove( modal, 'md-show' );
	
			if( hasPerspective ) {
				classie.remove( document.documentElement, 'md-perspective' );
			}
		}

			
		overlay.removeEventListener('click', removeModalHandler);
		overlay.addEventListener('click', removeModalHandler);
		
		
		classie.add(modal, 'md-show');
		if (classie.has(document.querySelector("#alert-modal"), 'md-setperspective')) {
			setTimeout( function() {
				classie.add( document.documentElement, 'md-perspective' );
			}, 25 );
			
		}
		$("#alert-modal").delegate('.md-close', 'click', function(e) {
			e.stopPropagation();
			removeModalHandler();
		});
	}

	function confirmMessage(title, msg) {
		//var title = "비즈스토리";
		//$("#alertmodal .md-content h3").html( title );
		//$("#alertmodal .md-content div ul").html( msg );
		var message = $("#confirm-modal").html();
		message = message.replace("[TITLE]", title);
		message = message.replace("[MESSAGE]", msg);
		
		$("#confirm-modal").html( message );
		
		var overlay = document.querySelector('.md-overlay');
		
		modal = document.querySelector("#confirm-modal");


		function removeModalHandler() {
			removeModal( classie.has( document.querySelector("#alert-modal"), 'md-setperspective' ) ); 
		}
	
		function removeModal( hasPerspective ) {
			classie.remove( modal, 'md-show' );
	
			if( hasPerspective ) {
				classie.remove( document.documentElement, 'md-perspective' );
			}
		}

			
		overlay.removeEventListener('click', removeModalHandler);
		overlay.addEventListener('click', removeModalHandler);
		
		
		classie.add(modal, 'md-show');
		if (classie.has(document.querySelector("#confirm-modal"), 'md-setperspective')) {
			setTimeout( function() {
				classie.add( document.documentElement, 'md-perspective' );
			}, 25 );
			
		}
		
		$("#confirm-modal").delegate('.md-close', 'click', function(e) {
			e.stopPropagation();
			removeModalHandler();
		});
	}
	
	
	function viewMemInfo(idx) {
		
		$.ajax({
			type: 'post',
			url: './process/ajax_member_info.php',
			async: true,
			data: {'mem_idx': idx},
			dataType: 'json',
			success: function(json) {
				//console.log(json);
				if (json.data !== null) {
					var info = json.data;
					
					var html = '<li class="group">' + info.part_name + ' : ' + info.group_name + '</li>' + 
						'<li class="email"><a href="mailto:' + info.mem_email + '">' + info.mem_email + '</a></li>' + 
						'<li class="tel"><a href="tel:' + info.hp_num + '">' + info.hp_num + '</a></li>' + 
						'<li class="date">최종접속 : ' + info.last_date + '</li>';
					
					$("#modal .md-content h3").html( info.mem_name + '<span>' + info.duty_name + '</span>' );
					$("#modal .md-content .photo").remove();
					$("#modal .md-content div").prepend(json.mem_img.img_53);
					$("#modal .md-content div ul").html( html );

				}

			}
		});
	}
	
	function popupMemInfo(idx) {
		
		$.ajax({
			type: 'post',
			url: './process/ajax_member_info.php',
			async: true,
			data: {'mem_idx': idx},
			dataType: 'json',
			success: function(json) {
				//console.log(json);
				if (json.data !== null) {
					var info = json.data;
					var html = [];
					
					html.push('<h3>' + info.mem_name + '<span>' + info.duty_name + '</span></h3>');
					html.push('<div>');
					//html.push('<img class="photo" src="' + json.mem_img.img_53 + '" alt="" width="53px" height="70px" />');
					html.push(json.mem_img.img_53);
					html.push('<ul>');
					html.push('<li class="group">' + info.part_name + ' : ' + info.group_name + '</li>');
					html.push('<li class="email"><a href="mailto:' + info.mem_email + '">' + info.mem_email + '</a></li>');
					html.push('<li class="tel"><a class="call_me" href="tel:' + info.hp_num + '">' + info.hp_num + '</a></li>');
					html.push('<li class="date">최종접속 : ' + info.last_date + '</li>');
					html.push('</ul>');
					html.push('<button class="b-close"><img src="./images/btn_close.png" alt="닫기"></button>');
					html.push('</div>');
					
					$("#popup").html(html.join('\n'));

				}

			}
		});
		
	}
	
	function clearPopup() {
		$("#popup").html("&nbsp");
	}
	
	$(function() {
		$(document).delegate('b-close', 'click', function(e) {
			clearPopup();
		});
	})
	// this is important for IEs 팝업관련
	var polyfilter_scriptpath = '/js/';
</script>
<!-- 팝업관련 -->
<script type="text/javascript" src="./js/modalEffects.js"></script>
<script type="text/javascript" src="./js/classie.js"></script>
<!-- //팝업관련 -->

<script type="text/javascript" src="./js/jquery.bpopup-0.9.4.min.js"></script> <!-- bpopup관련 -->
<script type="text/javascript" src="./js/scripting.min.js"></script> <!-- bpopup관련 -->
<!-- 팝업창 -->	


</body>
</html>
<?
	db_close();
?>
