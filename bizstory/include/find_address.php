<?
	$find_address_link = $local_dir . '/bizstory/include/find_post_list.php';
?>
<div id="address_find" title="우편번호 검색">
	<fieldset>
		<legend class="blind">우편번호 검색 폼</legend>

		<table class="tinytable write" summary="동, 우편번호등">
		<caption>우편번호 검색</caption>
		<colgroup>
			<col />
		</colgroup>
		<tbody>
			<tr>
				<td>
					<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return address_search();">
						<input type="hidden" name="search_type" id="post_search_type" value="" />
						<div class="left">
							<input type="text" name="txtdong" id="post_txtdong" class="type_text" title="동 이름을 입력하세요." size="25" />
							<strong class="btn_sml" onclick="address_search()"><span>검색</span></strong>
						</div>
					</form>

					<div class="ui-widget" id="poster_view" style="display:none">
						<div class="ui-state-highlight ui-corner-all">
							<p><span class="ui-icon ui-icon-info"></span>
							<span id="poster_memo">
								<strong>주의</strong> 주의사항 입력
							</span>
							</p>
						</div>
					</div>

					<div class="left mt">
						예) 서울 서초구 잠원동을 검색하시려면 "잠원" 이라고 입력해 주십시오.
					</div>
				</td>
			</tr>
			<tr id="address_list">
				<td id="address_result_list" class="address_list">
					<p>찾고자 하는 우편번호를 위해 검색해주세요.</p>
				</td>
			</tr>
		</tbody>
		</table>
	</fieldset>

</div>

<script type="text/javascript">
//<![CDATA[
// Address Find Dialog
	$('#address_find').dialog({
		autoOpen: false,
		width: 450,
		modal: true,
		buttons: {
			"창닫기": function() {
				$(this).dialog("close");
			}
		}
	});

//------------------------------------ 우편번호 창열기
	function check_address_find()
	{
		$("#poster_view").hide();
		$('#address_find').dialog('open');
		$("#post_search_type").val();
		return false;
	}

//------------------------------------ 우편번호 창열기
	function check_address_find2()
	{
		$("#poster_view").hide();
		$('#address_find').dialog('open');
		$("#post_search_type").val('other1');

		return false;
	}

//------------------------------------ 우편번호검색
	function address_search()
	{
		$("#poster_view").hide();

		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_txtdong').val();
		chk_title = $('#post_txtdong').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				dataType: 'json',
				url     : "<?=$find_address_link;?>",
				data    : {"txtdong" : chk_value},
				success : function(json) {
                   
                    var total_html  = '<div class="left mt">';
                        total_html += ' <ul>';
                        
				    if (json.result > 0) {
 

				        $.each(json.post, function(idx, item) {
    			            var address = item.addr_1 + ' ' + item.addr_2 + ' ' + item.addr_3 + ' ' + item.addr_4;
                            var postcd  = item.post;
                            var postcd1 = postcd.substring(0, 3);
                            var postcd2 = postcd.substring(3, 6);
    
                            total_html += '<li><a href="javascript:void(0);" onclick="address_select(\'' + postcd1 + '\', \'' + postcd2 + '\', \'' + address + '\')">' + postcd1 + '-' + postcd2 + ' ' + address + '</a></li>';
				        });
				        
				    } else if (json.result == 0) {
				        alert("검색결과가 없습니다.");
				    } else if (json.result == -1) {
				        alert("검색결과가 너무 많습니다. 입력하신 검색어 " + chk_value + " 뒤에 단어를 추가해서 검색해보세요.");
				    } else if (json.result < 0) {
                        alert("검색실패 : " + json.message);
                    }
					
					total_html += '	</ul>';
					total_html += '</div>';

					$("#poster_view").hide();

					$('#address_find').dialog({autoOpen: true, title: '우편번호검색결과', height: 400});
					$('#address_result_list').html(total_html);
				}
			});
		}
		else
		{
			$("#poster_view").show();
			$("#poster_memo").html(chk_total);
		}
		return false;
	}

//------------------------------------ 우편번호선택
	function address_select(zipcode1, zipcode2, address)
	{
		$("#poster_view").hide();

		var chk_search = $("#post_search_type").val();
		if (chk_search == 'other1')
		{
			$('#post_tax_zip_code1').val(zipcode1);
			$('#post_tax_zip_code2').val(zipcode2);
			$('#post_tax_address1').val(address);
		}
		else
		{
			$('#post_zip_code1').val(zipcode1);
			$('#post_zip_code2').val(zipcode2);
			$('#post_address1').val(address);
		}


		$('#address_find').dialog('close');
	}
//]]>
</script>
