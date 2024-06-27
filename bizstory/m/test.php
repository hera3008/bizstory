 <!DOCTYPE html>
 <html>
 <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
  <title>jQuery Mobile: iScroll Test</title>
  <link rel="stylesheet"  href="http://code.jquery.com/mobile/1.0a4/jquery.mobile-1.0a4.min.css" />
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.5.min.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/mobile/1.0a4/jquery.mobile-1.0a4.min.js"></script>
  <script src="http://cubiq.org/dropbox/iscroll/iscroll.js?v3.7.1"></script>
     <script src="https://github.com/yappo/javascript-jquery.mobile.iscroll/raw/master/jquery.mobile.iscroll.js"></script>  
 </head> 
<body> 

<div data-role="page" data-iscroll="enable" data-theme="b" id="home">
  <div data-role="header">
   <h1>iScroll Test</h1>
  </div>
  <div data-role="content">
   <div data-iscroll="scroller"> 
   <ul data-role="listview" data-theme="c" >
     <li><a href="#about">01</a></li>
     <li><a href="#about">02</a></li>
     <li><a href="#about">03</a></li>
     <li><a href="#about">04</a></li>
     <li><a href="#about">05</a></li>
     <li><a href="#about">06</a></li>
     <li><a href="#about">07</a></li>
     <li><a href="#about">08</a></li>
     <li><a href="#about">09</a></li>
     <li><a href="#about">10</a></li>
     <li><a href="#about">11</a></li>
     <li><a href="#about">12</a></li>
     <li><a href="#about">13</a></li>
     <li><a href="#about">14</a></li>
     <li><a href="#about">15</a></li>
     <li><a href="#about">16</a></li>
     <li><a href="#about">17</a></li>
     <li><a href="#about">18</a></li>
     <li><a href="#about">19</a></li>
     <li><a href="#about">20</a></li>
    </ul>
   </div>
  </div>
  
	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li><a href="index.php">메인</a></li>
				<li><a href="#">멤버</a></li>
				<li><a href="vdrive.php">V-Drive</a></li>
				<li><a href="#">즐겨찾기</a></li>
				<li><a href="#">설정</a></li>
			</ul>
		</div >
	</div>
 </div>
 
<div data-role="page" data-theme="b" id="about">
  <div data-role="header">
   <h1>iScroll Test</h1>
  </div>
  <div data-role="content">
   Hello!!!
  </div>
 </div>
 
</body>
 </html>
