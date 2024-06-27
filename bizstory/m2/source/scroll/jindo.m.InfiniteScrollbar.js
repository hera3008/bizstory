/**
    @fileOverview 컨텐츠를 무한으로 스크롤하는 컴포넌트
    @author sculove
    @version #__VERSION__#
    @since 2013. 4. 27.
*/
/**
    컨텐츠를 무한으로 스크롤하는 컴포넌트

    @history 1.9.0 Release 최초 릴리즈  
**/
jindo.m.InfiniteScrollbar = jindo.$Class({
  /**
      초기화 함수

      @constructor
      @param {String|HTMLElement} el 플리킹 기준 Element (필수)
      @param {Object} [htOption] 초기화 옵션 객체
        @param {Boolean} [htOption.bActivateOnload=true] 컴포넌트 로드시 activate 여부
        @param {Boolean} [htOption.bUseHighlight=true] 하이라이트 사용 여부
        @param {Boolean} [htOption.bUseDiagonalTouch=true] 대각선스크롤 방향의 터치를 사용할지 여부
        @param {Boolean} [htOption.bUseMomentum=true] 가속을 통한 모멘텀 사용여부
        @param {Number} [htOption.nDeceleration=0.0006] 가속도의 감속계수. 이 값이 클수록, 가속도는 감소한다
        @param {Function} [oOptions.fEffect=jindo.m.Effect.linear] 애니메이션에 사용되는 jindo.m.Effect 의 함수들
        @param {Boolean} [htOption.bUseCss3d=jindo.m.useCss3d()] css3d(translate3d) 사용여부<br />
            모바일 단말기별로 다르게 설정된다. 상세내역은 <auidoc:see content="jindo.m">[jindo.m]</auidoc:see>을 참조하기 바란다.
        @param {Boolean} [htOption.bUseTimingFunction=jindo.m.useTimingFunction()] 애니메이션 동작방식을 css의 TimingFunction을 사용할지 여부<br />false일 경우 setTimeout을 이용하여 애니메이션 실행.<br />
        모바일 단말기별로 다르게 설정된다. 상세내역은 <auidoc:see content="jindo.m">[jindo.m]</auidoc:see>을 참조하기 바란다.
        @param {Number} [htOption.nZIndex=2000] 컴포넌트 base엘리먼트 z-Index 값


        @param {Number} [htOption.nHeight=0] Wrapper의 height값. 값이 0일 경우 wrapper의 height로 설정됨
        @param {Number} [htOption.nWidth=0] Wrapper의 width값. 값이 0일 경우 wrapper의 width로 설정됨    
        @param {Boolean} [htOption.bUseHScroll=false] 수평 스크롤 사용 여부. 스크롤영역의 width가 wrapper의 width보다 클 경우 적용 가능함.
        @param {Boolean} [htOption.bUseVScroll=true] 수직 스크롤 사용 여부. 스크롤영역의 height가 wrapper의 height보다 클 경우 적용 가능함.
        @param {Boolean} [htOption.bUseBounce=true] 가속 이동후, 바운스 처리되는 여부


        @param {String|$Element|HTMLElement} [htOption.vTemplate=template] 스크롤을 하기위한 기본 템플릿 엘리먼트
        @param {Number} [htOption.nRatio=1] 스크롤시 유지하는 엘리먼트 뷰의 비율

  **/    
  $init : function(el,htUserOption) {
    this.option({
      vTemplate : "template",
      nRatio : 1
    });

    if(this instanceof jindo.m.InfiniteScrollbar) {
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
    jindo.m.Scrollbar.prototype._initVar.call(this);
    this._aDatas = [];         // 데이터 저장
    this._aElements = [];      // 엘리먼트 저장
    this._nCursor = -1;        // 엘리먼트 변경할 대상 index

    this._isLoading = false;
    this._bUseRaf = jindo.m.useRequestAnimationFrame();
    this._htUpdateData = {
      direction : "",
      destDataIndex : 0
    };
    this._htBeforeUpdateData = {};
    this._nUpdaterId = -1;
  },

  _setWrapperElement : function(el) {
    jindo.m.Scrollbar.prototype._setWrapperElement.call(this,el);
    
    var elTemplateNode = jindo.$Element(this.option("vTemplate")).$value().cloneNode(true);
    elTemplateNode.id = null;

    this._htWElement["sizeCheck"] = jindo.$Element(elTemplateNode).css({
      "position" : "absolute",
      "width" : "100%",
      "visibility" : "hidden",
      "left" : "-100%"
    });

    // @todo 개선 필요 (scroll 마다 한개씩 생김)
    this._htWElement["view"].append(this._htWElement["sizeCheck"]);
  },

  _startImpl : function(we) {
    if(this._isLoading) {
      return false;
    }
    return jindo.m.Scrollbar.prototype._startImpl.call(this,we);
  },

  _moveImpl : function(we) {
    if(this._isLoading) {
      return;
    }
    jindo.m.Scrollbar.prototype._moveImpl.call(this,we);
    this._updateListStatus(we.nDistanceY < 0 ? "forward" : "backward");
  },

  _endImpl : function(we) {
    if(this._isLoading) {
      return;
    }
    jindo.m.Scrollbar.prototype._endImpl.call(this,we);
  },

  _onActivate : function() {
    jindo.m.Scrollbar.prototype._onActivate.apply(this);
    this._setElementStyle();
    if(this._bUseRaf) {
      this._htEvent["updater"] = jindo.$Fn(this._updaterAsync, this).bind();
      this.restartUpdater();
    }
  },

  /**
   * 스크롤시 엘리먼트의 위치를 변경하는 업데이터를 재시작한다.
   * @method restartUpdater
   */
  restartUpdater : function() {
    this.stopUpdater();
    this.startUpdater();
  },

  /**
   * 스크롤시 엘리먼트의 위치를 변경하는 업데이터를 시작한다.
   * @method startUpdater
   */
  startUpdater : function() {
    this._nUpdaterId = window.requestAnimationFrame(this._htEvent["updater"]);
  },

  /**
   * 스크롤시 엘리먼트의 위치를 변경하는 업데이터를 중지한다.
   * @method stopUpdater
   */
  stopUpdater : function() {
    // updater 종료
    window.cancelAnimationFrame(this._nUpdaterId);
    this._nUpdaterId = -1;
  },

  /**
   * 애니메이션 이동 시점
   * @param  {[type]} we [description]
   * @return {[type]}    [description]
   */
  _onProgressAniImpl : function(we) {
    var nBeforePos = this._bUseV ? this._nY : this._nX;
    jindo.m.Scrollbar.prototype._onProgressAniImpl.call(this,we);
    this._updateListStatus(nBeforePos > (this._bUseV ? this._nY : this._nX) ? "forward" : "backward");
  },

  /**
   * 애니메이션 종료 시점
   * @param  {[type]} we [description]
   * @return {[type]}    [description]
   */
  _onEndAniImpl : function(we) {
      jindo.m.Scrollbar.prototype._onEndAniImpl.call(this,we);
  },

  /**
      스크롤러를 위한 환경을 재갱신함

      @method refresh
  **/
  refresh : function() {
    if(!this.isActivating()) {
        return;
    }
    jindo.m.Scrollbar.prototype.refresh.apply(this);
  },

  // 엘리먼트 리스트를 초기화
  _setElementStyle : function() {
    var wel = this._htWElement["sizeCheck"],
      aElements = this._htWElement["container"].queryAll(wel.tag),
      htCss = {
        "position" : "absolute",
        "top" : 0,
        "left" : 0
      };
    htCss[this.getAnimation().p("TransitionProperty")] = "-" + this.getAnimation().sCssPrefix + "-transform";
    htCss[this.getAnimation().p("Transform")] = this.getAnimation().getTranslate(0,0);

    if(this._bUseV) {
      htCss["width"] = (this._htSize.viewWidth - (parseInt(wel.css("marginLeft"),10) + parseInt(wel.css("marginRight"),10))) + "px";
    } else {
      htCss["height"] = (this._htSize.viewHeight - (parseInt(wel.css("marginTop"),10) + parseInt(wel.css("marginBottom"),10))) + "px";
    }
    jindo.$A(aElements).forEach(function(v,i,a) {
      // 엘리먼트 저장
      this._aElements.push({
        wel : jindo.$Element(v).css(htCss),
        dataIndex : -1
      });
    },this);

    // 처음에는 데이터가 없으므로 최대 사이즈를 0으로 고정
    // @todo 개선이 필요함
    if(this._bUseV) {
      this._htSize.maxY = 0;
    } else {
      this._htSize.maxX = 0;
    }
    // console.log(this._aElements, this._nCursor, this._htSize.maxY);
  },

  _resizeImpl : function() {
    // jindo.m.Scrollbar.prototype._resizeImpl.apply(this);
    this.resize();
    this.resizeElement();
  },

  resizeElement : function() {
    //@to-do 개선 필요
    this._isLoading = true;
    this.stopUpdater();

    var htCss = {},
      wel = this._htWElement["sizeCheck"];
    if(this._bUseV) {
      htCss["width"] = (this._htSize.viewWidth - (parseInt(wel.css("marginLeft"),10) + parseInt(wel.css("marginRight"),10))) + "px";
    } else {
      htCss["height"] = (this._htSize.viewHeight - (parseInt(wel.css("marginTop"),10) + parseInt(wel.css("marginBottom"),10))) + "px";
    }
    wel.css(htCss);

    // content resize 된후에 발생
    this.fireEvent("resizeContent", {
      welView : this._htWElement["view"],
      nSize : this._bUseV ? htCss["width"] : htCss["height"]
    });

    // 데이터 갱신
    var nPos = 0,
      nSize = 0;
    jindo.$A(this._aDatas).forEach(function(v,i,a) {
      nSize = this._getDataSize(v.data);
      v.size = nSize;
      v.pos = nPos;
      nPos += nSize;
    },this);
    if(this._bUseV) {
      this._htSize.maxY = -(nPos - this._htSize.viewHeight);
    } else {
      this._htSize.maxX = -(nPos - this._htSize.viewWidth);
    }

    // 엘리먼트 크기, 위치 갱신
    jindo.$A(this._aElements).forEach(function(v,i,a) {
      v.wel.hide();
      v.wel.css(htCss);
      if(v.dataIndex != -1) {
        this._setElementPos(v.wel, this._aDatas[v.dataIndex].pos);
      }
      v.wel.show();
    },this);
    this._resetListStatus();
    this._isLoading = false;
    this.restartUpdater();
    this.restore(0);
  },

  /**
   * 화면에 보이는 엘리먼트 반환
   * @return {Array} 화면에 보이는 $Element 엘리먼트 배열
   */
  getVisibleElement : function() {
    var nRangeStart ,nRangeEnd, htData, aResult = [];

    if(this._bUseV) {
      nRangeStart = -this._nY - this._htSize.viewHeight;
      nRangeEnd = -this._nY + (this._htSize.viewHeight/4*3);
    } else {
      nRangeStart = -this._nX - this._htSize.viewWidth;
      nRangeEnd = -this._nX + (this._htSize.viewWidth/4*3);
    }

    jindo.$A(this._aElements).forEach(function(v,i,a) {
      if(v.dataIndex != -1) {
        htData = this._aDatas[v.dataIndex];
        if(htData.pos > nRangeStart && htData.pos < nRangeEnd) {
          aResult.push(v.wel);
        }
      }
    },this);
    return aResult;
  },

  _increaseCursor : function(bUpdate) {
    var nCur = this._nCursor;
    nCur++;
    if(nCur >= this._aElements.length) {
      nCur = 0;
    }
    if(bUpdate) {
      this._nCursor = nCur;
    }
    return nCur;
  },

  _decreaseCursor : function(bUpdate) {
    var nCur = this._nCursor;
    nCur--;
    if(nCur < 0) {
      nCur = this._aElements.length-1;
    }
    if(bUpdate) {
      this._nCursor = nCur;
    }
    return nCur;
  },

  /**
      위치 이동시 엘리먼트를 변경한다.
  **/
  _updateListStatus : function(sDirection, isSync) {
    if(this._nCursor === -1) { 
      return;
    }

    isSync = !this._bUseRaf ? true : isSync;
    var nIndex = this._getMarginIndex(sDirection);
    var nCurrentDataIndex;

    if(sDirection === "forward") {
      nCurrentDataIndex = this._getLastDataIndex();
      if(nIndex != nCurrentDataIndex) {
        if(isSync) {
          // console.warn("change -- forward (sync) [",nCurrentDataIndex, "->", nIndex,"]");
          this._forwardChangeData(nIndex);  
        } else {
          // console.warn("change -- forward (async) [",nCurrentDataIndex, "->", nIndex,"]");
          // this._aUpdateQueue.push({
          //   direction : "forward",
          //   destDataIndex : nIndex
          // });
          this._htUpdateData = {
            direction : "forward",
            destDataIndex : nIndex
          };
        }
      }
    } else {
      nCurrentDataIndex = this._getFirstDataIndex();
      if(nIndex != nCurrentDataIndex) {
        if(isSync) {
          // console.warn("change -- backward (sync) [",nCurrentDataIndex, "->", nIndex,"]");
          this._backwardChangeData(nIndex);
        } else {
          // console.warn("change -- backward (async) [",nCurrentDataIndex, "->", nIndex,"]");
          // this._aUpdateQueue.push({
          //   direction : "backward",
          //   destDataIndex : nIndex
          // });
          this._htUpdateData = {
            direction : "backward",
            destDataIndex : nIndex
          };
        }
      }
    }
  },

  // webkitRequestAnimationFrame
  _updaterAsync : function() {
    // console.log("heart bit...!");
    this._updaterProcess();
    this.startUpdater();
  },

  _updaterProcess : function() {
    if(!this._isLoading && this._aDatas.length > 0) {
      if(this._htUpdateData.direction != this._htBeforeUpdateData.direction || this._htUpdateData.destDataIndex != this._htBeforeUpdateData.destDataIndex ) {
        // console.warn("change -- " + this._htUpdateData.direction + " (async) [ ->", this._htUpdateData.destDataIndex,"]");
        if(this._htUpdateData.direction == "forward" ) {
          this._forwardChangeData(this._htUpdateData.destDataIndex);  
        } else {
          this._backwardChangeData(this._htUpdateData.destDataIndex); 
        }
        this._htBeforeUpdateData.direction = this._htUpdateData.direction;
        this._htBeforeUpdateData.destDataIndex = this._htUpdateData.destDataIndex;
      }
    }
  },

  _forwardChangeData : function(nDestDataIndex) {
    var nDataIndex = this._aElements[this._nCursor].dataIndex + 1,
      htTarget,
      htData;

    // console.debug("forward changeData : " , (nDataIndex-1), "=>" , nDestDataIndex);
    while(nDataIndex <= nDestDataIndex ) {
      htTarget = this._aElements[this._increaseCursor()];
      htData = this._aDatas[nDataIndex];

      htTarget.wel.$value().style.display = "none";
      this._setElementPos(htTarget.wel, htData.pos);
      htTarget.dataIndex = nDataIndex;
      this._fireUpdateContentEvent(htTarget.wel, htData.data, true);
      htTarget.wel.$value().style.display = "block";

      this._increaseCursor(true);
      nDataIndex++;
    }
  },

  _backwardChangeData : function(nDestDataIndex) {
    var nDataIndex = this._aElements[this._increaseCursor()].dataIndex -1,
      htTarget,
      htData;

    // console.debug("backward changeData : " , (nDataIndex+1), "=>" , nDestDataIndex);
    while(nDestDataIndex <= nDataIndex) {
      htTarget = this._aElements[this._nCursor];
      htData = this._aDatas[nDataIndex];
      htTarget.wel.$value().style.display = "none";
      this._setElementPos(htTarget.wel, htData.pos);
      htTarget.dataIndex = nDataIndex;
      this._fireUpdateContentEvent(htTarget.wel, htData.data, true);
      htTarget.wel.$value().style.display = "block";
      this._decreaseCursor(true);
      nDataIndex--;
    }
  },

  /**
   * updateContent 이벤트를 발생한다.
   * 이 이벤트는 사용자가 데이터를 갱신하거나, 화면 사이즈를 유지할때 필요하다.
   * @param  {[type]} wel      [description]
   * @param  {[type]} htData   [description]
   * @param  {[type]} bCorrupt [description]
   * @return {[type]}          [description]
   */
  _fireUpdateContentEvent : function(wel, htData, bCorrupt) {
    this.fireEvent("updateContent", {
      wel : wel,
      htData : htData,
      bCorrupt : bCorrupt
    });
  },

  // 텔플릿의 사이즈를 구함.
  _getDataSize : function(htData) {
    var nSize = 0,
      wel = this._htWElement["sizeCheck"];
    this._fireUpdateContentEvent(wel, htData, false);

    if(this._bUseV) {
      nSize = wel.height() + ( parseInt(wel.css("marginTop"),10) + parseInt(wel.css("marginBottom"),10) );
    } else {
      nSize = wel.width() + ( parseInt(wel.css("marginLeft"),10) + parseInt(wel.css("marginRight"),10) );
    }
    return nSize;
  },

  /**
   * 데이터를 뒤에서 추가한다
   * @param  {Array} aData 템플릿에 적용할 HashTable형태의 데이터 배열
   * @method append
   */
  append : function(aData) {
    if(this._isLoading) {
      return;
    }
    this.stopUpdater();
    this._isLoading = true;
    var wel = this._htWElement["sizeCheck"],
      nSize = 0,
      nPos = 0;

    if(this._aDatas.length > 0) {
      var htBefore = this._aDatas[this._aDatas.length-1];
      nPos = htBefore.pos + htBefore.size;
    }
    // console.log("init pos : " + nPos, this._htSize.maxY, this._htSize.viewHeight, (nPos+this._htSize.maxY));

    // // 첫번째 엘리먼트 숨기기
    jindo.$A(aData).forEach(function(v,i,a) {
      // 외부 함수
      nSize = this._getDataSize(v);

      this._aDatas.push({
        size : nSize,
        data : v,
        pos : nPos
      });
      nPos += nSize;
    },this);

    if(this._bUseV) {
      this._htSize.maxY = -(nPos - this._htSize.viewHeight);
    } else {
      this._htSize.maxX = -(nPos - this._htSize.viewHeight);
    }
    this._resetListStatus();
    this._isLoading = false;
    this.restartUpdater();
  },

  _getFirstDataIndex : function() {
    return this._aElements[this._increaseCursor()].dataIndex;
  },

  _getLastDataIndex : function() {
    return this._aElements[this._nCursor].dataIndex;
  },

  _getMarginIndex : function(sDirection) {
    // console.profile("-getScrollMargin");
    var htData,nViewPos,nDataIdx,nLastIdx = this._aDatas.length-1;

    if(sDirection === "forward") {
      nDataIdx = this._getLastDataIndex();
      htData = this._aDatas[nDataIdx];
      nViewPos = -(this._nY - this._htSize.viewHeight - (this._htSize.viewHeight * this.option("nRatio") ));
      while( (htData.pos + htData.size) < nViewPos) {
        if(nDataIdx >= nLastIdx) {
          break;
        }
        htData = this._aDatas[++nDataIdx];
      }
    } else {
      nDataIdx = this._getFirstDataIndex();
      htData = this._aDatas[nDataIdx];
      nViewPos = -this._nY - (this._htSize.viewHeight * this.option("nRatio") );
      nViewPos = nViewPos < 0 ? 0 : nViewPos;
      while( htData.pos > nViewPos) {
        if(nDataIdx <= 0) {
          break;
        }
        htData = this._aDatas[--nDataIdx];
      }
    }
    // console.log(nDataIdx);
    return nDataIdx;
  },

  /**
   * 데이터 추가시 데이터의 범위를 재정의한다.
   */
  _resetListStatus : function() {
    this._isLoading = true;
    this._aUpdateQueue = [];

    if(this._nCursor == -1) { // 신규일 경우, 새로 갱신. (동기식)
      // console.error("append new Data (sync)");
      var htData = null,
        nLen = this._aDatas.length;
      jindo.$A(this._aElements).forEach(function(v,i,a) {
        if(i >= nLen) {
          jindo.$A.Break();
        }
        htData = this._aDatas[i];
        // 위치 이동
        this._setElementPos(v.wel, htData.pos);
        v.dataIndex = i;
        // 데이터 내용 변경
        this._fireUpdateContentEvent(v.wel, htData.data, true);
      },this);
      this._nCursor = this._aElements.length-1;
    } else {  // (비동기식)
      this._updateListStatus("forward");
    }
  },

  _setElementPos : function(wel, nPos) {
    var htCss = {};
    htCss[this.getAnimation().p("TransitionProperty")] = "-" + this.getAnimation().sCssPrefix + "-transform";
    htCss[this.getAnimation().p("Transform")] = this.getAnimation().getTranslate(0,nPos + "px");
    wel.css(htCss);
  },

  /**
      jindo.m.Scrollbar 에서 사용하는 모든 객체를 release 시킨다.
      @method destroy
  **/
  destroy: function() {
    jindo.m.Scrollbar.prototype.destroy.apply(this);
  }
}).extend(jindo.m.Scrollbar);