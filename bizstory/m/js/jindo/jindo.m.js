/**
    @fileOverview 진도모바일 컴포넌트의 기본 네임스페이스인 동시에, static 객체이다
    @author sculove
    @version 1.9.0
    @since 2011. 11. 16
**/
/**
    진도모바일 컴포넌트의 기본 네임스페이스인 동시에, static 객체이다

    @class jindo.m
    @group Component
    @update

    @history 1.7.0 Support 갤럭시S4 대응
    @history 1.7.0 Bug ie10 msPointerEnabled 값 버그 수정
    @history 1.5.0 Update Component 의존성 제거
    @history 1.5.0 Support Window Phone8 지원
    @history 1.4.0 Support iOS 6 지원
    @history 1.2.0 Support Chrome for Android 지원<br /> 갤럭시 S2 4.0.3 업데이트 지원
    @history 1.1.0 Support Android 3.0/4.0 지원<br /> jindo 2.0.0 mobile 버전 지원
    @history 1.1.0 Update Namespace, jindo의 Namespace 하위로 지정
    @history 0.9.5 Update getTouchPosition() Method 삭제<br />
                        hasTouchEvent() Method 삭제
    @history 0.9.0 Release 최초 릴리즈
**/
if(typeof jindo.m == "undefined" && typeof Node != "undefined") {
    /**
        addEventListener된 객체를 알기위한 함수
        A태그에 click 이벤트가 bind될 경우에만 적용
    **/
    var ___Old__addEventListener___ = Node.prototype.addEventListener;
    Node.prototype.addEventListener = function(type, listener, useCapture){
            var callee = arguments.callee;
            if(callee && type === "click" && this.tagName === "A"){
                (this.___listeners___ || (this.___listeners___=[]) ).push({
                    listener : listener,
                    useCapture : useCapture
                });
            }
            return ___Old__addEventListener___.apply(this, arguments);
    };

    /**
        removeEventListener된 객체를 알기위한 함수
        A태그에 click 이벤트가 unbind될 경우에만 적용
    **/
    var ___Old__removeEventListener___ = Node.prototype.removeEventListener;
    Node.prototype.removeEventListener = function(type, listener, useCapture){
            var callee = arguments.callee;
            if(callee && type === "click" && this.tagName === "A"){
                if(this.___listeners___) {
                    this.___listeners___.pop();
                }
            }
            return ___Old__removeEventListener___.apply(this, arguments);
    };
}


var raf = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame|| window.msRequestAnimationFrame;
var caf = window.cancelAnimationFrame || window.webkitCancelAnimationFrame|| window.mozCancelAnimationFrame|| window.msCancelAnimationFrame;

if(raf&&!caf){
    var keyInfo = {};
    var oldraf = raf;
    raf = function(callback){
        function wrapCallback(){
            if(keyInfo[key]){
            callback();
            }
        }
        var key = oldraf(wrapCallback);
        keyInfo[key] = true;
        return key;
    };
    caf = function(key){
        delete keyInfo[key];
    };
    
}else if(!(raf&&caf)){
    raf = function(callback) { return window.setTimeout(callback, 16); };
    caf = window.clearTimeout;
}

window.requestAnimationFrame = raf;
window.cancelAnimationFrame = caf;
    
// window.requestAnimationFrame = (function() {
    // return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame|| window.msRequestAnimationFrame || function(callback) { return setTimeout(callback, 16); };
// })();
// 
// window.cancelAnimationFrame = (function () {
    // return window.cancelAnimationFrame || window.webkitCancelAnimationFrame|| window.mozCancelAnimationFrame|| window.msCancelAnimationFrame || clearTimeout;
// })();

