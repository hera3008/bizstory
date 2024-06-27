/**
    @fileOverview 특정 Layer에 애니메이션 효과를 적용하여 보여주거나, 숨기거나, 이동시키는 컴포넌트
    @author "oyang2"
    @version 1.9.0
    @since 2011. 12. 13.
**/
/**
    특정 Layer에 애니메이션 효과를 적용하여 보여주거나, 숨기거나, 이동시키는 컴포넌트

    @class jindo.m.LayerEffect
    @extends jindo.m.UIComponent
    @uses jindo.m.Transition
    @keyword layer, effect, animation, 레이어, 효과, 애니메이션
	@group Component
    
    @history 1.5.0 Support Window Phone8 지원
    @history 1.4.0 Support  iOS 6 지원
    @history 1.2.0 Support Chrome for Android 지원<br />갤럭시 S2 4.0.3 업데이트 지원
    @history 1.1.0 Support Android 3.0/4.0 지원<br />jindo 2.0.0 mobile 버전 지원
    @history 1.1.0 Bug slide시 옵션으로 거리값을 지정해도 설정되지 않던 문제 해결
    @history 0.9.0 Release 최초 릴리즈
**/

jindo.m.LayerEffect = jindo.$Class({
    /* @lends jindo.m.LayerEffect.prototype */
    /**
        초기화 함수

        @constructor
        @param {Object} [htOption] 초기화 옵션 객체
            @param {Number} [htOption.nDuration=250] 애니메이션 적용시간 (ms)
            @param {String} [htOption.sTransitionTimingFunction='ease-in-out'] css Timeing function을 설정
            @param {Boolean} [htOption.bActivateOnload=true] 컴포넌트 로드시 activate() 수행여부
    **/
    $init : function(el, htUserOption) {
        this.option({
            nDuration : 250,
            bActivateOnload : true
        });
        this.option(htUserOption || {});
        this._initVar();
        this.setLayer(el);

        this._initTransition();

        if(this.option("bActivateOnload")) {
            this.activate();
        }
    },

    _htEffect :{
        'expand' : "jindo.m.ExpandEffect",
        'contract' : "jindo.m.ContractEffect",
        "fade" : "jindo.m.FadeEffect",
        "pop" : "jindo.m.PopEffect",
        "slide" : "jindo.m.SlideEffect",
        "flip" : "jindo.m.FlipEffect"
    },

    /**
        jindo.m.LayerEffect 에서 사용하는 모든 인스턴스 변수를 초기화한다.
    **/
    _initVar: function() {
        this._htEffectInstance  = {};
        this._htLayerInfo = {};
        this._htWElement = {}; //jindo.m.LayerEffect에서 사용하는 엘리먼트 참조

        this.bAndroid = jindo.m.getDeviceInfo().android;
        this.sClassHighligting = '_effct_hide_highlighting_tmp';
    },

    /**
        Transition 컴포넌트 생성
    **/
    _initTransition : function(){
        this._oTransition = new jindo.m.Transition();
    },

    /**
        sType에 해당하는 Effect의 인스턴스 생성한다.
        @param {String} sType
    **/
    _createEffect : function(sType){
        if(this._htEffect[sType] && !this._htEffectInstance[sType]) {

            //console.log("객체 생성 : new " +this._htEffect[sType] + "()" );
            try{
                this._htEffectInstance[sType] = eval("new " + this._htEffect[sType] + "()");
            }catch(e){
                //console.log(e);
            }

            this._htEffectInstance[sType].setLayerInfo(this._htLayerInfo);
        }
    },

    /**
        높이나 넓이값을 조정하여 레이어를 확대한다.
        @remark 현재 레이어가 안보이는 상태일 경우 레이어를 보이게 하고 애니메이션을 수행한다.

        @method expand
        @param {Object} htOption
        @example
            oLayerEffect.expand() //아래쪽으로 높이값을 조정하여 확대한다.
        @example
            oLayerEffect.expand({
                sDirection : 'up',  // 'up','down','left',right'설정가능하며 기본값은 'down'이다
                nDuration : 500, //효과 애니메이션 적용시간 (ms)
                sTransitionTimingFunction : 'ease-in-out', //효과 effect ('ease', 'linear', ease-in', 'ease-out', 'ease-in-out')
                htFrom : {opacity : 0, zIndex: 10}, //expand 이전의 css를 설정한다.
                htTo : {opacity : 1, zIndex: 20} //expand 이후의 css를 설정한다.
            });
    **/
    expand : function(htOption){
        var sType = 'expand';
        this._run(sType, htOption);
    },

    /**
        높이나 넓이값을 조정하여 레이어를 축소한다
        @remrark 현재 레이어가 안보이는 상태일 경우 레이어를 보이게 하고 애니메이션을 수행한다.

        @method contract
        @param {Object} htOption
        @example
            oLayerEffect.contract() //레이어를 아래쪽으로 방향으로 축소한다.
        @example
            oLayerEffect.contract({
                sDirection : 'up',  // 'up','down','left',right'설정가능하며 기본값은 'down'이다
                nDuration : 500, //효과 애니메이션 적용시간 (ms)
                sTransitionTimingFunction : 'ease-in-out', //효과 effect ('ease', 'linear', ease-in', 'ease-out', 'ease-in-out')
                htFrom : {opacity : 0, zIndex: 10}, //contract 이전의 css를 설정한다.
                htTo : {opacity : 1, zIndex: 20} //contract 이후의 css를 설정한다.
            });
    **/
    contract : function(htOption){
        var sType = 'contract';
        this._run(sType, htOption);
    },

    /**
        레이어의 투명도를 조정하여 숨기거나 보여준다.
        @remark fadeOut 이후에는 레이어를 감춘다.

        @method fade
        @param {Object} htOption
        @example
            oLayerEffect.fade(); //기본으로 fade In 효과 투명도를 높여 보여주는 효과를 준다
        @example
            oLayerEffect.fade({
                sDirection : 'out',  // 'in' 또는 'out'을 정할수 있으며 기본값은 'in' 이다.
                nDuration : 500, //효과 애니메이션 적용시간 (ms)
                sTransitionTimingFunction : 'ease-in-out', //효과 effect ('ease', 'linear', ease-in', 'ease-out', 'ease-in-out')
                htFrom : {opacity : 0, z-index: 10}, //fade 이전의 css를 설정한다. opacity 설정하지 않을 경우 기본값은 0이다
                htTo : {opacity : 1, z-index: 20} //fade 이후의 css를 설정한다.  opacity 설정하지 않을 경우 기본값은 1이다
            });
    **/
    fade : function(htOption){
        var sType = "fade";
        //console.log('\\\\\\ Fade', htOption );
        this._run(sType, htOption);
    },

    /**
        scale 조정을 통해 pop 효과를 낸다.
        @remark
            popOut 이후에는 레이어를 감춘다.<br />
            - ios3의 경우 scale 값이 0이 아닌 0.1로 세팅합니다. <br />
            - htFrom과 htTo의 scale을 설정하지 않으면 'in'일 경우 0-1로 설정하며 'out'일 경우 1-0으로 설정합니다.(ios3 예외)

        @method pop
        @param {Object} scale 조정을 통해 pop 효과를 낸다. popOut 이후에는 레이어를 감춘다.<br />
            - ios3의 경우 scale 값이 0이 아닌 0.1로 세팅합니다.<br />
            - htFrom과 htTo의 scale을 설정하지 않으면 'in'일 경우 0-1로 설정하며 'out'일 경우 1-0으로 설정합니다.(ios3 예외)

        @example
            oLayerEffect.pop() //pop in 효과를내며 scale을 점점 줄여서 레이어가 없어지는 효과를 낸다.
        @example
            oLayerEffect.pop({
                sDirection : 'in',  // 'in','out' 설정가능하며 기본값은 'in'이다
                nDuration : 500, //효과 애니메이션 적용시간 (ms)
                sTransitionTimingFunction : 'ease-in-out', //효과 effect ('ease', 'linear', ease-in', 'ease-out', 'ease-in-out')
                htFrom : {opacity : 0, zIndex: 10}, //pop 이전의 css를 설정한다. opacity의 기본값은 'in'의 경우 0.1이며 'out'의 경우 1이다
                htTo : {opacity : 1, zIndex: 20} //pop 이후의 css를 설정한다. opacity의 기본값은 'in'의 경우 1이며 'out'의 경우 0.1이다
            });
    **/
    pop : function(htOption){
        var sType = "pop";
        this._run(sType, htOption);
    },

    /**
        레이어를 설정된 방향으로 움직인다

        @method slide
        @param {Object} htOption
        @example
            oLayerEffect.slide(); //기본방향이 왼쪽이기 때문에 왼쪽으로 레이어의 넓이만큼 움직인다.
        @example
            oLayerEffect.slide({
                elBaseLayer :  jindo.$('wrapper') //기준 뷰 엘리먼트를 설정할 경우 레이어는 기준뷰를 중심으로 slide 동작을 합니다.
            });
        @example
            oLayerEffect.slide({
                sDirection : 'left', //'left, 'right, 'up', 'down' 설정가능하다
                nDuration : 500, //효과 애니메이션 적용시간 (ms)
                nSize : 200, //slide 할 거리, 디폴트 값은 레이어 크기가 됨 (px)
                sTransitionTimingFunction : 'ease-in-out', //효과 effect ('ease', 'linear', ease-in', 'ease-out', 'ease-in-out')
                elBaseLayer : jindo.$('wrapper'), //기준 뷰가 되는 엘리먼트, 없을 경우 설정하지 않는다.
                htTo : {opacity : 1} , //레이어의 slide 이후의 css를 설정
                htFrom : {opacity : 0.7}  //레이어의 slide 이전의 css를 설정
            });
    **/
    slide : function(htOption){
        var sType = "slide";
        this._run(sType, htOption);
    },

    /**
        레이어을 방향에 따라 뒤집는 효과를 낸다. (iOS 전용)

        @method flip
        @param{Object} htOption 레이어을 방향에 따라 뒤집는 효과를 낸다. (iOS 전용)
        @example
            oLayerEffect.flip(); //현재 레이어 엘리먼트를 좌우로 뒤집는다
        @example
            oLayerEffect.flip({
                sDirection : 'up',  // 'up','down','left',right'설정가능하며 기본값은 'left'이다
                nDuration : 500, //효과 애니메이션 적용시간 (ms)
                sTransitionTimingFunction : 'ease-in-out', //효과 effect ('ease', 'linear', ease-in', 'ease-out', 'ease-in-out')
                elFlipFrom : jindo.$('flip'), //두개의 레이어가 뒤집히는 효과를 낼때 뒤쪽으로 뒤집히는 엘리먼트, 한개 레이어 효과가 필요할때는 설정하지 않는다.
                elFlipTo : jindo.$('layer1') //두개의 레이어가 뒤집히는 효과를 낼때 앞쪽으로 뒤집히는 엘리먼트, 한개 레이어 효과가 필요할때는 설정하지 않는다
                htFrom : {opacity : 0, zIndex: 10}, //flip 이전의 css를 설정한다. opacity 값은 기본값은 0이다
                htTo : {opacity : 1, zIndex: 20} //flip 이후의 css를 설정한다. opacity 값은 기본값은 1이다
            });
    **/
    flip: function(htOption){
        var sType = "flip";
        this._run(sType, htOption);
    },

    /**
        현재 effect가 실행 여부를 리턴한다

        @method isPlaying
        @return {Boolean}
    **/
    isPlaying : function(){
        return this._oTransition.isPlaying();
    },

    /**
        커스텀 이벤트 발생
     */
    _fireCustomEvent : function(sType, htOption){
        return this.fireEvent(sType, htOption);
    },


    /**
        sType의 이펙트를 실행
        @param {String} sType
        @param {HashTabl}
     */
    _run : function(sType, htOption){
        if(!this._isAvailableEffect()){
            return;
        }

        this._createEffect(sType);

        if(typeof htOption === 'undefined'){
            htOption = {};
        }

        var oEffect = this._htEffectInstance[sType];

        var el = this.getLayer();
        var nDuration = (typeof htOption.nDuration  === 'undefined')? this.option('nDuration') : parseInt(htOption.nDuration,10);
        var htBefore = oEffect.getBeforeCommand(el, htOption);
        var htCommand = oEffect.getCommand(el, htOption);

        //customEvent
        /**
            애니메이션 효과가 시작하기 직전 발생한다

            @event beforeEffect
            @param {String} sType 커스텀 이벤트명
            @param {HTMLElement} elLayer 애니메이션 효과가 적용된 레이어 엘리먼트
            @param {String} sEffect 적용할 애니메이션 효과 이름 , '-'을 구분한다. (fade-in, slide-left)
            @param {Number} nDuration 애니메이션 적용 시간(ms)
            @param {Function} stop 수행시 애니메이션 효과 시작되지 않는다.
        **/
        if(!this._fireCustomEvent("beforeEffect", {
            elLayer : el,
            sEffect :htCommand.sTaskName,
            nDuration :nDuration
        })){
            return;
        }

        //console.log('LAYER=------- , rund');

        if(htBefore){
            this._oTransition.queue(this.getLayer(), 0, htBefore);
        }

        this._oTransition.queue(this.getLayer(), nDuration , htCommand);

        this._oTransition.start();
    },

    /**
        el을 을 effect 대상 레이어로 설정한다.

        @method setLayer
        @param {HTMLElement} el
    **/
    setLayer : function(el){
        this._htWElement["el"] = jindo.$(el);
        this._htWElement["wel"] = jindo.$Element(this._htWElement["el"]);
        var elFocus;
        //android 하이라이팅 문제로 인하여 엘리먼트 추가;
        if(!!this.bAndroid){
            elFocus = jindo.$$.getSingle('.'+this.sClassHighligting, this._htWElement['el']);

            if(!elFocus){
                var sTpl = '<a href="javascript:void(0)" style="position:absolute" class="'+this.sClassHighligting+'"></a>';
                elFocus = jindo.$(sTpl);
                this._htWElement['wel'].append(elFocus);
                elFocus.style.opacity = '0';
                elFocus.style.width= 0;
                elFocus.style.height= 0;
                elFocus.style.left = "-1000px";
                elFocus.style.top = "-1000px";
            }
        }

        this.setSize();
    },

    /**
        현재 이펙트를 멈춘다.
        @remark bAfter 가 true일 경우 이펙트 이후 상태로 멈추고, false 일경우 이펙트 이전 상태로 되돌린다.

        @method stop
        @return {Boolean} bAfter
    **/
    stop : function(bAfter){
        if(typeof bAfter === 'undefined'){
            bAfter = true;
        }
        if(this._oTransition){
            this._oTransition.stop(bAfter);
        }
    },

    /**
        현재 큐에 쌓여있는 모든 effect 실행을 삭제한다.
        @remark
            현재 이펙트가 실행중이면 중지하고 삭제한다.<br />
            bAfter 가 true일 경우 이펙트 이후 상태로 멈추고, false 일경우 이펙트 이전 상태로 되돌린다.

        @method clearEffect
        @return {Boolean} bAfter
        @history 1.1.0 Update Method 추가
    **/
    clearEffect : function(bAfter){
        if(this._oTransition){
            this._oTransition.clear(bAfter);
        }
    },
    /**
        현재 레이어를 리턴한다.

        @method getLayer
        @return {HTMLElement}
    **/
    getLayer : function(){
        return this._htWElement["el"];
    },

    /**
        레이어를 사이즈 및 CSS 정보를 설정한다.

        @method setSize
    **/
    setSize : function(){
        var elToMeasure = this._htWElement['el'].cloneNode(true);
        var welToMeasure = jindo.$Element(elToMeasure);
        welToMeasure.opacity(0);
        this._htWElement['wel'].after(welToMeasure);
        welToMeasure.show();

        this._htLayerInfo["nWidth"] = this._htWElement["wel"].width();
        this._htLayerInfo["nHeight"] = this._htWElement["wel"].height();

        welToMeasure.css({
            position : "absolute",
            top : "0px",
            left : "0px"
        });
        this._htLayerInfo['nMarginLeft'] = parseInt(welToMeasure.css('marginLeft'),10);
        this._htLayerInfo['nMarginTop'] = parseInt(welToMeasure.css('marginTop'),10);
        this._htLayerInfo['nMarginLeft']  = isNaN(this._htLayerInfo['nMarginLeft'] )? 0 : this._htLayerInfo['nMarginLeft'];
        this._htLayerInfo['nMarginTop'] = isNaN(this._htLayerInfo['nMarginTop'])? 0 : this._htLayerInfo['nMarginTop'];
        this._htLayerInfo['nOpacity'] = this._htWElement["wel"].opacity();
        this._htLayerInfo['sPosition'] = this._htWElement["wel"].css('position');
        var sDisplay = this._htWElement['wel'].css('display');

        sDisplay = ((sDisplay === 'none') || (sDisplay.length === 0))? 'block' : sDisplay;
        this._htLayerInfo['sDisplay'] = sDisplay;
        this._htLayerInfo['sClassHighligting'] = this.sClassHighligting;

        welToMeasure.leave();

        this._setEffectLayerInfo();

        //console.log('/////setSize', this._htLayerInfo);
    },

    /**
        레이어정보를 다시 설정한다.
     */
    _setEffectLayerInfo : function(){
        for(var p in this._htEffectInstance){
            this._htEffectInstance[p].setLayerInfo(this._htLayerInfo);
        }
    },
    /**
        transition end 이벤트 핸들러
     */
    _onTransitionEnd : function(oCustomEvent){
        if(oCustomEvent.sTaskName){
            /**
                애니메이션 효과가 종료된 직후 발생한다.

                @event afterEffect
                @param {String} sType 커스텀 이벤트명
                @param {HTMLElement} elLayer 애니메이션 효과가 적용된 레이어 엘리먼트
                @param {String} sEffect 적용할 애니메이션 효과 이름 , '-'을 구분한다. (fade-in, slide-left)
                @param {Number} nDuration 애니메이션 적용 시간(ms)
                @param {Function} stop stop를 호출하여 영향 받는 것은 없다.
            **/
            this._fireCustomEvent("afterEffect", {
                elLayer : oCustomEvent.element,
                sEffect : oCustomEvent.sTaskName,
                nDuration : oCustomEvent.nDuration
            });
        }
    },

    /**
        transition stop 이벤트 핸들러
     */
    _onTransitionStop : function(oCustomEvent){
        if(oCustomEvent.sTaskName){
            /**
                애니메이션 효과가 stop 될때 발생한다.

                @event stop
                @param {String} sType 커스텀 이벤트명
                @param {HTMLElement} elLayer 애니메이션 효과가 적용된 레이어 엘리먼트
                @param {String} sEffect 적용할 애니메이션 효과 이름 , '-'을 구분한다. (fade-in, slide-left)
                @param {Number} nDuration 애니메이션 적용 시간(ms)
                @param {Function} stop 호출하여 영향 받는 것은 없다.
            **/
            this._fireCustomEvent("stop", {
                elLayer : oCustomEvent.element,
                sEffect : oCustomEvent.sTaskName,
                nDuration : oCustomEvent.nDuration
            });
        }
    },

    /**
        현재 effect를 실행 시킬수 있는 상태인지 리턴한다
        @return {Boolean}
    **/
    _isAvailableEffect : function(){
        return this.isActivating();
    },

    /**
        jindo.m.LayerEffect 컴포넌트를 활성화한다.
        activate 실행시 호출됨
    **/
    _onActivate : function() {
        this._attachEvent();
    },

    /**
        jindo.m.LayerEffect 컴포넌트를 비활성화한다.
        deactivate 실행시 호출됨
    **/
    _onDeactivate : function() {
        this._detachEvent();
    },


    /**
        jindo.m.LayerEffect 에서 사용하는 모든 이벤트를 바인드한다.
    **/
    _attachEvent : function() {
        this._htEvent = {};
        this._htEvent["end"] = jindo.$Fn(this._onTransitionEnd, this).bind();
        this._htEvent["stop"] = jindo.$Fn(this._onTransitionStop, this).bind();

        if(this._oTransition){
            this._oTransition.attach({
                "end" : this._htEvent["end"],
                "stop" : this._htEvent["stop"]
            });
        }
    },

    /**
        jindo.m.LayerEffect 에서 사용하는 모든 이벤트를 해제한다.
    **/
    _detachEvent : function() {
        this._htEvent = null;

        if(this._oTransition){
            this._oTransition.detachAll();
        }
    },

    /**
        jindo.m.LayerEffect 에서 사용하는 모든 객체를 release 시킨다.
        @method destroy
    **/
    destroy: function() {
        this.deactivate();

        for(var p in this._htWElement) {
            this._htWElement[p] = null;
        }
        this._htWElement = null;

    }
}).extend(jindo.m.UIComponent);


