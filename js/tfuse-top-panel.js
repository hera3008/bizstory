function getCookie(name)
{
	var cookie = " " + document.cookie;
	var search = " " + name + "=";
	var setStr = null;
	var offset = 0;
	var end = 0;
	if (cookie.length > 0) {
		offset = cookie.indexOf(search);
		if (offset != -1) {
			offset += search.length;
			end = cookie.indexOf(";", offset)
			if (end == -1) {
				end = cookie.length;
			}
			setStr = unescape(cookie.substring(offset, end));
		}
	}
	return(setStr);
}


function SetCookie (name,value,expires,path,domain,secure)
{
    var today = new Date();
    var date = new Date();
    date.setTime(expires*1000);
    document.cookie = name + "=" + escape (value) +
    ((expires) ? "; expires=" + date.toGMTString() : "") +
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    ((secure) ? "; secure" : "");
}


jQuery(window).load(function()
{
	var speed = 400;
	var tfusetoppanelshow = getCookie('tfusetoppanelshow');
	var tfuse_top_panel = jQuery("#tfuse-top-sliding-panel-container");
	jQuery("body").prepend(tfuse_top_panel);
	tfuse_top_panel.show();

	// slideup on page load
	if(tfusetoppanelshow == 'yes')
	{
		jQuery("#tfuse-top-sliding-panel-container").css({top:"-546px"});
		jQuery(this).removeClass("ajax-close-tfuse-top-slide").addClass("ajax-open-tfuse-top-slide");
	}
	else
	{
		jQuery(this).removeClass("ajax-open-tfuse-top-slide").addClass("ajax-close-tfuse-top-slide");
		//jQuery("#tfuse-top-sliding-panel-container").animate({top:"-1px"}, 900);
		//jQuery("#tfuse-top-sliding-panel-container").animate({top:"-1px"}, 2000);
		jQuery("#tfuse-top-sliding-panel-container").animate({top:"-546px"}, 900);
		jQuery(this).removeClass("ajax-close-tfuse-top-slide").addClass("ajax-open-tfuse-top-slide");
		document.cookie = "tfusetoppanelshow=yes; path=/";
	}

	jQuery(this).removeClass("ajax-close-tfuse-top-slide").addClass("ajax-open-tfuse-top-slide");

	//slid up/down top panel
	jQuery("#tfuse-top-sliding-panel-container #tfuse-top-sliding-panel-btn-slide").click(function()
	{
		document.cookie = "tfusetoppanelshow=yes; path=/";
		if( jQuery(this).hasClass("ajax-open-tfuse-top-slide") ) {
			jQuery("#tfuse-top-sliding-panel-container").animate({top:"-1px"}, speed);
			jQuery(this).removeClass("ajax-open-tfuse-top-slide").addClass("ajax-close-tfuse-top-slide");
		}
		else if ( jQuery(this).hasClass("ajax-close-tfuse-top-slide") )
		{
			jQuery("#tfuse-top-sliding-panel-container").animate({top:"-546px"}, speed);
			jQuery(this).removeClass("ajax-close-tfuse-top-slide").addClass("ajax-open-tfuse-top-slide");
		}
	});

	//slide up when click outside
	jQuery("#tfuse-top-sliding-panel-container").hover
	(
		function() {
			jQuery('body').unbind('click');
		},
		function() {
			if( jQuery("#tfuse-top-sliding-panel-container #tfuse-top-sliding-panel-btn-slide").hasClass("ajax-close-tfuse-top-slide") )
			{
				jQuery('body').one('click',function()
				{
					jQuery(this).unbind('click');
					jQuery("#tfuse-top-sliding-panel-container").animate({top:"-546px"}, speed);
					jQuery("#tfuse-top-sliding-panel-container #tfuse-top-sliding-panel-btn-slide").removeClass("ajax-close-tfuse-top-slide").addClass("ajax-open-tfuse-top-slide");
				});
			}
		}
	);
});