jindo.m = (function() {
    var _isVertical = null,
        _nPreWidth = -1,
        _nRotateTimer = null,
        _htHandler = {},
        _htDeviceInfo = {},
        _htAddPatch = {},
        _htOsInfo = {},
        _htBrowserInfo = {},
        _htTouchEventName = {
            start : 'mousedown',
            move : 'mousemove',
            end : 'mouseup',
            cancel : null
        },
        _htDeviceList = {
            "galaxyTab" : ["SHW-M180"],
            "galaxyTab2" : ["SHW-M380"],
            "galaxyS" : ["SHW-M110"],
            "galaxyS2" : ["SHW-M250","GT-I9100"],
            "galaxyS2LTE" : ["SHV-E110"],
            "galaxyS3" : ["SHV-E210", "SHW-M440", "GT-I9300"],
            "galaxyNote" : ["SHV-E160"],
            "galaxyNote2" : ["SHV-E250"],
            "galaxyNexus" : ["Galaxy Nexus"],
            "optimusLte2" : ["LG-F160"],
            "optimusVu" : ["LG-F100"],
            "optimusLte" : ["LG-LU6200", "LG-SU640", "LG-F120K"]
        },
        _htClientSize = {
            "galaxyTab" : {
                "4" : {
                    "portrait" : 400
                },
                "default" : {
                    "portrait" : 300,
                    "landscape" : 100
                }
            },
            "galaxyTab2" : {
                "default" : {
                    "portrait" : 500,
                    "landscape" : 100
                }
            },
            "galaxyNexus" : {
                "default" : { 
                    "portrait" : 800,
                    "address" : 30,
                    "landscape" : 100
                }
            }
        };

    /**
         터치이벤트 명 정제
     */
    function _initTouchEventName() {
        if('ontouchstart' in window){
            _htTouchEventName.start = 'touchstart';
            _htTouchEventName.move  = 'touchmove';
            _htTouchEventName.end = 'touchend';
            _htTouchEventName.cancel = 'touchcancel';
        } else if(window.navigator.msPointerEnabled && window.navigator.msMaxTouchPoints > 0) {
            _htTouchEventName.start = 'MSPointerDown';
            _htTouchEventName.move  = 'MSPointerMove';
            _htTouchEventName.end = 'MSPointerUp';
            _htTouchEventName.cancel = 'MSPointerCancel';
        }
    }

    /**
         resize 이벤트 정제해서 리턴.
        @return {String} 이벤트명
        @date 2011. 11. 11
        @author sculove
     */
    function _getOrientationChangeEvt(){
        var bEvtName = 'onorientationchange' in window ? 'orientationchange' : 'resize';
        /**
         * andorid 버그
         * 2.3에서는 orientationchange 이벤트가 존재하나, orientationchange를 적용할 경우, width와 height가 바꿔서 나옴 (setTimeout 500ms 필요)
         *  : 삼성안드로이드 2.3에서는 방향전환을 resize 이벤트를 이용하여 확인할 경우,
         *    만약, 사용자가 window에 resize이벤트를 bind할 경우 브라우저가 죽는 버그가 있음
         * 2.2에서는 orientationchange 이벤트가 2번 발생함. (처음에는 width,height가 바뀌고, 두번째는 정상적으로 나옴)
         * 그 이하는 resize로 처리
         * in-app 버그
         * in-app인 경우 orientationChange발생시, width,height값이 바꿔서 나옴 (setTimeout 200ms 필요)
         */
        if( (_htOsInfo.android && _htOsInfo.version === "2.1") ) {//|| htInfo.galaxyTab2) {
            bEvtName = 'resize';
        }
        return bEvtName;
    }

    /**
        디바이스 기기의 가로,세로 여부를 판단함.
        @date 2011. 11. 11
        @author sculove
     */
    function _getVertical() {
        var bVertical = null,
            sEventType = _getOrientationChangeEvt();
        if(sEventType === "resize") {
            var screenWidth = document.documentElement.clientWidth;
            if (screenWidth < _nPreWidth) {
                bVertical = true;
            } else if (screenWidth == _nPreWidth) {
                bVertical = _isVertical;
            } else {
                bVertical = false;
            }
            _nPreWidth = screenWidth;
            // console.log("getVertical : resize로 판별 -> " + bVertical);
        } else {
            var windowOrientation = window.orientation;
            if (windowOrientation === 0 || windowOrientation == 180) {
                bVertical = true;
            } else if (windowOrientation == 90 || windowOrientation == -90) {
                bVertical = false;
            }
            // console.log("getVertical : orientationChange로 판별 -> " + bVertical);
        }
        return bVertical;
    }

    /**
        indo.m. 공통 이벤트 attach
        @date 2011. 11. 11
        @author sculove
     */
    function _attachEvent() {
       var fnOrientation = jindo.$Fn(_onOrientationChange, this).attach(window, _getOrientationChangeEvt()).attach(window, 'load');
       var fnPageShow = jindo.$Fn(_onPageshow, this).attach(window, 'pageshow');
    }

    /**
        브라우저 정보와 버전 정보를 갖는 this._htDeviceInfo를 초기화한다
        @date 2011. 11. 11
        @modify 2012.03.05 bInapp 추가
        @modify 2012.05.09 android 버전 정규식 수정
        @modify oyang2 2012.07.30 optimus 추가
        @modify oyang2 2012.09.17 단말기 정보 추가
        @author oyang2, sculove
     */
    function _initDeviceInfo() {
        var sName = navigator.userAgent;
        var ar = null;
        function f(s,h) {
            return ((h||"").indexOf(s) > -1);
        }
        _htDeviceInfo = {
            "iphone" : f('iPhone', sName),
            "ipad" : f('iPad', sName),
            "android" : f('Android', sName),
            "win" : f('Windows Phone', sName),
            "galaxyTab" : f('SHW-M180', sName),
            "galaxyTab2" : f('SHW-M380', sName),
            // "galaxyK" : f('SHW-M130K',sName),
            // "galaxyU" : f('SHW-M130L',sName),
            "galaxyS" : f('SHW-M110',sName),
            "galaxyS2" : f('SHW-M250',sName) || f('GT-I9100',sName),
            "galaxyS2LTE" : f('SHV-E110',sName),
            "galaxyS3" : f('SHV-E210',sName) || f('SHW-M440',sName) || f('GT-I9300',sName),
            "galaxyNote" : f('SHV-E160', sName),
            "galaxyNote2" : f('SHV-E250', sName),
            "galaxyNexus" : f('Galaxy Nexus', sName),
            "optimusLte2" : f('LG-F160', sName),
            "optimusVu" : f('LG-F100', sName),
            "optimusLte" : f('LG-LU6200', sName) || f('LG-SU640', sName) || f('LG-F120K', sName),
            "galaxyS4" : f('SHV-E300', sName) || f('GT-I9500', sName) || f('GT-I9505', sName) || f('SGH-M919', sName)|| f('SPH-L720', sName)|| f('SGH-I337', sName)|| f('SCH-I545', sName),
            "bChrome" : (f('CrMo',sName) || f('Chrome', sName)),
            "bSBrowser" : f('SAMSUNG', sName) && f('Chrome', sName),
            "bInapp" : false,
            "version" : "",
            "browserVersion" : ""
        };
     
        if(_htDeviceInfo.iphone || _htDeviceInfo.ipad){
            ar = sName.match(/OS\s([\d|\_]+\s)/i);
            if(ar !== null&& ar.length > 1){
                _htDeviceInfo.version = ar[1];
            }
        } else if(_htDeviceInfo.android){
            ar = sName.match(/Android\s([^\;]*)/i);
            if(ar !== null&& ar.length > 1){
                _htDeviceInfo.version = ar[1];
            }
        } else if(_htDeviceInfo.win){
            ar = sName.match(/Windows Phone\s([^\;]*)/i);
            if(ar !== null&& ar.length > 1){
                _htDeviceInfo.version = ar[1];
            }
        }
        _htDeviceInfo.version = _htDeviceInfo.version.replace(/\_/g,'.').replace(/\s/g, "");

        // browser 버전 
        if(_htDeviceInfo.bChrome) {
            ar = sName.match(/Chrome\/([^\s]*)/i);
            if(ar !== null&& ar.length > 1){
                _htDeviceInfo.browserVersion = ar[1].replace(/\_/g,'.').replace(/\s/g, "");
            }
        } else {
            _htDeviceInfo.browserVersion = _htDeviceInfo.version;
        }

        // device name 설정
        for(var x in _htDeviceInfo){
            if (typeof _htDeviceInfo[x] == "boolean" && _htDeviceInfo[x] && _htDeviceInfo.hasOwnProperty(x)) {
                if(x[0] !== "b") {
                    _htDeviceInfo.name = x;
                }
            }
        }

        //제조사 추가
        // _htDeviceInfo["samsung"] = f('GT-', sName) || f('SCH-', sName) || f('SHV-', sName)||f('SHW-', sName) ||f('SPH', sName) || f('SWT-', sName) ||f('SGH-', sName) || f("EK-", sName) || f("Galaxy Nexus", sName) || f("SAMSUNG", sName);
        // _htDeviceInfo["lg"] = f('LG-', sName);
        // _htDeviceInfo["pantech"] = f('IM-', sName);

        //inapp여부 추가.true 일경우는 확실한 inapp이며,false - 웹브라우저 혹은 알수없는 경우
        if(_htDeviceInfo.iphone || _htDeviceInfo.ipad) {
             if(!f('Safari', sName)){
                 _htDeviceInfo.bInapp = true;
             }
        }else if(_htDeviceInfo.android){
            sName = sName.toLowerCase();
            if( f('inapp', sName) || f('app', sName.replace('applewebkit',''))){
                _htDeviceInfo.bInapp = true;
            }
        }
        
        
        // 1.8.0 이전 deprecate
        // _htOsInfo = jindo.$Agent().os();
        // _htBrowserInfo = jindo.$Agent().navigator();
        _setOsInfo();
        _setBrowserInfo();
        
    }

    /**
     * os 정보 조회
     *      jindo.$Agent().os() 정보를 이용하며 info 데이터 또한 동일하다.
     *      하지만 version 정보는 jindo 2.3.0 이상부터 지원하고 있어 이를 보완하는 작업 진행 
     */    
    function _setOsInfo(){
        _htOsInfo = jindo.$Agent().os();
        _isInapp();
        _htOsInfo.version = _htOsInfo.version || _getOsVersion();
    }
    
    /**
     *  browser 정보 조회
     *      jindo.$Agent().navigator() 정보를 이용하며 info 데이터 또한 동일하다.
     *      SBrowser 정보 추가로 browser 정보에 SBrowser 정보 추가하는 함수 호출.
     */
    function _setBrowserInfo(){
        _htBrowserInfo = jindo.$Agent().navigator();
        _isSBrowser();
    }
    
    /**
     *  inapp 여부 조회
     *      _htOsInfo에 정보를 추가한다.
     *      _htOsInfo.bInapp = true | false
     */
    function _isInapp(){
        var sName = navigator.userAgent;
        if(_htOsInfo.iphone || _htOsInfo.ipad) {
            if(sName.indexOf('Safari') == -1 ){
                _htOsInfo.bInapp = true;
            }
        }else if(_htOsInfo.android){
            sName = sName.toLowerCase();
            if( sName.indexOf('inapp') != -1 || sName.replace('applewebkit','').indexOf('app') != -1){
                _htOsInfo.bInapp = true;
            }
        }
    }
    /**
     *  samsung 기기 이면서 chrome 인 경우 galaxyS4 sbrowser 여부 판단
     *      _htBrowserInfo 에 정보를 추가한다. 
     *      _htBrowserInfo.bSBrowser = true | false
     */
    function _isSBrowser(){
        _htBrowserInfo.bSBrowser = false;
        var sUserAgent = navigator.userAgent;
        var aMatchReturn = sUserAgent.match(/(SAMSUNG|Chrome)/gi) || "";
        if(aMatchReturn.length > 1){
            _htBrowserInfo.bSBrowser = true;
        }
    }
    
    /**
     *  디바이스 버전
     *  @return {String} 디바이스 버전 정보
     */
    function _getOsVersion(){
        if(!_htOsInfo.version){
            var sName = navigator.userAgent,
                sVersion = "",
                ar;
    
            if(_htOsInfo.iphone || _htOsInfo.ipad){
                ar = sName.match(/OS\s([\d|\_]+\s)/i);
                if(ar !== null&& ar.length > 1){
                    sVersion = ar[1];
                }
            } else if(_htOsInfo.android){
                ar = sName.match(/Android\s([^\;]*)/i);
                if(ar !== null&& ar.length > 1){
                    sVersion = ar[1];
                }
            } else if(_htOsInfo.mwin){
                ar = sName.match(/Windows Phone\s([^\;]*)/i);
                if(ar !== null&& ar.length > 1){
                    sVersion = ar[1];
                }
            }
            return sVersion.replace(/\_/g,'.').replace(/\s/g, "");
        }
    }

    /**
        가로,세로 변경 여부 확인
        @date 2011. 11. 11
        @author sculove
        @history 1.8.0 Update 안드로이드 orientattionChange 의 delay 값을 정수가 아닌 상태 변화에 따르도록 대응.
     */
    function _onOrientationChange(we) {
        var self = this;
        if(we.type === "load") {
            _nPreWidth = document.documentElement.clientWidth;
            /**
             * 웹 ios에서는 사이즈가 아닌 orientationChange로 확인
             * 왜? iphone인 경우, '개발자콘솔'이 설정된 경우 초기 처음 오동작
             */
            if(!_htOsInfo.bInapp && ( _htOsInfo.iphone || _htOsInfo.ipad || _getOrientationChangeEvt() !== "resize")) {    // 웹ios인 경우
                _isVertical = _getVertical();
            } else {
                if(_nPreWidth > document.documentElement.clientHeight) {
                    _isVertical = false;
                } else {
                    _isVertical = true;
                }
            }
            // console.log("Rotate init isVertical : " + this._isVertical);
            return;
        }
        if (_getOrientationChangeEvt() === "resize") { // android 2.1 이하...
            // console.log("Rotate Event is resize");
            setTimeout(function(){
                _orientationChange(we);
            }, 0);
        } else {
            var screenWidth = jindo.$Document().clientSize().width;
            var nTime = 200;
            if(_htDeviceInfo.android) {  // android 2.2이상
                if (we.type == "orientationchange" && screenWidth == _nPreWidth) {
                    setTimeout(function(){
                    _onOrientationChange(we);
                    }, 500);
                    return false;
                }
                _nPreWidth = screenWidth; 
                // nTime = 200;
            }
            clearTimeout(_nRotateTimer);
            _nRotateTimer = setTimeout(function() {
                _orientationChange(we);
            },nTime);
            //console.log("Rotate Event is orientationChange");
        }
    }

    /**
        현재 폰의 위치가 가로인지 세로인지 확인
        @date 2011. 11. 11
        @author sculove
     */
    function _orientationChange(we) {
        var nPreVertical = _isVertical;
        _isVertical = _getVertical();
        //console.log("회전 : " + nPreVertical + " -> " + this._isVertical);
        if (jindo.$Agent().navigator().mobile || jindo.$Agent().os().ipad) {
            if (nPreVertical !== _isVertical) {
                we.sType = "rotate";
                we.isVertical = _isVertical;
                _fireEvent("mobilerotate", we);
            }
        // } else {    // PC일 경우, 무조건 호출
        //     _fireEvent("mobilerotate", {
        //         isVertical: _isVertical
        //     });
        }
    }

    /**
         pageShow 이벤트
         @date 2011. 11. 11
         @author sculove
     */
    function _onPageshow(we) {
        // pageShow될 경우, 가로/세로 여부를 다시 확인
        _isVertical = _getVertical();
        we.sType = "pageShow";
        setTimeout(function() {
            _fireEvent("mobilePageshow", we);
        },300);
    }

    /**
        WebKitCSSMatrix를 이용하여 left, top 값을 추출
        @return {HashTable} top, left
     */
    function _getTranslateOffsetFromCSSMatrix(element) {
        var curTransform  = new WebKitCSSMatrix(window.getComputedStyle(element).webkitTransform);
        return {
            top : curTransform.m42,
            left : curTransform.m41
        };
    }

    function _fireEvent(sType, ht) {
        if(_htHandler[sType]) {
            for (var i=0, len=_htHandler[sType].length; i < len; i++){
                _htHandler[sType][i].call(this, ht);
            }
        }
    }

    /**
        transform에서 translate,translate3d의 left와 top 값을 추출
        @return {HashTable} top,left
     */
    function _getTranslateOffsetFromStyle(element) {
        var nTop = 0,
            nLeft = 0,
            aTemp = null,
            s = element.style[jindo.m.getCssPrefix() + "Transform"];
        if(!!s && s.length >0){
            aTemp = s.match(/translate.{0,2}\((.*)\)/);
            if(!!aTemp && aTemp.length >1){
                var a = aTemp[1].split(',');
                if(!!a && a.length >1){
                    nTop = parseInt(a[1],10);
                    nLeft = parseInt(a[0],10);
                }
            }
        }
        return {
            top : nTop,
            left : nLeft
        };
    }

    // 내부 변수 m
    var __M__ = {
        /** MOVE 타입 */
        MOVETYPE : {
            0 : 'hScroll',
            1 : 'vScroll',
            2 : 'dScroll',
            3 : 'tap',
            4 : 'longTap',
            5 : 'doubleTap',
            6 : 'pinch',
            7 : 'rotate',
            8 : 'pinch-rotate'
        },
        sVersion : "unknown",   // deprecated (jindo.m.Component.VERSION 으로 이관)

        /** @lends jindo.m.prototype */
        /**
            초기화 함수

            @constructor
            @ignore
            @static
        **/
        $init : function() {
            _initDeviceInfo();
            _initTouchEventName();
            _attachEvent();
        },

        /**
            모바일 기기 회전시, 적용할 함수를 bind 함

            @method bindRotate
            @param {Object} fHandlerToBind
            @history 1.8.0 Update 이벤트발생시 sType속성에 'rotate' 으로 표기되도록 수정
            @history 1.7.0 Bug PC일 경우, 초기 로딩시 rotate이벤트가 발생하는 문제 제거
            @history 0.9.5 Bug rotate 인식오류 문제 해결
            @date 2011. 11. 11
            @author sculove
            @example
                var f = jindo.$Fn(this.setSize, this).bind();

                jindo.m.bindRotate(f);  // bind함
                jindo.m.unbindRotate(f);    // unbind함
        **/

        bindRotate : function(fHandlerToBind) {
            var aHandler = _htHandler["mobilerotate"];
            if (typeof aHandler == 'undefined'){
                aHandler = _htHandler["mobilerotate"] = [];
            }
            aHandler.push(fHandlerToBind);
        },
        /**
            모바일 기기 회전시, 적용할 함수를 unbind 함

            @method unbindRotate
            @param {Object} fHandlerToUnbind
            @date 2011. 11. 11
            @author sculove
            @example
                var f = jindo.$Fn(this.setSize, this).bind();

                jindo.m.bindRotate(f);  // bind함
                jindo.m.unbindRotate(f);    // unbind함
        **/
        unbindRotate : function(fHandlerToUnbind) {
            var aHandler = _htHandler["mobilerotate"];
            if (aHandler) {
                for (var i = 0, fHandler; (fHandler = aHandler[i]); i++) {
                    if (fHandler === fHandlerToUnbind) {
                        aHandler.splice(i, 1);
                        break;
                    }
                }
            }
        },

        /**
            pageshow호출, 함수 bind

            @method bindPageshow
            @param {Object} fHandlerToBind
            @history 1.9.0 Update 이벤트발생시 persisted 속성 제공
            @history 1.8.0 Update 이벤트발생시 sType속성에 'pageShow' 으로 표기되도록 수정
            @history 1.8.0 Bug pageshow 이벤트 바인드되지 않는 오류 수정
            @history 0.9.5 Update Method 추가
            @author sculove
            @date 2011. 11. 11
            @example
                var f = jindo.$Fn(this.setSize, this).bind();

                jindo.m.bindPageshow(f);    // bind함
                jindo.m.unbindPageshow(f);  // unbind함
        **/
        bindPageshow : function(fHandlerToBind) {
            var aHandler = _htHandler["mobilePageshow"];
            if (typeof aHandler == 'undefined'){
                aHandler = _htHandler["mobilePageshow"] = [];
            }
            aHandler.push(fHandlerToBind);
            // this.attach("mobilePageshow", fHandlerToBind);
        },

        /**
            pageshow호출, 함수 unbind

            @method unbindPageshow
            @param {Object} fHandlerToBind
            @history 0.9.5 Update Method 추가
            @author sculove
            @date 2011. 11. 11
            @example
                var f = jindo.$Fn(this.setSize, this).bind();

                jindo.m.bindPageshow(f);    // bind함
                jindo.m.unbindPageshow(f);  // unbind함
        **/
        unbindPageshow : function(fHandlerToUnbind) {
            var aHandler = _htHandler["mobilePageshow"];
            if (aHandler) {
                for (var i = 0, fHandler; (fHandler = aHandler[i]); i++) {
                    if (fHandler === fHandlerToUnbind) {
                        aHandler.splice(i, 1);
                        break;
                    }
                }
            }
        },

        /**
            브라우저 정보와 버전 정보를 제공한다.

            @method getDeviceInfo
            @author oyang2, sculove
            @date 2011. 11. 11
            @deprecated
            @return {Object}
            @history 1.8.0 deprecated getDeviceName() 을 통해 갤럭시, 옵티머스 등의 정보는 얻을 수 있고, iphone, android, version 정보등은 $jindo.$agent() 정보로 확인한다.
            @history 1.7.0 Bug 갤럭시S4, bSBrowser, browserVersion 속성 추가
            @history 1.7.0 Bug name 잘못 나오는 오류 수정
            @history 1.7.0 Bug 갤럭시S3 해외판(GT-I9300) 갤럭시S3로 인지못하는 버그 수정
            @history 1.6.0 Bug name에 제조사 이름이 들어가는 버그 수정
            @history 1.5.0 Upate win,galaxyNote2 속성 추가
            @history 1.5.0 Upate samsung, lg 속성 추가
            @history 1.5.0 Upate pentech 속성 추가
            @history 1.4.0 Upate 단말기 정보(samsung, lg, pentech) 추가
            @history 1.3.5 Upate 단말기 속성 추가<br /> (optimusLte, optimusLte2, optimusVu)
            @history 1.2.0 Upate bChrome 속성 추가
            @history 1.1.0 Upate bInapp 속성 추가,<br /> galaxyTab2 속성 추가
            @history 0.9.5 Upate bInapp galaxyU 속성 추가<br /> galaxyS 속성 추가
            @example
                jindo.m.getDeviceInfo().iphone      //아이폰 여부
                jindo.m.getDeviceInfo().ipad        //아이패드 여부
                jindo.m.getDeviceInfo().android  //안드로이드 여부
                jindo.m.getDeviceInfo().galaxyTab   //갤럭시탭 여부
                jindo.m.getDeviceInfo().galaxyTab2  //갤럭시탭2 여부
                jindo.m.getDeviceInfo().galaxyS  //갤럭시S 여부
                jindo.m.getDeviceInfo().galaxyS2    //갤럭시S2 여부
                jindo.m.getDeviceInfo().galaxyS2LTE    //갤럭시S2 LTE 여부
                jindo.m.getDeviceInfo().galaxyNexus    //갤럭시 넥서스 LTE 여부
                jindo.m.getDeviceInfo().optimusLte2    //옵티머스 LTE2 여부
                jindo.m.getDeviceInfo().optimusVu    //옵티머스뷰 여부
                jindo.m.getDeviceInfo().optimusLte    //옵티머스 LTE 여부
                jindo.m.getDeviceInfo().version  //안드로이드, 아이폰시 버젼정보 제공
                jindo.m.getDeviceInfo().bChrome  //크롬 브라우저 여부
                jindo.m.getDeviceInfo().bInapp      //인앱여부, true- 인앱, false - 웹브라우저 혹은 알수없는 경우
                jindo.m.getDeviceInfo().win        //MS Window 인경우
                jindo.m.getDeviceInfo().pantech    //팬텍 단말기인 경우
                jindo.m.getDeviceInfo().samsung    //삼성 단말기인 경우
                jindo.m.getDeviceInfo().lg          //엘지 단말기인 경우
                jindo.m.name                        //현재 단말기기 정보제공
        **/
        getDeviceInfo : function(){
            return _htDeviceInfo;
        },

        /**
         * OS 정보 반환을 위한 함수
         * @method getOsInfo
         * 
         *  @history 1.8.0 Update jindo.m 에서 Agent 체크 부분을 jindo.$Agent().os() 로 이관. jindo.$Agent().os() 참고 
         */
        getOsInfo : function(){
            return _htOsInfo;
        },
        
        /**
         * 브라우저 정보 반환을 위한 함수
         * @method getBrowserInfo
         * 
         * @history 1.8.0 Update jindo.m 에서 Agent 체크 부분을 jindo.$Agent().navigator() 로 이관, jindo.$Agent().navigator() 참고   
         */
        getBrowserInfo : function(){
            return _htBrowserInfo;
        },

         /**
            현재 모바일기기의 가로,세로 여부를 반환한다.

            @method isVertical
            @author sculove
            @history 1.9.0 Bug pageShow되었을 경우, 가로/세로 여부의 기존 정보를 유지하는 버그
            @history 1.3.0 Bug 페이지 캐쉬될 경우, rotate 값이 갱신되지 않는 버그 수정
            @history 1.1.0 Update 초기 로드시 가로일경우 값이 제대로 나오지 않는 문제 해결
            @example
                jindo.m.isVertical; // 수직여부 반환
            
        **/
        isVertical : function() {
            if(_isVertical === null) {
                return _getVertical();
            } else {
                return _isVertical;
            }
        },

        /**
            TextNode를 제외한 상위노드를 반환한다.

            @return {HTMLElement} el
            @date 2011. 11. 11
            @method getNodeElement
            @deprecated
            @history 1.5.0 Update deprecated
            @history 0.9.5 Update Method 추가
            @author oyang2
            @example
                var elParent=jindo.m.getNodeElement(el); // TextNode를 제외한 상위노드를 반환한다.
        **/
        getNodeElement : function(el){
            while(el.nodeType != 1){
                el = el.parentNode;
            }
            return el;
        },

        /**
            현재 Element의 offet을 구한다.

            @method getTranslateOffset
            @date 2011. 11. 11
            @author sculove
            @param {jindo.$Element|HTMLElement} element  ComputedStyle 값을 이용하여 offset을 얻는 함수
            @return {Object} {top,left}
            @history 1.8.0 Update getCssOffset -> getTranslateOffset 으로 변경
            @history 1.1.0 Update 웹킷 이외의 브라우저도 처리 가능하도록 기능 개선
            @example
                var oObject=jindo.m.getTranslateOffset(el); // CSSOffset을 반환한다.
        **/
        getTranslateOffset : function(wel){
            wel = jindo.$Element(wel);

            var element = wel.$value(),
                htOffset;
            /** Andorid 3.0대에서는 WebKitCSSMatrix가 있지만, 안됨. 버그 */
            if(_htOsInfo.android && parseInt(_htOsInfo.version,10) === 3) {
               htOffset = _getTranslateOffsetFromStyle(element);
            } else {
               if('WebKitCSSMatrix' in window && 'm11' in new WebKitCSSMatrix()){
                  htOffset = _getTranslateOffsetFromCSSMatrix(element);
               } else {
                  htOffset = _getTranslateOffsetFromStyle(element);
               }
            }
            return htOffset;
        },


        /**
            Style의 left,top을 반환함
            @date 2013. 5. 10
            @author sculove
            @method getStyleOffset
            @history 1.8.0 Update Method 추가
            @param {jindo.$Element} wel
            @return {Object} {top,left}
        **/
        getStyleOffset : function(wel) {
            var nLeft = parseInt(wel.css("left"),10),
              nTop = parseInt(wel.css("top"),10);
            nLeft = isNaN(nLeft) ? 0 : nLeft;
            nTop = isNaN(nTop) ? 0 : nTop;
            return {
              left : nLeft,
              top : nTop
            };
        },
        /**
            TransitionEnd 이벤트 bind

            @method attachTransitionEnd
            @author sculove, oyang2
            @date 2011. 11. 11
            @param {HTMLElement} element attach할 엘리먼트
            @param {Function} fHandlerToBind attach할 함수
            @example
                jindo.m.attachTransitionEnd(el, function() { alert("attach"); }); // el에 transitionEnd 이벤트를 attach한다.
                jindo.m.detachTransitionEnd(el, function() { alert("detach"); }); // el에 transitionEnd 이벤트를 detach한다.

        **/
        attachTransitionEnd : function(element,fHandlerToBind) {
            var nVersion = + jindo.$Jindo().version.replace(/[a-z.]/gi,"");
            // console.log(nVersion);
            /* 진도 1.5.1에서 정상 동작. 그 이하버젼은 버그 */
            if(nVersion > 230) {   // jindo
                element._jindo_fn_ = jindo.$Fn(fHandlerToBind,this).attach(element, "transitionend");
            } else {
                var sEvent = ((this.getCssPrefix() === "ms")? "MS": this.getCssPrefix()) + "TransitionEnd";
                element.addEventListener(sEvent, fHandlerToBind, false);
            }
        },

        /**
            TransitionEnd 이벤트 unbind

            @method detachTransitionEnd
            @date 2011. 11. 11
            @author sculove, oyang2
            @param {HTMLElement} element dettach할 엘리먼트
            @param {Function} fHandlerToUnbind dettach할 함수
            @example
                jindo.m.attachTransitionEnd(el, function() { alert("attach"); }); // el에 transitionEnd 이벤트를 attach한다.
                jindo.m.detachTransitionEnd(el, function() { alert("detach"); }); // el에 transitionEnd 이벤트를 detach한다.
        **/
        detachTransitionEnd : function(element, fHandlerToUnbind) {
            var nVersion = + jindo.$Jindo().version.replace(/[a-z.]/gi,"");
            // console.log(nVersion);
            /* 진도 1.5.1에서 정상 동작. 그 이하버젼은 버그 */
            if(nVersion > 230) {   // jindo
                if(element._jindo_fn_) {
                    element._jindo_fn_.detach(element, "transitionend");
                    delete element._jindo_fn_;
                }
            } else {
                var sEvent = ((this.getCssPrefix() === "ms")? "MS": this.getCssPrefix()) + "TransitionEnd";
                element.removeEventListener(sEvent, fHandlerToUnbind, false);
            }
        },

        /**
             MSPointerEvent 처럼 신규 이벤트들이 2.3.0이하 진도에서 attach안되는 문제를 해결하기 위한 코드
            jindo 2.4.0 이상 버전에서는 사용가능, 하위 버전에서는 _notSupport namespace  진도 사용
            @date 2012. 12.06
            @author oyang2
            @example
            jindo.m._attachFakeJindo(el, function(){alert('MSPointerDown'), 'MSPointerDown' });a
         */
        _attachFakeJindo : function(element, fn, sEvent){
            var nVersion = + jindo.$Jindo().version.replace(/[a-z.]/gi,"");
            var wfn = null;
            if(nVersion < 230 && (typeof _notSupport !== 'undefined')) {
                //use namespace jindo
                wfn = _notSupport.$Fn(fn).attach(element, sEvent);
            }else{
                //use jindo
                wfn = jindo.$Fn(fn).attach(element, sEvent);
            }
            return wfn;
        },

        /**
            브라우저별 대처 가능한 이벤트명을 리턴한다.
            @date 2012. 12.06
            @author oyang2
            @example
            jindo.m._getTouchEventName();
         */
        _getTouchEventName : function(){
            return  _htTouchEventName;
        },

        /**
            브라우저 CssPrefix를 얻는 함수

            @method getCssPrefix
            @author sculove
            @date 2011. 11. 11
            @return {String} return cssPrefix를 반환. webkit, Moz, O,...
            @history 0.9.5 Update Method 추가
            @example
                jindo.m.getCssPrefix(); // 브라우저별 prefix를 반환한다.
        **/
        getCssPrefix : function() {
            var sCssPrefix = "";
            if(typeof document.body.style.MozTransition !== "undefined") {
                sCssPrefix = "Moz";
            } else if(typeof document.body.style.OTransition !== "undefined") {
                sCssPrefix = "O";
            } else if(typeof document.body.style.msTransition !== 'undefined'){
                sCssPrefix = "ms";
            } else {
                sCssPrefix = "webkit";
            }
            return sCssPrefix;
        },


        /**
            자신을 포함하여 부모노드중에 셀렉터에 해당하는 가장 가까운 엘리먼트를 구함

            @method getClosest
            @date 2012. 02. 20
            @author sculove
            @param {String} sSelector CSS클래스명 또는 태그명
            @param {HTMLElement} elBaseElement 기준이 되는 엘리먼트
            @return {HTMLElement} 구해진 HTMLElement
            @history 1.1.0 Update Method 추가
            @example
                jindo.m.getClosest("cssName", elParent);   // elParent하위에 cssName 클래스명이 아닌 첫번째 Element를 반환한다.
        **/
        getClosest : function(sSelector, elBaseElement) {
            //console.log("[_getClosest]", sSelector, elBaseElement)
            var elClosest;
            var welBaseElement = jindo.$Element(elBaseElement);

            var reg = /<\/?(?:h[1-5]|[a-z]+(?:\:[a-z]+)?)[^>]*>/ig;
            if (reg.test(sSelector)) {
                // 태그 일경우
                 if("<" + elBaseElement.tagName.toUpperCase() + ">" == sSelector.toUpperCase()) {
                     elClosest = elBaseElement;
                 } else {
                     elClosest = welBaseElement.parent(function(v){
                         if("<" + v.$value().tagName.toUpperCase() + ">" == sSelector.toUpperCase()) {
                            //console.log("v", v)
                            return v;
                        }
                    });
                    elClosest = elClosest.length ? elClosest[0].$value() : false;
                 }
            } else {
                //클래스명일 경우
                 if(sSelector.indexOf('.') == 0) { sSelector = sSelector.substring(1,sSelector.length); }
                 if(welBaseElement.hasClass(sSelector)) {
                    elClosest = elBaseElement;
                 } else {
                    elClosest = welBaseElement.parent(function(v){
                        if(v.hasClass(sSelector)) {
                            //console.log("v", v)
                            return v;
                        }
                    });
                    elClosest = elClosest.length ? elClosest[0].$value() : false;
                }
            }
            //console.log("elClosest", elClosest)
            return elClosest;
        },

        /**
            CSS3d를 사용할수 있는 기기 값 불린 반환.
            @method useCss3d
            @param {Boolean} flicking 에서 사용하는지 여부
            @return {Boolean} CSS3d를 사용할 수 있는 기기일 경우 true를 반환
            @since 2012. 6. 22
            @history 1.8.0 Update 사용자가 패치하여 사용할 수 있도록 사용자 인터페이스 제공
            @history 1.7.0 Update Method 추가
            @history 1.7.0 Update 안드로이드 4.1이상에서는 CSS3d가속을 사용하도록 변경 (안드로이드 4.1부터는 BlackList 기반)<br/>
            네이버 메인 호환 장비 추가 등록
        **/
        useCss3d : function(isFlicking) {
            if(isFlicking === undefined){
                isFlicking = false;
            }

            if(_htAddPatch.useCss3d && typeof _htAddPatch.useCss3d == "function"){
                switch (_htAddPatch.useCss3d()){
                    case -1 :
                        return false;
                    case 1 :
                        return true;
                }
            }

            var bRet = false;
            // 크롬일 경우, false처리 (why? 크롬은 글짜가 약간 틀어져 보임. 속도상도 css3d적용 전후와 크게 차이가 나지 않음)
            // 크롬 25이상일 경우에는 글짜가 blur되는 버그가 수정됨.
            // 또한 삼섬 SBrowser에서도 이러한 문제가 수정됨.
            if(_htBrowserInfo.chrome && _htBrowserInfo.version < "25" && !_htBrowserInfo.bSBrowser) {
                return bRet;
            }
            if(_htOsInfo.iphone || _htOsInfo.ipad) {
                bRet = true;
            } else if(_htOsInfo.android){
                if(_htOsInfo.version >= "4.1.0") {
                    // 안드로이드 젤리빈 이상은 BlackList 기반으로 관리
                    bRet = true;
                } else {
                    var s = navigator.userAgent.match(/\(.*\)/)[0];
                    // if(!isFlicking){
                    //     isEtc = isEtc || f('SHV-E110', s);    // 갤s2 LTE 제외
                    // }
                    if(_htOsInfo.version >= "4.0.3" && 
                        /SHW-|SHV-|GT-|SCH-|SGH-|SPH-|LG-F160|LG-F100|LG-F180|LG-F200|EK-|IM-A|LG-F240|LG-F260/.test(s) &&
                        !/SHW-M250|SHW-M420|SHW-M200|GT-S7562/.test(s)) {
                        bRet = true;
                    } 
                }
            }
            return bRet;
        },

        /**
         *  jindo.m 을 사용자가 특정 인터페이스를 통해 사용할 수 있도록 제공하기 위한 패치 버전 정의<br />
         * 입력한 버전보다 하위 JMC 에 대해서만 적용되며 상위 JMC 에 대해서는 적용되지 않는다.
         *  @method patch
         *  
         *  @param {String} ver     패치를 위한 패치 버전 정보 
         *  @history 1.8.0 Update   디바이스 정보를 사용자가 추가 및 업데이트 등을 위한 패치 함수 제공 
         */
        patch : function(ver){
            _htAddPatch.ver = ver;
            return this;
        },

        /**
         *  컴포넌트의 버전과 패치 버전을 비교하여 등록 여부 결정
         *  @return {Boolean}   버전을 비교하여 패치가 가능하다면 true, 아니면 false
         */
        _checkPatchVersion : function(){
            if(_htAddPatch.ver >= jindo.m.Component.VERSION){
                return true;
            }
            return false;
        },
     
        /**
         *  jindo.m 패치 인터페이스
         * @method add
         * 
         *  @paran {HashTable}  htOption    패치하고자 하는 {함수명, 함수} 형태로 정의
         * @history 1.8.0 Update   디바이스 정보를 사용자가 추가 및 업데이트 등을 위한 add 함수 제공
         *  @example
                jindo.m.patch("1.7.0").add({
                    "useCss3d" : function(){
                        if(jindo.$Agent().os().android){
                            return 1;   // true
                        }
                        else if(jindo.$Agent().os().ios){
                            return -1;  // false
                        }else{
                            return 0;   // continue
                        }
                    },
                    "useTimingFunction" : function(){
                        if(jindo.$Agent().os().android){
                            return 1;   // true
                        }
                        else if(jindo.$Agent().os().ios){
                            return -1;  // false
                        }else{
                            return 0;   // continue
                        }
                    },
                    "hasClickBug" : function(){
                        if(jindo.$Agent().os().android){
                            return 1;   // true
                        }
                        else if(jindo.$Agent().os().ios){
                            return -1;  // false
                        }else{
                            return 0;   // continue
                        }
                    },
                    "getDeviceName" : function(){
                        if(navigator.userAgent.indexOf("Galaxy Nexus") > -1){
                            return "galaxyNexus";
                        }
                    },
                    "useFixed" : function(){
                        if(jindo.$Agent().os().android){
                            return 1;   // true
                        }
                        else if(jindo.$Agent().os().ios){
                            return -1;  // false
                        }else{
                            return 0;   // continue
                        }
                    }
                })
         */
        add : function(htOption){
            if(this._checkPatchVersion()){
                for ( var i in htOption){
                    _htAddPatch[i] = htOption[i];
                }
            }
            return this;
        },

        /**
         *  디바이스 이름(galaxyS, optimusLTE 등..) 정보 반환
         *  @method getDeviceName 
         * 
         *  @return {String}    디바이스 이름 - _htDeviceList에 정의되어 있는 key가 name 이 되어 반환된다.
         *                              (디바이스 이름이 존재하지 않으면 iphone, ipad, android 등의 정보 반환한다.)
         *  @history 1.8.0 Update   디바이스 이름 정보 반환을 위한 함수 추가     
         */
        getDeviceName : function(){
            if(_htAddPatch.getDeviceName && typeof _htAddPatch.getDeviceName == "function"){ 
                if(_htAddPatch.getDeviceName()){
                    return _htAddPatch.getDeviceName();
                }
            }
            var sUserAgent = navigator.userAgent;
            for (var i in _htDeviceList){
                if(eval("/" + _htDeviceList[i].join("|") + "/").test(sUserAgent)){
                    // _htDeviceInfo[i] = true;
                    return i;
                    break;
                }
            }

            // 아무런 정보도 넘어오지 않았을때 iphone, ipad, android 여부 리턴.
            var htInfo = jindo.$Agent().os();
            for ( var x in htInfo){
                if(htInfo[x] === true && htInfo.hasOwnProperty(x)){
                    return x;
                    break;
                }
            }
        },
        
        /**
            fixed  속성을 지원하는지 확인하는 함수
            @method useFixed
            @since 2012. 6. 22
            @return {Boolean} isFixed
            @history 1.8.0 Update 사용자가 패치하여 사용할 수 있도록 사용자 인터페이스 제공
            @history 1.7.0 Update Method 추가
            @remark
                1. ios
                - ios5 (scrollTo가 발생된 경우 랜더링 되지 않는 버그)
                2. android
                - 3.x 부터 지원함 (그전에도 지원했지만, 하이라이트 적용문제로 처리할 수 없음)
                scroll, flicking과 함께 사용할 경우, 깜빡거림
        **/
        useFixed : function() {
            
            if(_htAddPatch.useFixed && typeof _htAddPatch.useFixed == "function"){
                switch (_htAddPatch.useFixed()){
                    case -1 :
                        return false;
                    case 1 :
                        return true;
                }
            }
            
            var isFixed = false;
            if(_htBrowserInfo.chrome ||
               (_htOsInfo.android && parseInt(_htOsInfo.version,10) >= 3) ||
               ((_htOsInfo.iphone || _htOsInfo.ipad) && (parseInt(_htOsInfo.version,10) >= 5)) ||
               (_htOsInfo.mwin && parseInt(_htOsInfo.version,10) >= 8)) {
                isFixed = true;
            }
            return isFixed;
        },



        /**
            TimingFunction를 사용할수 있는 기기 값 불린 반환.
            @method useTimingFunction
            @since 2012. 6. 30
            @history 1.8.0 Update 사용자가 패치하여 사용할 수 있도록 사용자 인터페이스 제공
            @history 1.7.1 Bug iOS6.0일 경우에만, timingFunction=false되도록 수정
            @history 1.7.0 Update Method 추가
            @param {Boolean} bUseFlicking flicking 컴포넌트에서 사용여부
            @return {Boolean} TimingFunction를 사용할 수 있는 기기일 경우 true를 반환
        **/
        useTimingFunction : function(bUseFilcking) {
            
            if(_htAddPatch.useTimingFunction && typeof _htAddPatch.useTimingFunction == "function"  && _htAddPatch.useTimingFunction()){
                switch (_htAddPatch.useTimingFunction()){
                    case -1 :
                        return false;
                    case 1 :
                        return true;
                }
            }
            
            if(typeof bUseFilcking === 'undefined'){
                bUseFilcking = false;
            }
            var bUse = this.useCss3d();
            if(_htDeviceInfo.android || 
                ( (_htDeviceInfo.iphone || _htDeviceInfo.ipad) && /^6.0/.test(_htDeviceInfo.version) )
                ) {
                bUse = false;
            }
            return bUse;
        },

        /**
            RequestAnimationFrame를 사용할수 있는 기기 값 불린 반환.
            @since 2013. 6. 20
            @return {Boolean} RequestAnimationFrame를 사용할 수 있는 기기일 경우 true를 반환
        **/
        // useRequestAnimationFrame : function() {
        //     var htOs = jindo.m.getOsInfo(),
        //         bResult = true;
        //     if(htOs.android) {
        //       if(htOs.version < "4.0") {
        //         bResult = false;
        //       }
        //     }
        //     return bResult;
        // },

        /**
         *  브라우저 사이즈 정보 반환
         *  _htClientSize 변수에 정의되어 있는 값을 참조로 리턴한다.
         *  @return {HashTable} adress , 브라우저 height  값을 반환한다. 
         */
        // getClientHeight : function(){
            // var sDeviceName = this.getDeviceName();
            // var sWay = this.isVertical() ? "portrait" : "landscape";
            // var htRet = {};
// 
             // if(_htAddPatch.getClientHeight && typeof _htAddPatch.getClientHeight == "function"){
                // if(htRet = _htAddPatch.getClientHeight({
                    // "sDeviceName" : sDeviceName,
                    // "sVersion" : _htOsInfo.version
                // })){
                    // return htRet;
                // }
            // }
// 
            // var aSearch = [sDeviceName, _htOsInfo.version, sWay];
            // htRet = jindo.$Document().clientSize();
// 
            // function getDefault(htData, oRet){
                // if(htData.hasOwnProperty("default")){
                    // htRet.address = htData["default"]["address"] || htRet.address;
                    // htRet.height = htData["default"][sWay] || htRet.height;
                    // // return htData["default"][sWay];
                // }
            // }
            // var htTmpData = _htClientSize;
            // if(sDeviceName){
                // for ( var i = 0 , nFor = aSearch.length ; i < nFor ; i++ ){
                    // if(htTmpData.hasOwnProperty(aSearch[i])){
                        // htTmpData = htTmpData[aSearch[i]];
                        // htRet.height = htTmpData[sWay] || htRet.height;
                        // htRet.address = htTmpData.address || htRet.address;
                    // }else{
                        // getDefault(htTmpData, htRet);
                        // break;
                    // }
                // }
            // }
            // return htRet;
        // },

        /**
            디바이스 화면 사이즈를 반환 (viewport가 device-width 속성으로 지정되었을때의 크기)

            @since 2012. 6. 22
            @param {Boolean} isMinSize
            @return {Object} width, height
        **/
        _clientSize : function(isMinSize) {
            if(typeof isMinSize === 'undefined'){
                isMinSize = false;
            }
            var oSize = {};
            var oRet = jindo.$Document().clientSize();
            var nVersion = parseInt(_htOsInfo.version,10);

            if( (_htOsInfo.ipad || _htOsInfo.iphone) || _htBrowserInfo.chrome) {
                if(isMinSize && _htOsInfo.iphone) {
                    oRet.height = this.isVertical()? 356 : 268;
                }
                return oRet;
            }

            switch(_htDeviceInfo.name){
                case "galaxyTab"    : oSize = { portrait : 400,  landscape : 683 };
                    oSize.landscape -= 92;
                    oSize.portrait -= 66;
                    break;
                case "galaxyTab2"   : oSize = { portrait : 1280,  landscape : 800 };
                    oSize.landscape -= 152;
                    oSize.portrait -= 152;
                    break;
                case "galaxyS"      : oSize = { portrait : 320,  landscape : 533 };
                    oSize.landscape -= 81;  // android 2.2/2.3
                    oSize.portrait -= 81;
                    break;
                case "galaxyS2LTE"  :
                case "galaxyS2"     : oSize = { portrait : 320,  landscape : 533 };
                    if(nVersion==4) {
                        oSize.landscape -= 77;
                        oSize.portrait -= 77;
                    } else {
                        oSize.landscape -= 83;
                        oSize.portrait -= 83;
                    }
                    break;
                case "galaxyS3"     : oSize = { portrait : 360,  landscape : 640 };
                    oSize.landscape -= 73;
                    oSize.portrait -= 73;
                    break;
                case "galaxyNote"   :
                case "galaxyNote2"   : oSize = { portrait : 400,  landscape : 640 };
                    if(nVersion==4) {
                        oSize.landscape -= 77;
                        oSize.portrait -= 77;
                    } else {
                        oSize.landscape -= 103;
                        oSize.portrait -= 103;
                    }
                    break;
                case "galaxyNexus"  : oSize = { portrait : 360,  landscape : 598 };
                    oSize.landscape -= 83;
                    oSize.portrait -= 83;
                    break;
                case "optimusLte" : oSize = { portrait : 360,  landscape : 640 };
                    oSize.landscape -= 73;
                    oSize.portrait -= 73;
                    break;
                case "optimusLte2" : oSize = { portrait : 360,  landscape : 640 };
                    oSize.landscape -= 73;
                    oSize.portrait -= 73;
                    break;
                case "optimusVu" : oSize = { portrait : 439,  landscape : 585 };
                    oSize.landscape -= 73;
                    oSize.portrait -= 73;
                    break;
            }
            if(this.isVertical()) {
                if(isMinSize || (oSize.landscape && oSize.landscape > oRet.height)) {
                    oRet.height = oSize.landscape;
                }
            } else {
                if(isMinSize || (oSize.portrait && oSize.portrait > oRet.height)) {
                    oRet.height = oSize.portrait;
                }
            }
            return oRet;
        },

        /**
            기기별 주소창 높이를 구한다.
            @author oyang2
            @return {Number} nHeight
         */
        _getAdressSize : function(){
            var nSize = 0;
            if(_htOsInfo.bInapp){
                return nSize;
            }
             var nVersion = parseInt(_htOsInfo.version,10);
            if( _htOsInfo.iphone){
                nSize = 60;
            }else if(_htOsInfo.android){
                switch(_htOsInfo.name ){
                    case "galaxyTab"    :
                        nSize = 66;
                        break;
                    case "galaxyTab2"   :
                        nSize = 48;
                        break;
                    case "galaxyS"      :
                        nSize = 56;  // android 2.2/2.3
                        break;
                    case "galaxyS2LTE"  :
                    case "galaxyS2"     :
                        if(nVersion==4) {
                            nSize = 52;
                        } else {
                           nSize = 58;
                        }
                        break;
                    case "galaxyS3"     :
                        nSize  = 48;
                        break;
                    case "galaxyNote"   :
                    case "galaxyNote2"   :
                        if(nVersion==4) {
                            nSize = 52;
                        } else {
                            nSize = 78;
                        }
                        break;
                    case "galaxyNexus"  :
                        nSize = 52;
                        break;
                    case "optimusVu" :  //lg ics는 모두 48인
                    case "optimusLte" :
                    case "optimusLte2" :
                        nSize = 48;
                        break;
                 }
            }
            return nSize;
        },

        /**
            엘리먼트 offset 변경 이후, 하이라이트/롱탭/클릭 이 기존 offset에서 발생하는 버그를 가지고 있는 지 판단
            @date 2013.05.10
            @method hasOffsetBug
            @return {Boolean}
            @author sculove
            @history 1.8.0 Update Method 추가
         */
        hasOffsetBug : function() {
            return _htOsInfo.android && !_htBrowserInfo.chrome && _htOsInfo.version < "4";
        },

        /**
            터치이벤트에 따라 엘리먼트 애니메이션 진행후 클릭되는 이슈를 가진 브라우저인지 판단
            @date 2012.11.05
            @method hasClickBug
            @return {Boolean}
            @author sculove
            @history 1.9.0 Bug Window8 IE10 확인 모듈 수정
            @history 1.8.0 Update Method 추가
         */
        hasClickBug : function(){
            if(_htAddPatch.hasClickBug && typeof _htAddPatch.hasClickBug == "function"){
                switch (_htAddPatch.hasClickBug()){
                    case -1 :
                        return false;
                    case 1 :
                        return true;
                }
            }
            
            // (_htOsInfo.mwin && ((_htOsInfo.version *1) >= 8)
            return ( _htOsInfo.iphone || _htOsInfo.ipad || (window.navigator.msPointerEnabled && window.navigator.msMaxTouchPoints > 0) || false );
        }
    };
    __M__._isUseFixed = __M__.useFixed;
    __M__._isUseTimingFunction = __M__.useTimingFunction;
    __M__._isUseCss3d = __M__.useCss3d;
    __M__.getCssOffset = __M__.getTranslateOffset;
    __M__.$init();
    return __M__;
})();