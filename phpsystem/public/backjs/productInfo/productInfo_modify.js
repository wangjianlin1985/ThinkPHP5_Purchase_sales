$(function () {
	//实例化编辑器
	//建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
	UE.delEditor('productInfo_productDesc_edit');
	var productInfo_productDesc_edit = UE.getEditor('productInfo_productDesc_edit'); //产品描述编辑器
	productInfo_productDesc_edit.addListener("ready", function () {
		 // editor准备好之后才可以使用 
		 ajaxModifyQuery();
	}); 
  function ajaxModifyQuery() {	
	$.ajax({
		url :  backURL + getVisitPath("ProductInfo") + "/update",
		type : "get",
		data : {
			productNo : $("#productInfo_productNo_edit").val(),
		},
		dataType: "json",
		beforeSend : function () {
			$.messager.progress({
				text : "正在获取中...",
			});
		},
		success : function (productInfo, response, status) {
			$.messager.progress("close");
			if (productInfo) { 
				$("#productInfo_productNo_edit").val(productInfo.productNo);
				$("#productInfo_productNo_edit").validatebox({
					required : true,
					missingMessage : "请输入产品编号",
					editable: false
				});
				$("#productInfo_productClass_productClassId_edit").combobox({
					url: backURL + getVisitPath("ProductClass") + "/listAll",
					valueField:"productClassId",
					textField:"productClassName",
					panelHeight: "auto",
					editable: false, //不允许手动输入 
					onLoadSuccess: function () { //数据加载完毕事件
						$("#productInfo_productClass_productClassId_edit").combobox("select", productInfo.productClass);
						//var data = $("#productInfo_productClass_productClassId_edit").combobox("getData"); 
						//if (data.length > 0) {
							//$("#productInfo_productClass_productClassId_edit").combobox("select", data[0].productClassId);
						//}
					}
				});
				$("#productInfo_productName_edit").val(productInfo.productName);
				$("#productInfo_productName_edit").validatebox({
					required : true,
					missingMessage : "请输入产品名称",
				});
				$("#productInfo_price_edit").val(productInfo.price);
				$("#productInfo_price_edit").validatebox({
					required : true,
					validType : "number",
					missingMessage : "请输入产品单价",
					invalidMessage : "产品单价输入不对",
				});
				$("#productInfo_leftCount_edit").val(productInfo.leftCount);
				$("#productInfo_leftCount_edit").validatebox({
					required : true,
					validType : "integer",
					missingMessage : "请输入产品库存",
					invalidMessage : "产品库存输入不对",
				});
				$("#productInfo_madeDate_edit").datebox({
					value: productInfo.madeDate,
					required: true,
					showSeconds: true,
				});
				$("#productInfo_productPhoto").val(productInfo.productPhoto);
				$("#productInfo_productPhotoImg").attr("src", publicURL + productInfo.productPhoto);
				productInfo_productDesc_edit.setContent(productInfo.productDesc);
			} else {
				$.messager.alert("获取失败！", "未知错误导致失败，请重试！", "warning");
				$(".messager-window").css("z-index",10000);
			}
		}
	});

  }

	$("#productInfoModifyButton").click(function(){ 
		if ($("#productInfoEditForm").form("validate")) {
			$("#productInfoEditForm").form({
			    url: backURL + getVisitPath("ProductInfo") + "/update",
			    onSubmit: function(){
					if($("#productInfoEditForm").form("validate"))  {
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
			$("#productInfoEditForm").submit();
		} else {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		}
	});
});
