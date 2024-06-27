jindo.m.Env = (function() {
  var _Env_ = {
    // 엘리먼트 개수를 지정 (무한스크롤의 성능을 결정하는 요인 - DOM의 개수를 정한다)
    getListCount : function() {
      var htOs = jindo.m.getOsInfo();
      var nResult = 10;
      if(htOs.ios) {
        nResult = 4;
      } else if(htOs.android) {
        if(htOs.version <= "4.1.0") {
          nResult = 10;
        } else {
          nResult = 5;
        }
      } else {
        nResult = 10;
      }
      //return nResult;
      return 14;
    },
    getTranslate : function(nX, nY) {
      var bUseCss3d = jindo.m.useCss3d();
      return "translate" + (bUseCss3d ? "3d(" : "(") + nX + "px," + nY + "px" + (bUseCss3d ? ",0)" : ")");
    }
  };
  return _Env_;
})();