/**
    @fileOverview 여러개의 콘텐츠 영역을 사용자 터치의 움직임을 통해 좌/우, 상/하 로 슬라이드하여 보여주는 컴포넌트
    @author sculove
    @version #__VERSION__#
    @since 2013. 4. 27.
*/
/**
    여러개의 콘텐츠 영역을 사용자 터치의 움직임을 통해 좌/우, 상/하 로 슬라이드하여 보여주는 컴포넌트

    @class jindo.m.SlideFlicking
    @extends jindo.m.Flick
    @uses jindo.m.Slide
    @keyword flicking, 플리킹
    @group Component

		@history 1.8.0 Release 최초 릴리즈  
**/
jindo.m.SlideFlicking = jindo.$Class({
	/* @lends jindo.m.SlideFlicking.prototype */
	/**
      초기화 함수

      @constructor
      @param {String|HTMLElement} el 플리킹 기준 Element (필수)
      @param {Object} [htOption] 초기화 옵션 객체
  **/
	$init : function(el,htUserOption) {
		// console.log("--Panel init");
		this.option(htUserOption || {});
		this._initVar();
		this._setWrapperElement(el);
		if(this.option("bActivateOnload")) {
			this.activate();
		}
	},

	_onActivate : function() {
		jindo.m.Flick.prototype._onActivate.apply(this);

		var self = this;
		this.set(new jindo.m.Slide(this._getAnimationOption())
			.attach({
				"set" : function(we) {
					self._setStyle(we.css);
				}
			}),
			this._htWElement["container"]
		);
	},

	// 엘리먼트 구조 설정하기
	_setStyle : function(htCss) {
		var htContCss = {},
			nPos = 0,
			nSizeKey = this._bUseH ? "width" : "height",
			nPosKey = this._bUseH ? "left" : "top";

		for(var i in htCss) {
			htContCss[i] = htCss[i];
		}

		if(this._bUseCircular) {
			// container
			htContCss[nSizeKey] = "100%";
			htContCss[nPosKey] = "-100%";
			// panel
			htCss["position"] = "absolute";
			htCss[nSizeKey] = "100%";
			htCss["left"] = 0;
			htCss["top"] = 0;
		} 
		if(this._bUseH) {
			htContCss["clear"] = "both";
			htCss["float"] = "left";
		}
		this._htWElement["container"].css(htContCss);
		jindo.$A(this._htWElement["aPanel"]).forEach(function(v,i,a){
			nPos = (((i+1)%3)*100) + "%";
   		if(this._bUseCircular) {
   			if(this._hasOffsetBug()) {
   				htCss[nPosKey] = nPos;
   			} else {
   				htCss[this._oAnimation.p("Transform")] = this._getTranslate(nPos);
   			}
   		}
   		v.css(htCss);
		},this);
	},

	resize : function() {
		jindo.m.Flick.prototype.resize.call(this);
		var nSizeKey = this._bUseH ? "width" : "height",
			nViewSize = this._htWElement["view"][nSizeKey]();

		// 비순환일 경우는 패널의 크기도 변하기 때문에 resize될때 위치를 다시 맞춰준다.
		if(!this._bUseCircular) {
    	this._htWElement["container"].css(nSizeKey, (this._htWElement["aPanel"].length * nViewSize) + "px");
			jindo.$A(this._htWElement["aPanel"]).forEach(function(v,i,a){
	   		v.css(nSizeKey, nViewSize + "px");
			});
			this._updateFlickInfo();
			this._oAnimation.move(this._nX, this._nY);
    }
	},

	// 3개의 판을 wel중심으로 맞춘다. 
	// wel이 없을 경우, 현재 엘리먼트기준으로 맞춘다.
	_restorePanel : function(wel) {
		wel = wel || this.getElement();
		var n = this._getIndexByElement(wel),
			sPosition = this._hasOffsetBug() ? (this._bUseH ? "left" : "top") : this._oAnimation.p("Transform"),
			nPrev = (((n-1) < 0 )? 2 : (n-1))%3,
			nNext = ((((n+1)%3) > 2 )? 0 : (n+1))%3,
			nCenter = n%3;

		this._welElement = this._htWElement["aPanel"][nCenter];
		// container는 항상 고정!
		this._htWElement["container"].css(this._oAnimation.p("Transform"), this._getTranslate(0));
		this._welElement.css(sPosition, this._getPosValue("100%")).css('zIndex',10);
		this._htWElement["aPanel"][nPrev].css(sPosition, this._getPosValue("0%")).css('zIndex',1);
		this._htWElement["aPanel"][nNext].css(sPosition, this._getPosValue("200%")).css('zIndex',1);
	},

	_getPosValue : function(sV) {
		return this._hasOffsetBug() ? sV : this._getTranslate(sV);
	},

	/**
	 * 페이지 종료 시점
	 * @param  {[type]} option [description]
	 * @return {[type]}    [description]
	 */
  _aniEventImpl : function(option) {
  	jindo.m.Flick.prototype._aniEventImpl.call(this,option);
  	if(this._bUseCircular) {
  		this._restorePanel();
  	}
  },

	_moveImpl : function(we) {
		var bNext = jindo.m.Flick.prototype._moveImpl.call(this,we);
    this._oAnimation.move(this._nX, this._nY, 0, this._makeOption({ next : we.bNext}) );
	},

  /**
      jindo.m.SwapCommonScroll 에서 사용하는 모든 객체를 release 시킨다.
      @method destroy
  **/
	destroy: function() {
		jindo.m.Flick.prototype.destroy.apply(this);
	}
}).extend(jindo.m.Flick);