$(function () {
	$("#sellInfo_productObj_productNo").combobox({
	    url: backURL + getVisitPath("ProductInfo") + '/listAll',
	    valueField: "productNo",
	    textField: "productName",
	    panelHeight: "auto",
        editable: false, //不允许手动输入
        required : true,
        onLoadSuccess: function () { //数据加载完毕事件
            var data = $("#sellInfo_productObj_productNo").combobox("getData"); 
            if (data.length > 0) {
                $("#sellInfo_productObj_productNo").combobox("select", data[0].productNo);
            }
        }
	});
	$("#sellInfo_sellDate").datebox({
	    required : true, 
	    showSeconds: true,
	    editable: false
	});

	$("#sellInfo_price").validatebox({
		required : true,
		validType : "number",
		missingMessage : '请输入销售价格',
		invalidMessage : '销售价格输入不对',
	});

	$("#sellInfo_count").validatebox({
		required : true,
		validType : "integer",
		missingMessage : '请输入销售数量',
		invalidMessage : '销售数量输入不对',
	});

	$("#sellInfo_customerObj_customerId").combobox({
	    url: backURL + getVisitPath("CustomerInfo") + '/listAll',
	    valueField: "customerId",
	    textField: "customerName",
	    panelHeight: "auto",
        editable: false, //不允许手动输入
        required : true,
        onLoadSuccess: function () { //数据加载完毕事件
            var data = $("#sellInfo_customerObj_customerId").combobox("getData"); 
            if (data.length > 0) {
                $("#sellInfo_customerObj_customerId").combobox("select", data[0].customerId);
            }
        }
	});
	$("#sellInfo_personName").validatebox({
		required : true, 
		missingMessage : '请输入销售负责人',
	});

	//单击添加按钮
	$("#sellInfoAddButton").click(function () {
		//验证表单 
		if(!$("#sellInfoAddForm").form("validate")) {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		} else {
			$("#sellInfoAddForm").form({
			    url: backURL + getVisitPath("SellInfo") + "/add",
			    onSubmit: function(){
					if($("#sellInfoAddForm").form("validate"))  { 
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
                        $("#sellInfoAddForm").form("clear");
                    }else{
                        $.messager.alert("消息",obj.message);
                        $(".messager-window").css("z-index",10000);
                    }
			    }
			});
			//提交表单
			$("#sellInfoAddForm").submit();
		}
	});

	//单击清空按钮
	$("#sellInfoClearButton").click(function () { 
		$("#sellInfoAddForm").form("clear"); 
	});
});
