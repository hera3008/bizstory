<?
/*
    케어콘 관련 함수
	생성 : 2024.04.03 김소령
	수정 : 2024.04.03 김소령
*/

//-------------------------------------- 케어콘 버튼
function carecon_button_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
{
    if ($orderby == '') $orderby = "cbu.sort asc";
    if ($del_type == 1) $where = "cbu.del_yn = 'N'" . $where;
    else $where = "1" . $where;

    $query_page = "
        select
            count(cbu.cbu_idx)
        from
            carecon_button cbu
        where
            " . $where . "
    ";
    //echo "<pre>" . $query_page . "</pre><br />";
    $query_string = "
        select
            cbu.*
        from
            carecon_button cbu
        where
            " . $where . "
        order by
            " . $orderby . "
    ";
    //echo "<pre>" . $query_string . "</pre><br />";

    if ($query_type == 'view') $data_info = query_view($query_string);
    else if ($query_type == 'page') $data_info = query_page($query_page);
    else
    {
        $data_sql['query_page']   = $query_page;
        $data_sql['query_string'] = $query_string;
        $data_sql['page_size']    = $page_size;
        $data_sql['page_num']     = $page_num;

        $data_info = query_list($data_sql);
    }

    Return $data_info;
}



//-------------------------------------- 배너 함수
function carecon_banner_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
{
    if ($orderby == '') $orderby = "cbn.sort asc";
    if ($del_type == 1) $where = "cbn.del_yn = 'N'" . $where;
    else $where = "1" . $where;

    $query_page = "
        select
            count(cbn.cbn_idx)
        from
            carecon_banner cbn
        where
            " . $where . "
    ";
    //echo "<pre>" . $query_page . "</pre><br />";
    $query_string = "
        select
            cbn.*
        from
            carecon_banner cbn
        where
            " . $where . "
        order by
            " . $orderby . "
    ";
    //echo "<pre>" . $query_string . "</pre><br />";

    if ($query_type == 'view') $data_info = query_view($query_string);
    else if ($query_type == 'page') $data_info = query_page($query_page);
    else
    {
        $data_sql['query_page']   = $query_page;
        $data_sql['query_string'] = $query_string;
        $data_sql['page_size']    = $page_size;
        $data_sql['page_num']     = $page_num;

        $data_info = query_list($data_sql);
    }

    Return $data_info;
}
?>