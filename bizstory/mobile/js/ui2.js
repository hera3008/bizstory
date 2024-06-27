(function($){
	jQuery.fn.extend({
		mainVisualControl:function() {
			/*var _this = $(this),
				clickEvt = function() {
					_this.click();
				};*/
			setTimeout(function() {
				//clickEvt();
				var objs = jQuery("#advice button.remote-circle"),
					pause = jQuery("#advice button.pause"),
					play = jQuery("#advice button.play"),
					init;
				if (jQuery("#advice .visual-child").length == 1) {
					_this.parent().addClass('banner-pause')
					pause.hide();
					play.hide();
				}
				if(objs.size == 1)
					return;
				
				var visualFun = function(){
					var objs = jQuery("#advice button.remote-circle");
					var objs2 = jQuery("#advice .visual-child");
					var temp;
					if(objs.size==1){
						return;
					}					
					for(var i=0;i<objs.size();i++){
						if(jQuery(objs.get(i)).hasClass("off")){
							temp = i;
						}
					}
					return;
				}
				
			},1500);
		},
		mainVisual:function() {
			var _this = jQuery(this),
				item = _this.find('button.remote-circle'),
				visualBody = _this.find('.advice-body'),
				visualChild = visualBody.find('>div'),
				visualControl = _this.find('.control-btn');
				if (!jQuery('body').is('#english')){
					_this.addClass('visual01 close-visual');
					jQuery('.control-btn').mainVisualControl();
				} else {
					_this.addClass('visual01');	
				}
				visualBody.hide();
			visualControl.click(function() {
				if (_this.is('.close-visual')) {
					jQuery(this).find('em').html('도움말 닫기')
					visualBody.slideDown(700,'easeInOutQuart',function() {
						_this.removeClass('close-visual')
					});	
				} else {
					jQuery(this).find('em').html('도움말 열기')
					visualBody.slideUp(700,'easeInOutQuart',function() {
						_this.addClass('close-visual')
					});
				}	
			})
		}
	})
})(jQuery)