<?php
    header('Content-Type: application/json; charset=UTF-8');

	include "../common/setting.php";
	include "../common/no_direct.php";

    $search = ($txtdong);               // json은 UTF-8만 지원합니다. (결과값도 UTF-8로 리턴)
    
    //echo $search . '<br/>';
    $url  = "http://post.phpschool.com/json.phps.kr";
    //개발 서버용 키 (182.252.167.20)
    //$data = array("addr"=>$search, "ipkey"=>"4257367", "type"=>"new");
    //운영 서버용 키 (183.111.148.114)
    $data = array("addr"=>$search, "ipkey"=>"2504904", "type"=>"new");
    // ipkey는 인증메일에서 안내합니다.
    // 구주소검색일경우 array("addr"=>$search, "ipkey"=>"XXX",  "type"=>"old");  
    // 지번주소로 도로명주소를 찾기위한 검색 "type"=>"newdong"  /* "가산동 371-50" 식으로 동/번지입력 */
    // 지번주소로 찾기의 경우  http://post.phpschool.com/post.html 예를 참조바랍니다.
    // 구주소검색일경우(구 우편번호로만 찾기) "type"=>"old"  
    

    $output = (HTTP_Post($url, $data));
    $output = substr($output, strpos($output,"\r\n\r\n")+4);

    echo $output;
    /*
    $json = json_decode($output);

    if ($json->result > 0) {

        //echo "검색건수 : {$json->result}\n";
        //echo "검색시간 : {$json->time}\n";
        //echo "조회횟수 : {$json->cnt}\n";
        //echo "조회한도 : {$json->maxcnt}\n";

        foreach ($json->post as $key=>$value) {
                 //$value->post;                // 우편번호
                 //$value->addr_1;              // 시/도
                 //$value->addr_2;              // 구
                 //$value->addr_3;              // 도로명
                 //$value->addr_4;              // 동/건물
                 //$value->addr_5;              // 구주소 (도로명주소1:1매칭) // 도로명주소검색일경우만 리턴
                 //$value->addr_eng;            // 영문주소 // 도로명주소검색일경우만 리턴

                 //print_r($value);
                 json_encode($value);
        }
    } else if ($json->result == 0) {
        echo "검색결과가 없습니다.";
    } else if ($json->result == -1) {
        echo "검색결과가 너무 많습니다. 입력하신 검색어 $search 뒤에 단어를 추가해서 검색해보세요.";
    } else if ($json->result < 0) {
        echo "검색실패 : ".$json->message;
    }
    */
    // $result  "-1"  일경우 :  너무많은검색결과 1000건이상
    // $result  "-2"  일경우 :  서버 IP 미인증
    // $result  "-3"  일경우 :  조회횟수초과
    // $result  "-4"  일경우 :  미인증 사용자

    // 실제 구현 소스는 위에까지입니다. 아래는 소켓함수(curl로 구현가능)
    function HTTP_Post($URL,$data) { 
        $URL_Info=parse_url($URL);
        if(!empty($data)) foreach($data AS $k => $v) $str .= urlencode($k).'='.urlencode($v).'&';
        $path = $URL_Info["path"];
        $host = $URL_Info["host"];
        $port = $URL_Info["port"];
        if (empty($port)) $port=80;
        $result = "";
        $fp = fsockopen($host, $port, $errno, $errstr, 30);
        $http  = "POST $path HTTP/1.0\r\n";
        $http .= "Host: $host\r\n";
        $http .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $http .= "Content-length: " . strlen($str) . "\r\n";
        $http .= "Connection: close\r\n\r\n";
        $http .= $str . "\r\n\r\n";
        fwrite($fp, $http);
        while (!feof($fp)) { $result .= fgets($fp, 4096); }
        fclose($fp);
        return $result;
    }
?>