//<![CDATA[

	function SNSScrap(sns, title, bitlyUrl) {
		switch (sns) {
			case "twitter":
				s_url = "http://twitter.com/home?status=" + encodeURIComponent(title + " ") + escape(bitlyUrl);
				window.open(s_url,"twitter","").focus();
				break;
			case "me2day":
				s_url = "http://me2day.net/posts/new?new_post[body]=" + encodeURIComponent(title + " ") + '"' + escape(bitlyUrl) + '":' + escape(bitlyUrl);
				window.open(s_url,"me2day","").focus();
				break;
			case "facebook":
				s_url = "http://www.facebook.com/share.php?u=" + escape(bitlyUrl) + "&t=" + encodeURIComponent(title);
				window.open(s_url, "facebook", "width=550,height=500,scrolls=no").focus();
				break;
			case "youtube":
				s_url = "http://www.youtube.com/watch?" + encodeURIComponent(title);
				window.open(s_url,"youtube","").focus();
				break;
		}
	}

//]]>