/**
    @fileOverview 여러개의 콘텐츠 영역을 사용자 터치의 움직임을 통해 좌/우, 상/하로 커버되어 보여주는 컴포넌트

    @author sculove
    @version #__VERSION__#
    @since 2013. 4. 27.
*/
/**
    여러개의 콘텐츠 영역을 사용자 터치의 움직임을 통해 좌/우, 상/하로 커버되어 보여주는 컴포넌트

    @class jindo.m.CoverFlicking
    @extends jindo.m.Flick
    @uses jindo.m.Cover
    @keyword flicking, 플리킹
    @group Component
	
		@history 1.8.0 Release 최초 릴리즈  
**/
jindo.m.CoverFlicking = jindo.$Class({
	/* @lends jindo.m.CoverFlicking.prototype */
	/**
    초기화 함수

    @constructor
    @param {String|HTMLElement} el 플리킹 기준 Element (필수)
    @param {Object} [htOption] 초기화 옵션 객체

  **/
	$init : function(el,htUserOption) {
		this.option(htUserOption || {});
		this._initVar();
		this._setWrapperElement(el);
		if(this.option("bActivateOnload")) {
			this.activate();
		}
	},

	_onActivate : function() {
		jindo.m.Flick.prototype._onActivate.apply(this);
		var ht = this._getAnimationOption();
		if(this.option("nDefaultScale")) {
      ht["nDefaultScale"] = this.option("nDefaultScale");
    }
      ht["bUseCircular"] = this.option("bUseCircular");
		var self = this;
		this.set(new jindo.m.Cover(ht)
			.attach({
				"set" : function(we) {
					self._setStyle(we.css);
				}
			}),
			this._htWElement["aPanel"]
		);
	},

	_setStyle : function() {
		this._htWElement["container"].css(this._bUseH ? "width" : "height", "100%");
		this._changeTarget(true);
	},

	refresh : function(n) {
		jindo.m.Flick.prototype.refresh.call(this,n);
		this._changeTarget(true);	// 타겟을 변경
	},

	_changeTarget : function(bNext) {
		// if(this._oAnimation.isPlaying()) {
		// 	return;
		// }
		var welTarget = this.getElement(),
			welZoom = bNext ? this.getNextElement() : this.getPrevElement();
		welZoom = welTarget.isEqual(welZoom) ? null : welZoom;
		this._oAnimation.change(welTarget, welZoom, bNext);
	},

	moveTo : function(nIndex, nDuration) {
		var nMax = this.getTotalContents()-1,
			nCurrentIndex = this.getContentIndex(),
			bNext = nCurrentIndex < nIndex;
		if(this._bUseCircular) {
			if(nCurrentIndex === nMax && nIndex === 0) {
				bNext = true;
			} else if(nCurrentIndex === 0 && nIndex === nMax) {
				bNext = false;
			}
		}
		this._changeTarget(bNext);
		jindo.m.Flick.prototype.moveTo.call(this, nIndex, nDuration);
	},

	/**
	 * 페이지 종료 시점
	 * @param  {[type]} we [description]
	 * @return {[type]}    [description]
	 */
  _aniEventImpl : function(we) {
  	jindo.m.Flick.prototype._aniEventImpl.call(this,we);
  	this._changeTarget(we.next);
  },

	_moveImpl : function(we) {
		jindo.m.Flick.prototype._moveImpl.call(this,we);
		
		var nPos = this._bUseH ? this._nX : this._nY;
		// console.log(nPos, this._nRange , nPos % this._nRange);
		var bNext = true;
        if (this._nPosToIndex != this._posToIndex(nPos) ){
            bNext = false;
        }
        we.bNext = bNext;
		
		this._changeTarget(we.bNext);
    this._oAnimation.move(this._nX, this._nY, 0, this._makeOption({ next : we.bNext}) );
	},

  /**
      jindo.m.CoverFlicking 에서 사용하는 모든 객체를 release 시킨다.
      @method destroy
  **/
	destroy: function() {
		jindo.m.Flick.prototype.destroy.apply(this);
	}
}).extend(jindo.m.Flick);