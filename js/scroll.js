
/*

#트랙, 바, 화살표등에 이벤트를 줄 경우 target.scrollbar object를 이용해서 접근.
#target에 width와 height이 지정되어있어야 하고, overflow:auto를 기본 적용해 놓은 상태를 권장.
#arrow를 사용할 경우 css에서 margin값을 주면 margin만큼 간격 사용가능.
#태그 없이 텍스트만 있을 경우 wrapper로 사용할 div를 추가하긴 하지만 기본적으로 <div id="scrolltarget"><div>내용</div></div> 형식을 권장.

#onscroll, onscrollstart, onscrollend 이벤트에서 this는 target을 참조.
#지원 함수 target.scrollbar.scrollLeft(value), target.scrollbar.scrollTop(value), target.scrollbar.scrollTo(x, y)

- 함수인수
1. target = 스크롤 할 object (object 자체 또는 object의 아이디(string))
2. option = {
	wrapper : target 안에서 전체 내용을 감싸는 object (object 자체 또는 object의 아이디(string))(지정하지 않을 경우 target object 내에서 첫번째 자식 노드를 사용)
	animate : true, // 애니매이션 효과를 사용할 것인지 여부 true 또는 false
	tracksize : 0, // 트랙의 길이 px(number)(고정) 또는 overflow:hidden된 target object의 offset size에 대해 상대적으로 지정가능(문자열)(ex: '-20', '+40')
						// tracksize의 값이 false가 될 경우 target object의 offset size에 버튼 사용시 버튼의 크기를 제외한 값으로 지정
	movesize : 100, // 버튼 또는 트랙 클릭시 한 번에 이동할 거리 px(number)
	barsize : 'auto', // 바의 길이 px(number) 또는 'auto'
	barminsize : 20, // 바의 최소 길이 px(number)
	barinnerhtml : '', // 바에 들어갈 html
	trackinnerhtml : '', // 트랙에 들어갈 html
	outsidetrack : null, // 트랙을 target으로 지정한 object 외부에서 사용할 경우 지정 (object 자체 또는 object의 아이디(string))
	onscroll : null, // 스크롤 될 때 이벤트 function
	onscrollstart : null, // 스크롤이 시작될 때 이벤트 function
	onscrollend : null, // 스크롤 끝났을 때 이벤트 function
	x : { // 위 옵션을 x 스크롤에 별도로 지정할 경우 지정
		hidden : false, // x 스크롤을 사용할 것인지 여부 true(감추기) 또는 false(보이기)
		... // 이 외 onscroll관련 속성을 제외한 속성명, 속성값 동일
	},
	y : { // 위 옵션을 y 스크롤에 별도로 지정할 경우 지정
		hidden : false, // x 스크롤을 사용할 것인지 여부 true(감추기) 또는 false(보이기)
		... // 이 외 onscroll관련 속성을 제외한 속성명, 속성값 동일
	}
}

*/

