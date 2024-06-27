/**
    @fileOverview  expandeffect 플러그인 
    @author "oyang2"
    @version #__VERSION__#
    @since 2011. 12. 15.
**/
/**
    expandeffect 플러그인

    @class jindo.m.ExpandEffect
    @invisible
    @extends jindo.m._Effect_
    @keyword expand, effect, 펼치기
    @group Component
**/

jindo.m.ExpandEffect = jindo.$Class({
	/** @lends jindo.m.ExpandEffect.prototype */
	 /**
        초기화 함수
    **/
	sEffectName : "expand",
	
	getCommand : function(el, htOption){
		var sDirection = htOption.sDirection? htOption.sDirection :'down';
		
		var sProperty = 'width';
		var nSize = this._htLayerInfo["nWidth"];
		
		if(sDirection == 'up' || sDirection == 'down'){
			sProperty = 'height';
			nSize = this._htLayerInfo["nHeight"];
		}
		
		var htStyle = htOption.htTo || {};
		htStyle[sProperty] = nSize+"px";
		
		if(sDirection == 'left'){
			htStyle["margin-left"] = this._htLayerInfo["nMarginLeft"]+"px";
		}
		
		if(sDirection == 'up'){
			htStyle["margin-top"] = this._htLayerInfo["nMarginTop"]+"px";
		}
		
		return {
			sTaskName : this.sEffectName+"-"+sDirection , 
			htStyle : htStyle,
			htTransform : {}
		};
	},
	
	getBeforeCommand : function(el, htOption){
		var sDirection = htOption.sDirection? htOption.sDirection :'down';
		
		var sProperty = 'width';
		
		if(sDirection == 'up' || sDirection == 'down'){
			sProperty = 'height';
		}
		
		var htBeforeStyle = htOption.htFrom || {};	
		htBeforeStyle[sProperty] = "0";
		htBeforeStyle["overflow"] = "hidden";
		
		if(sDirection == 'left'){			
			htBeforeStyle["margin-left"] = (this._htLayerInfo["nWidth"] + this._htLayerInfo["nMarginLeft"])+"px";
		}
		
		if(sDirection == 'up'){
			htBeforeStyle["margin-top"] = (this._htLayerInfo["nHeight"] +this._htLayerInfo["nMarginTop"]) +"px";
			//console.log(htBeforeStyle);
		}	
				
		return {
			htStyle : htBeforeStyle ,
			htTransform : {}
		};
	}
	
}).extend(jindo.m._Effect_);