/**
    @fileOverview 커버 효과를 구현한 애니메이션
    @author sculove
    @version #__VERSION__#
    @since 2013. 4. 15.
*/
/**
    커버 효과를 처리하는 애니메이션 컴포넌트

    @class jindo.m.Cover
    @extends jindo.m.Animation
    @keyword component
    @group Component
    @invisible
**/
jindo.m.Cover = jindo.$Class({
    /* @lends jindo.m.Cover.prototype */
    $init : function(htUserOption) {
        this.option({
            nDefaultScale : 0.94
        });
        this.option(htUserOption || {});
        this._welZoom = null;
        this._welTmpPanel = null;
    },

    setStyle : function(aArgs) {
        var htCss = {
                "position" : "absolute",
                "width" : "100%",
                "height" : "100%",
                "left" : "0px",
                "top" : "0px",
                "zIndex" : 1,
                "opacity" : 0.2,
                "display" : "none"
            };
        htCss[this.p("TransitionProperty")] = "-" + this.sCssPrefix + "-transform";
        htCss[this.p("Transform")] = this.getTranslate(0, 0);

    // 각각 패널들 설정
        jindo.$A(aArgs[0]).forEach(function(v,i,a) {
            v.css(htCss);
        });

        this.fireEvent("set", {
        css : htCss
    });
    },
    
    _getIndexByElement : function(htWElement){
        var bValue = -1;
        var wel = this._getTarget(true, true);
        jindo.$A(htWElement["aPanel"]).forEach(function(v,i,a) {
            if(v.isEqual(wel)) {
                bValue = i;
            }
        });
        return bValue;
    },
    
    _getTarget : function(isWrapper, bIn){
        var welTarget = this._welTmpPanel ? this._welTmpPanel : this._welTarget;
        welTarget = bIn ? welTarget : this._welTarget;
        if(isWrapper) {
            return welTarget;
        } else {
            return welTarget.$value();
        }
    },
    
  /**
   * 현재 엘리먼트를 반환한다.
   * @return {$Element} 현재 패널의 엘리먼트를 반환한다.
   */
    _getElement : function(htWElement) {
        if(htWElement) {
            var n = this._getIndexByElement(htWElement);
            return htWElement["aPanel"][n] || null;
        }
        return null;
    },
    
    /**
   * 다음 엘리먼트를 반환한다.
   * @return {$Element} 다음 패널의 엘리먼트를 반환한다.
   */
    _getNextElement : function(htWElement) {
        var n = this._getIndexByElement(htWElement);
        if(this.option("bUseCircular")){
           n = ((((n+1)%3) > 2 )? 0 : (n+1))%3;
        } else {
            n++;
        }
        return htWElement["aPanel"][n];
    },
    
  /**
   * 이전 엘리먼트를 반환한다.
   * @return {$Element} 이전 패널의 엘리먼트를 반환한다.
   *
   * @method getPrevElement
   */
    _getPrevElement : function(htWElement) {
        var n = this._getIndexByElement(htWElement);
        if(this.option("bUseCircular")){
            n = ((n-1) < 0 )? 2 : (n-1);
        } else {
            n--;
        }
        return htWElement["aPanel"][n];
    },
    
    
    
    move : function(nX, nY, nDuration, option) {
        if(option.useH) {
            nX = this._getPos(nX, option);
        } else {
            nY = this._getPos(nY, option);
        }
        var welTarget = this.getTarget(true),
            htCss = {
            "@transitionProperty" : "-" + this.sCssPrefix + "-transform",
            "@transform" : this.getTranslate(nX+"px", nY+"px")
        };

        var elElement = welTarget;
        var elZoomElement = this._welZoom;

        /**
         * 순환일경우 morph 에 큐를 쌓기 위한 element 처리
         * 현재의 element 만을 가지고 큐에 쌓고 있기 때문에 임시 this._welTmpPanel 변수에 큐에 쌓인 element 를 참조로 next/prev element 를 검색할 수 있도록 적용 
         * - by phpmania 
         */
        if(option.moveCount > 1){
            elElement = this._getElement(option.htWElement);
            elZoomElement = this[(option.next ? "_getNextElement" : "_getPrevElement" )](option.htWElement);
        }
        if(!!nDuration) {
            this._welTmpPanel = elZoomElement;
            var array = [elElement, htCss];
            if(elZoomElement) {
                array.push(elZoomElement);
                array.push(this.getZoomCss(option.useH ? nX : nY, option));
            }
            this._oMorph.pushAnimate(nDuration, array);
        } else {
            welTarget.css(this.toCss(htCss));
            if(this._welZoom) {
                this._welZoom.css(this.toCss(this.getZoomCss(option.useH ? nX : nY, option)));
            }
             this._oMorph.fireEvent("progress", {
                nTop : nY,
                nLeft : nX
            });
        }
        
        return this._oMorph;
    },

    /**
     * Cover에서 타겟이 슬라이드 될때 함께 zoomin되어야할 엘리먼트를 제어
     * @param  {Number} nPos  이동할 좌표
     * @param  {Boolen} bNext 다음으로 이동하는 경우 true, 이전으로 이동하는 경우 false
     */
    getZoomCss : function(nPos, option) {
        var nRange = option.range,
            nOpacity = option.next ? (nRange - nPos) / nRange -1 : 1 - (nRange - nPos) / nRange,
            nScale = this.option("nDefaultScale"),
            htCss = {};

        nScale = nScale + nOpacity * (1 - nScale);
        htCss["@opacity"] = nOpacity;
        htCss["@transitionProperty"] = "-" + this.sCssPrefix + "-transform";
        htCss["@transform"] = this.getTranslate(0, 0) + " scale" + this._htTans.open + nScale + ", " + nScale + this._htTans.end;
        return htCss;
    },

    change : function(welTarget, welZoom, bNext) {
        // Target 변환
        var htCss = {
            "zIndex" : 1,
            "opacity" : 0.2,
            "display" : "block"
        };
        htCss[this.p("TransitionProperty")] = "-" + this.sCssPrefix + "-transform";
        this._changeZoom(welZoom, htCss, bNext);
        this._changeTarget(welTarget, htCss);
    },

    /**
     * 애니메이션 도중 타겟이 변경될 필요가 있을때 호출
     * @param  {$Element} welTarget 슬라이드 될 엘리먼트
     * @param  {$Element} welZoom   zoom-in 될 엘리먼트
     */
    _changeTarget : function(welTarget, htCss) {
        if(this._welTarget && this._welTarget.isEqual(welTarget)) {
            return;
        }
        // 기존 엘리먼트 복원
        if(this._welTarget) {
            this._welTarget.hide();
        }
        // Target 변환
        htCss["zIndex"] = 10;
        htCss["opacity"] = 1;
        htCss[this.p('Transform')] = this.getTranslate(0, 0);
        this._welTarget = welTarget.css(htCss);
    },

    /**
     * 애니메이션 도중 Zoom이 변경될 필요가 있을때 호출
     * @param  {$Element} welZoom   zoom-in 될 엘리먼트
     */
    _changeZoom : function(welZoom, htCss, bNext) {
        if(this._welZoom && this._welZoom.isEqual(welZoom)) {
            return;
        }
        // 기존 엘리먼트 복원
        if(this._welZoom) {
                  // var htCssDefault = {};
        // htCssDefault[this.p('Transform')] = "";
        // htCssDefault[this.p('TransitionDuration')] = "";
        // htCssDefault[this.p('TransitionProperty')] = "";
        // htCssDefault["display"] = "none";
        // this._welZoom.css(htCssDefault);
            this._welZoom.hide();
        }
        if(welZoom) {
            htCss["zIndex"] = 1;
            htCss["opacity"] = 0.2;
            htCss[this.p('Transform')] = this.getTranslate(0, 0) + " scale" + this._htTans.open + this.option("nDefaultScale") + "," + this.option("nDefaultScale") + this._htTans.end;
            this._welZoom = welZoom.css(htCss); 
        } else {
            this._welZoom = null;
        }
    },

    _getPos : function(nPos, option) {
        var n=nPos,
            nRange = option.range,
            bNext = option.next;
        if(option.restore) {
            n = 0;
        } else {
            n%=nRange;
            if(option.duration &&  n === 0) {
                n += bNext? -nRange : nRange;
            } else if(nPos < 0) {
                n += bNext? 0 : nRange;
            }
        }
        // console.warn(nPos + " => " + n, "range/"+ nRange, bNext);
        return n;
    }
}).extend(jindo.m.Animation);