function fakescroll(target, option){

	//target 지정
	if(typeof(target)=='string') target=document.getElementById(target);

	//target이 없을 경우 취소
	if(!target) return;

	//target에 자식 노드가 하나도 없을 경우(내용이 없을 경우) 취소
	var childs=target.childNodes;
	if(!childs.length) return;

	//기본 변수
	var i=0, j=0, max, flag=null;

	//태그 찾기
	var findtagsexceptbr=false, insidetags=target.getElementsByTagName('*');
	for(i=0, max=insidetags.length; i<max; i++){
		if(insidetags[i].tagName.toLowerCase()!='br'){
			findtagsexceptbr=true;
			break;
		}
	}

	//target에 태그가 하나도 없을 경우 wrapper로 지정할 div 태그 삽입
	if(!findtagsexceptbr){
		var wrapper=document.createElement('div');
		wrapper.innerHTML=target.innerHTML;
		target.innerHTML='';
		target.appendChild(wrapper);
		childs=target.childNodes;
	}

	//target의 position값이 static일 경우 relative 지정
	if(getstyle(target, 'position')=='static') setstyle(target, 'position', 'relative');

	//target에 overflow:hidden 지정
	setstyle(target, 'overflow', 'hidden');

	//target의 scroll 값을 초기화
	target.scrollTop=0;
	target.scrollLeft=0;

	//기본 옵션 셋팅
	var basicoption={
		wrapper : null,
		animate : true,
		tracksize : 0,
		movesize : 100,
		barsize : 'auto',
		barminsize : 20,
		barinnerhtml : '',
		trackinnerhtml : '',
		outsidetrack : null,
		onscroll : null,
		onscrollstart : null,
		onscrollend : null
	}

	basicoption.tracksizefixed=false;
	if(!option) option={};

	//basicoption변수에 함수인수 option값 지정
	for(i in option){
		if((i=='wrapper' || i=='outsidetrack') && typeof(option[i])=='string') basicoption[i]=document.getElementById(option[i]);
		else if(i=='tracksize'){
			if(typeof(option[i])=='number'){
				basicoption.tracksizefixed=true;
				basicoption.tracksize=option[i];
			}else if(typeof(option[i])=='string' && (/^(\-|\+)/).test(option[i])){
				basicoption.tracksize=parseInt(option[i]);
			}
		}else if(i!='x' && i!='y') basicoption[i]=option[i];
	}

	//wrapper가 지정되지 않았을 경우 target의 첫번째 자식 노드를 지정
	if(!basicoption.wrapper){
		basicoption.wrapper=childs[0];
		if(basicoption.wrapper.nodeType!=1){
			for(i=0; i<childs.length; i++){
				if(childs[i].nodeType==1){
					basicoption.wrapper=childs[i];
					break;
				}
			}
		}
	}

	//browser check
	var browser={
		ie : (/msie/i).test(navigator.userAgent),
		ie6 : (/msie 6/i).test(navigator.userAgent),
		ie7 : (/msie 7/i).test(navigator.userAgent),
		ie8 : (/msie 8/i).test(navigator.userAgent),
		ie9 : (/msie 9/i).test(navigator.userAgent),
		firefox : (/firefox/i).test(navigator.userAgent),
		webkit : (/applewebkit/i).test(navigator.userAgent),
		opera : (/opera/i).test(navigator.userAgent),
		mobile : (/(ipod|iphone|ipad|android)/i).test(navigator.userAgent)
	}

	//함수내에서 참조할  옵션값 - basicoption에서 카피해서 x, y별도 분리
	var opt={
		x : { outsidetrack : null, hidden : false },
		y : { outsidetrack : null, hidden : false }
	}
	for(i=0; i<2; i++){
		flag=(!i)? 'x' : 'y';
		for(var j in basicoption){
			if(!(/onscroll/).test(j)) opt[flag][j]=basicoption[j];
		}
		if(option[flag]){
			for(j in option[flag]){
				if((j=='wrapper' || j=='outsidetrack') && typeof(option[flag][j])=='string'){
					opt[flag][j]=document.getElementById(option[flag][j]);
				}else if(j=='tracksize'){
					if(typeof(option[flag][j])=='number'){
						opt[flag].tracksizefixed=true;
						opt[flag][j]=option[flag][j];
					}else if(typeof(option[flag][j])=='string' && (/^(\-|\+)/).test(option[flag][j])){
						opt[flag][j]=parseInt(option[flag][j]);
					}
				}else{
					opt[flag][j]=option[flag][j];
				}
			}
			if(!opt[flag].wrapper) opt[flag].wrapper=basicoption.wrapper;
		}
		opt[flag].wrapper.flag='wrapper';
	}
	if(browser.webkit) setstyle(opt.y.wrapper, 'marginTop', 1);//for webkit bug

	//scrollbar object
	var scrollbar={
		x : {},
		y : {},
		option : opt,
		onscroll : basicoption.onscroll,
		onscrollstart : basicoption.onscrollstart,
		onscrollend : basicoption.onscrollend,
		setted : false
	};

	//클래스 명
	var classes=[
		['scroll-x-track', 'scroll-x-bar', 'scroll-x-arrow-left', 'scroll-x-arrow-right'],
		['scroll-y-track', 'scroll-y-bar', 'scroll-y-arrow-up', 'scroll-y-arrow-down']
	];

	//object 생성 및 추가
	for(i=0; i<2; i++){

		flag=(!i)? 'x' : 'y';

		if(!opt[flag].hidden){

			scrollbar[flag].track=create(classes[i][0]);
			if(opt[flag].trackinnerhtml) scrollbar[flag].track.innerHTML=opt[flag].trackinnerhtml;
			scrollbar[flag].bar=create(classes[i][1]);
			if(opt[flag].barinnerhtml) scrollbar[flag].bar.innerHTML=opt[flag].barinnerhtml;
			scrollbar[flag].track.appendChild(scrollbar[flag].bar);

			scrollbar[flag].arrow=[];
			scrollbar[flag].arrow[0]=create(classes[i][2]);
			scrollbar[flag].arrow[1]=create(classes[i][3]);
			scrollbar[flag].track.appendChild(scrollbar[flag].arrow[0]);
			scrollbar[flag].track.appendChild(scrollbar[flag].arrow[1]);

			appendtarget=(opt[flag].outsidetrack)? opt[flag].outsidetrack : target;
			appendtarget.appendChild(scrollbar[flag].track);

			//arrow[0]의 offsetWidth가 없을 경우 arrow를 사용하지 않는 것으로 간주. arrow제거
			scrollbar[flag].arrowsize=[0, 0];
			if(!scrollbar[flag].arrow[0].offsetWidth){
				scrollbar[flag].track.removeChild(scrollbar[flag].arrow[0]);
				scrollbar[flag].track.removeChild(scrollbar[flag].arrow[1]);
				scrollbar[flag].arrow[0]=scrollbar[flag].arrow[1]=null;
			//아닐경우 arrow size 계산
			}else{
				if(flag=='x'){
					for(j=0; j<2; j++){
						scrollbar[flag].arrowsize[j]=scrollbar[flag].arrow[j].offsetWidth
							+getstyle(scrollbar[flag].arrow[j], 'marginLeft')
							+getstyle(scrollbar[flag].arrow[j], 'marginRight');
					}
				}else{
					for(j=0; j<2; j++){
						scrollbar[flag].arrowsize[j]=scrollbar[flag].arrow[j].offsetHeight
							+getstyle(scrollbar[flag].arrow[j], 'marginTop')
							+getstyle(scrollbar[flag].arrow[j], 'marginBottom');
					}
				}
				scrollbar[flag].arrowsize[3]=scrollbar[flag].arrowsize[0]+scrollbar[flag].arrowsize[1];
			}

		}

	}

	//reset
	scrollbar.reset=function(){

		if(opt[flag].wrapper.ani){
			for(i in opt[flag].wrapper.ani.timer){
				clearTimeout(opt[flag].wrapper.ani.timer[i]);
			}
		}

		if(browser.webkit && !scrollbar.setted) setstyle(opt.y.wrapper, 'marginTop', 0);//for webkit bug

		//현재 scroll 값 저장 후 초기화
		scrollbar.x.savemoved=opt.x.wrapper.offsetLeft;
		scrollbar.y.savemoved=opt.y.wrapper.offsetTop;
		setstyle(opt.x.wrapper, 'marginLeft', 0);
		setstyle(opt.y.wrapper, 'marginTop', 0);

		if(browser.ie7) target.style.overflow='visible';//for ie7 bug

		scrollbar.x.scrollsize=target.scrollWidth;
		scrollbar.y.scrollsize=target.scrollHeight;

		if(browser.ie7) target.style.overflow='hidden';//for ie7 bug

		scrollbar.x.show=scrollbar.x.scrollsize>target.clientWidth;
		scrollbar.y.show=scrollbar.y.scrollsize>target.clientHeight;

		//y scroll이 생길 경우 target과 y scroll 자체에 wheel event 추가, 아닐 경우 제거
		if(scrollbar.y.show){
			addevent(target, 'mousewheel', handle_mousewheel);
			addevent(scrollbar.y.track, 'mousewheel', handle_mousewheel);
		}else{
			removeevent(target, 'mousewheel', handle_mousewheel);
			removeevent(scrollbar.y.track, 'mousewheel', handle_mousewheel);
		}

		//mobile 에서 scroll이 생길 경우 wrapper에 swipe 이벤트 추가
		if(browser.mobile && (scrollbar.x.show || scrollbar.y.show)){
			addevent(opt.x.wrapper, 'touchstart', handle_start);
			addevent(opt.y.wrapper, 'touchstart', handle_start);
		}

		var flags=[];
		var barsize, barmaxsize, barminusvalue, tracksizebase;
		for(i=0; i<2; i++){

			flags=(!i)? 
				['x', 'y', 'width', 'left', 'clientWidth', 'offsetWidth', 'paddingLeft', 'paddingRight', 'offsetLeft', 'marginLeft'] : 
				['y', 'x', 'height', 'top', 'clientHeight', 'offsetHeight', 'paddingTop', 'paddingBottom', 'offsetTop', 'marginTop'];

			if(!opt[flags[0]].hidden){

				//가능한 최대 scroll 값
				scrollbar[flags[0]].ablesize=target[flags[4]]-scrollbar[flags[0]].scrollsize;

				//스크롤이 필요없을 경우 스크롤 바 제거
				scrollbar[flags[0]].track.style.display=(scrollbar[flags[0]].show)? 'block' : 'none';

				if(scrollbar[flags[0]].show){

					//set track
					tracksizebase=(opt[flags[0]].outsidetrack)? opt[flags[0]].outsidetrack : target;
					scrollbar[flags[0]].tracksize=
						(opt[flags[0]].tracksizefixed)? opt[flags[0]].tracksize : 
							((opt[flags[0]].outsidetrack && (!opt[flags[1]].outsidetrack || opt[flags[0]].outsidetrack!=opt[flags[1]].outsidetrack))? tracksizebase[flags[4]] : 
								(!opt[flags[1]].hidden && scrollbar[flags[1]].show)? tracksizebase[flags[4]]-scrollbar[flags[1]].track[flags[5]] : 
									tracksizebase[flags[4]])
						+opt[flags[0]].tracksize;
					setstyle(scrollbar[flags[0]].track, flags[2], scrollbar[flags[0]].tracksize);

					//set bar
					barmaxsize=(scrollbar[flags[0]].arrowsize[0])? scrollbar[flags[0]].tracksize-scrollbar[flags[0]].arrowsize[3] : scrollbar[flags[0]].tracksize;
					barminusvalue=scrollbar[flags[0]].bar[flags[5]]-scrollbar[flags[0]].bar[flags[4]];
					barminusvalue+=getstyle(scrollbar[flags[0]].bar, flags[6])+getstyle(scrollbar[flags[0]].bar, flags[7]);
					if(opt[flags[0]].barsize=='auto'){
						barsize=Math.round(barmaxsize*(target[flags[4]]/scrollbar[flags[0]].scrollsize))-barminusvalue;
						if(opt[flags[0]].barminsize>barsize+barminusvalue) barsize=opt[flags[0]].barminsize-barminusvalue;
					}else{
						barsize=opt[flags[0]].barsize-barminusvalue;
					}

					scrollbar[flags[0]].barminposition=scrollbar[flags[0]].arrowsize[0];
					scrollbar[flags[0]].barmaxposition=barmaxsize-barsize-barminusvalue+scrollbar[flags[0]].arrowsize[0];
					setstyle(scrollbar[flags[0]].bar, flags[2], barsize);
					
					//저장된 scroll 값으로 재설정
					if(scrollbar[flags[0]].savemoved){
						setstyle(opt[flags[0]].wrapper, flags[9], 
							(scrollbar[flags[0]].ablesize>scrollbar[flags[0]].savemoved)? scrollbar[flags[0]].ablesize : scrollbar[flags[0]].savemoved);
					}

				}
			}

		}

		scrollbar.setted=true;

	}

	//scrollLeft
	scrollbar.scrollLeft=function(value, noanimate){
		if(value!=undefined){
			scroll('x', -value, true, null, noanimate);
			return value;
		}else{
			return -opt.x.wrapper.offsetLeft;
		}
	}

	//scrollTop
	scrollbar.scrollTop=function(value, noanimate){
		if(value!=undefined){
			scroll('y', -value, true, null, noanimate);
			return value;
		}else{
			return -opt.y.wrapper.offsetTop;
		}
	}

	//scrollTo
	scrollbar.scrollTo=function(x, y, noanimate){
		if(x!=undefined) scrollbar.scrollLeft(x, noanimate);
		if(y!=undefined) scrollbar.scrollTop(y, noanimate);
	}

	//scroll
	function scroll(targetflag, value, withbar, fromswipe, noanimate){

		var flag=(targetflag=='x' || targetflag=='left' || targetflag=='right')? 'x' : 'y';
		var cssflag=(flag=='x')? 'left' : 'top';
		var wrappercssflag=(flag=='x')? 'marginLeft' : 'marginTop';

		var nowvalue=getstyle(opt[flag].wrapper, wrappercssflag);
		var newvalue=(value!=undefined)? value : (targetflag=='up' || targetflag=='left')? nowvalue+opt[flag].movesize : nowvalue-opt[flag].movesize;
		if(newvalue>0) newvalue=0;
		else if(newvalue<scrollbar[flag].ablesize) newvalue=scrollbar[flag].ablesize;

		if(nowvalue==newvalue) return;

		if(opt[flag].animate && !noanimate){
			if(Math.abs(nowvalue-newvalue)>2){
				ani.set(opt[flag].wrapper, wrappercssflag, newvalue, {onupdate:(value==undefined || withbar)? function(){
					movebar(flag, cssflag, this.ani.values[wrappercssflag][this.ani.step[wrappercssflag]]);
					if(scrollbar.onscroll) scrollbar.onscroll.call(target);
				} : function(){
					if(scrollbar.onscroll) scrollbar.onscroll.call(target);
				}, easing : (fromswipe)? 'easeOutCubic' : ''});
			}else{
				if(opt[flag].wrapper.ani) clearTimeout(opt[flag].wrapper.ani.timer[wrappercssflag]);
				setstyle(opt[flag].wrapper, wrappercssflag, newvalue);
				if(value==undefined || withbar) movebar(flag, cssflag, newvalue);
				if(scrollbar.onscroll) scrollbar.onscroll.call(target);
			}
		}else{
			setstyle(opt[flag].wrapper, wrappercssflag, newvalue);
			if(value==undefined || withbar) movebar(flag, cssflag, newvalue);
			if(scrollbar.onscroll) scrollbar.onscroll.call(target);
		}
		
		if(!mousedownonbar) scrolleventstart();

	}

	//scroll events
	var scrolleventtimer=null;
	function scrolleventstart(frombar){

		if(mousedownonbar) return;

		if(!scrolleventtimer && !frombar){
			if(scrollbar.onscrollstart) scrollbar.onscrollstart.call(target);
		}

		clearTimeout(scrolleventtimer);
		scrolleventtimer=setTimeout(function(){
			scrolleventtimer=null;
			if(scrollbar.onscrollend) scrollbar.onscrollend.call(target);
		}, 1000);

	}

	//move bar
	function movebar(flag, cssflag, value){
		setstyle(
			scrollbar[flag].bar, 
			cssflag, 
			scrollbar[flag].barminposition+((scrollbar[flag].barmaxposition-scrollbar[flag].barminposition)*(value/scrollbar[flag].ablesize))
		);
	}

	//auto scroll
	var autoscrolltimer=null;
	function autoscroll(flag, value){

		clearautoscroll();

		addevent(document, 'mouseup', clearautoscroll);

		if((/track/).test(flag)){
			if(flag=='x-track'){
				flag=((scrollbar.x.bar.offsetLeft+scrollbar.x.bar.offsetWidth)>value)? 'left' : 'right';
			}else{
				flag=((scrollbar.y.bar.offsetTop+scrollbar.y.bar.offsetHeight)>value)? 'up' : 'down';
			}
		}else{
			flag=flag.replace(/(x|y)-arrow-/,'');
		}

		var isfirst=true;
		var action=function(){

			if(value && (
				((flag=='left' && (scrollbar.x.bar.offsetLeft<value)) || (flag=='right' && ((scrollbar.x.bar.offsetLeft+scrollbar.x.bar.offsetWidth)>value))) ||
				((flag=='up' && (scrollbar.y.bar.offsetTop<value)) || (flag=='down' && ((scrollbar.y.bar.offsetTop+scrollbar.y.bar.offsetHeight)>value)))
			)){
				clearautoscroll();
				return;
			}

			scroll(flag);

			autoscrolltimer=setTimeout(action, (isfirst)? 500 : 30);

			isfirst=false;

		}
		action();

	}

	//clear auto scroll
	function clearautoscroll(){
		clearTimeout(autoscrolltimer);
		removeevent(document, 'mouseup', clearautoscroll);
	}

	//variables for bar move event
	var clicktarget, offsetposition, clientposition;

	//check scroll by using bar
	var mousedownonbar=false;

	//event handler - mouse down | touch start
	function handle_start(e){

		if(!e) e=window.event;
		var offsetX, offsetY, etarget=e.currentTarget || e.target || e.srcElement;

		if(browser.mobile){
			offsetX=e.touches[0].pageX+e.layerX;
			offsetY=e.touches[0].pageY+e.layerY;
		}else{
			offsetX=e.offsetX || e.layerX;
			offsetY=e.offsetY || e.layerY;
		}

		var flag=etarget.flag;

		//track
		if((/track/).test(flag)){

			autoscroll(flag, (flag=='x-track')? offsetX : offsetY);
			if(browser.mobile) addevent(document, 'touchend', clearautoscroll);

		//arrow
		}else if((/arrow/).test(flag)){

			autoscroll(flag);
			if(browser.mobile) addevent(document, 'touchend', clearautoscroll);

		//wrapper for mobile
		}else if(flag=='wrapper'){

			clicktarget=etarget;
			offsetposition=[etarget.offsetLeft, etarget.offsetTop];
			clientposition=[e.touches[0].pageX, e.touches[0].pageY];
			addevent(document, 'touchmove', handle_swipe);
			addevent(document, 'touchend', handle_swipeend);

			return true;

		//bar
		}else{

			clicktarget=etarget;
			offsetposition=(flag=='x-bar')? etarget.offsetLeft : etarget.offsetTop;
			if(browser.mobile) clientposition=(flag=='x-bar')? e.touches[0].pageX : e.touches[0].pageY;
			else clientposition=(flag=='x-bar')? e.clientX : e.clientY;

			if(browser.mobile){
				addevent(document, 'touchmove', handle_move);
				addevent(document, 'touchend', handle_end);
			}else{
				addevent(document, 'mousemove', handle_move);
				addevent(document, 'mouseup', handle_end);
			}

		}

		if(e.stopPropagation) e.stopPropagation();
		else e.cancelBubble=true;
		if(e.preventDefault) e.preventDefault();
		return false;

	}

	//event handler - mouse move | touch move
	function handle_move(e){

		if(!e) e=window.event;

		var newvalue, nowposition;
		if(browser.mobile) nowposition=(clicktarget.flag=='x-bar')? e.touches[0].pageX : e.touches[0].pageY;
		else nowposition=(clicktarget.flag=='x-bar')? e.clientX : e.clientY;

		var flag=clicktarget.flag.match(/(x|y)-bar/)[1];
		var cssflag=(flag=='x')? 'left' : 'top';

		if(clientposition!=nowposition){

			if(!mousedownonbar){
				mousedownonbar=true;
				if(scrollbar.onscrollstart) scrollbar.onscrollstart.call(target);
			}

			newvalue=offsetposition-(clientposition-nowposition);
			if(scrollbar[flag].barminposition>newvalue) newvalue=scrollbar[flag].barminposition;
			else if(newvalue>scrollbar[flag].barmaxposition) newvalue=scrollbar[flag].barmaxposition;
			setstyle(clicktarget, cssflag, newvalue);
			scroll(flag, Math.round(scrollbar[flag].ablesize*((newvalue-scrollbar[flag].barminposition)/(scrollbar[flag].barmaxposition-scrollbar[flag].barminposition))));

		}

		return false;

	}

	//event handler - mouse up | touch end
	function handle_end(e){

		mousedownonbar=false;

		if(browser.mobile){
			removeevent(document, 'touchmove', handle_move);
			removeevent(document, 'touchend', handle_end);
		}else{
			removeevent(document, 'mousemove', handle_move);
			removeevent(document, 'mouseup', handle_end);
		}

		scrolleventstart(true);

	}

	//event handler - mouse wheel
	function handle_mousewheel(e){

		//only y scroll
		if(scrollbar.y.show){

			if(!e) e=window.event;

			var wheeldata=e.wheelDelta || e.detail;
			if(browser.firefox) wheeldata*=-1;

			scroll((wheeldata>0)? 'up' : 'down');

			/* if document scroll enable when target scroll y end
			if(scrollbar.y.moveable){
				if(e.preventDefault) e.preventDefault();
				return false;
			}
			*/

		}

		if(e.stopPropagation) e.stopPropagation();
		else e.cancelBubble=true;
		if(e.preventDefault) e.preventDefault();
		return false;

	}

	//event handler - mobile swipe
	var swipestarttime, swipenowposition, swipenewvalue;
	function handle_swipe(e){

		if(!e) e=window.event;

		swipenowposition=[e.touches[0].pageX, e.touches[0].pageY];

		if(clientposition[0]!=swipenowposition[0] || clientposition[1]!=swipenowposition[1]){

			if(!mousedownonbar){
				mousedownonbar=true;
				swipestarttime=new Date().getTime();
				if(scrollbar.onscrollstart) scrollbar.onscrollstart.call(target);
			}

			swipenewvalue=[offsetposition[0]-(clientposition[0]-swipenowposition[0]), offsetposition[1]-(clientposition[1]-swipenowposition[1])];
			if(scrollbar.x.show){
				setstyle(clicktarget, 'marginLeft', swipenewvalue[0]);
				movebar('x', 'left', swipenewvalue[0]);
			}
			if(scrollbar.y.show){
				setstyle(clicktarget, 'marginTop', swipenewvalue[1]);
				movebar('y', 'top', swipenewvalue[1]);
			}
			if(scrollbar.onscroll) scrollbar.onscroll.call(target);

		}

		e.preventDefault();
		return false;

	}

	//event handler - mobile swipe end
	function handle_swipeend(e){

		mousedownonbar=false;

		var swipeendtime=new Date().getTime();
		var newvalue, basetime=500, swipetime=swipeendtime-swipestarttime;

		if(basetime>swipetime){
			newvalue=[swipenewvalue[0]-offsetposition[0], swipenewvalue[1]-offsetposition[1]];
			newvalue[0]=swipenewvalue[0]+(newvalue[0]*((basetime-swipetime)/100));
			newvalue[1]=swipenewvalue[1]+(newvalue[1]*((basetime-swipetime)/100));
			if(scrollbar.x.show){
				scroll('x', newvalue[0], true, true);
			}
			if(scrollbar.y.show){
				scroll('y', newvalue[1], true, true);
			}
		}else{
			if(scrollbar.x.show){
				if(clicktarget.offsetLeft>0) scroll('x', 0, true, true);
				else if(scrollbar.x.ablesize>clicktarget.offsetLeft) scroll('x', scrollbar.x.ablesize, true, true);
			}
			if(scrollbar.y.show){
				if(clicktarget.offsetTop>0) scroll('y', 0, true, true);
				else if(scrollbar.y.ablesize>clicktarget.offsetTop) scroll('y', scrollbar.y.ablesize, true, true);
			}
		}

		removeevent(document, 'touchmove', handle_swipe);
		removeevent(document, 'touchend', handle_swipeend);

		scrolleventstart(true);

	}

	//preset
	scrollbar.preset=function(namea){

		trace(window.scrollbar.presets[namea]);
		window.scrollbar.presets[name].call(scrollbar);
	}


	//create element
	function create(classname){
		var style='position:absolute;overflow:hidden;cursor:pointer;';
		var rv, ck=false;
		try{document.createElement('<div>');}catch(e){ck=true;}
		if(!ck && (browser.ie6 || browser.ie7 || browser.ie8)){
			rv=document.createElement('<div class="'+classname+'" style="'+style+'">');
		}else{
			rv=document.createElement('div');
			rv.setAttribute('class', classname);
			rv.setAttribute('style', style);
		}
		addevent(rv, (browser.mobile)? 'touchstart' : 'mousedown', handle_start);
		rv.flag=classname.replace('scroll-', '');
		return rv;
	}

	//add event
	function addevent(tg, name, func){
		if(tg.attachEvent) tg.attachEvent('on'+name, func);
		else{
			if(browser.firefox && name=='mousewheel') name='DOMMouseScroll';
			tg.addEventListener(name, func, false);
		}
	}

	//remove event
	function removeevent(tg, name, func){
		if(tg.detachEvent) tg.detachEvent('on'+name, func);
		else{
			if(browser.firefox && name=='mousewheel') name='DOMMouseScroll';
			tg.removeEventListener(name, func, false);
		}
	}

	//get style
	function getstyle(tg, p){
		var rv=(tg.currentStyle)? tg.currentStyle[p] : document.defaultView.getComputedStyle(tg, null)[p];
		if((/px$/).test(rv)) rv=parseInt(rv);
		return rv;
	}

	//set style
	function setstyle(tg, p, v){
		tg.style[p]=(typeof(v)=='number')? v+'px' : v;
	}
	
	//animation.js minimize
	var ani={
		fps : 72,
		time : 1,
		easing : 'easeOutQuint'
	}

	ani.set=function(tg, p, to, option){

		if(!tg.ani) tg.ani={ step : {}, timer : {}, property : {}, values : {}, onstart : {}, onupdate : {}, onend : {}, starttime : {}, endtime : {} }
		if(!option) option={};

		clearTimeout(tg.ani.timer[p]);

		tg.ani.step[p]=0;
		tg.ani.property[p]=p;
		tg.ani.values[p]=[];
		tg.ani.onstart[p]=option.onstart;
		tg.ani.onupdate[p]=option.onupdate;
		tg.ani.onend[p]=option.onend;

		var from=getstyle(tg, p), easing=option.easing || ani.easing;
		var sv, gap=to-from, max=Math.round(ani.fps*ani.time);
		for(var i=1; i<=max; i++){
			sv=ani.equations[easing](i, from, gap, max);
			tg.ani.values[p].push((from==to)? from : (p=='opacity')? parseInt(Math.abs(sv*100))/100 : Math.round(sv));
		}

		tg.ani.timer[p]=setTimeout(function(){
			tg.ani.starttime[p]=new Date()-1;
			tg.ani.endtime[p]=tg.ani.starttime[p]+(ani.time*1000);
			if(tg.ani.onstart[p]) tg.ani.onstart[p].call(tg);
			ani.action(tg, p);
		}, 0);

	}

	ani.action=function(tg, p){
		tg.ani.step[p]=Math.round(ani.fps*(((new Date()-1)-tg.ani.starttime[p])/(tg.ani.endtime[p]-tg.ani.starttime[p])))+1;
		if(Math.round(ani.time*ani.fps)>tg.ani.step[p]){
			setstyle(tg, p, tg.ani.values[p][tg.ani.step[p]]);
			if(tg.ani.onupdate[p]) tg.ani.onupdate[p].call(tg);
			tg.ani.timer[p]=setTimeout(function(){
				ani.action(tg, p);
			}, (ani.time*1000)/(ani.fps*ani.time));
		}else{
			if(tg.ani.step[p]>Math.round(ani.time*ani.fps)-1) setstyle(tg, p, tg.ani.values[p][Math.round(ani.time*ani.fps)-1]);
			if(tg.ani.onend[p]) tg.ani.onend[p].call(tg);
		}
	}

	//Convert to JS from "Robert Penner's Easing Equations".
	//http://robertpenner.com/easing/
	ani.equations={
		easeOutQuad : function(t,b,c,d){
			return -c*(t/=d)*(t-2)+b;
		},
		easeOutCubic : function(t,b,c,d){
			return c*((t=t/d-1)*t*t+1)+b;
		},
		easeOutQuint : function(t,b,c,d){
			return c*((t=t/d-1)*t*t*t*t+1)+b;
		}
	}

	//add scroll bar object to target
	target.scrollbar=scrollbar;

	//initialize
	scrollbar.reset();

	//return target.scrollbar
	return target.scrollbar;

}

fakescroll.presets={

	classes : function(){
		//trace(this);
	}

}