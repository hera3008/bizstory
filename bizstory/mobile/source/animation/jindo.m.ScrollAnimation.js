/**
    @fileOverview 애니메이션 동작을 담당하는 컴포넌트
    @author sculove
    @version #__VERSION__#
    @since 2013. 4. 15.
*/
/**
    애니메이션 동작을 제어하는 컴포넌트

**/
jindo.m.ScrollAnimation = jindo.$Class({
	/**
      초기화 함수

      @constructor
      @param {String|HTMLElement} el 플리킹 기준 Element (필수)
      @param {Object} [htOption] 초기화 옵션 객체
				@param {Boolean} [htOption.bHasOffsetBug=false] android 하위 버전에 존재하는 offset변환시 하이라이트,롱탭, 클릭이 offset변경 전 엘리먼트에서 발생하는 버그 여부
				@param {Function} [oOptions.fEffect=jindo.m.Effect.easeOut] 애니메이션에 사용되는 jindo.m.Effect 의 함수들
	      @param {Boolean} [htOption.bUseCss3d=jindo.m.useCss3d()] css3d(translate3d) 사용여부<br />
	          모바일 단말기별로 다르게 설정된다. 상세내역은 <auidoc:see content="jindo.m">[jindo.m]</auidoc:see>을 참조하기 바란다.
	      @param {Boolean} [htOption.bUseTimingFunction=jindo.m.useTimingFunction()] 애니메이션 동작방식을 css의 TimingFunction을 사용할지 여부<br />false일 경우 setTimeout을 이용하여 애니메이션 실행.<br />
	      모바일 단말기별로 다르게 설정된다. 상세내역은 <auidoc:see content="jindo.m">[jindo.m]</auidoc:see>을 참조하기 바란다.
  **/	
	$init : function(htUserOption) {
		this.option({
			bHasOffsetBug : false,
			fEffect : jindo.m.Effect.easeOut,
			bUseCss3d : jindo.m.useCss3d(),
			bUseTimingFunction : jindo.m.useTimingFunction()
		});
		this.option(htUserOption || {});
		this._initVar();
	},

	/**
	 * 변수를 초기화 한다.
	 */
	_initVar: function(el) {
		this.sCssPrefix = jindo.m.getCssPrefix();
		this._htTans = this.option("bUseCss3d") ? {
    	open : "3d(",
    	end : ",0)"
		} : {
    	open : "(",
    	end : ")"
		};
		// this._oMorph = new jindo.m.Morph({
		// 	'fEffect' : this.option("fEffect"),
		// 	'bUseTransition' : this.option("bUseTimingFunction")
		// }).attach({
		// 	"progress" : jindo.$Fn(function(we) {
		// 		this.fireEvent("progress",we);
		// 	},this).bind(),
		// 	"end" : jindo.$Fn(function(we) {
		// 		this.fireEvent("end",we);
		// 	},this).bind()
		// });
		// set 이후 설정되는 값.
		this._welTarget = null;	// 하위에서 필수 설정.
		this._isPlaying = false;

    this._nX = 0;
    this._nY = 0;
    this._fpTransitionEnd = jindo.$Fn(this._onTransitionEnd, this).bind();
    this._nTimer = -1;
	},

	/**
	 * [필수 구현]
	 * 대상 컴포넌트를 초기화
	 */
	setStyle : function(aArgs) {
		var htCss ={};
		if(this.option("bUseTimingFunction")) {
			htCss[this.p("TransitionTimingFunction")] = "cubic-bezier(0.33,0.66,0.66,1)";
    }
		htCss[this.p("TransitionProperty")] = "-" + this.sCssPrefix + "-transform";
		htCss[this.p("Transform")] = this.getTranslate(0, 0);
		this._welTarget = aArgs[0].css(htCss);
		this.fireEvent("set", {
			css : htCss
		});
		return htCss;
	},

	// 실제 좌표 이동
	setPos : function(nX, nY) {
		var htCss = {};
		if(this.option("bHasOffsetBug")) {
			var htStyleOffset = jindo.m.getStyleOffset(this._welTarget);
			nX -= htStyleOffset.left;
			nY -= htStyleOffset.top;
		}
		htCss[this.p("Transform")] = this.getTranslate(nX+"px", nY+"px");
		this._welTarget.css(htCss);
	},

  /**
      transition duration 지정
      @param {Nubmer} nDuration
  **/
  _transitionTime: function (nDuration) {
      nDuration += 'ms';
      this._welTarget.css(this.p("TransitionDuration"), nDuration);
  },

		/**
      TransitionEnd 이벤트 핸들러
      @param {jindo.$Event} we
  **/
  _onTransitionEnd : function(we) {
      jindo.m.detachTransitionEnd(this._welTarget.$value(), this._fpTransitionEnd);
      // this._animate();
      this._isPlaying = false;
      this.fireEvent("end");
  },


	/**
	 * [필수 구현]
	 * 엘리먼트 이동시 발생함
	 * @param  {Number} nPos  이동할 좌표
	 * @param  {Boolen} bNext 다음으로 이동하는 경우 true, 이전으로 이동하는 경우 false
	 */
	move : function(nX, nY, nDuration, option) {
		option = option || {};
		var self = this;
		if(nDuration == 0) {
      if(this.option("bUseTimingFunction")) {
         this._transitionTime(0);
      }
      // 좌표 이동
      // this._nX = nX;
      // this._nY = nY;
      this.setPos(nX, nY);

      this.fireEvent("progress", {
				nTop : nY,
				nLeft : nX
			});
	    // this.fireEvent("end");
    } else {
        this._isPlaying = true;
        // Transition을 이용한 animation
        if (this.option("bUseTimingFunction")) {
            this._transitionTime(nDuration);
            // this._setPos(oStep.nLeft, oStep.nTop);
            
            // 좌표 이동
			      this._nX = nX;
			      this._nY = nY;

            // this._isPlaying = false;
            jindo.m.attachTransitionEnd(this._welTarget.$value(), this._fpTransitionEnd);
        } else {
            // AnimationFrame을 이용한 animation
            var startTime = (new Date()).getTime(),
            		nStartX = this._nX, nStartY = this._nY;
            (function animate () {
                var now = (new Date()).getTime(),
                	nEaseOut;
                if (now >= startTime + nDuration) {

                    // updater를 중지시키고, 바로 셋팅
                    // self._isActivateUpdater = false;
                    // self._setPos(oStep.nLeft, oStep.nTop);
                    // self._oMoveData=oStep;
                    // self._isActivateUpdater = true;

                    // 좌표이동
                    self._nX = nX;
                    self._nY = nY;
                    self.fireEvent("progress", {
											nTop : nY,
											nLeft : nX
										});

                    self._isPlaying = false;
                    self.fireEvent("end");
                    // self._animate();
                    return;
                }
                now = (now - startTime) / nDuration - 1;
                nEaseOut = Math.sqrt(1 - Math.pow(now,2));
                self._nX = (nX - nStartX) * nEaseOut + nStartX;
                self._nY = (nY - nStartY) * nEaseOut + nStartY;
								self.fireEvent("progress", {
									nTop : self._nY,
									nLeft : self._nX
								});

                if (self._isPlaying) {
                   self._nTimer = requestAnimationFrame(animate);
                }
            })();
        }
    }
	},

	/**
	 * 애니메이션 대상 타겟
	 * @param  {Boolean} isWrapper $Element 반환 여부
	 * @return {$Element|HTMLElement} 타겟
	 *
	 * @method getTarget
	 */
	getTarget : function(isWrapper) {
		if(isWrapper) {
			return this._welTarget;
		} else {
			return this._welTarget.$value();
		}
	},

	/**
	 * prefix를 붙인 스트링을 반환한다.
	 * @param  {String} str prefix를 붙일 문자열
	 * @return {String}     prefix를 붙인 문자열
	 */
	p : function(str) {
		return this.sCssPrefix + str;
	},

	getTranslate : function(sX,sY) {
		return "translate" + this._htTans.open + sX + "," + sY + this._htTans.end;
	},

	// toCss : function(ht) {
	// 	var p, pResult, prefix, htResult = {};
	// 	for(p in ht) {
	// 		pResult = p;
	// 		if(/^@/.test(p)) {
	// 			p.match(/^(@\w)/);
	// 			prefix = RegExp.$1;
	// 			if(/transition|transform/.test(pResult)) {
	// 				pResult = p.replace(prefix, prefix.toUpperCase());
	// 				pResult = pResult.replace("@",this.sCssPrefix);
	// 			} else {
	// 				pResult = pResult.replace("@","");
	// 			}
	// 		}
	// 		htResult[pResult] = ht[p];
	// 	}
	// 	return htResult;
	// },

	/**
	 * 애니메이션 동작 여부를 반환
	 * @return {Boolean} 애니메이션 동작 여부
	 */
	isPlaying : function() {
		// return this._oMorph.isPlaying();
		return this._isPlaying;
	},

  /**
      애니메이션을 멈춘다.
  **/
	stop : function(nMode) {
		if(typeof nMode === "undefined") {
			nMode = 0;
		}
		if(this.option("bUseTimingFunction")) {
        jindo.m.detachTransitionEnd(this._welTarget.$value(), this._fpTransitionEnd);
    } else {
        cancelAnimationFrame(this._nTimer);
    }
    this._isPlaying = false;
	},


  /**
      애니메이션을 호출한다.
  **/
  _animate : function() {
      var self = this,
          oStep;
      if (this._isPlaying) {
          return;
      }
      // if(!this._aAni.length) {
      //     this.restorePos(300);
      //     return;
      // }
      // 동일할 경우가 아닐때 까지 큐에서 Step을 뺌.
      // do {
      //     oStep = this._aAni.shift();
      //     if(!oStep) {
      //         return;
      //     }
      // } while( oStep.nLeft == this._nLeft && oStep.nTop == this._nTop );
      
  },

  /**
      사용하는 모든 객체를 release 시킨다.
      @method destroy
  **/
	destroy: function() {
		this.detachAll("progress");
	}
}).extend(jindo.m.UIComponent);