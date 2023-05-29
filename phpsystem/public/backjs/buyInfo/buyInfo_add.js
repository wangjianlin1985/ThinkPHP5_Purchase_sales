$(function () {
	$("#buyInfo_productObj_productNo").combobox({
	    url: backURL + getVisitPath("ProductInfo") + '/listAll',
	    valueField: "productNo",
	    textField: "productName",
	    panelHeight: "auto",
        editable: false, //不允许手动输入
        required : true,
        onLoadSuccess: function () { //数据加载完毕事件
            var data = $("#buyInfo_productObj_productNo").combobox("getData"); 
            if (data.length > 0) {
                $("#buyInfo_productObj_productNo").combobox("select", data[0].productNo);
            }
        }
	});
	$("#buyInfo_buyDate").datebox({
	    required : true, 
	    showSeconds: true,
	    editable: false
	});

	$("#buyInfo_price").validatebox({
		required : true,
		validType : "number",
		missingMessage : '请输入进货单价',
		invalidMessage : '进货单价输入不对',
	});

	$("#buyInfo_count").validatebox({
		required : true,
		validType : "integer",
		missingMessage : '请输入进货数量',
		invalidMessage : '进货数量输入不对',
	});

	$("#buyInfo_supplyerObj_supplyerId").combobox({
	    url: backURL + getVisitPath("Supplyer") + '/listAll',
	    valueField: "supplyerId",
	    textField: "supplyerName",
	    panelHeight: "auto",
        editable: false, //不允许手动输入
        required : true,
        onLoadSuccess: function () { //数据加载完毕事件
            var data = $("#buyInfo_supplyerObj_supplyerId").combobox("getData"); 
            if (data.length > 0) {
                $("#buyInfo_supplyerObj_supplyerId").combobox("select", data[0].supplyerId);
            }
        }
	});
	//单击添加按钮
	$("#buyInfoAddButton").click(function () {
		//验证表单 
		if(!$("#buyInfoAddForm").form("validate")) {
			$.messager.alert("错误提示","你输入的信息还有错误！","warning");
			$(".messager-window").css("z-index",10000);
		} else {
			$("#buyInfoAddForm").form({
			    url: backURL + getVisitPath("BuyInfo") + "/add",
			    onSubmit: function(){
					if($("#buyInfoAddForm").form("validate"))  { 
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
                        $("#buyInfoAddForm").form("clear");
                    }else{
                        $.messager.alert("消息",obj.message);
                        $(".messager-window").css("z-index",10000);
                    }
			    }
			});
			//提交表单
			$("#buyInfoAddForm").submit();
		}
	});

	//单击清空按钮
	$("#buyInfoClearButton").click(function () { 
		$("#buyInfoAddForm").form("clear"); 
	});
});
