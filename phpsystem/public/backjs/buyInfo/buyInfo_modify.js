$(function () {
	$.ajax({
		url :  backURL + getVisitPath("BuyInfo") + "/update",
		type : "get",
		data : {
			buyId : $("#buyInfo_buyId_edit").val(),
		},
		dataType: "json",
		beforeSend : function () {
			$.messager.progress({
				text : "正在获取中...",
			});
		},
		success : function (buyInfo, response, status) {
			$.messager.progress("close");
			if (buyInfo) { 
				$("#buyInfo_buyId_edit").val(buyInfo.buyId);
				$("#buyInfo_buyId_edit").validatebox({
					required : true,
					missingMessage : "请输入进货编号",
					editable: false
				});
				$("#buyInfo_productObj_productNo_edit").combobox({
					url: backURL + getVisitPath("ProductInfo") + "/listAll",
					valueField:"productNo",
					textField:"productName",
					panelHeight: "auto",
					editable: false, //不允许手动输入 
					onLoadSuccess: function () { //数据加载完毕事件
						$("#buyInfo_productObj_productNo_edit").combobox("select", buyInfo.productObj);
						//var data = $("#buyInfo_productObj_productNo_edit").combobox("getData"); 
						//if (data.length > 0) {
							//$("#buyInfo_productObj_productNo_edit").combobox("select", data[0].productNo);
						//}
					}
				});
				$("#buyInfo_buyDate_edit").datebox({
					value: buyInfo.buyDate,
					required: true,
					showSeconds: true,
				});
				$("#buyInfo_price_edit").val(buyInfo.price);
				$("#buyInfo_price_edit").validatebox({
					required : true,
					validType : "number",
					missingMessage : "请输入进货单价",
					invalidMessage : "进货单价输入不对",
				});
				$("#buyInfo_count_edit").val(buyInfo.count);
				$("#buyInfo_count_edit").validatebox({
					required : true,
					validType : "integer",
					missingMessage : "请输入进货数量",
					invalidMessage : "进货数量输入不对",
				});
				$("#buyInfo_supplyerObj_supplyerId_edit").combobox({
					url: backURL + getVisitPath("Supplyer") + "/listAll",
					valueField:"supplyerId",
					textField:"supplyerName",
					panelHeight: "auto",
					editable: false, //不允许手动输入 
					onLoadSuccess: function () { //数据加载完毕事件
						$("#buyInfo_supplyerObj_supplyerId_edit").combobox("select", buyInfo.supplyerObj);
						//var data = $("#buyInfo_supplyerObj_supplyerId_edit").combobox("getData"); 
						//if (data.length > 0) {
							//$("#buyInfo_supplyerObj_supplyerId_edit").combobox("select", data[0].supplyerId);
						//}
					}
				});
				$("#buyInfo_personName_edit").val(buyInfo.personName);
			} else {
				$.messager.alert("获取失败！", "未知错误导致失败，请重试！", "warning");
				$(".messager-window").css("z-index",10000);
			}
		}
	});

	$("#buyInfoModifyButton").click(function(){ 
		if ($("#buyInfoEditForm").form("validate")) {
			$("#buyInfoEditForm").form({
			    url: backURL + getVisitPath("BuyInfo") + "/update",
			    onSubmit: function(){
					if($("#buyInfoEditForm").form("validate"))  {
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
			$("#buyInfoEditForm").submit();
		} else {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		}
	});
});
