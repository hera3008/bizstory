jindo.m.LazyImage = (function() {
  var nImageTimer = -1,
    fpImageLoad = function(we) {
      we.element.className = "appear-image";
      // console.log("onload", we.element);
    };

  var _LazyImage_ = {
    // 이미지를 보이는 함수
    showImage : function(oScroll) {
      var aEle = oScroll.getVisibleElement(),
        wel = null,
        el = null;
      jindo.$A(aEle).forEach(function(v,i,a) {
        wel = v.query(".news .cds_addition .cds_snapshot img");
        el = wel.$value();
        if(!!el.__src__ && (el.src != el.__src__) ) {
          if(!wel.hasEventListener("load")) {
            wel.attach("load", fpImageLoad);
          }
          el.src = el.__src__;
        } else {
          fpImageLoad({element : el});
        }
      });
    },
    stopImageTimer : function() {
      clearTimeout(nImageTimer);
      nImageTimer = -1;
    },
    startImageTimer : function(oScroll) {
      nImageTimer = setTimeout(function() {
        if(!oScroll.isPlaying()) {
          _LazyImage_.showImage(oScroll);
        }
      },200);
    }
  };
  return _LazyImage_;
})();