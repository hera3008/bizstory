/**
    @fileOverview 페이지 메뉴 transform 컴포넌트
    @author mania
    @version 1.9.0
    @since 2013. 4. 22
**/
/**
    페이지 메뉴 transform 컴포넌트
    @class jindo.m.SlideReveal
    @extends jindo.m.RevealCommon
    @uses jindo.m.Effect
    @uses jindo.m.Morph
    @keyword 메뉴, 햄버거 매뉴
    @group Component
    @update 

    @history 1.9.0 Update 왼쪽에서 오른쪽 방향으로 이동하는 Slide 추가
    @history 1.8.0 Release 최초 릴리즈
**/
jindo.m.SlideReveal = jindo.$Class({
    /* @lends jindo.m.SlideReveal.prototype */
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
    
    /**
     *  네비게이션, 메뉴, 컨텐츠 영역의 이동 위치를 계산하는 공통 함수
     */
    _getPosInfo : function(){
        switch (this.option("sDirection")){
            case "down" :
                return {
                    "nHeader" : {
                        "Y" : this._nNavHeight * (this._bShow ? (this.option("bUseOffsetBug") ? -1 : 0): 1),
                        "X" : 0
                    },
                    "nNav" : {
                        "Y" : this._nNavHeight * (this._bShow ? (this.option("bUseOffsetBug") ? -1 : 0) : 1),
                        "X" : 0
                    },
                    "nContent" : {
                        "Y" : this._nNavHeight * (this._bShow ? (this.option("bUseOffsetBug") ? -1 : 0): 1),
                        "X" : 0
                    },
                    
                    "nLastHeader" : this._nNavHeight * (this._bShow ? (this._bNavInHeader ? -1 : 0) : (this._bNavInHeader ? 0 : 1)),
                    "nLastNav" : this._nNavHeight * (this._bShow ? (this.option("bUseOffsetBug") ? -1 : 0) : 0),
                    "nLastContent" : this._nNavHeight * (this._bShow ? 0 : 1),

                    "nLeftPos" : 0,
                    "nNavHeight" : this._nNavHeight
                };
                break;
            case "left" : 
                return {
                    "nHeader" : {
                        "Y" : !this._bShow ? (this.option("bUseOffsetBug")? -parseInt(this._htWElement["content"].css("top") | 0 ,10) : 0) : 0,
                        "X" : (this._nWidth - this.option("nMargin")) * (this._bShow ? (this.option("bUseOffsetBug")? 1 : 0) : -1)
                    },
                    "nContent" : {
                        "Y" : !this._bShow ? (this.option("bUseOffsetBug")? -parseInt(this._htWElement["content"].css("top") | 0 ,10) : 0) : 0,
                        "X" : (this._nWidth - this.option("nMargin")) * (this._bShow ? (this.option("bUseOffsetBug")? 1 : 0) : -1)
                    },
                    "nNav" : {
                        "Y" : 0,
                        "X" : this._bShow ? (this.option("bUseOffsetBug") ? this._nWidth - this.option("nMargin") : 0 ) : (this._nWidth - this.option("nMargin")) * (this._bShow ? 0 : -1)
                    },
                    "nDefaultNavPos" : (this._nWidth - this.option("nMargin")) * (this._bShow ? 0 : 1),
                    "nMarginLeftPos" : (this._nWidth - this.option("nMargin")),
                    "nLeftPos" : !this._bShow ? this.option("nMargin") - this._nWidth : 0,
                    "nNavHeight" : 0,
                    "nNavLeftPos" : !this._bShow ? this.option("nMargin") : this._nWidth
                };
            case "right" : 
                return {
                    "nHeader" : {
                        "Y" : !this._bShow ? (this.option("bUseOffsetBug")? -parseInt(this._htWElement["content"].css("top") | 0 ,10) : 0) : 0,
                        "X" : (this._nWidth - this.option("nMargin")) * (this._bShow ? (this.option("bUseOffsetBug")? -1 : 0) : 1)
                    },
                    "nContent" : {
                        "Y" : !this._bShow ? (this.option("bUseOffsetBug")? -parseInt(this._htWElement["content"].css("top") | 0 ,10) : 0) : 0,
                        "X" : (this._nWidth - this.option("nMargin")) * (this._bShow ? (this.option("bUseOffsetBug")? -1 : 0) : 1)
                    },
                    "nNav" : {
                        "Y" : 0,
                        "X" : this._bShow ? (this.option("bUseOffsetBug") ? -this._nWidth + this.option("nMargin") : 0 ) : (this._nWidth - this.option("nMargin")) * (this._bShow ? 0 : 1)
                    },
                    "nDefaultNavPos" : (this._nWidth - this.option("nMargin")) * (this._bShow ? 0 : 1),
                    "nMarginLeftPos" : -(this._nWidth - this.option("nMargin")),
                    "nLeftPos" : !this._bShow ? this._nWidth - this.option("nMargin")  : 0,
                    "nNavHeight" : 0,
                    "nNavLeftPos" : !this._bShow ? 0 : -this._nWidth + this.option("nMargin")
                };
                
                break;
        }
    }
    
}).extend(jindo.m.RevealCommon);