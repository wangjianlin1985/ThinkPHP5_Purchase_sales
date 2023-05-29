$(function () {
	$.ajax({
		url :  backURL + getVisitPath("CustomerInfo") + "/update",
		type : "get",
		data : {
			customerId : $("#customerInfo_customerId_edit").val(),
		},
		dataType: "json",
		beforeSend : function () {
			$.messager.progress({
				text : "正在获取中...",
			});
		},
		success : function (customerInfo, response, status) {
			$.messager.progress("close");
			if (customerInfo) { 
				$("#customerInfo_customerId_edit").val(customerInfo.customerId);
				$("#customerInfo_customerId_edit").validatebox({
					required : true,
					missingMessage : "请输入客户编号",
					editable: false
				});
				$("#customerInfo_customerName_edit").val(customerInfo.customerName);
				$("#customerInfo_customerName_edit").validatebox({
					required : true,
					missingMessage : "请输入客户名称",
				});
				$("#customerInfo_personName_edit").val(customerInfo.personName);
				$("#customerInfo_telephone_edit").val(customerInfo.telephone);
				$("#customerInfo_address_edit").val(customerInfo.address);
			} else {
				$.messager.alert("获取失败！", "未知错误导致失败，请重试！", "warning");
				$(".messager-window").css("z-index",10000);
			}
		}
	});

	$("#customerInfoModifyButton").click(function(){ 
		if ($("#customerInfoEditForm").form("validate")) {
			$("#customerInfoEditForm").form({
			    url: backURL + getVisitPath("CustomerInfo") + "/update",
			    onSubmit: function(){
					if($("#customerInfoEditForm").form("validate"))  {
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
			$("#customerInfoEditForm").submit();
		} else {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		}
	});
});
