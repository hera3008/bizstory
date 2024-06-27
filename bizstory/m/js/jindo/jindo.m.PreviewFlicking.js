/**
    @fileOverview 기본 플리킹에서 좌/우 컨텐츠를 미리 보여주는 컴포넌트
    @author mania
    @version 1.9.0
    @since 2012. 2. 25
**/
/**
    기본 플리킹에서 좌/우 컨텐츠를 미리 보여주는 컴포넌트 

    @class jindo.m.PreviewFlicking
    @extends jindo.m.UIComponent
    @uses jindo.m.Touch
    @keyword previewflicking
    @group Component

    @history 1.9.0 Update IOS(iPad, iPhone5 가로 모드)에서 플리킹시 깜빡이는 현상으로 각 패널에 translate 속성 적용  
    @history 1.8.0 Update timingfunction 으로 이동하는 방식을 상황에 따라 left 등으로 위치 이동 방식으로 변경 (IOS , Android 4.X 이상)
    @history 1.8.0 Bug bActivateOnload 옵션 변경시 오류 수정
    @history 1.8.0 Bug 안드로이드 2.X 버전 깜빡이는 현상 수정
    @history 1.8.0 Bug refresh 호출시 오동작 버그 수정 
    @history 1.8.0 Bug bUseDiagonalTouch=false일 경우, 대각선 플리킹시 플리킹이 약간 움직이는 버그 수정
    @history 1.7.0 Release 최초 릴리즈
**/
jindo.m.PreviewFlicking = jindo.$Class({
    /*  @lends jindo.m.PreviewFlicking.prototype */
    
    /**
        초기화 함수

        @constructor
        @param {String|HTMLElement} el 플리킹 기준 Element (필수)
        @param {Object} [htOption] 초기화 옵션 객체
        @param {Number} [htOption.nDefaultIndex=0] 초기 로드시의 화면에 보이는 콘텐츠의 인덱스
        @param {String} [htOption.sClassPrefix='flick-'] Class의 prefix명
        @param {String} [htOption.sContentClass='ct'] 컨텐츠 영역의 class suffix명
        @param {String} [htOption.sBaseClass='base'] 컨텐츠를 아우르는 영역의 class suffix명
        @param {String} [htOption.nMinWidth=''] 컴포넌트의 최소 넓이 
        @param {Number} [htOption.nDuration=100] 슬라이드 애니메이션 지속 시간
        @param {Number} [htOption.nFlickThreshold=40] 콘텐츠가 바뀌기 위한 최소한의 터치 드래그한 거리 (pixel)
        @param {Boolean} [htOption.bUseCircular=false] 순환플리킹여부를 지정한다. true로 설정할 경우 5판이 연속으로 플리킹된다.
        @param {Boolean} [htOption.bAutoResize=true] 화면전환시에 리사이즈에 대한 처리 여부
        @param {Number} [htOption.nBounceDuration=100] nFlickThreshold 이하로 움직여서 다시 제자리로 돌아갈때 애니메이션 시간
        @param {Boolean} [htOption.bUseCss3d=jindo.m._isUseCss3d(true)] css3d(translate3d) 사용여부<br />
            모바일 단말기별로 다르게 설정된다. 상세내역은 <auidoc:see content="jindo.m">[jindo.m]</auidoc:see>을 참조하기 바란다.
        @param {Boolean} [htOption.bUseTimingFunction=jindo.m._isUseTimingFunction()] 애니메이션 동작방식을 css의 TimingFunction을 사용할지 여부<br />false일 경우 setTimeout을 이용하여 애니메이션 실행.<br />
        모바일 단말기별로 다르게 설정된다. 상세내역은 <auidoc:see content="jindo.m">[jindo.m]</auidoc:see>을 참조하기 바란다.
        @param {Boolean} [htOption.bUseTranslate=true] css의 translate 속성을 사용할지 여부<br /> false일 경우 "left" 속성을 이용함.
        @param {Boolean} [htOption.bUseDiagonalTouch=true] 대각선스크롤 방향으 터치도 플리킹으로 사용할지 여부
        @param {Boolean} [htOption.bActivateOnload=true] 컴포넌트 로드시 activate 여부
    **/
    $init : function(sEl, htUserOption){
        this.option({
            nDefaultIndex : 0,
            sClassPrefix : 'flick-',
            sContentClass : 'ct',
            sBaseClass : 'base',
            nMinWidth : "",
            nDuration : 100,
            nFlickThreshold : 40,
            bUseCircular : false,
            bAutoResize : true,
            nBounceDuration : 100,
            bUseCss3d : jindo.m._isUseCss3d(true), //css3d사용여부 bUseTranslate가 true 일때만 사
            bUseTimingFunction : jindo.m._isUseTimingFunction(true), //스크립트방식으로 애니메이션을 사용할지 csstimingfunction을 사용할지 여부
            bUseTranslate : true,   //css의 translate를 사용할지 style 속성의 left속성 사용할지 여부
            bActivateOnload : true,
            bUseDiagonalTouch : true //대각선스크롤을 플리킹에 사용할지 여부
        });
        
        this.option(htUserOption || {});
        this._sEl = sEl;

        if(this.option("bActivateOnload")) {
            this.activate();
        }
        
    },  

    /**
     * 플리킹 내부에서 쓰는 엘리먼트를 저장한다.
     */
    _setWrapperElement : function(){
        this._htWElement = {};
        var el = jindo.$(this._sEl);
        var sClass = '.'+ this.option("sClassPrefix");

        this._htWElement.base = jindo.$Element(jindo.$$.getSingle(sClass+this.option('sBaseClass'),el));
        this._htWElement.container = jindo.$Element(jindo.$$.getSingle(sClass+'container',el));
        var aContents = jindo.$$(sClass+this.option('sContentClass'), el);
        var self = this;

        this._htWElement.aPanel= jindo.$A(aContents).forEach(function(value,index, array){
            array[index] = jindo.$Element(value);
        }).$value();
        
        //ie10 대응 코드
        // if(typeof this._htWElement.base.$value().style.msTouchAction !== 'undefined'){
            // this._htWElement.base.css('msTouchAction','none');
        // }
    },

    /**
     *  jindo.m.PreViewFlicking 에서 사용하는 모든 인스턴스 변수를 초기화한다.
     */
    _initVar: function() {
        this._htPosition = {};
        this._sTranslateStart = "translate(";
            this._sTranslateEnd = ")";
        this._doFlicking = false;
        this._bTouchStart  = false;
        this._bMove = false;
        this._fnDummyFnc = function(){return false;};
        this._wfTransitionEnd = jindo.$Fn(this._onTransitionEnd, this).bind();
        this._bClickBug = jindo.m.hasClickBug();

        //더미 엘리먼트를 만들어서 focus 호출해야 하는 것들
        this._bDummyTagException = (this._bAndroid && (this._nVersion < "3") ); 
         
        var nDefaultIndex = this.option("nDefaultIndex");
        this._htIndexInfo = {
            nContentIndex : nDefaultIndex, 
            nNextContentIndex : nDefaultIndex,
            welElement : this._htWElement.aPanel[nDefaultIndex],
            welNextElement : this._htWElement.aPanel[nDefaultIndex],
            sDirection : null
        };
    },

    /**
     * 플리킹 내부에서 사용하는 터치컴포넌트 인스턴스 생성한다.
     */ 
    _initTouch : function(){
        this._oTouch = new jindo.m.Touch(jindo.$(this._sEl),{
            nSlopeThreshold : 4,
            nMoveThreshold : 0,
            nEndEventThreshold : (jindo.m.getDeviceInfo().win)? 400:0,
            bActivateOnload : true,
            bHorizental : true,
            bVertical : false
        });

    },

    /**
     * 플리킹을 초기화 하기 위한 초기 셋팅 처리를 한다.
     */
    _initFlicking : function(){
        this._setElementStyle();
        this._prepareFlicking();
        this._attachEvent();
        this._setPanelPos();

        for ( var i = 0 , nFor = this._htWElement.aPanel.length ; i < nFor ; i++ ){
            this._htWElement.aPanel[i].css("left", (i*20)+ "%");
        }
    },

    /**
     *  플리킹에 필요한 스타일을 추가한다.
     */
    _setElementStyle : function() {
        
        jindo.$Element(this._sEl).css("overflow","hidden");
        this._htWElement.base.css({
            "position" : "relative",
            "width" : "50%",
            "min-width" : this.option("nMinWidth").replace(/\D/gi, "") + "px",
            "margin" : "0 auto"
        });

        this._htWElement.container.css({
            "position" : "relative",
            "width" : this._htWElement.aPanel.length * 100 + "%"
        });
        this._htWElement.container.css('clear','both');
        var self = this;

        jindo.$A(this._htWElement.aPanel).forEach(function(value,index, array){
            var wel = value;
            if(self.option('bUseCircular')){
                wel.css('position', 'absolute');
            }
            wel.css({
                "left" : 0,
                "float" : "left",
                "width" : (100 / self._htWElement.aPanel.length)  +"%"
            });
        });
    },

    /**
     * 판넬의 위치 정의
     */
    _setPanelPos : function(){
        var el = this._htWElement.base.$value();
        var nW = el.clientWidth;
        this._nDefaultSize = nW;

        if(this.option('bUseCircular')){
            this._htPosition.htPanel = {
                left : -100,
                center : -100,
                right : -100
            };
            this._htPosition.htContainer = {
                left: nW * -1,
                center : 0,
                right : nW 
            };
        }else{
            this._htPosition.aPos = [];
            var sLen = 'width';
            var nPos = 0;
            var nBeforePos = 0;
            for(var i=0,nLen = this._htWElement.aPanel.length; i<nLen;i++){
                if(i != 0){
                        nPos += this._htWElement.aPanel[i-1][sLen]()*-1;
                }           
                this._htPosition.aPos.push(nPos);     
            }
        }
    },

    /**
     * 애니메이션 작업전에 각 패널및 컨테이너의 설정값을 설정한다. 
     */
    _prepareFlicking : function(){
        // css3d 사용
        if(this.option('bUseCss3d')){
            this._sTranslateStart = "translate3d(";
            this._sTranslateEnd = ",0px)";
        }

        for(var i=0, nLen =  this._htWElement.aPanel.length; i<nLen; i++){
            if(this.option('bUseTranslate')){
                this._htWElement.aPanel[i].css(this._sCssPrefix + 'Transform', this._sTranslateStart +"0px,0px" + this._sTranslateEnd);   
            }
            this._htWElement.aPanel[i].css(this._sCssPrefix + 'TransitionProperty', "all");           
        }   
    },
    
    /**
     * jindo.m.Flicking 에서 사용하는 모든 이벤트를 바인드한다.
     **/
    _attachEvent : function() {
        this._htEvent = {};
        /* Touch 이벤트용 */
        this._htEvent["touchMove"] = jindo.$Fn(this._onMove, this).bind();
        this._htEvent["touchEnd"] = jindo.$Fn(this._onEnd, this).bind();
        this._htEvent["touchStart"] = jindo.$Fn(this._onStart, this).bind();

        /* Touch attach */
        this._oTouch.attach("touchStart", this._htEvent["touchStart"]);
        this._oTouch.attach("touchMove", this._htEvent["touchMove"]);
        this._oTouch.attach("touchEnd", this._htEvent["touchEnd"]);

        /* rotate */
        this._htEvent["rotate"] = jindo.$Fn(this._onResize, this).bind();
        jindo.m.bindRotate(this._htEvent["rotate"]);

         // pageshow 이벤트 처리 
        this._htEvent["pageshow"] = jindo.$Fn(this._onResize, this).bind();
        jindo.m.bindPageshow(this._htEvent["pageshow"]);

    },

    /**
     * touchStart 이벤트 호출 함수
     * @param {Objext} oCustomEvent jindo.$Event object
     **/
    _onStart : function(oCustomEvent){
        if (this._doFlicking) {
            return;
        }

        this._bTouchStart = true;
        this._clearAnchor();
        this.fireEvent('touchStart', oCustomEvent);

    },

    /**
     * touchMove 이벤트 호출 함수
     * @param {Objext} oCustomEvent jindo.$Event object
     **/    
    _onMove : function(oCustomEvent){
        /** 시스템 스크롤 막기 */
        var weParent = oCustomEvent.oEvent;

        if(oCustomEvent.sMoveType === jindo.m.MOVETYPE[0]) {  //수평이고,수평스크롤인 경우 시스템 스크롤 막기
            weParent.stop(jindo.$Event.CANCEL_ALL);
        } else if(oCustomEvent.sMoveType === jindo.m.MOVETYPE[1]) {   //수직이고, 수직스크롤인 경우 시스템 스크롤 막기
            this.fireEvent('scroll');
            // 2013.5.3 sculove 추가 
            this._bTouchStart = false;
            return;
        }else if(oCustomEvent.sMoveType === jindo.m.MOVETYPE[2]) {
            //대각선 일때 시스템 스크롤 막기
            if(this.option('bUseDiagonalTouch')){
                weParent.stop(jindo.$Event.CANCEL_ALL);
            }else{
                this.fireEvent('scroll');
                // 2013.5.3 sculove 추가 
                this._bTouchStart = false;
                return;
            }
        }

        if (this._doFlicking) {
            return;
        }
        if(!this._bTouchStart){
            return;
        }

        /**
            플리킹영역에 터치 움직임이 있을 때 발생한다. Touch이벤트의 'touchMove'와 동일하다

            @event touchMove
            @param {String} sType 커스텀 이벤트명
            @param {String} sMoveType 현재 분석된 움직임
            @param {HTMLElement} stopelement 현재 터치된 영역의 Element
            @param {Number} nX 터치영역의 X좌표
            @param {Number} nY 터치 영역의 Y좌표
            @param {Number} nVectorX 이전 touchMove(혹은 touchStart)의 X좌표와의 상대적인 거리.(직전 좌표에서 오른쪽방향이면 양수, 왼쪽 방향이면 음수)
            @param {Number} nVectorY 이전 touchMove(혹은 touchStart)의 Y좌표와의 상대적인 거리.(직전 좌표에서 위쪽방향이면 음수, 아래쪽 방향이면 양수)
            @param {Number} nDistanceX touchStart의 X좌표와의 상대적인 거리.(touchStart좌표에서 오른쪽방향이면 양수, 왼쪽 방향이면 음수)
            @param {Number} nDistanceY touchStart의 Y좌표와의 상대적인 거리.(touchStart좌표에서 위쪽방향이면 음수, 아래쪽 방향이면 양수)
            @param {Number} nStartX touchStart의 X좌표
            @param {Number} nStartY touchStart의 Y좌표
            @param {Object} oEvent jindo.$Event object
            @param {Function} stop수행시 영향 받는것 없다.
        **/
        if(this.fireEvent('touchMove', oCustomEvent)){
    
            var nDis = oCustomEvent.nDistanceX;
            var nVector = oCustomEvent.nVectorX;
            if(!jindo.m.hasOffsetBug()){
                nDis = (this._nOffsetPos || 0) + nDis;
            }
            this._setPosition( nDis, nVector, 0, false);
            this._bMove = true;
        }else{
            weParent.stop(jindo.$Event.CANCEL_ALL);
        }

    },

    /**
     * touchEnd 이벤트 호출 함수
     * @param {Objext} oCustomEvent jindo.$Event object
     * @param {Number} nDuration 슬라이드 애니메이션 지속 시간
     **/    
    _onEnd : function(oCustomEvent, nDuration){

        if (this._doFlicking) {
            return;
        }
        if(!this._bTouchStart){
            return;
        }
        
        this._doFlicking = true;
    
        //스크롤일경우 뒤의 click이벤트를 막기위한 코드 젤리빈의 경우 아래 코드 실행시 시스템 스크롤의 가속 기능이 꺼진다.
        if( !(this._bAndroid && (this._nVersion >= "4.1")) ){
            if (oCustomEvent.sMoveType === jindo.m.MOVETYPE[0] || oCustomEvent.sMoveType === jindo.m.MOVETYPE[1] || oCustomEvent.sMoveType === jindo.m.MOVETYPE[2]) {
            oCustomEvent.oEvent.stop(jindo.$Event.CANCEL_DEFAULT);
            }
        }
        
        //탭 혹은 롱탭일때
        if (oCustomEvent.sMoveType === jindo.m.MOVETYPE[3] || oCustomEvent.sMoveType === jindo.m.MOVETYPE[4]) {
            this._restoreAnchor();
        }
        
        var nTime = this.option('nDuration');
        var htInfo = this._getSnap(oCustomEvent.nDistanceX, oCustomEvent.nDistanceY, nTime, oCustomEvent.sMoveType);
        
        //플리킹이 다시 되돌아 갈때..(기준픽셀을 채우지 못하여 되돌아 갈때 )
        if(htInfo.sDirection === null){
            nTime = this.option('nBounceDuration');
            if(nDis === 0 || ((oCustomEvent.sMoveType === jindo.m.MOVETYPE[2]) && !this.option('bUseDiagonalTouch')) ) {
                this._endAnimation(false);
                return;
            }
        }
        
        var htParam = {
            nContentsIndex : this.getContentIndex(),
            nContentsNextIndex: htInfo.nContentIndex
        };
        
        if(this._bFlickLeft !== null){
            htParam.bLeft = this._bFlickLeft;
        }
        
        if(htInfo.sDirection !== null){
            /**
                플리킹되기 전에 발생한다

                @event beforeFlicking
                @param {String} sType 커스텀 이벤트명
                @param {Number} nContentsIndex 현재 콘텐츠의 인덱스
                @param {Number} nContentsNextIndex (Number) :플리킹될 다음 콘텐츠의 인덱스
                @param {Boolean} bLeft 플리킹 방향이 왼쪽인지에 대한 여부
                @param {Function} stop 플리킹되지 않는다.
            **/
            if(!this.fireEvent('beforeFlicking', htParam)){
                this.restorePosition();
                return;
            }
        } else {
             /**
                플리킹 임계치에 도달하지 못하고 사용자의 액션이 끝났을 경우, 원래 인덱스로 복원하기 전에 발생하는 이벤트

                @event beforeRestore
                @param {String} sType 커스텀 이벤트명
                @param {Number} nContentsIndex 현재 콘텐츠의 인덱스
                @param {Function} stop 플리킹이 복원되지 않는다.
            **/
            if(!this.fireEvent('beforeRestore', {
                nContentsIndex : this.getContentIndex()
            })) {
                return;
            }
        }

        this._htIndexInfo.nNextContentIndex = htInfo.nContentIndex;
        this._htIndexInfo.welNextElement = htInfo.welElement;
        this._htIndexInfo.sDirection = htInfo.sDirection;
        
        var nDis = oCustomEvent.nDistanceX;
        var nVector = oCustomEvent.nVectorX;
        // var nPos = oCustomEvent.nX;
        
        if(!jindo.m.hasOffsetBug()){
                nDis = (this._nOffsetPos || 0) + nDis;
            }
        
        this._onAfterEnd(nDis, nVector, nDuration);
        /**
            플리킹영역에 터치가 끝났을 때 발생한다. Touch이벤트의 'touchEnd'와 동일하다.
        
            @event touchEnd
            @param {String} sType 커스텀 이벤트명
            @param {String} sMoveType 현재 분석된 움직임
            @param {HTMLElement} element 현재 터치된 영역의 Element
            @param {Number} nX 터치영역의 X좌표
            @param {Number} nY 터치 영역의 Y좌표
            @param {Number} nVectorX 이전 touchMove(혹은 touchStart)의 X좌표와의 상대적인 거리.(직전 좌표에서 오른쪽방향이면 양수, 왼쪽 방향이면 음수)
            @param {Number} nVectorY 이전 touchMove(혹은 touchStart)의 Y좌표와의 상대적인 거리.(직전 좌표에서 위쪽방향이면 음수, 아래쪽 방향이면 양수)
            @param {Number} nDistanceX touchStart의 X좌표와의 상대적인 거리.(touchStart좌표에서 오른쪽방향이면 양수, 왼쪽 방향이면 음수)
            @param {Number} nDistanceY touchStart의 Y좌표와의 상대적인 거리.(touchStart좌표에서 위쪽방향이면 음수, 아래쪽 방향이면 양수)
            @param {Number} nStartX touchStart의 X좌표
            @param {Number} nStartY touchStart의 Y좌표
            @param {Object} oEvent jindo.$Event object
            @param {Function} stop수행시 영향 받는것 없다.
        **/
        this.fireEvent('touchEnd', oCustomEvent);
    },

    restorePosition : function(){
        this._onAfterEnd();
    },
    /**
     * 판넬 위치처리시 옵션에 따른 분기 처리
     * @param {Number} nDis touchstart 시점에서 부터의 거리
     * @param {Number} nVector 이전 터치와의 상대 거리 
     * @param {Number} nDuration 애니메이션 시간 
     * @param {Boolean} bEnd 현재 touchEnd 시점여부 
     */
    _setPosition : function( nDis, nVector, nDuration, bEnd){
        if(typeof nDuration === 'undefined'){
            nDuration = 0;
        }    
        if(!this.option('bUseTranslate')){
            this._setPositionForStyle(nDis, nVector , nDuration, bEnd);
        }else{
            this._setPositionTransform(nDis,nDuration, bEnd);
        }

    },

    /**
    * wel 엘리먼트의 위치를 left, top 속성으로 설정한다. 
    * @param {HTMLElement} wel 위치를 잡을 대상 엘리먼트 
    * @param {Number} nDis touchstart 시점에서 부터의 거리
    * @param {Number} nVector 이전 터치와의 상대 거리 
    * @param {Number} nDuration 애니메이션 시간 
    * @param {Boolean} bEnd 현재 touchEnd 시점여부 
    */
    _setPositionForStyle : function( nDis, nVector, nDuration, bEnd){
        var sName = 'left';

        if(bEnd){
            if(this.option('bUseCircular')){
                if(this._htIndexInfo.sDirection === null){
                    nDis = -100;
                }else{
                    if(nDis < 0){
                        nDis = -100;
                    }else{
                        nDis = 0;
                    }
                }
            }else{
                nDis = this._getMovePos();
            }
        }

        var n = 0;
        if(this.option('bUseCircular')){
            n = ((nDis/this._nDefaultSize) * 100) ;
        }else{
            if(bEnd){
                n = nDis;
            }else{
                n = nVector + this._getContainerPos()['css_'+ sName];
            }
        }

        if(bEnd && !this.option('bUseCircular')){
            var nIndex = this._htIndexInfo.nContentIndex;

            if( this._htIndexInfo.sDirection === "next"){
                nIndex++;
            }else if( this._htIndexInfo.sDirection === "prev") {
                nIndex--;
            }
            nDis = this._htPosition.aPos[nIndex];

        }

        var nPos = bEnd? nDis : n;

        this._nLastDis = nDis;
        if(bEnd){
            if(nPos === parseFloat(this._htWElement.container.css(sName).replace('px',''),10) ){
                nDuration = 0;
            }
            this._attachTransitionEnd(this._htWElement.container.$value(), nDuration);
        }

        this._setPosContainer(nPos, nDuration);

    },

    /**
    * @description wel 엘리먼트의 위치를 css의 translate 속성으로 설정한다. 
    * @param {HTMLElement} wel 위치를 잡을 대상 엘리먼트 
    * @param {Number} nDis touchstart 시점에서 부터의 거리
    * @param {Number} nDuration 애니메이션 시간 
    * @param {Boolean} bEnd 현재 touchEnd 시점여부 
    */
    _setPositionTransform : function(nDis, nDuration, bEnd){
        // var bH = this.option('bHorizontal');
        if(bEnd){
            if(this._htIndexInfo.sDirection === null){
                nDis = jindo.m.hasOffsetBug() ? 0 : (this._nOffsetPos || 0);
            }else{

                if(this.option('bUseCircular')){
                    nDis = (this._htIndexInfo.sDirection === "next")? this._htPosition.htContainer.left : this._htPosition.htContainer.right;
                }else{
                    nDis = this._getMovePos();
                }
            }
        }

        this._nLastDis = nDis;

        if(bEnd){
            var htCssOffset = jindo.m.getCssOffset(this._htWElement.container.$value());
            if((htCssOffset.left === nDis) && (htCssOffset.top === 0)){
                nDuration = 0;
            }
            this._attachTransitionEnd(this._htWElement.container.$value(), nDuration);
        }

        this._setPosContainer(nDis, nDuration);
    },

    /**
     * container 영역 위치 설정
     * 
     * @param {Number} nX 이동할 X 좌표
     * @param {Number} nDuration 슬라이드 애니메이션 지속 시간
     */
    _setPosContainer : function(nX, nDuration){
        if(typeof nDuration === 'undefined'){
            nDuration = 0;
        }
        var htCss = {};
        htCss[this._sCssPrefix+'TransitionProperty'] = "all";
        htCss[this._sCssPrefix+'TransitionDuration'] =  (nDuration === 0)? '0' : nDuration +"ms" ;

        if(this.option('bUseTranslate')){
            htCss[this._sCssPrefix+'Transform'] =  this._sTranslateStart + nX +"px, 0px" + this._sTranslateEnd;
        }else{
            var sUnit = this.option('bUseCircular')? "%" : "px";
            htCss['left']  = nX+ sUnit;
        }
        this._htWElement.container.css(htCss);
    },

    /**
     * 플리킹 이후에 움직여야하는 거리와 컨텐츠 인덱스를 구한다
     * 
     * @param {Number} nDistanceX touchStart의 X좌표와의 상대적인 거리.(touchStart좌표에서 오른쪽방향이면 양수, 왼쪽 방향이면 음수)
     * @param {Number} nDistanceY touchStart의 Y좌표와의 상대적인 거리.(touchStart좌표에서 위쪽방향이면 음수, 아래쪽 방향이면 양수)
     * @param {Number} nDuration 슬라이드 애니메이션 지속 시간
     * @param {String} sType 커스텀 이벤트명
     * @return {Object} 플리킹 이후에 움직여야 하는 거리와 컨텐츠 인덱스
     */
    _getSnap : function(nDistanceX, nDistanceY, nDuration, sType){
        var nFinalDis = nDistanceX;

        var welElement = this._htIndexInfo.welElement;
        var nContentIndex = this.getContentIndex();
        var sDirection = null;

        //가로 대각선일 경우
        if(!((sType === jindo.m.MOVETYPE[2]) && !this.option('bUseDiagonalTouch')) && this._bMove){
            if(Math.abs(nFinalDis) >= this.option('nFlickThreshold') ){
                if(nFinalDis < 0 ){ //왼쪽 방향 혹은 위쪽 방향으로 밀고 있을 때
                    welElement = this.getNextElement();
                    nContentIndex =  this.getNextIndex();
                    this._bFlickLeft = true; //
                    sDirection = 'next';
                }else{ //오른쪽 방향 혹은 아래 방향으로 밀때
                    welElement = this.getPrevElement();
                    nContentIndex = this.getPrevIndex();
                    this._bFlickLeft = false;
                    sDirection  = 'prev';
                }
            }
        }

        if(this._htIndexInfo.welElement.$value() === welElement.$value()){
            sDirection = null;
        }
        return {
            elElement : welElement.$value(),
            welElement: welElement,
            nContentIndex : nContentIndex,
            sDirection : sDirection
        };
    },

    /**
     * 플리킹 애니메이션이 끝나는 시점의 처리 함수 
     * 
     * @param {Boolean} bFireEvent 사용자 호출 함수 실행 여부 
     */
    _endAnimation : function(bFireEvent){
        //
        var self = this;
        if(typeof bFireEvent === 'undefined'){
            bFireEvent = true;
        }
        this._doFlicking = false;
        this._bTouchStart =  false;
        this._bMove = false;

        var isFireRestore = this._htIndexInfo.sDirection == null && 
        this._htIndexInfo.nContentIndex === this._htIndexInfo.nNextContentIndex ? true : false;
        //index 정보 업데이트
        this._resetIndexInfo(this._htIndexInfo.nNextContentIndex, this._htIndexInfo.welNextElement);

        if(bFireEvent){
            /**
            현재 화면에 보이는 콘텐츠가 플리킹액션을 통해 바뀔경우 수행된다.

            @event flicking
            @param {String} sType 커스텀 이벤트명
            @param {Number} nContentsIndex 현재 콘텐츠의 인덱스
            @param {Boolean} bLeft 플리킹 방향이 왼쪽인지에 대한 여부
            @param {Function} stop 수행시 영향을 받는것은 없다.
            **/
            this._fireCustomEvent("flicking");
        }
        if(isFireRestore) {
            /**
            플리킹 임계치에 도달하지 못하고 사용자의 액션이 끝났을 경우, 원래 인덱스로 복원한 후에 발생하는 이벤트

            @event restore
            @param {String} sType 커스텀 이벤트명
            @param {Number} nContentsIndex 현재 콘텐츠의 인덱스
            **/               
            this.fireEvent("restore", {
                nContentsIndex : this._htIndexInfo.nContentIndex
            });
        }

        //ios 업데이트
        this._restoreAnchor();
        this._setAnchorElement();
        setTimeout(function(){
            self._createDummyTag();
            self._focusFixedBug();
        }, 5);
        this._bFlickLeft = null;
    },

    /**
     * _onEnd 함수 실행 후 호출되는 함수
     * 판넬을 지정하는 위치로 이동시킨다.
     * 
     * @param {Number} nDis touchstart 시점에서 부터의 거리
     * @param {Number} nVector 이전 터치와의 상대 거리 
     * @param {Number} nDuration 슬라이드 애니메이션 지속 시간
     */
    _onAfterEnd : function(nDis, nVector, nDuration){
        var wel = this._htWElement.container;
        if(typeof nDuration === 'undefined'){
            nDuration = this.option('nDuration');
        }
        //var nDuration = this.option('nDuration');
        if(this._htIndexInfo.sDirection === null){
            nDuration = this.option('nBounceDuration');
        }

        if(!this.option('bUseTimingFunction') && (nDuration > 0) ){
            //script  방식으로 애니메이션 처리
            var self = this;
            var nDistance =  this._nLastDis? this._nLastDis :  nDis;
            var startTime = (new Date()).getTime(),
            nStartDis =  nDis, nBeforeDis = nDis, nStartVector = nVector, nTotalDis = this._htWElement.base.width();
            // nStartDis =  nDis, nBeforeDis = nDis, nStartVector = nVector, nTotalDis = this.option('bHorizontal')? this._htWElement.base.width(): this._htWElement.base.height();
            
            if(this._htIndexInfo.sDirection === null){
                if(!this.option('bUseTranslate')){ 
                    nTotalDis = -100;
                }else{
                    nTotalDis = 0;
                }
            }
            // if(nDistance < 0){
            if(this._htIndexInfo.sDirection == "next"){
                nTotalDis = nTotalDis*-1;
            }
            
            if(!jindo.m.hasOffsetBug()){
                nTotalDis = (this._nOffsetPos || 0 ) + nTotalDis; 
                // nDis = (this._nOffsetPos || 0) + nDis;
                // console.log("---------- > " , nDis);
            }
            (function animate () {
                var now = (new Date()).getTime(),nEaseOut, nDis;
                if (now >= startTime + nDuration) {
                //clearTimeout(self._nTimerAnimate);
                    cancelAnimationFrame(self._nTimerAnimate);
                    delete self._nTimerAnimate;
                    self._setPosition(nTotalDis,  (nDis-nBeforeDis), 0, false);
                    setTimeout(function(){
                    self._onTransitionEnd();
                    },100);
                    //self._onTransitionEnd();
                    return;
                }

                now = (now - startTime) / nDuration - 1;
                nEaseOut = Math.sqrt(1 - Math.pow(now,2));
                nDis = (nTotalDis - nStartDis)*nEaseOut + nStartDis;
                self._setPosition( nDis,  (nDis-nBeforeDis), 0, false);
                nBeforeDis = nDis;
                //self._nTimerAnimate = setTimeout(animate, 1);   
                self._nTimerAnimate = requestAnimationFrame(animate);
            })();

        }else{
            this._setPosition(nDis, nVector, nDuration, true);
        }

    },

    /**
     * 이동할 판넬의 넓이 정보를 얻어온다.
     * 
     * @param {Number} nIndex 판넬의 index
     * @return {Number} nRet 판넬의 넓이 정보  
     */
    _getMovePos : function(nIndex){
        var nRet = 0;
        var sPos =  "left";
        var htPos =  this._getContainerPos();

        if(typeof nIndex === 'undefined'){
            if(this._htIndexInfo.sDirection !== null){
                nIndex = this._htWElement.aPanel.length-1;
                htPos =  this._getContainerPos();
                var nCurrent = htPos[sPos];
                var nMax = this._htPosition.aPos.length;
                // for(var i=0,nLen = nMax; i<nLen; i++){               
                    // if(nCurrent >= (this._htPosition.aPos[i])){
                        // nIndex = i;
                        // break;
                    // }               
                // }

                // if ((nIndex == this.getContentIndex()) && nIndex > 0 && (this._htIndexInfo.sDirection === 'prev')) nIndex--;
                // if ((nIndex == this.getContentIndex()) && (nIndex < (nMax-1)) && (this._htIndexInfo.sDirection === 'next')) nIndex++;
                if (this.getContentIndex() > 0 && (this._htIndexInfo.sDirection === 'prev')){
                    nIndex = this.getContentIndex() - 1;
                } else if (this.getContentIndex() < nMax-1 && (this._htIndexInfo.sDirection === 'next')) {
                    nIndex = this.getContentIndex() + 1;
                }
            }else{
                nIndex = this.getContentIndex();
            }

        }

        nRet  = this._htPosition.aPos[nIndex];
        if(this.option('bUseTranslate')){
            nRet -= (htPos['css_'+sPos]);
        }
        nRet  = this._htPosition.aPos[nIndex] - (htPos['css_'+sPos]);

        return nRet;
    },
    
    /**
     * container 의 속성 정보 
     * 
     * @return {Object} left, top 등의 속성 정보 
     */
    _getContainerPos : function(){
        var wel = this._htWElement.container;
        var nLeft = parseInt(wel.css("left"),10),
        nTop = parseInt(wel.css("top"),10);
        nLeft = isNaN(nLeft) ? 0 : nLeft;
        nTop = isNaN(nTop) ? 0 : nTop;
        var htPos = jindo.m.getCssOffset(wel.$value());
        //nLeft += htPos.left;
        //nTop += htPos.top;

        return {
            left : nLeft+htPos.left, 
            top : nTop+htPos.top,
            css_left : nLeft, 
            css_top :  nTop
        };
    },
    
    /**
     * transition 종료 후 처리 함수 
     */
    _onTransitionEnd : function(){
        //
        this._detachTarnsitonEnd();
        var bFireEvent = true;

        if(this._htIndexInfo.sDirection === null){
            bFireEvent = false;
        }

        this._nLastDis  = null;
        this._restorePanel(this._htIndexInfo.welNextElement.$value());
        this._endAnimation(bFireEvent);

        if(this._bFireMoveEvent){
            this._fireCustomEvent("move");
            this._bFireMoveEvent = false;
        }
    },


    /**
     * @description el이 화면에 중앙에 오도록 각 패널과 컨테이너 재배치 한다.
     * @param {HTMLElement} el 화면에 중앙에 오는 엘리먼트 
    */
    _restorePanel : function(el){
        var self =this;
        var nPer = this._htWElement.aPanel.length;
        var nCenter = this.getIndexByElement(el);

        this._refresh(nCenter, false);

        if(this.option('bUseCircular')){
            
            if(this._isIos && this.option('bUseCircular')){
                var nPrev = (((nCenter-1) < 0 )? (nPer - 1) : (nCenter-1))%nPer;
                var nNext =  (((nCenter+1) > (nPer - 1) )? 0 : (nCenter+1))%nPer;
                var welClonePrev = jindo.$Element(this._htWElement.aPanel[nPrev].$value().cloneNode(true));
                var welCloneNext = jindo.$Element(this._htWElement.aPanel[nNext].$value().cloneNode(true));

                this._htWElement.aPanel[nPrev].replace(welClonePrev);
                this._htWElement.aPanel[nNext].replace(welCloneNext);

                this._htWElement.aPanel[nPrev] = welClonePrev;
                this._htWElement.aPanel[nNext] = welCloneNext;
            } 
        }
    },

    /**
     * @description n번째 패널 중앙에 오도록 panel을 다시 좌우 배열해서 배치한다.
     * @param {Number} n 현재 화면에 보여져야할 content의 인덱스
     * @param {Boolean} bResize 화면 크기가 변화되어 다시 사이즈를 업데이트 해야 할경우 true 
    */
    _refresh : function(n, bResize ){

        var nCenter = n;

        if(this.option('bUseCircular')){
            nCenter = n % this._htWElement.aPanel.length;
        }

        if(bResize){
            this._setPanelPos();
        }   

        var sPosition = 'left'; 
        this._htWElement.container.css(this._sCssPrefix+'TransitionDuration', '0ms');
        if(this.option('bUseCircular')){
            //순환일 경우 
            // this._htWElement.container.css(sPosition, '-100%');
            if (this.option('bUseTranslate')) {
                this._htWElement.container.css(this._sCssPrefix + 'Transform', this._sTranslateStart + "0px,0px" + this._sTranslateEnd);
            }else {

                this._htWElement.container.css("left" , "0");
            }

            var nSum = 0;
            var nLen = this._htWElement.aPanel.length;
            var nCompare = Math.floor(nLen/2);

            for ( var i = 0  ; i < nLen ; i++ ){
                nSum = i - nCenter;
                if( nSum > nCompare ){
                    nSum = nSum - nLen;
                }else if( nSum < -nCompare ) {
                    nSum = nSum + nLen;
                }
                this._htWElement.aPanel[i].css(sPosition, (nSum  * 20 )+ "%");
                if(nSum == 0){
                    this._htWElement.aPanel[i].css("zIndex", 10);
                }else{
                    this._htWElement.aPanel[i].css("zIndex", 1);
                }
            }



        }else{
            //비순환일 경우
            var nPos = 0;
            if(nCenter > 0){
                nPos = this._htPosition.aPos[nCenter];
            }
            
                this._nOffsetPos = nPos;
            if(jindo.m.hasOffsetBug()){
                this._htWElement.container.css(this._sCssPrefix + 'Transform', "");
                this._htWElement.container.css(sPosition, nPos+"px");
            }else{
                this._htWElement.container.css(this._sCssPrefix + 'Transform', this._sTranslateStart + nPos +"px, 0px" + this._sTranslateEnd);
            }
        }

    },


    /**
     * transitionEnd 함수 attach 를 위한 처리 
     * 
     * @param {Element} el 대상의 엘리먼
     * @param {Number} nTime 슬라이드 애니메이션 지속 시간 
     */
    _attachTransitionEnd : function(el, nTime){
        if(el !== this._elTransition ){
            this._elTransition = el;
            var self = this;
            //
            if(nTime === 0){
                setTimeout(function(){
                    self._onTransitionEnd();    
                }, 10);
            }else{
                jindo.m.attachTransitionEnd(el, this._wfTransitionEnd);
            }
        }
    },
    
    /**
     * transitionEnd 함수 detach 를 위한 처리 
     */
    _detachTarnsitonEnd : function(){
        if(this._elTransition){
            jindo.m.detachTransitionEnd(this._elTransition, this._wfTransitionEnd);
            this._elTransition = null;
        }
    },

    /**
     * 컨텐츠인덱스 정보를 다시 세팅한다.
     * 
     * @param {Number} n 컨텐츠 인덱스
     * @param {HTMLElement} el  컨텐츠 인덱스에 해당하는 Element
     */
    
    _resetIndexInfo : function(n, el){
        

        this._htIndexInfo.nContentIndex = n;
        this._htIndexInfo.nNextContentIndex = n;

        if(typeof el === 'undefined'){
            if(this.option('bUseCircular')){
                n = n%this._htWElement.aPanel.length;
            }
            el =  this._htWElement.aPanel[n];
        }

        this._htIndexInfo.welElement = el;
        this._htIndexInfo.welNextElement = el;
        this._htIndexInfo.sDirection = null;
    },


    /**
     * 모바일 기기의 rotate 시 호출 함수
     * 회전으로 인한 재 정의 
     * @param {Object} evt isVertical 수직여부
     */
    _onResize : function(evt){
        if(this.option('bAutoResize')){
            var n = this.getIndexByElement(this.getElement().$value());
            this.refresh(n, true, false);
        }

        /**
          단말기가 회전될 때 발생한다

          @event rotate
          @param {String} sType 커스텀 이벤트명
          @param {Boolean} isVertical 수직여부
          @param {Function} stop 수행시 영향을 받는것은 없다
        **/
        this.fireEvent("rotate",{
            isVertical : evt.isVertical
        });
    },
    

    /**
     * jindo.m.PreviewFlicking 컴포넌트를 활성화한다.
     * activate 실행시 호출됨
     */
    _onActivate : function() {
        // this._attachEvent();

        var htInfo = jindo.m.getDeviceInfo();
        this._bAndroid = htInfo.android && (!htInfo.bChrome);
        this._isIos = (htInfo.iphone || htInfo.ipad);
        this._nVersion = htInfo.version;

        this._sCssPrefix = jindo.m.getCssPrefix();

          this._setWrapperElement();
          this._initVar();
          this._createDummyTag();
        
        this._setAnchorElement();
        this._initTouch();
        this._initFlicking();
        this.refresh(this.getContentIndex(), true);

    },

    /**
     * jindo.m.PreviewFlicking 컴포넌트를 비활성화한다.
     * deactivate 실행시 호출됨 
     */
    _onDeactivate : function() {
        this._detachEvent();
    },
    
    /**
     * 객체 초기화 및 Bind 해제, 이벤트를 detach 한다.
     */
    _detachEvent : function() {
        /* touch detach */
        this._oTouch.detachAll();

        /* rotate */
        jindo.m.unbindRotate(this._htEvent["rotate"]);

        /*그외*/
        for(var p in this._htEvent){
            var htTargetEvent = this._htEvent[p];
            if (typeof htTargetEvent.ref !== "undefined") {
                htTargetEvent.ref.detach(htTargetEvent.el, p);
            }
        }

        this._htEvent = null;

    },

    /**
     * flicking 내에 a 엘리먼트를 모두 가져와서 세팅한다. (ios에서만) 
     */
    _setAnchorElement : function(el){
    //ios에서만 처리되도록 수정.
        if(this._bClickBug){
            this._aAnchor = jindo.$$("A", this._htWElement.container.$value());
        }
    },

    /**
     * Anchor 삭제
     * IOS인경우 플리킹시 링크가 의도치 않게 처리될 수 있어 링크 제거하는 처리 
     */
    _clearAnchor : function() {
        if(this._aAnchor && !this._bBlocked) {
            var aClickAddEvent = null;
            for(var i=0, nILength=this._aAnchor.length; i<nILength; i++) {
                if (this._fnDummyFnc !== this._aAnchor[i].onclick) {
                    this._aAnchor[i]._onclick = this._aAnchor[i].onclick;
                }
                this._aAnchor[i].onclick = this._fnDummyFnc;
                aClickAddEvent = this._aAnchor[i].___listeners___ || [];
                for(var j=0, nJLength = aClickAddEvent.length; j<nJLength; j++) {
                    ___Old__removeEventListener___.call(this._aAnchor[i], "click", aClickAddEvent[j].listener, aClickAddEvent[j].useCapture);
                }
            }
            this._bBlocked = true;
        }
    },

    /**
     * Anchor 복원. for iOS
     */
    _restoreAnchor : function() {
        if(this._aAnchor && this._bBlocked) {
            var aClickAddEvent = null;
            for(var i=0, nILength=this._aAnchor.length; i<nILength; i++) {
                if(this._fnDummyFnc !== this._aAnchor[i]._onclick) {
                    this._aAnchor[i].onclick = this._aAnchor[i]._onclick;
                } else {
                    this._aAnchor[i].onclick = null;
                }
                aClickAddEvent = this._aAnchor[i].___listeners___ || [];
                for(var j=0, nJLength = aClickAddEvent.length; j<nJLength; j++) {
                    ___Old__addEventListener___.call(this._aAnchor[i], "click", aClickAddEvent[j].listener, aClickAddEvent[j].useCapture);
                }
            }
            this._bBlocked = false;
        }
    },

    /**
     * 플리킹 동작 시 브라우저 별 또는 상태에 따라 발생된 버그를 해결하기 위한 처
     */
    _setFixedBug : function(){
        var self = this;
        //ios 업데이트
        this._restoreAnchor();
        this._setAnchorElement();

        //android css transform 이후에 포커싱 안되는 문제를 해결하기 위한 코드
        this._createDummyTag();
        setTimeout(function(){
            self._focusFixedBug();
        }, 100);
    },

    /**
     * 안드로이드 전용 랜더링 버그 해결을 위한 더미 태그를 만든다.
     */
    _createDummyTag : function(){
    //android 포커스를 위한 더미 태그가 필요
        if(this._bDummyTagException) {
            //debugger;
            this._htWElement.aDummyTag = [];
            for(var i=0,nLen = this._htWElement.aPanel.length;i<nLen;i++){
                var wel =this._htWElement.aPanel[i];
                var elDummyTag = jindo.$$.getSingle("._cflick_dummy_atag_", wel.$value());
                if(!elDummyTag){
                    elDummyTag = jindo.$("<a href='javascript:void(0);' class='_cflick_dummy_atag_'></a>");
                    elDummyTag.style.position = "absolute";
                    elDummyTag.style.left = "-1000px";
                    elDummyTag.style.top = "-1000px";
                    elDummyTag.style.width = 0;
                    elDummyTag.style.height = 0;
                    wel.append(elDummyTag);
                }
                this._htWElement.aDummyTag.push(elDummyTag);
            }
        }
    },

    /**
     * 안드로이드에서 css 속성을 사용해서 transform 이후에 포커스를 잃는 현상의 버그 수정하는 코드
     */
    _focusFixedBug : function(){
        if(!this._htWElement || typeof this._htWElement.aDummyTag === 'undefined'){
            return;
        }

        for(var i=0,nLen= this._htWElement.aDummyTag.length;i<nLen;i++){
            this._htWElement.aDummyTag[i].focus();
        }
    },


    /**
     * 커스텀이벤트 발생시킨다
     * @param {String} 커스텀 이벤트 명
     * @param {Object} 커스텀 이벤트 파라미터
     * @return {Boolean} 
     */
    _fireCustomEvent : function(sEventName, htParam){
        if(typeof htParam === 'undefined'){
            htParam =  {
                //nContentsIndex : this.getContentIndex()
                nContentsIndex : this._htIndexInfo.nContentIndex
            };
            if(this._bFlickLeft){
                htParam.bLeft = this._bFlickLeft;
            }
        }

        return this.fireEvent(sEventName,htParam);
    },


    /**
     * nIndex 컨텐츠로 이동한다 
     * @param {Number} nIndex 컨텐츠 인덱스 
     * @param {Number} nDruation  슬라이드 애니메이션 지속 시간
     * @param {Boolean} bFireEvent  커스텀 이벤트 발생여부
     */
    _moveTo : function(nIndex, nDuration , bFireEvent){
        if(this.option('bUseCircular')){
            this.refresh(nIndex, false, bFireEvent);
        }else{
            if(bFireEvent){
                if(!this._fireCustomEvent('beforeMove',{
                    nContentsIndex : this.getContentIndex(),
                    nContentsNextIndex : nIndex
                })){
                    return;
                }
            }

            var nDis = this._getMovePos(nIndex);
            this._htIndexInfo.welNextElement = this._htWElement.aPanel[nIndex];
            this._htIndexInfo.nNextContentIndex = nIndex;
            if(bFireEvent){
                this._bFireMoveEvent = true;
            }
            if(nDuration !== 0){
                this._attachTransitionEnd(this._htWElement.container.$value(), nDuration);
            }else{
                var self = this;
                setTimeout(function(){
                self._onTransitionEnd();
                },100);
            }
            this._setPosContainer(nDis, nDuration);
        }
    },

    /**
     * 플리킹 영역을 다시 재정의 한다.
     * 
     * @method refresh
     * @param {Number|} n 컨텐츠의 index 정보 
     * @param {Boolean} bResize 리사이즈 발생 여부 
     * @param {Boolean} bFireEvent 커스텀 이벤트 발생 여부 
     */
    refresh : function(n, bResize, bFireEvent){
        var self = this;
        if(typeof n === 'undefined'){
            n = this.getContentIndex();
        }

        if(typeof bResize === 'undefined'){
            bResize = true;
        }

        if(typeof bFireEvent === 'undefined'){
            bFireEvent = true;
        }

        if(bFireEvent){
            /**
            현재 화면에 보이는 콘텐츠가 바꾸기 직전에 수행된다.

            @event beforeMove
            @param {String} sType 커스텀 이벤트명
            @param {Number} nContentsIndex 현재 콘텐츠의 인덱스
            @param {Number} nContentsNextIndex (Number) :이동 할 콘텐츠의 인덱스
            @param {Function} stop 이동하지 않는다.
            **/
            if(!this._fireCustomEvent('beforeMove',{
                nContentsIndex : this.getContentIndex(),
                nContentsNextIndex : n
            })){
                return;
            }
        }
        
        this._refresh(n, bResize);
        this._resetIndexInfo(n);

        if(bFireEvent){
            /**
            현재 화면에 보이는 콘텐츠가 바뀔경우 수행된다

            @event move
            @param {String} sType 커스텀 이벤트명
            @param {Number} nContentsIndex 현재 콘텐츠의 인덱스
            @param {Function} stop 수행시 영향을 받는것은 없다
            **/
            this._fireCustomEvent('move');
        }

        this._setFixedBug();
    },

    /**
     * 현재 플리킹 화면에 보이는 컨텐츠의 인덱스를 리턴한다.
     * @method getContentIndex
     * @return {Number} n
     */
    getContentIndex : function(){
        return this._htIndexInfo.nContentIndex;
    },

    /**
     * 이후 컨텐츠의 패널 엘리먼트의 래핑된 엘리먼트를 리턴한다.
     * @method getNextElement
     * @return {jindo.$Element} el
     */
    getNextElement : function(){
        var n = this.getNextIndex();

        if(this.option('bUseCircular')){
            n = this.getIndexByElement(this.getElement().$value());
            n = ((n+1) > this._htWElement.aPanel.length -1 )?  0 : (n+1);
        }

        return this._htWElement.aPanel[n];
    },

    /**
     * 이전 컨텐츠의 패널 엘리먼트의 래핑된 엘리먼트를 리턴한다.
     * @method getPrevElement
     * @return {jindo.$Element} el
     */
    getPrevElement : function(){

        var n = this.getPrevIndex();

        if(this.option('bUseCircular')){
            n = this.getIndexByElement(this.getElement().$value());
            n = ((n-1)< 0)? this._htWElement.aPanel.length - 1: (n-1);
        }
        return this._htWElement.aPanel[n];
    },
    
    /**
     * 이후 컨텐츠의 인덱스를 리턴한다.
     * @method getNextIndex
     * @return {Number} n
     */
    getNextIndex : function(nIndex){

        var n = (nIndex || this.getContentIndex()) +1;
        var nMax = this.getTotalContents() - 1;
        
        if(this.option('bUseCircular') && (n > nMax) ){
            n = 0;
        }
        n = Math.min(nMax, n);

        return n;
    },

    /**
     * 이전 컨텐츠의 인덱스를 리턴한다.
     * @method getPrevIndex
     * @return {Number} n
     */
    getPrevIndex : function(){

        var n = this.getContentIndex()-1;

        if(this.option('bUseCircular') && (n < 0) ){
            n = this.getTotalContents() - 1;
        }

        n = Math.max(0, n);
        return n;
    },

    /**
     * 전체 컨텐츠의 개수를 리턴한다.
     * @method getTotalContents
     * @return {Number} nValue
     */
    getTotalContents : function(){
        var nValue = this._htWElement.aPanel.length;

        if(this.option('bUseCircular')){
            if(typeof this.option('nTotalContents') ==='undefined'){
                nValue = this._htWElement.aPanel.length;
            }else{
                nValue = this.option('nTotalContents');
            }
        }
        return nValue;

    },

    /**
     * 엘리먼트를 기준으로 하는 index 정보 리턴
     * @param {Element} el 조회하고자 하는 element
     * @return {Number} nValue Index 정보 
     */
    getIndexByElement : function(el){
        var nValue = -1;
        for(var i=0, nLen = this._htWElement.aPanel.length; i<nLen; i++){
            if(this._htWElement.aPanel[i].$value() === el){
                nValue = i;
                break;
            }
        }
        return nValue;
    },

    /**
     * 현재 화면에 중앙에 보이는 컨텐츠 혹은 패널의 래핑된 엘리먼트를 리턴한다.
     * @method getElement
     * @return {jindo.$Element} el
     */
    getElement : function(){
        return this._htIndexInfo.welElement;
    },

    /**
     * 다음 플리킹화면으로 이동한다.
     * @method moveNext
     * @param {Number} nDuration 플리킹 애니메이션 시간
     */
    moveNext : function(nDuration){
        
        if(!this.isActivating()){
            return;
        }
        if(this._doFlicking){
            return;
        }
        var welNext = this.getNextElement();
        if(welNext.$value() === this.getElement().$value()){
            return;
        }

        if(typeof nDuration === 'undefined'){
            nDuration = this.option('nDuration');
        }

        this._bTouchStart = true;
        this._bMove = true;

        var n = this.option('nFlickThreshold')*-1;
        // var nPos = this._htWElement.base.width();

        this._onEnd({
            nDistanceX : n-10,
            nDistanceY : n-10,
            nX : 10,
            nY : 10
        }, nDuration);

    },

    /**
     * 이전  플리킹화면으로 이동한다.
     * @method movePrev
     * @param {Number} nDuration 플리킹 애니메이션 시간
     */
    movePrev : function(nDuration){
        if(!this.isActivating()){
            return;
        }
        if(typeof nDuration === 'undefined'){
            nDuration = this.option('nDuration');
        }

        if(this._doFlicking){
            return;
        }

        var welPrev = this.getPrevElement();
        if(welPrev.$value() === this.getElement().$value()){
            return;
        }
        if(typeof nDuration === 'undefined'){
            nDuration = this.option('nDuration');
        }
        this._bTouchStart = true;
        this._bMove = true;

        var n = this.option('nFlickThreshold');
        this._onEnd({
            nDistanceX : n+10,
            nDistanceY : n+10,
            nX : 10,
            nY : 10
        }, nDuration); 
    },


    /**
     * n 번째 컨텐츠로 현재 플리킹화면을 이동한다.
     * @method moveTo
     * @param {Number} n 이동해야하는 컨텐츠 인덱스
     * @param {Number} nDuration 애니메이션 시간
     * @param {Number} bFireEvent 커스텀 이벤트 발생여부
     */
    moveTo : function(nIndex, nDuration, bFireEvent){
        
        if((typeof nIndex === 'undefined') || (nIndex == this.getContentIndex()) ){
            return;
        }
        if(nIndex < 0 || nIndex >= this.getTotalContents() ){
            return;
        }

        if((typeof nIndex === 'undefined') || (nIndex == this.getContentIndex()) ){
            return;
        }
        if(nIndex < 0 || nIndex >= this.getTotalContents() ){
            return;
        }

        if(typeof nDuration === 'undefined'){
            nDuration = this.option('nDuration');
        }

        if(typeof bFireEvent === 'undefined'){
            bFireEvent = true;
        }
        this._moveTo(nIndex, nDuration, bFireEvent);

    },

    /**
     * 초기화 처리 
     */
    destroy : function(){
        this.deactivate();

        this._htWElement = null;
        this._htPosition = {};
        this._sTranslateStart = "translate(";
        this._sTranslateEnd = ")";
        this._bTouchStart  = false;
        this._bMove = false;
        this._sCssPrefix = null;
        this._wfTransitionEnd = jindo.$Fn(this._onTransitionEnd, this).bind();
        this._htIndexInfo = null;
        this._sEl = null;
        this._isIos = null;
        this._bAndroid = null;
        this._nVersion = null;
        this._fnDummyFnc = null;
        this._doFlicking = null;
        this._bClickBug = null;
        this._b3dExecption = null;
        this._bDummyTagException = null;
    }

}).extend(jindo.m.UIComponent);