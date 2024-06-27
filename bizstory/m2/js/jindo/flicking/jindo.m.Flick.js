/**
    @fileOverview 플리킹 판을 처리하는 컴포넌트
    @author sculove
    @version #__VERSION__#
    @since 2013. 2. 27.
*/
/**
    여러개의 콘텐츠 영역을 사용자 터치의 움직임을 통해 좌/우, 상/하로 보여주는 상위 컴포넌트

    @class jindo.m.Flick
    @extends jindo.m.SwapCommon
    @keyword flicking, 플리킹
    @group Component
    @invisible

		@history 1.9.0 jindo.m.Morph 기반으로 변경   
		@history 1.8.0 Release 최초 릴리즈   	  
**/
jindo.m.Flick = jindo.$Class({
	/* @lends jindo.m.Flick.prototype */
	/**
    초기화 함수

    @constructor
    @param {String|HTMLElement} el 플리킹 기준 Element (필수)
    @param {Object} [htOption] 초기화 옵션 객체
      @param {Boolean} [htOption.bHorizontal=true] 가로여부
      @param {String} [htOption.sClassPrefix='flick-'] Class의 prefix명
      @param {String} [htOption.sContentClass='ct'] 컨텐츠 영역의 class suffix명
      @param {Boolean} [htOption.bUseCircular=false] 순환플리킹여부를 지정한다. true로 설정할 경우 3판이 연속으로 플리킹된다.
			@param {Number} [htOption.nTotalContents=3] 전체 플리킹할 콘텐츠의 개수.<br/>순환플리킹일 경우, 패널의 개수보다 많이 지정하여 flicking 사용자 이벤트를 이용하여 동적으로 컨텐츠를 구성할 수 있다.
      @param {Number} [htOption.nFlickThreshold=40] 콘텐츠가 바뀌기 위한 최소한의 터치 드래그한 거리 (pixel)
      @param {Number} [htOption.nDuration=100] 슬라이드 애니메이션 지속 시간
      @param {Number} [htOption.nBounceDuration=100] nFlickThreshold 이하로 움직여서 다시 제자리로 돌아갈때 애니메이션 시간
      @param {Function} [htOption.fpPanelEffect=jindo.m.Effect.easeIn] 패널 간의 이동 애니메이션에 사용되는 jindo.m.Effect 의 함수들
      @param {Number} [htOption.nDefaultIndex=0] 초기 로드시의 화면에 보이는 콘텐츠의 인덱스
      @param {Boolean} [htOption.bUseMomentum=false] 가속을 통한 모멘텀 사용여부
      @param {Number} [htOption.nDeceleration=0.001] 가속도의 감속계수. 이 값이 클수록, 가속도는 감소한다
  **/
	$init : function(el,htUserOption) {
		this.option({
			bHorizontal : true,
			sClassPrefix : "flick-",
			sContentClass : "ct",
			bUseCircular : false,
			nTotalContents : 3,
			nFlickThreshold : 40,
			nDuration : 100,
			nBounceDuration : 100,
			fpPanelEffect : jindo.m.Effect.easeIn,
			nDefaultIndex : 0,
			nDeceleration : 0.001,	// panel에서는 스크롤보다 가속도 감도가 약함
			bUseMomentum : false 	// panel에서는 가속도 사용하지 않는것이 default
		});
	},

  /**
      jindo.m.Flick 에서 사용하는 모든 인스턴스 변수를 초기화한다.
  **/
	_initVar: function() {
		jindo.m.SwapCommon.prototype._initVar.apply(this);
		this._bUseH = this.option("bHorizontal");
    this._bUseV = !this._bUseH;
    this._bUseCircular = this.option("bUseCircular");
    this._nContentIndex = 0;	// 현재 인덱스
    this._nTotalContents = 0;	// 전체 컨텐츠 크기
		this._welElement = null;	// 현재 엘리먼트
		this._aPos = [];					// 판의 위치
		this._nRange = null;			// 패널의 크기
	},

	_setWrapperElement : function(el) {
		jindo.m.SwapCommon.prototype._setWrapperElement.call(this,el);

		// container, panel의 높이나 폭을 100%로 설정
		var sSizeKey = this.option("bHorizontal") ? "height" : "width";
		this._htWElement["container"].css(sSizeKey, "100%");
		this._htWElement["aPanel"] = this._htWElement["container"].queryAll("." + this.option("sClassPrefix") + this.option('sContentClass'));
		this._htWElement["aPanel"] = jindo.$A(this._htWElement["aPanel"]).forEach(function(v,i, a){
		   a[i] = jindo.$Element(v).css(sSizeKey, "100%");
		}).$value();
	},

	// 인덱스 범위 확인
  _checkIndex : function(n){
    var bRet = true,
    	nMax = this.getTotalContents()-1;
      if(isNaN((n*1)) || n < 0){
        bRet = false;
      }
      if( n > nMax){
        bRet = false;
      }
      return bRet;
  },

  /**
   * 패널 데이터를 갱신한다.
   */
	_refreshPanelInfo : function(){
	  var nTotal = 0;
	  
	  this._aPos = [0];
	  for(var i=0; i<this._nTotalContents; i++) {
	  	nTotal -= this._nRange;
	  	this._aPos.push(nTotal);
	  }
	},

	_onActivate : function() {
		jindo.m.SwapCommon.prototype._onActivate.apply(this);
	},

	_onDeactivate : function() {
		jindo.m.SwapCommon.prototype._onDeactivate.apply(this);
	},

	/**
	 * Animation를 등록한다.
	 *
	 * @method set
	 */
	set : function() {
		jindo.m.SwapCommon.prototype.set.apply(this, Array.prototype.slice.apply(arguments));
		this._nTotalContents = Math.max(this._htWElement["aPanel"].length, this.option("nTotalContents"));
		// index 초기화
  	var n = this.option("nDefaultIndex");
		if(!this._checkIndex(n)) { 
			n = 0;
		}
		this.resize();
		this.refresh(n);
		return this._oAnimation;
	},
	
	/**
	 * 패널 정보를 갱신한다.
	 * @param  {Number} n 갱신할 패널을 지정한다.
	 *
	 * @method refresh
	 */
	refresh : function(n) {
  	jindo.m.SwapCommon.prototype.refresh.call(this);
  	n = typeof n === "undefined" ? this.getContentIndex() : n;
  	this.moveTo(n,0);
  },

	
	/**
	 * 패널 사이즈 정보를 갱신한다.
	 *
	 * @method resize
	 */
  resize : function() {
  	jindo.m.SwapCommon.prototype.resize.call(this);
  	this._nRange = this._bUseH ? this._htSize.viewWidth : this._htSize.viewHeight;

  	this._refreshPanelInfo();
  	
  	if(this._bUseH) {
  		this._htSize.maxX = (this._nTotalContents-1) * -this._htSize.viewWidth;
			this._nX = this._aPos[this.getContentIndex()];
  	} else {
			this._htSize.maxY = (this._nTotalContents-1) * -this._htSize.viewHeight;
			this._nY = this._aPos[this.getContentIndex()];
  	}
  },


  /**
   * 현재 엘리먼트를 반환한다.
   * @return {$Element} 현재 패널의 엘리먼트를 반환한다.
   *
   * @method getElement
   */
	getElement : function() {
		if(this._welElement) {
    	return this._welElement;
		} else {
			var n = this.getContentIndex();
			if(this._bUseCircular) {
				n %= 3;
			} 
			return this._htWElement["aPanel"][n];
		}
	},

  /**
   * 다음 엘리먼트를 반환한다.
   * @return {$Element} 다음 패널의 엘리먼트를 반환한다.
   *
   * @method getNextElement
   */
	getNextElement : function() {
		var n;
    if(this._bUseCircular){
    	n = this._getIndexByElement(this.getElement());
      n = ((((n+1)%3) > 2 )? 0 : (n+1))%3;
		} else {
			n = this.getNextIndex();
		}
		return this._htWElement["aPanel"][n];
	},

  /**
   * 이전 엘리먼트를 반환한다.
   * @return {$Element} 이전 패널의 엘리먼트를 반환한다.
   *
   * @method getPrevElement
   */
	getPrevElement : function() {
		var n;
    if(this._bUseCircular){
    	n = this._getIndexByElement(this.getElement());
      n = ((n-1) < 0 )? 2 : (n-1);
    } else {
    	n = this.getPrevIndex();
    }
    return this._htWElement["aPanel"][n];
	},

	// 엘리먼트에 해당하는 index를 반환한다.
	_getIndexByElement : function(wel){
		var bValue = -1;
		jindo.$A(this._htWElement["aPanel"]).forEach(function(v,i,a) {
			if(v.isEqual(wel)) {
				bValue = i;
			}
		});
		return bValue;
	},

	/**
	 * 현재 컨텐츠의 인덱스를 반환한다.
	 * @return {Number} 현재 컨텐츠 인덱스 
	 *
	 * @method getContentIndex
	 */
	getContentIndex : function() {
		return this._nContentIndex;
	},

	/**
	 * 다음 컨텐츠의 인덱스를 반환한다.
	 * @return {Number} 다음 컨텐츠 인덱스 
	 *
	 * @method getNextIndex
	 */
	getNextIndex : function() {
		var n = this.getContentIndex() + 1,
    	nMax = this.getTotalContents() - 1;
    if(this._bUseCircular && (n > nMax) ) {
			n = 0;
    }
    return Math.min(nMax, n);
	},

	/**
	 * 이전 컨텐츠의 인덱스를 반환한다.
	 * @return {Number} 다음 컨텐츠 인덱스 
	 *
	 * @method getPrevIndex
	 */
	getPrevIndex : function() {
		var n = this.getContentIndex() - 1;
    if(this._bUseCircular && n < 0 ) {
			n = this.getTotalContents() - 1;
    }
    return Math.max(0, n);
	},

	/**
	 * 전체 컨텐츠의 개수를 반환한다.
	 * @return {Number} 전체 컨텐츠 개수
	 *
	 * @method getTotalContents
	 */
	getTotalContents : function() {
		return this._nTotalContents;
	},

	/**
	 * 전체 패널의 개수를 반환한다.
	 * @return {Number} 전체 패널 개수
	 *
	 * @method getTotalPanels
	 */
	getTotalPanels : function() {
		return this._htWElement["aPanel"].length;
	},

	/**
	 * 패널 레퍼런스를 반환한다.
	 * @return {Array} $Element의 패널배열
	 *
	 * @method getPanels
	 */
	getPanels : function() {
		return this._htWElement["aPanel"];
	},

	/**
	 * 특정 패널로 이동한다.
	 * @param  {Number} nIndex     이동하려는 판 인덱스 (0부터)<br>
	 * @param  {Number} nDuration  이동하는 시간 (기본은 nDuration 옵션값)
	 *
	 * @method moveTo
	 */
	moveTo : function(nIndex, nDuration){
		var nMax = this.getTotalContents();
	  if(this.isPlaying() || isNaN(nIndex) || nIndex < 0 || nIndex >= nMax) {
	  	return;
	  }
	  if(typeof nDuration === "undefined") {
	  	nDuration = this.option('nDuration');
	  }
		var nStart = this._bUseH ? this._nX : this._nY, 
	  	nEnd = this._aPos[nIndex];
	  	
	  // 동일 판에서 재배열을 위한 코드
	  if(nStart == nEnd) {
	  	if(nDuration === 0) {
		  	var ht = {
		  		next : true,
		  		moveCount : 0,
		  		corrupt : true,
		  		contentsNextIndex : nIndex
		  	};
	  		if(this._fireFlickingEvent('beforeFlicking', ht)) {
	  			this._fireFlickingEvent('flicking', ht);
	  		}
	  	}
	  	return;
	  }

		// 순환 여부 처리
		if(this._bUseCircular) {
			if(nStart >= 0 && nIndex == nMax-1) {
				nEnd= this._nRange;
			} else if(nStart <= (this._bUseH ? this._htSize.maxX : this._htSize.maxY) && nIndex == 0) {
				nEnd= this._aPos[nMax];
			}
		}	
		
		this._move(nStart, nEnd, {
			duration : nDuration,
			contentsNextIndex : nIndex
		});
  },

	/**
	 * nDuration 동안 다음판으로 이동한다.
	 * @param  {Number} nDuration ms단위 시간
	 *
	 * @method moveNext
	 */
	moveNext : function(nDuration){
		this.moveTo(this.getNextIndex(), nDuration);	   
	},

	/**
	 * nDuration 동안 이전판으로 이동한다.
	 * @param  {Number} nDuration ms단위 시간
	 *
	 * @method movePrev
	 */
	movePrev : function(nDuration){
		this.moveTo(this.getPrevIndex(), nDuration);	   
	},

	// touchStart 구현
	_startImpl : function(we) {
		if(this.isPlaying()) {
      return false;
    }
		jindo.m.SwapCommon.prototype._startImpl.apply(this);
		
		// TODO :  nDistanceX 가 실제 이동한 수치가 달라 bNext 값이 정확하지 않음.. 그래서 시작시 위치 및 index 값을 기억. 
		this._nPosToIndex = this._posToIndex(this._bUseH ? this._nX : this._nY);
		// return true;
	},

	// touchMove 구현
	_moveImpl : function(we) {
		var nVector = this._bUseH ? we.nVectorX : we.nVectorY,
			nDis = this._bUseH ? we.nDistanceX : we.nDistanceY,
			bNext = nDis < 0,
			nPos = this._bUseH ? this._nX : this._nY,
			nMoveIdx = bNext? this.getNextIndex() : this.getPrevIndex();

        
        // TODO :  nDistanceX 가 실제 이동한 수치가 달라 bNext 값이 정확하지 않음..
        // 순환일경우 하단에 bNext 값을 재 정의 
        
		// 비순환 일경우, 마지막이나 처음 인덱스 일경우, 좌표이동간격을 1/2로 조정
		if(this._bUseCircular) {
			nPos += nVector;
			bNext = true;
            if (this._nPosToIndex != this._posToIndex(nPos) || nPos % this._nRange == 0){
                bNext = false;
            }
		} else {
			if( nMoveIdx == this.getContentIndex()) {
				nPos += nVector/2;
			} else {
				nPos += nVector;
			}
		}
		this._nX = this._bUseH ? nPos : 0;
    this._nY = this._bUseV ? nPos : 0;
    we.bNext = bNext;
    return bNext;
	},

	// touchEnd 구현
  _endImpl : function(we) {
    var ht = null,
    	bNext = (this._bUseH ? we.nDistanceX : we.nDistanceY) < 0,
    	nContentsIndex = this.getContentIndex(),
    	nContentsNextIndex = bNext? this.getNextIndex() : this.getPrevIndex(),
    	nStart = this._aPos[nContentsIndex],
    	nDis = Math.abs((this._bUseH ? this._nX : this._nY) - nStart),
    	isRestore = (nContentsIndex === nContentsNextIndex) || (nDis < parseInt(this.option("nFlickThreshold"),10));

    // 가로방향일 경우, 세로로 움직이면 nDis=0. 이때는 return
    if(nDis == 0 ) {
    	return;
    }

		var nMaxPos = this._nRange-1;
		if(this._bUseH) {
			this._nX = nStart + (bNext? -nMaxPos : nMaxPos);
		} else {
			this._nY = nStart + (bNext? -nMaxPos : nMaxPos);
		}

    if(isRestore) {
    	this._restore();
    } else {
		ht = this._getMomentumData(we,1.5);
    	// 모멘텀이 없거나, 처음과 마지막에서의 모멘텀은 한판씩 이동.
    	if(ht.duration === 0 || 
    		(bNext && nContentsIndex === this.getTotalContents() -1) || (!bNext && nContentsIndex === 0) ) {
    		// 한판 이상을 움직였을 경우 보정 작업
  			this.moveTo(nContentsNextIndex, this.option("nDuration"));
    	} else { // 모멘텀
  			var nEndIndex = this._posToIndex(this._bUseH ? ht.nextX : ht.nextY);
  			if(nEndIndex == nContentsIndex) {
  				this._restore();
  			} else {
  				this.moveTo(nEndIndex, ht.duration);
  			}
    	}
	  }
  },

  // rotate될 때
	_resizeImpl : function(we) {
		this.resize();
	},

  // 위치를 반환한다.
  _restore : function() {
  	if(!this._bUseH && !this._bUseV) {
        return;
    }
    var nNewPos = this._aPos[this.getContentIndex()],
    	nPos = this._bUseH ? this._nX : this._nY;
    if (nNewPos === nPos) {
        /* 최종 종료 시점 */
      return;
    } else {
    	this._move(nPos, nNewPos, {
    		duration : this.option("nBounceDuration"),
    		restore : true
    	});
    }
  },

  // _onBeforeAniImpl 이벤트
	_beforeAniEventImpl : function(option) {
		if(option.restore) {
			// console.warn("  . [panelStart] - beforeRestore ", this.getContentIndex());
			if(!this._fireRestoreEvent('beforeRestore',option)) {
				return false;
			}
		} else {
		    // n번째 아이템 인덱스로 이동할때 beforeFlicking 이벤트에서 정상적인 index 값이 전달되지 않음.
		    // 해당 정보는 moveTo 함수에서 정의해 전달하고 아래 코드로 재 정의 하도록 수정.  
		    if(option.duration > 0){
		        option.contentsNextIndex = this._getRevisionNo(option.no);
		    }
			// console.warn("  . [panelStart] - beforeFlicking ", this.getContentIndex());
			if(!this._fireFlickingEvent('beforeFlicking', option)) {
				this._restore();
				return false;
			}
		}
		return true;
	},

    _getRevisionNo : function(nNo){
        var nMax = this.getTotalContents();
        
        if(nNo < 0) {
            nNo += nMax;
        } else if(nNo > nMax-1) {
            nNo = nNo % nMax;
        }
        return nNo;
    },
    
	// panelEnd 이벤트
  _aniEventImpl : function(option) {
  	if(option.restore) {
  	    this._nX = this._bUseH ? option.no * -this._nRange : 0;
        this._nY = this._bUseV ? option.no * -this._nRange : 0;
  		// console.warn("  . [panelEnd] - restore ", we.no);
   		this._fireRestoreEvent('restore', option);
   	} else {
	   	// 순환인 경우, index를 보정함
	   	// var no = option.no,
	   		// nMax = this.getTotalContents();
	   		// bNext = no > this._nContentIndex,
	   	
	   	var no = this._getRevisionNo(option.no);
	   	
	   	option.no = no;
	   	this._updateFlickInfo(no, option.next ? this.getNextElement() : this.getPrevElement());
			/**
          플리킹 임계치에 도달하지 못하고 사용자의 액션이 끝났을 경우, 원래 인덱스로 복원하기 전에 발생하는 이벤트

          @event beforeRestore
          @param {String} sType 커스텀 이벤트명
          @param {Number} nContentsIndex 현재 콘텐츠의 인덱스
          @param {Function} stop 플리킹이 복원되지 않는다.
      **/
               
      /**
          플리킹 임계치에 도달하지 못하고 사용자의 액션이 끝났을 경우, 원래 인덱스로 복원한 후에 발생하는 이벤트

          @event restore
          @param {String} sType 커스텀 이벤트명
          @param {Number} nContentsIndex 현재 콘텐츠의 인덱스
      **/      
	   	// console.warn("  . [panelEnd] - flicking ", no);
   		this._fireFlickingEvent('flicking', option);
   	}
  },

  // beforeFlicking, Flicking 사용자 이벤트 콜
  _fireFlickingEvent : function(name, we) {
    var	nMoveIdx = we.next ? this.getNextIndex() : this.getPrevIndex(),
    	ht = {
    		nContentsIndex : this.getContentIndex(),
    		nContentsNextIndex : nMoveIdx,
    		bNext : we.next
    	};
		// we에 동일 항목이 존재할 경우
		if(typeof we.contentsNextIndex !== "undefined") {
			ht.nContentsNextIndex = we.contentsNextIndex;
		}
  	ht.nMoveCount = we.moveCount;
  	ht.bCorrupt = we.corrupt;
    // deprecated 예정
    ht[this._bUseH ? "bLeft" : "bTop"] = !we.next;
		/**
        플리킹되기 전에 발생한다

        @event beforeFlicking
        @param {String} sType 커스텀 이벤트명
        @param {Number} nContentsIndex 현재 콘텐츠의 인덱스
        @param {Number} nContentsNextIndex 플리킹될 다음 콘텐츠의 인덱스
				@param {Boolean} bCorrupt 순환 플리킹일 경우, 현재 판의 재정렬이 필요할 경우, true를 반환한다.<br/>
				@param {Number} nMoveCount 이동한 판의 개수를 양수로 반환한다.
				@param {Boolean} bNext 플리킹 방향이 다음인지에 대한 여부
        @param {Boolean} bLeft 플리킹 방향이 왼쪽인지에 대한 여부 (세로 플리킹일 경우 이 값은 없다. @deprecated bNext로 변경)
        @param {Boolean} bTop 플리킹 방향이 위쪽인지에 대한 여부 (가로 플리킹일 경우 이 값은 없다. @deprecated bNext로 변경)
        @param {Function} stop 플리킹되지 않는다.
    **/
    /**
        플리킹된 후에 발생한다

        @event flicking
        @param {String} sType 커스텀 이벤트명
        @param {Number} nContentsIndex 현재 콘텐츠의 인덱스
        @param {Number} nContentsNextIndex 플리킹될 다음 콘텐츠의 인덱스
				@param {Boolean} bCorrupt 순환 플리킹일 경우, 현재 판의 재정렬이 필요할 경우, true를 반환한다.<br/>
				@param {Number} nMoveCount 이동한 판의 개수를 양수로 반환한다.
				@param {Boolean} bNext 플리킹 방향이 다음인지에 대한 여부
        @param {Boolean} bLeft 플리킹 방향이 왼쪽인지에 대한 여부 (세로 플리킹일 경우 이 값은 없다. @deprecated bNext로 변경)
        @param {Boolean} bTop 플리킹 방향이 위쪽인지에 대한 여부 (가로 플리킹일 경우 이 값은 없다. @deprecated bNext로 변경)
        @param {Function} stop 플리킹되지 않는다.
    **/
    // console.debug(name,ht);
    return this.fireEvent(name, ht);
  },

  // beforeRestore, Restore 사용자 이벤트 콜
  _fireRestoreEvent : function(name, we) {
  	return this.fireEvent(name, {
  		nContentsIndex : this.getContentIndex()
  	});
  },

	// _nContentIndex에 맞게 좌표 조정
  _updateFlickInfo : function(nIndex, wel) {
  	if(typeof nIndex === "undefined") {
  		nIndex = this.getContentIndex();
  	}
  	this._nContentIndex = nIndex;

  	if(typeof wel === "undefined") {
  		wel = this.getElement();
  	}
		// 좌표 조정
  	this._nX = this._bUseH ? nIndex * -this._nRange : 0;
		this._nY = this._bUseV ? nIndex * -this._nRange : 0;
		// console.log("=-=-=-=-=>", nIndex, this._nX, this._nY, this._nRange);
		// 엘리먼트 조정
		this._welElement = wel;
  },

  /**
   * 애니메이션 종료 시점
   * @param  {[type]} we [description]
   * @return {[type]}    [description]
   */
  _onEndAniImpl : function(we) {
  	if(this._bUseCircular) {
  		// 순환일 경우, anchor를 재갱신한다.
  		this._refreshAnchor();
  	} else {
  		this._fixOffsetBugImpl();
  	}
  },

	_makeOption : function(ht) {
		ht = ht || {};
		ht.duration = typeof ht.duration === "undefined" ? 0 : ht.duration;
		ht.restore = typeof ht.restore === "undefined" ? false : ht.restore;
		if(ht.restore) {
			ht.moveCount = 0;
		} else {
			ht.moveCount = typeof ht.moveCount === "undefined" ? 1 : ht.moveCount;
		}
		ht.corrupt = ht.moveCount > 1 && ht.duration === 0 ? true : false;

		// 넘기는 데이터
		ht.useCircular = this._bUseCircular;
		ht.useH = this._bUseH;
		ht.range = this._nRange;
		return ht;
	},

	_moveWithEvent : function(nPos, nDuration, htOption) {
		var self = this,
			htParam = {};
		htOption.no = this._posToIndex(nPos);
		htOption.htWElement = this._htWElement;   // cover 에서 다음, 이전 element 를 참조해야 하지만 이를 알 수 없어 함께 넘기도록 수정.
		
		// 복사...
		for(var p in htOption) {
			htParam[p] = htOption[p];
		}
		// before
		this._oAnimation._oMorph.pushCall(function() {
			if(!self._beforeAniEventImpl(htParam)) {
				this.pause().clear();
			}
		});
		// animating...
		this._oAnimation.move(this._bUseH ? nPos : 0, this._bUseV ? nPos : 0, nDuration, htParam);
		// ani
		this._oAnimation._oMorph.pushCall(function() {
			self._aniEventImpl(htParam);
		});
		return this._oAnimation._oMorph;
	},

	_move : function (nStart, nEnd, htOption) {
		if(nStart === nEnd) {
			return;
		}
		var bNext = nStart > nEnd,
			nStepCount = this._getStepCount(nStart, nEnd);
		htOption = htOption || {};
		htOption.moveCount = nStepCount;
		htOption.next = bNext;
		this._makeOption(htOption);
		if(htOption.restore) {
			nStepCount = 1;
		}
		// console.debug("run : " + nStart + " -> " + nEnd, " ( " + htOption.duration, " ms)", htOption);
		
		// duration 이 0일 경우 바로 이동.
		if(htOption.duration == 0) {
			this._moveWithEvent(nEnd, 0, htOption).play();
			return;
		}

		var nStepDuration = 0,
			nStartPart = nStart,
			nEndPart = 0,
			fnEffect = this.option("fpPanelEffect")(htOption.duration);

		// 애니메이션 queue 쌓기
		for(var i=0; i<nStepCount; i++) {
			// console.warn("  . (" + nStartPart + " -> " + this._getPanelEndPos(nStartPart, bNext) + ")",
			//  	"range/" + this._nRange,
			//  	"next/" + bNext,
			//  	" ( " + nStepDuration, " ms)"
			// );
			nEndPart = this._getPanelEndPos(nStartPart, bNext);
			nStepDuration = fnEffect((i+1)/nStepCount) - fnEffect(i/nStepCount);
			this._moveWithEvent(nEndPart, nStepDuration, htOption);
			nStartPart = nEndPart;
		}
		this._oAnimation._oMorph.play();		
	},

	_getStepCount : function(nStart, nEnd) {
		// 비순환일 경우, 0보다 큰경우에는 되돌아야함으로 bNext는 false
		var	bNext = nStart > 0 && !this._bUseCircular ? false : nStart > nEnd,
			nStartIdx = this._getStartIndex(nStart, bNext),
			nEndIdx = this._posToIndex(nEnd),
			nEndRangePos = nEndIdx * -this._nRange,
			nCount = Math.abs(nEndIdx-nStartIdx),
			nStartRangePos = nStartIdx;
			if(nStart%this._nRange === 0) {
				nStartRangePos += bNext ? 0 : -1;
				if(!bNext) {
					nCount--;
				}
			}
			nStartRangePos *= -this._nRange;	
			// console.debug("_getStepCount",nStart + "[" + nStartIdx + "]," + nStartRangePos + " ===> " +  nEnd + "[" + nEndIdx + "], " + nEndRangePos + " [[ " + bNext +" (" + nCount + ")");
		return nCount;
	},

  // 포지션값을 인덱스로 변경
  _posToIndex : function(nPos) {
  	return Math.floor(-nPos/this._nRange);
  },

  // 좌표를 시작 인덱스로 변경
	_getStartIndex : function (nPos, bNext) {
		var nIdx = this._posToIndex(nPos);
		nIdx += bNext ? 0 : 1;
		return nIdx;
	},

	// 시작좌표를 기준으로 종료 좌표를 구하기
	_getPanelEndPos : function(nStart, bNext) {
		var nPos = 0, 
			nCurrentPanel = this._getStartIndex(nStart, bNext),
			bAdjust = (nStart%this._nRange === 0);
		if(bAdjust) {
			nPos = nStart + (bNext? -this._nRange : this._nRange);
		} else {
			nPos = (bNext ? nCurrentPanel +1 : nCurrentPanel-1) * -this._nRange;
		}
		return nPos;
	},

	_getTranslate : function(nPos) {
		return this._oAnimation.getTranslate(this._bUseH ? nPos : 0,  this._bUseV ? nPos : 0);
	},

  /**
      jindo.m.Flick 에서 사용하는 모든 객체를 release 시킨다.
      @method destroy
  **/
	destroy: function() {
		jindo.m.SwapCommon.prototype.destroy.apply(this);
	}
}).extend(jindo.m.SwapCommon);