(function(c){
	var d={};
	function a(e){return plupload.translate(e)||e}
	function b(f,e){e.contents().each(function(g,h){h=c(h);if(!h.is(".plupload")){h.remove()}});

e.prepend('<div class="xupload_wrapper xupload_scroll"><div id="'+f+'_container" class="xupload_container"><div class="plupload"><div class="xupload_content"><div class="xupload_filelist_header"><div class="xupload_file_name">파일 이름</div><div class="xupload_file_action">&nbsp;</div><div class="xupload_file_status"><span>상태</span></div><div class="xupload_file_size">크기</div><div class="xupload_clearer">&nbsp;</div></div><ul id="'+f+'_filelist" class="xupload_filelist"></ul><div class="xupload_filelist_footer"><div class="xupload_file_name"><div class="xupload_buttons"><a href="#" class="xupload_button xupload_add"><span>파일 추가</span></a><a href="#" class="xupload_button xupload_start"><span>파일 전송</span></a></div><span class="xupload_upload_status"></span></div><div class="xupload_file_action"></div><div class="xupload_file_status"><span class="xupload_total_status">0%</span></div><div class="xupload_file_size"><span class="xupload_total_file_size">0 b</span></div><div class="xupload_progress"><div class="xupload_progress_container"><div class="xupload_progress_bar"></div></div></div><div class="xupload_clearer">&nbsp;</div></div></div></div></div><input type="hidden" id="'+f+'_count" name="'+f+'_count" value="0" /></div>')}

c.fn.xuploadQueue=function(e){
	if(e){this.each(function(){
		var j,i,k;
		i=c(this);
		k=i.attr("id");
if(!k){k=plupload.guid();
i.attr("id",k)}
j=new plupload.Uploader(c.extend({dragdrop:true,container:k},e));
d[k]=j;

function h(l)
{
	var n;
	if(l.status==plupload.DONE){n="xupload_done"}
	if(l.status==plupload.FAILED){n="xupload_failed"}
	if(l.status==plupload.QUEUED){n="xupload_delete"}
	if(l.status==plupload.UPLOADING){n="xupload_uploading"}
	var m=c("#"+l.id).attr("class",n).find("a").css("display","block");
	if(l.hint){m.attr("title",l.hint)}
}

function f(){
	c("span.xupload_total_status",i).html(j.total.percent+"%");
	c("div.xupload_progress_bar",i).css("width",j.total.percent+"%");
	c("span.xupload_upload_status",i).text(a("Uploaded %d/%d files").replace(/%d\/%d/,j.total.uploaded+"/"+j.files.length))}

function g(){
	var m=c("ul.xupload_filelist",i).html(""),n=0,l;
	c.each(j.files,function(p,o){
		l = "";
		if(o.status == plupload.DONE)
		{
			if(o.target_name)
			{
				l+='<input type="hidden" name="'+k+"_"+n+'_tmpname" value="'+plupload.xmlEncode(o.target_name)+'" />'
			}
			l+='<input type="hidden" name="'+k+"_"+n+'_name" value="'+plupload.xmlEncode(o.name)+'" />';
			l+='<input type="hidden" name="'+k+"_"+n+'_status" value="'+(o.status==plupload.DONE?"done":"failed")+'" />';
			n++;
			c("#"+k+"_count").val(n);
		}

		m.append('<li id="'+o.id+'"><div class="xupload_file_name"><span>'+o.name+'</span></div><div class="xupload_file_action"><a href="#"></a></div><div class="xupload_file_status">'+o.percent+'%</div><div class="xupload_file_size">'+plupload.formatSize(o.size)+'</div><div class="xupload_clearer">&nbsp;</div>'+l+"</li>");
		h(o);
		c("#"+o.id+".xupload_delete a").click(function(q){c("#"+o.id).remove();j.removeFile(o);q.preventDefault()})
	});

c("span.xupload_total_file_size",i).html(plupload.formatSize(j.total.size));
if(j.total.queued===0){c("span.xupload_add_text",i).text(a("Add files."))}
else{c("span.xupload_add_text",i).text(j.total.queued+" files queued.")}c("a.xupload_start",i).toggleClass("xupload_disabled",j.files.length==(j.total.uploaded+j.total.failed));
m[0].scrollTop=m[0].scrollHeight;
f();
if(!j.files.length&&j.features.dragdrop&&j.settings.dragdrop){c("#"+k+"_filelist").append('<li class="xupload_droptext">첨부하실 파일을 여기에 끌어 놓으세요.</li>')}}

j.bind("UploadFile",function(l,m){c("#"+m.id).addClass("xupload_current_file")});j.bind("Init",function(l,m){b(k,i);
if(!e.unique_names&&e.rename){c("#"+k+"_filelist div.xupload_file_name span",i).live("click",function(s){var q=c(s.target),o,r,n,p="";o=l.getFile(q.parents("li")[0].id);n=o.name;r=/^(.+)(\.[^.]+)$/.exec(n);if(r){n=r[1];p=r[2]}q.hide().after('<input type="text" />');
q.next().val(n).focus().blur(function(){q.show().next().remove()}).keydown(function(u){var t=c(this);if(u.keyCode==13){u.preventDefault();o.name=t.val()+p;q.text(o.name);t.blur()}})})}
c("a.xupload_add",i).attr("id",k+"_browse");l.settings.browse_button=k+"_browse";if(l.features.dragdrop&&l.settings.dragdrop){l.settings.drop_element=k+"_filelist";c("#"+k+"_filelist").append('<li class="xupload_droptext">첨부하실 파일을 여기에 끌어 놓으세요.</li>')}c("#"+k+"_container").attr("title","Using runtime: "+m.runtime);c("a.xupload_start",i).click(function(n){if(!c(this).hasClass("xupload_disabled")){j.start()}n.preventDefault()});c("a.xupload_stop",i).click(function(n){n.preventDefault();j.stop()});c("a.xupload_start",i).addClass("xupload_disabled")});j.init();

j.bind("Error",function(l,o){
	var m=o.file,n;
	if(m){
		n=o.message;
		if(o.details){n+=" ("+o.details+")"}

		if(o.code==plupload.FILE_SIZE_ERROR){alert(a("Error: 파일이 너무 큽니다.1 : ")+m.name)}
		if(o.code==plupload.FILE_EXTENSION_ERROR){alert(a("Error: 허용하지 않는 확장자입니다. : ")+m.name)}

		m.hint=n;
		c("#"+m.id).attr("class","xupload_failed").find("a").css("display","block").attr("title",n)
	}
});

j.bind("StateChanged",function(){
	if(j.state===plupload.STARTED){
		c("li.xupload_delete a,div.xupload_buttons",i).hide();
		c("span.xupload_upload_status,div.xupload_progress,a.xupload_stop",i).css("display","block");
		c("span.xupload_upload_status",i).text("Uploaded "+j.total.uploaded+"/"+j.files.length+" files");
		if(e.multiple_queues){
			c("span.xupload_total_status,span.xupload_total_file_size",i).show()
		}
	}
	else{
		g();
		c("a.xupload_stop,div.xupload_progress",i).hide();
		c("a.xupload_delete",i).css("display","block")
	}
});
j.bind("QueueChanged",g);

// 파일전송하고 난뒤 실행
j.bind("FileUploaded",function(l,m){
	h(m);
//////////////////////////////////////////
// 파일미리보기 추가 - 2013.03.19
	$.ajax({
		type: "post", dataType: 'html', url: 'file_preview.php',
		data: {'code_comp':$('#html_comp_idx').val(), 'code_part':$('#html_part_idx').val(), 'code_mem':$('#html_mem_idx').val() },
		success: function(msg) {
			$("#preview_file_result").html(msg);
		}
	});
//////////////////////////////////////////
});
j.bind("UploadProgress",function(l,m){
	c("#"+m.id+" div.xupload_file_status",i).html(m.percent+"%");
	h(m);
	f();
	if(e.multiple_queues&&j.total.uploaded+j.total.failed==j.files.length){
		c(".xupload_buttons,.xupload_upload_status",i).css("display","inline");
		c(".xupload_start",i).addClass("xupload_disabled");
		c("span.xupload_total_status,span.xupload_total_file_size",i).hide()
	}
});
if(e.setup){e.setup(j)}});return this}else{return d[c(this[0]).attr("id")]}}})(jQuery);
