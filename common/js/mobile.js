// Mobile UserAgent
	var uAgent = navigator.userAgent.toLowerCase();
	var mobilePhones = new Array('iphone', 'ipod', 'android', 'blackberry', 'windows ce','nokia', 'webos', 'operamini', 'sonyericsson', 'opera mobi', 'iemobile','lg','samsung','mot');

	for(var i=0;i<mobilePhones.length;i++)
	{
		if(uAgent.indexOf(mobilePhones[i]) != -1)
		{
			//document.location.href='/bizstory/mobile/';

		// Hide AddressBar
			if (window.addEventListener != null) {
				window.addEventListener('load', function(){
					setTimeout(scrollTo, 0, 0, 1);
				}, false);
			}
		}
	}