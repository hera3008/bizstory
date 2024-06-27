<?
	include "bizstory/common/setting.php";
	
    $sql = "select * from project_class order by comp_idx, pro_idx, proc_idx";
    
    $row = db_query($sql);
    $i = 0;
    while($data = mysql_fetch_array($row)) {
        $list[$i++] = string_output($data); 
    }
    
    $sort = 1;
    $tmp_comp_idx = "";
    $tmp_pro_idx = "";
    foreach($list as $k => $data) {
        if (is_array($data))
        {
            $comp_idx = $data['comp_idx'];
            $pro_idx = $data['pro_idx'];
            
            if ($comp_idx != $tmp_comp_idx || $pro_idx != $tmp_pro_idx) {
                $sort = 1;
                $tmp_comp_idx = $comp_idx;
                $tmp_pro_idx = $pro_idx;
            }
            
            $sql = "update project_class set sort=" . $sort . " where proc_idx='" . $data['proc_idx'] . "' ";
            db_query($sql);
            $sort++;
        }        
    }
?>