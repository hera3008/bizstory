<div id="layer" style="display:none;border:5px solid;position:fixed;width:500px;height:460px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden;-webkit-overflow-scrolling:touch;">
<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" onclick="closeDaumPostcode()" alt="닫기 버튼">
</div>

<!--<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>-->
<script>
    // 우편번호 찾기 화면을 넣을 element
    var element_layer = document.getElementById('layer');

    function closeDaumPostcode() {
        // iframe을 넣은 element를 안보이게 한다.
        element_layer.style.display = 'none';
    }

    function execDaumPostcode(zipType) {
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = data.address; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 기본 주소가 도로명 타입일때 조합한다.
                if(data.addressType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                switch(zipType) {
                    case 'zip':
                        $('#post_zip_code1').val( data.postcode1 );
                        $('#post_zip_code2').val( data.postcode2 );
                        $('#post_address1').val( fullAddr );
                        break;
                    case 'tax_zip':
                        $('#post_tax_zip_code1').val( data.postcode1 );
                        $('#post_tax_zip_code2').val( data.postcode2 );
                        $('#post_tax_address1').val( fullAddr );
                        break;
                    default:
                        // 우편번호와 주소 및 영문주소 정보를 해당 필드에 넣는다.
                        document.getElementById('zip1').value = data.postcode1;
                        document.getElementById('zip2').value = data.postcode2;
                        document.getElementById('address').value = fullAddr;
                        //document.getElementById('sample2_addressEnglish').value = data.addressEnglish;                    
                        break;
                }


                // iframe을 넣은 element를 안보이게 한다.
                element_layer.style.display = 'none';
            },
            width : '100%',
            height : '100%'
        }).embed(element_layer);

        // iframe을 넣은 element를 보이게 한다.
        element_layer.style.display = 'block';
    }
</script>