$(function () {
	//实例化编辑器
	//建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
	UE.delEditor('productInfo_productDesc');
	var productInfo_productDesc_editor = UE.getEditor('productInfo_productDesc'); //产品描述编辑框
	$("#productInfo_productNo").validatebox({
		required : true, 
		missingMessage : '请输入产品编号',
	});

	$("#productInfo_productClass_productClassId").combobox({
	    url: backURL + getVisitPath("ProductClass") + '/listAll',
	    valueField: "productClassId",
	    textField: "productClassName",
	    panelHeight: "auto",
        editable: false, //不允许手动输入
        required : true,
        onLoadSuccess: function () { //数据加载完毕事件
            var data = $("#productInfo_productClass_productClassId").combobox("getData"); 
            if (data.length > 0) {
                $("#productInfo_productClass_productClassId").combobox("select", data[0].productClassId);
            }
        }
	});
	$("#productInfo_productName").validatebox({
		required : true, 
		missingMessage : '请输入产品名称',
	});

	$("#productInfo_price").validatebox({
		required : true,
		validType : "number",
		missingMessage : '请输入产品单价',
		invalidMessage : '产品单价输入不对',
	});

	$("#productInfo_leftCount").validatebox({
		required : true,
		validType : "integer",
		missingMessage : '请输入产品库存',
		invalidMessage : '产品库存输入不对',
	});

	$("#productInfo_madeDate").datebox({
	    required : true, 
	    showSeconds: true,
	    editable: false
	});

	//单击添加按钮
	$("#productInfoAddButton").click(function () {
		if(productInfo_productDesc_editor.getContent() == "") {
			alert("请输入产品描述");
			return;
		}
		//验证表单 
		if(!$("#productInfoAddForm").form("validate")) {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		} else {
			$("#productInfoAddForm").form({
			    url: backURL + getVisitPath("ProductInfo") + "/add",
			    onSubmit: function(){
					if($("#productInfoAddForm").form("validate"))  { 
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
                    //此处data={"Success":true}是字符串
                	var obj = jQuery.parseJSON(data); 
                    if(obj.success){ 
                        $.messager.alert("消息","保存成功！");
                        $(".messager-window").css("z-index",10000);
                        $("#productInfoAddForm").form("clear");
                        productInfo_productDesc_editor.setContent("");
                    }else{
                        $.messager.alert("消息",obj.message);
                        $(".messager-window").css("z-index",10000);
                    }
			    }
			});
			//提交表单
			$("#productInfoAddForm").submit();
		}
	});

	//单击清空按钮
	$("#productInfoClearButton").click(function () { 
		$("#productInfoAddForm").form("clear"); 
	});
});
