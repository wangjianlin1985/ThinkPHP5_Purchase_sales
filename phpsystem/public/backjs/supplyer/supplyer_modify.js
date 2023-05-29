$(function () {
	$.ajax({
		url :  backURL + getVisitPath("Supplyer") + "/update",
		type : "get",
		data : {
			supplyerId : $("#supplyer_supplyerId_edit").val(),
		},
		dataType: "json",
		beforeSend : function () {
			$.messager.progress({
				text : "正在获取中...",
			});
		},
		success : function (supplyer, response, status) {
			$.messager.progress("close");
			if (supplyer) { 
				$("#supplyer_supplyerId_edit").val(supplyer.supplyerId);
				$("#supplyer_supplyerId_edit").validatebox({
					required : true,
					missingMessage : "请输入供应商编号",
					editable: false
				});
				$("#supplyer_supplyerName_edit").val(supplyer.supplyerName);
				$("#supplyer_supplyerName_edit").validatebox({
					required : true,
					missingMessage : "请输入供应商名称",
				});
				$("#supplyer_telephone_edit").val(supplyer.telephone);
				$("#supplyer_personName_edit").val(supplyer.personName);
				$("#supplyer_address_edit").val(supplyer.address);
			} else {
				$.messager.alert("获取失败！", "未知错误导致失败，请重试！", "warning");
				$(".messager-window").css("z-index",10000);
			}
		}
	});

	$("#supplyerModifyButton").click(function(){ 
		if ($("#supplyerEditForm").form("validate")) {
			$("#supplyerEditForm").form({
			    url: backURL + getVisitPath("Supplyer") + "/update",
			    onSubmit: function(){
					if($("#supplyerEditForm").form("validate"))  {
	                	$.messager.progress({
							text : "正在提交数据中...",
						});
	                	return true;
	                } else {
	                    return false;
	                }
			    },
			    success:function(data){
			    	$.messager.progress("close");
                	var obj = jQuery.parseJSON(data);
                    if(obj.success){
                        $.messager.alert("消息","信息修改成功！");
                        $(".messager-window").css("z-index",10000);
                        //location.href="frontlist";
                    }else{
                        $.messager.alert("消息",obj.message);
                        $(".messager-window").css("z-index",10000);
                    } 
			    }
			});
			//提交表单
			$("#supplyerEditForm").submit();
		} else {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		}
	});
});
