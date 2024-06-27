/**
    @fileOverview 플리킹 판을 처리하는 컴포넌트
    @author sculove
    @version #__VERSION__#
    @since 2013. 2. 27.
*/
/**
    페이지의 고정영역 내부를 터치하여 스크롤링 할 수 있는 컴포넌트

    @history 1.9.0 Release 최초 릴리즈  
**/
jindo.m.Scrollbar = jindo.$Class({
  /*
    @constructor
    @param {String|HTMLElement} el CoreScroll할 Element (필수)
    @param {Object} [htOption] 초기화 옵션 객체
        @param {Number} [htOption.nHeight=0] Wrapper의 height값. 값이 0일 경우 wrapper의 height로 설정됨
        @param {Number} [htOption.nWidth=0] Wrapper의 width값. 값이 0일 경우 wrapper의 width로 설정됨    
        @param {Boolean} [htOption.bUseHScroll=false] 수평 스크롤 사용 여부. 스크롤영역의 width가 wrapper의 width보다 클 경우 적용 가능함.
        @param {Boolean} [htOption.bUseVScroll=true] 수직 스크롤 사용 여부. 스크롤영역의 height가 wrapper의 height보다 클 경우 적용 가능함.
        @param {Boolean} [htOption.bUseBounce=true] 가속 이동후, 바운스 처리되는 여부
  */
  $init : function(el,htUserOption) {
    this.option({
      bUseBounce : true,
      bUseHScroll : false,
      bUseVScroll : true,
      nWidth : 0,
      nHeight : 0
    });

    if(this instanceof jindo.m.Scrollbar) {
      this.option(htUserOption || {});
      this._initVar();
      this._setWrapperElement(el);
      if(this.option("bActivateOnload")) {
        this.activate();
      }
    }
  },

  /**
      jindo.m.Animation 에서 사용하는 모든 인스턴스 변수를 초기화한다.
  **/
  _initVar: function() {
    jindo.m.SwapCommon.prototype._initVar.apply(this);
    this._bUseH = this.option("bUseHScroll");
    this._bUseV = this.option("bUseVScroll");
  },

  _setWrapperElement : function(el) {
    jindo.m.SwapCommon.prototype._setWrapperElement.call(this,el);
  },

  _startImpl : function(we) {
    jindo.m.SwapCommon.prototype._startImpl.call(this, we);
    
    if(this.isPlaying() && !this._isOutOfRange()) {
      this._stopScroll();
    }
    return true;
  },

  _moveImpl : function(we) {
    var nX=0, nY=0;
    if(this.option("bUseBounce")) {
      if(this._bUseH) {
        nX = this._nX + (this._nX >=0 || this._nX <= this._htSize.maxX ? we.nVectorX/2 : we.nVectorX);
      }
      if(this._bUseV) {
        nY = this._nY + (this._nY >=0 || this._nY <= this._htSize.maxY ? we.nVectorY/2 : we.nVectorY);
      }
      /** 갤럭시S3에서는 상단영역을 벗어나면 touchEnd가 발생하지 않음
       * 상단영역 30이하로 잡힐 경우 복원
       */
      // var self=this;
      // this._htTimer["touch"] = setTimeout(function() {
      //     self._forceRestore(we);
      // },500);
    } else {
      if(this._bUseH) {
        nX = this._getX(this._nX + we.nVectorX);
      }
      if(this._bUseV) {
        nY = this._getY(this._nY + we.nVectorY);
      }
    }
    this._nX = nX;
    this._nY = nY;
    this._oAnimation.move(this._nX, this._nY, 0 ); 
  },

  _endImpl : function(we) {
    var isBounce = this.option("bUseBounce");
    var ht = this._getMomentumData(we, 0, isBounce);
    // if (this._fireEventbeforeScroll(ht)) {
        if(isBounce) {
          if( this._nX !== ht.nextX || this._nY !== ht.nextY ) {
            this._scrollTo(ht.nextX, ht.nextY, ht.duration);
          } else {
            this._restore(300);
          }
        } else {
          this._scrollTo(this._getX(ht.nextX), this._getY(ht.nextY), ht.duration);
        }
        // this._fireEventScroll(ht);
    // }
  },

  // rotate될 때
  _resizeImpl : function(we) {
    this.resize();
    this.refresh();
  },

  /**
      애니메이션을 초기화한다.
  **/
  _stop : function() {
    this._oAnimation.stop();
  },

  /**
      이동중 멈추는 기능. 이때 멈춘 위치의 포지션을 지정
  **/
  _stopScroll : function() {
    var htTranslateOffset = jindo.m.getTranslateOffset(this._htWElement["container"]),
        htStyleOffset ={left : 0, top : 0}, nX, nY;
    if(this._hasOffsetBug()) {
        htStyleOffset = jindo.m.getStyleOffset(this._htWElement["container"]);
    }
    nX = htTranslateOffset.left + htStyleOffset.left;
    nY = htTranslateOffset.top + htStyleOffset.top;
    // console.warn("Let's stop for stopScroll ");
    this._stop();
    this._isStop = true;
    this._oAnimation.move(this._getX(nX), this._getY(nY), 0);
    // this._fixOffsetBugImpl();
  },  

  _isOutOfRange : function() {
    if (this._nY > 0 || this._nY < this._htSize.maxY ||  this._nX > 0 || this._nX < this._htSize.maxX) {
      return true;
    } else {
      false;
    }
  },

  _scrollTo: function (nX, nY , nDuration) {
    // console.warn("Let's stop for scrollTo ");
    this._stop();
    nX = this._bUseH ? nX : 0;
    nY = this._bUseV ? nY : 0;
    this._oAnimation.move(nX, nY, nDuration);
    // this._oAnimation.move(nX, nY, nDuration).play();
  },

  /**
    left, top 기준으로 스크롤을 이동한다.
    스크롤을 해당 위치(nX, nY)로 이동한다.<br/>
    @method scrollTo
    @param {Number} nX 0~양수 만 입력 가능하다. (-가 입력된 경우는 절대값으로 계산된다)
    @param {Number} nY 0~양수 만 입력 가능하다. (-가 입력된 경우는 절대값으로 계산된다)
    @param {Number} nDuration 기본값은 0ms이다.
    @remark
        최상위의 위치는 0,0 이다. -값이 입력될 경우는 '절대값'으로 판단한다.<br/>
        스크롤의 내용을 아래로 내리거나, 오른쪽으로 이동하려면 + 값을 주어야 한다.<br/>
    @example
        oScroll.scrollTo(0,100); //목록이 아래로 100px 내려간다.
        oScroll.scrollTo(0,-100); //목록이 아래로 100px 내려간다. (절대값이 100이므로)
  **/
  scrollTo : function(nX, nY, nDuration) {
      nDuration = nDuration || 0;
      nX = -Math.abs(nX);
      nY = -Math.abs(nY);
      this._scrollTo( this._getX(nX), this._getY(nY), nDuration);
  },

  _onActivate : function() {
    jindo.m.SwapCommon.prototype._onActivate.apply(this);
    // this.set(new jindo.m.Slide(this._getAnimationOption()),
    this.set(new jindo.m.ScrollAnimation(this._getAnimationOption()),
      this._htWElement["container"]
    );
  },

  set : function(oAni) {
    jindo.m.SwapCommon.prototype.set.apply(this, Array.prototype.slice.apply(arguments));
    this.resize();
    this.refresh();
    return this._oAnimation;
  },

  resize : function() {
    var nHeight = this.option("nHeight"),
      nWidth = this.option("nWidth");
    //View 영역 크기 지정
    nHeight = /%$/.test(nHeight) ? nHeight :  (nHeight== 0 ? this._htWElement["view"].css("height") : parseInt(nHeight,10) + "px");
    nWidth = /%$/.test(nWidth) ? nWidth :  (nWidth== 0 ? this._htWElement["view"].css("width") : parseInt(nWidth,10) + "px");
    this._htWElement["view"].css({
      height : nHeight,
      width : nWidth
    });
    // console.log("scrollbar", this._htSize.viewWidth);
    jindo.m.SwapCommon.prototype.resize.apply(this);
  },

  /**
      스크롤러를 위한 환경을 재갱신함

      @method refresh
  **/
  refresh : function() {
    if(!this.isActivating()) {
        return;
    }
    jindo.m.SwapCommon.prototype.refresh.apply(this);
    
    // if(!this._refreshPullPlugin()) {
    //     this.nScrollW = this._htWElement["scroller"].width();
    //     this.nScrollH = this._htWElement["scroller"].height() - this.option("nOffsetBottom");
    //     this.nMinScrollTop = -this.option("nOffsetTop");
    //     this.nMaxScrollTop = this.nWrapperH - this.nScrollH;
    // }
    this._htSize.maxX = this._htSize.viewWidth - this._htSize.contWidth;
    this._htSize.maxY = this._htSize.viewHeight - this._htSize.contHeight;

    // 스크롤 여부 판별
    this._bUseH = this.option("bUseHScroll") && (this._htSize.viewWidth <= this._htSize.contWidth);
    this._bUseV = this.option("bUseVScroll") && (this._htSize.viewHeight <= this._htSize.contHeight);

    // 스크롤 여부에 따른 스타일 지정
    // if(this.bUseHScroll && !this.bUseVScroll) { // 수평인 경우
    //     welContainer.$value().style["height"] = "100%";
    // }
    // if(!this.bUseHScroll && this.bUseVScroll) { // 수직인 경우
    //     welContainer.$value().style["width"] = "100%";
    // }

    // 스크롤바 refresh (없을시 자동 생성)
    // if(this.option("bUseScrollbar")) {
    //     this._refreshScroll("V");
    //     this._refreshScroll("H");
    // }

    if(!this._bUseH && !this._bUseV) { // 스크롤이 발생하지 않은 경우, 안드로이드인경우 포지션을 못잡는 문제
        // this._fixOffsetBugImpl();
    }
  },

  _onProgressAniImpl : function(we) {
    jindo.m.SwapCommon.prototype._onProgressAniImpl.call(this, we);
    // console.error("animation progress");
  },


  /**
   * 애니메이션 종료 시점
   * @param  {[type]} we [description]
   * @return {[type]}    [description]
   */
  _onEndAniImpl : function(we) {
    this._restore(300);
  },

  _restore : function(nDuration) {
    if(!this._bUseH && !this._bUseV) {
        return;
    }
    if(this._isOutOfRange()) {
      this._oAnimation.move(this._getX(this._nX), this._getY(this._nY), nDuration);
      // this._oAnimation.move(this._getX(this._nX), this._getY(this._nY), nDuration).play();
    } else {
      /* 최종 종료 시점 */
      this.fireEvent("scroll");
      // this._fixOffsetBugImpl();
      return;
    }

    // if(!this._bUseH && !this._bUseV) {
    //     return;
    // }
    // var nNewX = this._getX(this._nX),
    //   nNewY = this._getY(this._nY);

    // if (nNewX === this._nX && nNewY === this._nY) {
    //   /* 최종 종료 시점 */
    //   this.fireEvent("scroll");
    //   // this._fixOffsetBugImpl();
    //   return;
    // } else {
    //   this._oAnimation.move(nNewX, nNewY, nDuration).play();
    // }
  },

  /**
      jindo.m.SwapCommonScroll 에서 사용하는 모든 객체를 release 시킨다.
      @method destroy
  **/
  destroy: function() {
    jindo.m.SwapCommon.prototype.destroy.apply(this);
  }
}).extend(jindo.m.SwapCommon);