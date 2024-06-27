
			</div>
			<div id="footer">
				<div class="footer_copy">Copyright ©<strong>BIZSTORY</strong> All Rights Reserved.</div>
			</div>
			<div id="backgroundPopup"></div>
			<div class="top_btn"><a href="javascript:void(0);" title="Scroll to top"></a></div>
		</div>
	</div>
</div>

<div class="ui-widget" id="popup_notice_view" style="display:none">
	<div class="ui-state-highlight ui-corner-all">
		<p><span class="ui-icon ui-icon-info"></span>
		<span id="popup_notice_memo">
			<strong>주의</strong> 주의사항 입력
		</span>
		</p>
	</div>
</div>

<div id="popup_result_msg" title="처리결과"></div>
<script type="text/javascript">
//<![CDATA[
	$("#popup_result_msg").dialog({
		autoOpen: false, width: 350, modal: true,
		buttons: {
			"확인": function() {$(this).dialog("close");}
		}
	});
//]]>
</script>
</body>
</html>