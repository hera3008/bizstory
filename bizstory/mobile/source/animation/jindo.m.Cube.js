/**
    @fileOverview 큐브 효과를 구현한 애니메이션
    @author sculove
    @version #__VERSION__#
    @since 2013. 4. 15.
*/
/**
    큐브 효과를 처리하는 애니메이션 컴포넌트
    
    @class jindo.m.Cube
   	@extends jindo.m.Animation
    @keyword component
    @group Component
    @invisible
**/
jindo.m.Cube = jindo.$Class({
	/* @lends jindo.m.Cube.prototype */
	$init : function(htUserOption) {
	    htUserOption = htUserOption || {};
		this.option(htUserOption.htOption || {});
	},

    /**
     * 변수를 초기화 한다.
     * 
     * @param {Element} el  기준이 되는 element 
     */
    _initVar : function(el){
        jindo.m.Animation.prototype._initVar.call(this, el);
        
		this._nDefaultDeg = 0;
		this._nHalfWidth = 0;
		this._htAniOption = {};
		this._sRotate = this._bUseH  ? "Y" : "X";
    },
    
    /**
     * 패널 및 container 의 속성 초기화 처리 
     * 
     * @param {HashTable} htElement CubeFlicking 으로 부터 전달받은 element 변수 
     */
	setStyle : function( aArgs ){
	    //aArgs[0] = "container", aArgs[1] = "view"
		var htCss ={},
			htCloneCss = {};
		
		
		this._sRotate = aArgs[1]["bUseH"]  ? "Y" : "X";
		// this._setHalfWidth(aArgs[0]["view"]);
		// this._nHalfWidth = parseInt(htElement["view"].css(this._bUseH  ? "width" : "height"), 10)/2;
		htCss[this.p("TransitionProperty")] = "-" + this.sCssPrefix + "-transform";
		htCss[this.p("Transform")] = "translateZ(-"+aArgs[1]["nHalpSize"]+"px) rotate"+this._sRotate+"("+this._nDefaultDeg+"deg)";
		
		// target 엘리먼트
		if(this.option("bUseTimingFunction")) {
        	htCss[this.p("TransitionTimingFunction")] = "cubic-bezier(0.33,0.66,0.66,1)";
        }
        // css 복사
        for(var i in htCss) {
        	htCloneCss[i] = htCss[i];
        }
        if(this._bUseH) {
        	htCss["clear"] = "both";
        }

        htCss["position"] = "relative";
        htCss[this.p("TransformStyle")] = "preserve-3d";
        aArgs[0]["view"].css(this.p("Perspective") , aArgs[1]["nHalpSize"]*4+"px");        // perspective 의 값인 컨텐츠 넓이의 두배를 적용하는것이 자연스러움.
		this._welTarget = aArgs[0]["container"].css(htCss);
		// this.setPanelPos(aArgs[0], this.option("nDefaultIndex"));
    		
        this.fireEvent("set", {
        	elements : aArgs[0],
        	css : htCloneCss
        });
        
        this._welTarget = aArgs[0]["container"].css(htCss);
        return htCss;
	},

    /**
     * 엘리먼트 이동시 발생함
     * @param {Number} nX   이동 X 좌표 
     * @param {Number} nY   이동 Y 좌표 
     * @param {HashTable} option
     *      option.next : true          // 이전 패널의 방향성이 아닌 다음 방향성으로 이동했을때 true , 반대 false
     *      option.nDis : 10            // 터치한 시점으로 부터 이동한 거리                       
     */
	move : function(nX, nY, nDuration, option) {
	    // console.trace();
	    // console.log(nX, nY);
	    if(!option){
	        option = {};
	    }
	    if(option.nHalpSize){
	        this._htAniOption = option;
	    }
	    var nPos = option.useH ? nX : -nY;
	    nPos = this._nDefaultDeg + ((nPos * 45) / option.nHalpSize);
		
		// console.log("------------------------ > " , option);
		var welTarget = this.getTarget(true);
		// welTarget.css(this.p("Transform"), "translateZ(-"+ option.nHalpSize +"px) rotate"+this._sRotate+"(" + nPos + "deg)");
		
		var htCss = {
            "@transitionProperty" : "-" + this.sCssPrefix + "-transform",
            "@transform" : "translateZ(-"+ option.nHalpSize +"px) rotate"+this._sRotate+"(" + nPos + "deg)"
        };
        if(!!nDuration) {
            // console.error("duration : " +  nDuration);
            // for(var i in htCss) {
            //  console.warn("morph - " + i + " , " + htCss[i]);
            // }
            this._oMorph
                .pushAnimate(nDuration, [welTarget, htCss]);
        } else {
            welTarget.css(this.toCss(htCss));
            this._oMorph.fireEvent("progress", {
                nTop : nY,
                nLeft : nX,
                no : option.no
            });
        }
        return this._oMorph;

	}

}).extend(jindo.m.Animation);