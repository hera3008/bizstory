/**
    @fileOverview 페이지 메뉴 transform 컴포넌트 (Cube)
    @author mania
    @version 1.9.0
    @since 2013. 6. 3
**/
/**
    페이지 메뉴 transform 컴포넌트 (Cube)
    @class jindo.m.CubeReveal
    @extends jindo.m.RevealCommon
    @uses jindo.m.Effect
    @uses jindo.m.Morph
    @keyword 메뉴, 햄버거 매뉴, 큐브 
    @group Component
    @new
    
    @history 1.9.0 Release 최초 릴리즈
**/
jindo.m.CubeReveal = jindo.$Class({
    /* @lends jindo.m.CubeReveal.prototype */
    /**
        초기화 함수

        @constructor
        @param {Object} [htOption] 초기화 옵션 객체
            @param {String} [htOption.sClassPrefix=reveal-]
            컴포넌트 내에서 element select 시 참조할 Class의 prefix명
            @param {Number} [htOption.nDuration=500]
            슬라이드 애니메이션 지속 시간
        @param {Number} [htOption.nMargin=100] 
            오른쪽메뉴(햄버거 메뉴) 에서 사용할 컨텐츠 노출 사이즈
            @param {String} [htOption.sDirection=down]
            슬라이드 방향
            <ul>
            <li>"down" : 화면 상단에서 아래로 슬라이드</li>
            <li>"left" : 화면 오른쪽에서 왼쪽으로 슬라이드</li>
            </ul>
            @param {Boolean} [htOption.bActivateOnload=true] 
            컴포넌트 로드시 activate 여부
            @param {Boolean} [htOption.bUseTimingFunction=jindo.m.useTimingFunction()]
            Timingfunction 사용 여부 
            @param {Boolean} [htOption.bUseOffsetBug=jindo.m.hasOffsetBug()]
            offset 여부  
    **/
    
    $init : function(htOption){
        this.option(htOption || {});
    },
    
    _initVar : function(bInit){
        jindo.m.RevealCommon.prototype._initVar.call(this, bInit);
        this._htTmpNavSizeInfo = {};  
    },

    /**
      *  컴포넌트 내부에서 쓰는 엘리먼트를 저장한다.
      */
    _setWrapperElement: function() {
        jindo.m.RevealCommon.prototype._setWrapperElement.call(this);
        
        if(this._htWElement["header"].indexOf(this._htWElement["nav"]) > -1){
            console.error("Should not have to navigation in the header");
            return false;
        }
        this._bNavInHeader = false;
    },

    /**
     * header 영역 style 정의
     */
    _setHeaderStyle : function(){
        if(this.option("sDirection") == "down"){
                this._htWElement["header"].css("top" , this._bShow ? (!this.option("bUseOffsetBug") ? -this._nNavHeight : 0) : -this._nNavHeight);
        }
    },
    
    /**
     * navigation 영역 style 정의 
     */
    _setNavStyle : function(htPosInfo, bInit){
        var sCssPrefix = jindo.m.getCssPrefix();
        var htCss = {};
        if(bInit){
            htCss[sCssPrefix + "Transform"] = this._sTranslateStart +"0px, 0px" + this._sTranslateEnd + " rotate"+htPosInfo.sRotate+"("+htPosInfo.sRotateValue+"deg)";
        }
        
        if(this.option("sDirection") == "down"){
            htCss[sCssPrefix + "TransformOrigin"] = "50% "+this._nNavHeight+"px";
        // }else{
        }else if(this.option("sDirection") == "left"){
            htCss[sCssPrefix + "TransformOrigin"] = "0px 50% ";
        }else if(this.option("sDirection") == "right"){
            htCss[sCssPrefix + "TransformOrigin"] =  "right 50% ";
        }
        
        htCss[sCssPrefix + "BackfaceVisibility"] = "hidden";
            
        this._htWElement["nav"].css(htCss);

        var htNavStyle = {
            "width" : (this._nWidth - this.option("nMargin")) + "px"
        };

        if(this.option("sDirection") != "down"){
            
            if(this.option("sDirection") == "left"){
                htNavStyle.left = this._nWidth;
            }else if(this.option("sDirection") == "right"){
                htNavStyle.left = -this._nWidth + this.option("nMargin");
                
            }
            this._htWElement["nav"].css(htNavStyle);
             this._htWElement["nav"].parent().css({
                "position" : "absolute"
                // "height": oViewportSize.viewportHeight + "px"   
            });
            
        }
    },

    /**
     *  네비게이션, 메뉴, 컨텐츠 영역의 이동 위치를 계산하는 공통 함수
     */
    _getPosInfo : function(){
        switch (this.option("sDirection")){
            case "down" :
                return {
                    "sRotateValue" : 90,
                    "sRotate" : "X",
                    "nHeader" : {
                        "Y" : this._nNavHeight * (this._bShow ? 0: 1),
                        "X" : 0
                    },
                    "nNav" : {
                        "Y" : this._nNavHeight * (this._bShow ? 0 : 1),
                        "X" : 0
                    },
                    "nContent" : {
                        "Y" : this._nNavHeight * (this._bShow ? 0 : 1),
                        "X" : 0
                    },
                    
                    "nNavHeight" : this._nNavHeight
                };
                break;
            case "left" : 
                return {
                    "sRotateValue" : 45,
                    "sRotate" : "Y",
                    "nDefaultNavPos" : (this._nWidth - this.option("nMargin")) * (this._bShow ? 0 : 1),
                    "nHeader" : {
                        "Y" : !this._bShow ? 0 : 0,
                        "X" : (this._nWidth - this.option("nMargin")) * (this._bShow ? 0 : -1)
                    },
                    "nContent" : {
                        "Y" : !this._bShow ? -parseInt(this._htWElement["content"].css("top") | 0, 10) : 0,
                        "X" : (this._nWidth - this.option("nMargin")) * (this._bShow ? 0 : -1)
                    },
                    "nNav" : {
                        "Y" : 0,
                        "X" : this._bShow ?  0 : (this._nWidth ) * (this._bShow ? 0 : -1)
                    },
      
                    "nMarginLeftPos" : (this._nWidth - this.option("nMargin")),
                    "nLeftPos" : !this._bShow ? this.option("nMargin") - this._nWidth : 0,
                    "nNavHeight" : 0

                };
                
                break;
            case "right" : 
                return {
                    "sRotateValue" : -45,
                    "sRotate" : "Y",
                    "nDefaultNavPos" : (this._nWidth - this.option("nMargin")) * (this._bShow ? 0 : -1),
                    "nHeader" : {
                        "Y" : !this._bShow ? 0 : 0,
                        "X" : (this._nWidth - this.option("nMargin")) * (this._bShow ? 0 : 1)
                    },
                    "nContent" : {
                        "Y" : !this._bShow ? -parseInt(this._htWElement["content"].css("top") | 0, 10) : 0,
                        "X" : (this._nWidth - this.option("nMargin")) * (this._bShow ? 0 : 1)
                    },
                    "nNav" : {
                        "Y" : 0,
                        "X" : this._bShow ?  0 : (this._nWidth - this.option("nMargin")) * (this._bShow ? 0 : 1)
                    },
      
                    "nMarginLeftPos" : -(this._nWidth - this.option("nMargin")),
                    "nLeftPos" : !this._bShow ? this.option("nMargin") - this._nWidth : 0,
                    "nNavHeight" : 0

                };
                
                break;
        }
        
    },

    /**
     * transition instance 생성을 위한 option 정의 
     */
    _getTransitionOption : function(){
        return {
            "fEffect" : jindo.m.Effect.cubicEaseOut,
            "bUseTransition" : true
        };
    },
    
    /**
     * 영역 이동 상세 함수 
     */
    _setMoveCssDetail : function(nDuration){
        var sDirect = this.option("sDirection");
        var htPosInfo = this._getPosInfo();
        this.setNavHeight(jindo.$Document().clientSize().height);
        
        this._htWElement["nav"].parent().css(jindo.m.getCssPrefix() + "Perspective", this._htWElement["nav"].width() / 2 + "px");
        
        var aData = [];
         if(sDirect == "down"){
             aData.push(this._htWElement["nav"].$value(), !this._bShow ? {
                 //"@transitionProperty" : "transform",
                    "@transform" : this._sTranslateStart +"0px, 0px" + this._sTranslateEnd + " rotate"+htPosInfo.sRotate+"(0deg)"
                } :
                {
                    //"@transitionProperty" : "transform",
                    "@transform" : this._sTranslateStart + "0px, 0px" + this._sTranslateEnd + " rotate"+htPosInfo.sRotate+"(75deg)"
                });
        }else{
            this._htTmpNavSizeInfo["height"] = this._htWElement["nav"].css("height");
            this._htWElement["nav"].css({
                "height": this._htNavSize["height"] + "px",
                "overflow" : "hidden"
            });
            this._htWElement["nav"].parent().css({
                "height": this._htNavSize["height"] + "px"
                // "overflow" : "hidden"
            });
            
            aData.push(this._htWElement["nav"].$value(), 
            !this._bShow ?  {
                //"@transitionProperty" : "transform",
                "@transform" : this._sTranslateStart +( htPosInfo.nNav.X + (sDirect == "left" ? this.option("nMargin") : 0 )) +"px, "+htPosInfo.nNav.Y+"px" + this._sTranslateEnd + " rotate"+htPosInfo.sRotate+"(0deg)"
            } :
            {
                //"@transitionProperty" : "transform",
                "@transform" : this._sTranslateStart +"0px, 0px"+ this._sTranslateEnd + " rotate"+htPosInfo.sRotate+ "(" + htPosInfo.sRotateValue + "deg)"
            });
        }
        
        aData.push(this._htWElement["header"].$value(), {
            // "@transitionProperty" : "webkit-transform",
            "@transform" : this._sTranslateStart + htPosInfo.nHeader.X+"px, "+htPosInfo.nHeader.Y+"px" + this._sTranslateEnd
        }, 
        this._htWElement["content"].$value(), {
            // "@transitionProperty" : "webkit-transform",
            "@transform" : this._sTranslateStart + htPosInfo.nContent.X+"px, "+htPosInfo.nContent.Y+"px" + this._sTranslateEnd
        });
        // this._htTransition["header"].queue(this._htWElement["header"].$value(), nDuration, {
            // htTransform : {
                // "transform" : this._sTranslateStart + htPosInfo.nHeader.X+"px, "+htPosInfo.nHeader.Y+"px" + this._sTranslateEnd
            // }
        // });
        var self = this;
        this._htMorph.pushAnimate.apply(self._htMorph, [nDuration , aData]);
    },

    /**
     * content 영역 이동 후 실행되는 함수 
     */
    _setContentPos : function(){
        // this._aEndStatus.push(true);
        // this._checkEnd();  
    },
    
    /**
     * header 영역 이동 후 실행되는 함수 
     */
    _setHeaderPos : function(){
        // this._aEndStatus.push(true);
        // this._checkEnd();
    },
    
    /**
     * navigator 영역 이동 후 실행되는 함수 
     */
    _setNavPos : function(){
        var htPosInfo = this._getPosInfo();
        var htWrapStyle = {
            "overflow" : "hidden",
            "width" : this._nWidth + "px",
            "height" : this._nNavHeight + "px",
            "position" : "relative"
        };
        
        if(this._bShow){
            this._htWElement["nav"].css(jindo.m.getCssPrefix() + "TransitionDuration" , "");
            // this._htWElement["nav"].css(this._sCssPrefix + "Transform", "rotate"+htPosInfo.sRotate+"("+htPosInfo.sRotateValue+"deg)");
            htWrapStyle = {
                "overflow" : "",
                "width" : "",
                "height" : "",
                "position" : ""
            };
        }
        
        this._htWElement["nav"].css({
            "height" : this._htTmpNavSizeInfo["height"],
            "overflow" : ""
        });
        this._htWElement["nav"].parent().css({
            "height" : this._htTmpNavSizeInfo["height"]
        });
        
        if(this._htWElement["wrap"] && this.option("sDirection") != "down"){
            this._htWElement["wrap"].css(htWrapStyle);
        }
        // this._aEndStatus.push(true);
        // this._checkEnd();
        
    }
}).extend(jindo.m.RevealCommon);