/*jshint eqeqeq: false, white : false, sub: true, undef: true, evil : true, browser: true, -W099 : true, -W014 : true, -W041 : true, -W083 : true, -W027 : true */

/**
    순차적으로 이루어지는 애니메이션 컴포넌트

    @author hooriza
    @version #__VERSION__#
    
    @class jindo.m.Morph
    @extends jindo.m.Component
    @uses jindo.m.Effect
    @keyword 애니메이션, animation, transition
    @group Component
    @new

    @history 1.9.0 최초 릴리즈
**/
jindo.m.Morph = jindo.$Class({

    /**
        컴포넌트 생성
        @constructor

        @param {Hash} [oOptions] 옵션
            @param {Function} [oOptions.fEffect=jindo.m.Effect.linear] 애니메이션에 사용되는 jindo.m.Effect 의 함수들
            @param {Boolean} [oOptions.bUseTransition=true] CSS Transition 를 사용할지 여부, 사용할꺼면 true, 아니면 false
    **/
    $init : function(oOptions) {

        this.option({
            'fEffect' : jindo.m.Effect.linear,
            'bUseTransition' : true
        }).option(oOptions);

        var oStyle = document.body.style;

        this._bTransitionSupport = (
            'transition' in oStyle ||
            'webkitTransition' in oStyle ||
            'MozTransition' in oStyle ||
            'OTransition' in oStyle ||
            'msTransition' in oStyle
        );

        this._aQueue = [];
        this._aIngItem = null;

        this._oTimer = null;
        this._bPlaying = null;
        this._bReservePause = false;
        this._nPtr = 0;
        this._nPausePassed = 0;
        this._aRepeat = [];

        this._sTransitionEnd = (
            ('webkitTransition' in oStyle && 'webkitTransitionEnd') ||
            ('transition' in oStyle && 'transitionend') ||
            ('MozTransition' in oStyle && 'transitionend') ||
            ('OTransition' in oStyle && 'oTransitionEnd') ||
            ('msTransition' in oStyle && 'MSTransitionEnd')
        );

    },

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    /// PUSH 메서드군
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    /**
        애니메이션 동작을 재생목록에 넣음
        
        @method pushAnimate
        
        @param {Number} nDuration 변경 할일을 몇 ms 동안 진행되게 할지
        @param {Array} aLists 애니메이션을 진행 할 객체와 속성 목록
            @param {Object} aLists.0 어떤 객체에 대해서 진행 할지
            @param {Hash} aLists.1 어떤 속성들을 변경 할지
            @param {Object} [aLists.2] 어떤 객체에 대해서 진행 할지 (2)
            @param {Hash} [aLists.3] 어떤 속성들을 변경 할지 (2)
            @param {Object} [aLists.4] 어떤 객체에 대해서 진행 할지 (3)
            @param {Hash} [aLists.5] 어떤 속성들을 변경 할지 (3)
            ...
        
        @return {this}
        
        @example
            oMorph.pushAnimate(3000, [
                elFoo, {
                    '@left' : '300px',
                    'scrollTop' : 100
                },
                elBar, {
                    'scrollLeft' : 500,
                    '@backgroundColor' : '#f00'
                }
            ]).play();
    **/
    pushAnimate : function(nDuration, aLists) {

        if (aLists && !(aLists instanceof Array)) { throw Error('aLists should be a instance of Array'); }

        aLists = [].concat(aLists);
        aLists.duration = nDuration;

        this._aQueue.push(aLists);

        return this;

    },

    /**
        일정시간 또는 다른 jindo.m.Morph 의 애니메이션이 끝나기를 기다림
        
        @method pushWait
        @param {Number|jindo.m.Morph} vItem 기다리게 할 ms 단위의 시간 또는 다른 애니메이션 객체
        @param {Number|jindo.m.Morph} [vItem2] 기다리게 할 ms 단위의 시간 또는 다른 애니메이션 객체 (2)
        @param {Number|jindo.m.Morph} [vItem3] 기다리게 할 ms 단위의 시간 또는 다른 애니메이션 객체 (3)
        ...
        
        @example
            oMorph
            .pushWait(3000) // 3초 기다리기
            .pushWait(oOtherMorph); // 다른 morph 객체가 끝날때까지 기다리기

        @return {this}
    **/
    pushWait : function(nDuration) {

        var oMorph;

        for (var i = 0, nLen = arguments.length; i < nLen; i++) {

            var vItem = arguments[i];

            if (vItem instanceof this.constructor) {
                this._aQueue.push(vItem);
            } else {
                this.pushAnimate(vItem, []);
            }

        }

        return this;

    },

    /**
        실행 할 함수를 재생목록에 넣음
        
        @method pushCall
        @param {Function} fpCallback 순서가 되었을 때 실행 할 함수
        
        @return {this}
        
        @example
            oMorph.pushCall(function() {
                alert('애니메이션이 시작될꺼임');
            }).pushAnimate(3000,
                elFoo, {
                    '@left' : '300px',
                    'scrollTop' : 100
                }
            ).pushCall(function() {
                alert('애니메이션이 끝났음');
            }).play();
    **/
    pushCall : function(fpCallback) {
        this._aQueue.push(fpCallback);
        return this;
    },

    /**
        반복 구역 시작 지점을 재생목록에 넣음
        
        @method pushRepeatStart
        @param {Number} [nTimes=1] 몇 번 반복할껀지 (무한반복할꺼면 Infinity 를 지정)
        @return {this}
    **/
    pushRepeatStart : function(nTimes) {

        if (typeof nTimes === 'undefined') { nTimes = 1; }

        var sLabel = 'L' + Math.round(new Date().getTime() * Math.random());
        this._aRepeat.push(sLabel);

        this._pushLabel(sLabel, nTimes);
        return this;

    },

    /**
        goto 명령으로 이동하는데 사용되는 라벨을 재생목록에 넣음
        
        @ignore
        @method _pushLabel
        @param {String} sLabel 라벨명
        @return {this}
    **/
    _pushLabel : function(sLabel, nTimes) {

        if (typeof nTimes === 'undefined') { nTimes = Infinity; }
        this._aQueue.push({ action : 'label', args : { label : sLabel, times : nTimes } });

        return this;

    },

    /**
        반복 구역 종료 지점을 재생목록에 넣음
        
        @method pushRepeatEnd
        @return {this}
    **/
    pushRepeatEnd : function() {

        var self = this;
        var sLabel = this._aRepeat.pop();

        var fpLoop = function() {

            var nIndex = self._getLabelIndex(sLabel);
            if (nIndex === -1) throw 'Repeat calls don\'t matched.';

            var aLabelItem = this._aQueue[nIndex];
            aLabelItem.args.count = aLabelItem.args.count || 0;

            if (++aLabelItem.args.count < aLabelItem.args.times) {
                self._goto(nIndex + 1);
            }

        };

        fpLoop.__repeat_end = sLabel;
        this.pushCall(fpLoop);

        return this;

    },

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    /// flow 동작 구현
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    _waitMorph : function(oMorph) {

        var self = this;

        if (!oMorph.isPlaying()) {
            return true;
        }

        var fHandler = function() {
            oMorph.detach('end', fHandler).detach('pause', fHandler);
            self._flushQueue();
        };

        oMorph.attach('end', fHandler).attach('pause', fHandler);

        return false;

    },

    _getLabelIndex : function(sLabel) {

        var aItem = null;

        for (var i = 0, nLen = this._aQueue.length; i < nLen; i++) {
            aItem = this._aQueue[i];
            if (aItem.action === 'label' && aItem.args.label === sLabel) { return i; }
        }

        return -1;

    },

    _getRepeatEndIndex : function(sLabel, nFrom) {

        var aItem = null;

        for (var i = nFrom || 0, nLen = this._aQueue.length; i < nLen; i++) {
            aItem = this._aQueue[i];
            if (aItem instanceof Function && aItem.__repeat_end === sLabel) { return i; }
        }

        return -1;

    },

    _flushQueue : function() {

        var bSync, aItem;
        var self = this;

        do {

            bSync = false;
            aItem = this._aIngItem = this._aQueue[this._nPtr];

            if (this._bReservePause || !aItem) {

                this._bPlaying = false;
                this._bReservePause = false;

                /**
                    애니메이션이 종료 되었을 때(더이상 진행할 내용이 없을때) 발생
                    @event end
                **/
                if (!aItem) {
                    this.fireEvent('end');
                }

                return;
            }

            this._nPtr++;

            if (aItem instanceof Function) {
                aItem.call(this);
                bSync = true;
                continue;
            } else if (aItem instanceof this.constructor) {
                bSync = this._waitMorph(aItem); 
                continue;
            } else if (typeof aItem === 'number') {
                setTimeout(function() { self._flushQueue(); }, aItem);
                continue;
            } else if (aItem.action === 'label') {
                delete aItem.args.count;
                if (aItem.args.times < 1) {
                    var nIndex = this._getRepeatEndIndex(aItem.args.label, this._nPtr);
                    if (nIndex > -1) { this._goto(nIndex + 1); }
                }
                bSync = true;
                continue;
            } else if (aItem.action === 'goto') {
                this._goto(aItem.args.label);
                bSync = true;
                continue;
            }

            var aCompiledItem = this._aCompiledItem;
            var nPausePassed = this._nPausePassed;

            if (!nPausePassed) {
                aCompiledItem = this._aCompiledItem = this._compileItem(aItem);
            } else {
                // 전부 다 Timer 로 돌리도록 강제 변경
                for (var i = 0, nLen = aCompiledItem.length; i < nLen; i++) {
                    aCompiledItem[i].oEffectCSS = {};
                }
            }

            // console.log('_flushQueue', aCompiledItem);

            bSync = aCompiledItem.duration < 0;

            if (bSync) {
                this._processItem(1.0, true); // 마지막 상태로 바로 셋팅
                continue;
            }

            this._playItem(nPausePassed);
            this._nPausePassed = 0;

        } while(bSync);

    },

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    /// 애니메이션 동작 구현
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    _playItem : function(nPausePassed) {

        var self = this;

        this._nStart = new Date().getTime() - nPausePassed;
        this._nIng = 2;

        // 처음부터 진행하는거면 처음 상태로 셋팅
        if (!nPausePassed) {
            this._processItem(0.0, true, 2/*TIMER*/);
        }

        // Timer 돌리기
        this._animationLoop(true);

        setTimeout(function() {

            // CSS 를 적용해야 하는 것 적용
            var aCompiledItem = this._aCompiledItem;
            // console.log('...ing BEFORE', aCompiledItem[0].welObj.$value().style.cssText);

            var aTransitionCache = self._processItem(1.0, true, 1/*CSS*/);
            // console.log('...ing AFTER ', aCompiledItem[0].welObj.$value().style.cssText);

            if (!aTransitionCache) {
                if (--self._nIng === 0) {
                    self._flushQueue();
                }
                return;
            }


            var welObj = aTransitionCache[0];
            var elObj = welObj.$value();

            var fpOnTransitionEnd = function() {

                // console.log('transitionEnd');

                var oItem;

                while (oItem = aTransitionCache.pop()) {
                    oItem.css(self._getCSSKey('transitionProperty'), 'none');
                    oItem.css(self._getCSSKey('transitionDuration'), '0s');
                }

                elObj.removeEventListener(self._sTransitionEnd, self._fpOnTransitionEnd, true);

                aTransitionCache = null;
                self._fpOnTransitionEnd = null;

                if (--self._nIng === 0) {
                    self._flushQueue();
                }

            };

            self._fpOnTransitionEnd = fpOnTransitionEnd;
            elObj.addEventListener(self._sTransitionEnd, self._fpOnTransitionEnd, true);

        }, 0);

    },

    _animationLoop : function(bSetStatic) {

        var self = this;

        this._oTimer = this._requestAnimationFrame(function() {

            var nStart = self._nStart;
            var nDuration = self._aCompiledItem.duration;

            if (self._oTimer === null) { return; }
            self._oTimer = null;

            var nPer = Math.min(1, Math.max(0, (new Date().getTime() - nStart) / nDuration));
            self._processItem(nPer, bSetStatic, 2/*TIMER*/);

            if (nPer < 1) {
                self._animationLoop();
            } else {
                if (--self._nIng === 0) {
                    self._flushQueue();
                }
            }

        });

    },

    /**
     * @param nRate 얼마나 진행 시킬지
     * @param bSetStatic 상수를 사용한 값도 셋팅할꺼면 true, 변화하는 값만 셋팅할꺼면 false
     * @param nTargetType 셋팅할 대상을 지정 (1 : CSS Transition 를 쓰는 것, 2 : Timer 를 쓰는 것)
     */
    _processItem : function(nRate, bSetStatic, nTargetType) {
        // var aTransitionCache = self._processItem(1.0, true, 1/*CSS*/);

        // var aTransitionCache = self._processItem(1.0, true, 1/*CSS*/);

        var aCompiledItem = this._aCompiledItem;
        var nDuration = aCompiledItem.duration;

        var oObj, welObj, oProps, oEffectCSS;

        var vProp, nType;
        var bFirstCSS = true;

        var aTransitionCache = null;

        var sStyleKey;

        nTargetType = nTargetType || (1/*CSS*/ | 2/*TIMER*/);

        /**
            애니메이션 진행을 위해 값을 설정하기 직전에 발생
            @event beforeProgress

            @stoppable

            @param {Number} nRate 진행률 (0~1 사이의 값)
            @param {Function} stop 호출 시 값을 설정하지 않음
        **/
        if (!this.fireEvent('beforeProgress', { 'nRate' : nRate })) {
            return;
        }

        var aLists = [], oListProp;

        for (var i = 0, oItem; oItem = aCompiledItem[i]; i++) {

            oObj = oItem.oObj;
            welObj = oItem.welObj;
            oProps = oItem.oProps;
            oEffectCSS = oItem.oEffectCSS;

            bFirstCSS = true;

            oListProp = {};
            aLists.push(oObj);
            aLists.push(oListProp);

            // Transition CSS 를 먹여도 실행되지 않는 문제 해결
            welObj && welObj.$value().clientHeight;

            for (var sKey in oProps) if (oProps.hasOwnProperty(sKey)) {

                vProp = oProps[sKey];
                nType = oEffectCSS[sKey] ? 1/*CSS*/ : 2/*TIMER*/;

                // 지금꺼가 바꿔야 하는게 아니면 그만둠
                if (!(nTargetType & nType)) {
                    continue;
                }

                if (nTargetType & 1/*CSS*/ && nType & 1/*CSS*/ && bFirstCSS) {

                    aTransitionCache = aTransitionCache || [];
                    aTransitionCache.push(welObj);

                    if (!('@transition' in oProps)) {
                        if (!('@transitionProperty' in oProps)) { welObj.css(this._getCSSKey('transitionProperty'), 'all'); }
                        if (!('@transitionDuration' in oProps)) { welObj.css(this._getCSSKey('transitionDuration'), (nDuration / 1000).toFixed(3) + 's'); }
                        if (!('@transitionTimingFunction' in oProps)) { welObj.css(this._getCSSKey('transitionTimingFunction'), oEffectCSS[sKey]); }
                    }

                    bFirstCSS = false;

                }

                if (typeof vProp === 'function') { vProp = vProp(nRate); }
                else if (!bSetStatic) { continue; }

                if (/^@(.*)$/.test(sKey)) {
                    sStyleKey = RegExp.$1;
                    if (/transition/.test(sKey)) { vProp = this._getCSSVal(vProp); }
                    welObj.css(this._getCSSKey(sStyleKey), vProp);
                } else {
                    oObj[sKey] = vProp;
                }

                oListProp[sKey] = vProp;

            }

        }

        /**
            애니메이션 진행을 위해 값을 설정한 직후에 발생
            @event progress

            @param {Array} aLists 설정 한 애니메이션 정보 (객체와 프로퍼티 목록이 번갈아가며 존재)
            @param {Number} nRate 진행률 (0~1 사이의 값)
        **/
        this.fireEvent('progress', { 'aLists' : aLists, 'nRate' : nRate });

        return aTransitionCache;

    },

    _compileItem : function(aItem) {

        var aRet = [];
        aRet.duration = aItem.duration;

        var oObj, welObj, oProps;
        var vDepa, vDest;

        var oCompiledProps, oEffectCSS;
        var bAllUseTimer = false, bIsStyleKey, sStyleKey;

        var fEffect = this.option('fEffect');

        for (var i = 0, nLen = aItem.length; i < nLen; i += 2) {

            oObj = aItem[i];
            welObj = jindo.$Element(oObj);
            oProps = aItem[i + 1];
            oCompiledProps = {}, oEffectCSS = {};

            for (var sKey in oProps) if (oProps.hasOwnProperty(sKey)) {

                vDest = oProps[sKey];
                bIsStyleKey = /^@(.*)$/.test(sKey);

                sStyleKey = RegExp.$1;

                if (vDest instanceof Array) {
                    vDepa = vDest[0];
                    vDest = vDest[1];
                } else if (bIsStyleKey) {
                    vDepa = welObj.css(this._getCSSKey(sStyleKey));
                    // console.warn(this._getCSSKey(sStyleKey), vDepa);
                } else {
                    vDepa = oObj[sKey];
                }

                // if (vDepa === vDest) { continue; }

                if (!bIsStyleKey) {
                    oEffectCSS[sKey] = null;
                }

                if (/^@transform$/.test(sKey)) {
                    oCompiledProps[sKey] = this._getTransformFunction(vDepa, vDest, fEffect);
                } else {
                    try {
                        if (typeof vDest === 'function') {
                            if ('setStart' in vDest) { vDest.setStart(vDepa || ''); }

                            oCompiledProps[sKey] = vDest;
                            if (bIsStyleKey) {
                                bAllUseTimer = true;
                            }
                        } else {
                            oCompiledProps[sKey] = fEffect(vDepa || '', vDest);
                        }
                    } catch(e) {
                        if (!/^unit error/.test(e.message)) { throw e; }
                        oCompiledProps[sKey] = vDest;
                        oEffectCSS[sKey] = null;
                    }
                }

                if (!(sKey in oEffectCSS)) {
                    oEffectCSS[sKey] = this._getEffectCSS(fEffect);                 
                }

            }

            if (bAllUseTimer) { oEffectCSS = {}; }

            aRet.push({ 'oObj' : oObj, 'welObj' : welObj, 'oProps' : oCompiledProps, 'oEffectCSS' : oEffectCSS });

        }

        return aRet;

    },

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    /// public 메서드 구현
    ///////////////////////////////////////////////////////////////////////////////////////////////////

    /**
        현재 재생위치부터 재생목록에 들어있는 일을 수행
        
        @method play
        @return {this}
    **/
    play : function() {

        if (!this._bPlaying) {
            this._bPlaying = true;

            /**
                애니메이션이 재생이 시작 되었을 때 발생
                @event play
            **/
            this.fireEvent('play');
            this._flushQueue();

        }

        return this;

    },

    /**
        재생 위치를 맨 처음으로 변경
        @method reset
        @return {this}
    **/
    reset : function() {
        return this._goto(0);
    },

    /**
        애니메이션 수행 중단
        @method pause

        @param {Number} [nRate] 중단 위치 (0(시작상태)~1(종료상태) 사이의 값을 지정 할 수 있으며, 생략시 현 상태로 중단한다)

        @return {this}
    **/
    pause : function(nRate) {

        if (!this._bPlaying) { return this; }

        this._cancelAnimationFrame(this._oTimer);
        this._oTimer = null;

        if (this._fpOnTransitionEnd) {
            this._fpOnTransitionEnd.bind()();
        }

        if (typeof nRate === 'undefined') {

            var nStart = this._nStart;
            var nDuration = this._aCompiledItem.duration;
            var nPassed = new Date().getTime() - nStart;

            var nPer = Math.min(1, Math.max(0, nPassed / nDuration));
            this._processItem(nPer, true);

            this._nPtr--;
            this._nPausePassed = nPassed;

        } else {
            this._processItem(Math.max(0, Math.min(1, nRate)));
        }

        this._bPlaying = false;
        this._bReservePause = false;

        /*
        case 2: // 하던거 마저 하고 중단
            this._bReservePause = true;
            break;
        */

        /**
            애니메이션이 재생이 정지 되었을 때 발생
            @event pause
        **/
        this.fireEvent('pause');

        return this;

    },

    /**
        지정된 라벨로 실행 포인터를 이동함
        
        @ignore
        @method _goto
        @param {String} sLabel 라벨명
        @return {this}
    **/
    /**
        지정된 목록 위치로 실행 포인터를 이동함
        
        @ignore
        @method _goto
        @param {Number} nIndex 목록 위치
        @return {this}
    **/
    _goto : function(nIndex) {

        var sLabel = nIndex;

        if (typeof nIndex === 'number') {
            nIndex = nIndex || 0;
        } else {
            nIndex = this._getLabelIndex(sLabel);
            if (nIndex === -1) throw 'Label not found';
            nIndex++;
        }

        this._nPtr = nIndex;
        this._nPausePassed = 0;

        return this;

    },

    /**
        현재 재생중인 상태인지 반환
        
        @method isPlaying
        @return {Boolean} 재생중이면 true, 재생중이 아니면 false
    **/
    isPlaying : function() {
        return this._bPlaying || false;
    },

    /**
        재생목록을 모두 삭제함
        @method clear
        @return {this}
    **/
    clear : function() {

        this._aQueue.length = 0;
        this._aRepeat.length = 0;
        this._nPtr = 0;

        return this;

    },

    /**
        현재 재생 위치를 얻음
        
        @ignore
        @method _getPointer
        @return {Number} 현재 재생 위치
    **/
    _getPointer : function() {
        return this._nPtr;
    },

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    /// UTIL 성격의 메서드
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    _oProperPrefix : {},

    _getProperPrefix : function(sType) {

        var oProperPrefix = this._oProperPrefix;
        if (sType in oProperPrefix) {
            return oProperPrefix[sType];
        }

        var oBodyStyle = document.body.style;
        var aPrefix = [ 'webkit', '', 'Moz', 'O', 'ms' ];
        var sPrefix, sFullType;

        for (var i = 0, nLen = aPrefix.length; i < nLen; i++) {
            sPrefix = aPrefix[i];
            sFullType = sPrefix + (sPrefix ? sType.replace(/^[a-z]/, function(s) { return s.toUpperCase(); }) : sType);
            if (sFullType in oBodyStyle) {
                return (oProperPrefix[sType] = sPrefix);
            }
        }

        return (oProperPrefix[sType] = '');

    },

    _getCSSKey : function(sName) {

        var self = this;
        var sPrefix = '';

        var sFullname = sName.replace(/^(\-(webkit|o|moz|ms)\-)?([a-z]+)/, function(_0, _1, _sPrefix, sType) {
            sPrefix = _sPrefix || self._getProperPrefix(sType);
            if (sPrefix) { sType = sType.replace(/^[a-z]/, function(s) { return s.toUpperCase(); }); }
            return sType;
        }).replace(/\-(\w)/g, function(_, sChar) {
            return sChar.toUpperCase();
        });

        return (({ 'o' : 'O', 'moz' : 'Moz', 'webkit' : 'Webkit' })[sPrefix] || sPrefix) + sFullname;

    },

    _getCSSVal : function(sName) {

        var self = this;

        var sFullname = sName.replace(/(^|\s)(\-(webkit|moz|o|ms)\-)?([a-z]+)/g, function(_0, sHead, _2, sPrefix, sType) {
            sPrefix = sPrefix || self._getProperPrefix(sType);
            return sHead + (sPrefix && '-' + sPrefix + '-') + sType;
        });

        return sFullname;

    },

    _parseTransformText : function(sText) {

        sText = sText || '';

        var oRet = {};

        sText.replace(/([\w\-]+)\(([^\)]*)\)/g, function(_, sKey, sVal) {

            var aVal = sVal.split(/\s*,\s*/);

            switch (sKey) {
            case 'translate3d':
            case 'scale3d':
            case 'skew3d': // but, not support
                sKey = sKey.replace(/3d$/, '');
                oRet[sKey + 'Z'] = aVal[2];
                // cont.

            case 'translate':
            case 'scale':
            case 'skew':
                oRet[sKey + 'X'] = aVal[0];

                if (typeof aVal[1] === 'undefined') {
                    if (sKey === 'scale') { oRet[sKey + 'Y'] = aVal[0]; }
                } else {
                    oRet[sKey + 'Y'] = aVal[1];
                }

                break;

            //case 'rotate':
            //  sKey = 'rotateZ';
            //  // cont.

            default:
                oRet[sKey] = aVal.join(',');
                break;
            }

        });

        return oRet;

    },

    _getTransformFunction : function(vDepa, vDest, fEffect) {

        var self = this;

        var sKey;

        var oDepa = this._parseTransformText(vDepa);
        var oDest = this._parseTransformText(vDest);

        var oProp = {};

        for (sKey in oDepa) if (oDepa.hasOwnProperty(sKey)) {
            oProp[sKey] = fEffect(oDepa[sKey], oDest[sKey] || (/^scale/.test(sKey) ? 1 : 0));
        }

        for (sKey in oDest) if (oDest.hasOwnProperty(sKey) && !(sKey in oDepa)) {
            oProp[sKey] = fEffect(oDepa[sKey] || (/^scale/.test(sKey) ? 1 : 0), oDest[sKey]);
        }

        var fpFunc = function(nRate) {
            var aRet = [];
            for (var sKey in oProp) if (oProp.hasOwnProperty(sKey)) {
                aRet.push(sKey + '(' + oProp[sKey](nRate)+ ')');
            }
            /*
            aRet = aRet.sort(function(a, b) {
                return a === b ? 0 : (a > b ? -1 : 1);
            });
            */

            return aRet.join(' ');
        };

        return fpFunc;

    },

    _getEffectCSS : function(fEffect) {

        var bUseTransition = this.option('bUseTransition') && this._bTransitionSupport;

        // Transition 를 쓰지않도록 셋팅되어 있으면 Timer 사용
        if (!bUseTransition) { return null; }

        // progress 나 beforeProgress 핸들러가 등록되어 있으면 Timer 사용
        if (
            (this._htEventHandler.progress && this._htEventHandler.progress.length) ||
            (this._htEventHandler.beforeProgress && this._htEventHandler.beforeProgress.length)
        ) { return null; }

        switch (fEffect) {
        case jindo.m.Effect.linear:
            return 'linear'; break;
        case jindo.m.Effect.cubicEase:
            return 'ease'; break;
        case jindo.m.Effect.cubicEaseIn:
            return 'ease-in'; break;
        case jindo.m.Effect.cubicEaseOut:
            return 'ease-out'; break;
        case jindo.m.Effect.cubicEaseInOut:
            return 'ease-in-out'; break;
        default:
            if (fEffect.cubicBezier && Math.max.apply(Math, fEffect.cubicBezier) <= 1 && Math.min.apply(Math, fEffect.cubicBezier) >= 0) {
                return 'cubic-bezier(' + fEffect.cubicBezier.join(',') + ')';
            }
            break;
        }

        // CSS 에 없는 timing-function 이면 Timer 사용
        return null;

    },

    _requestAnimationFrame : function(fFunc) {

        var ret;
        var self = this;
 
        var fWrap = function() {

            if (ret === self._oLastRAF) {
                self._oLastRAF = null;
                fFunc();
            }
 
        };
 
        if (window.requestAnimationFrame) {
            ret = requestAnimationFrame(fWrap);
        } else {
            ret = setTimeout(fWrap, 1000 / 60);
        }
 
        return (this._oLastRAF = ret);

    },

    _cancelAnimationFrame : function(nTimer) {

        var ret;

        if (window.cancelAnimationFrame) {
            ret = cancelAnimationFrame(nTimer);
        } else {
            ret = clearTimeout(nTimer);
        }
 
        this._oLastRAF = null;
 
        return ret;

    }

}).extend(jindo.m.Component);