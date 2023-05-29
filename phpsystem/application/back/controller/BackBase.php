<?php
namespace app\back\controller;
use think\Controller;

class BackBase extends Controller
{
    protected $currentPage = 1;
    protected $request = null;

    public function _initialize(){
        if(!session('username')){
            $this->error('请先登录系统！','Login/index');
        }
    }

    public function writeJsonResponse($success, $message) {
        $response = array(
            "success" => $success,
            "message" => $message,
        );
        echo json_encode($response);
    }

    /**
     * @param $obj:  保存图片路径的对象
     * @param $indexName 索引名称
     * @param $photoFiledName 提交的图片表单名称
     */
    public function uploadPhoto(&$obj,$indexName,$photoFiledName) {
        if($_FILES[$photoFiledName]['tmp_name']){
            $file = $this->request->file($photoFiledName);
            //控制上传的文件类型，大小
            if(!(($_FILES[$photoFiledName]["type"]=="image/jpeg"
                    || $_FILES[$photoFiledName]["type"]=="image/jpg"
                    || $_FILES[$photoFiledName]["type"]=="image/png") && $_FILES[$photoFiledName]["size"] < 1024000)) {
                $message = "图书图片请选择jpg或png格式的图片!";
                $this->writeJsonResponse(false,$message);
                exit;
            }
            $file->setRule("short"); //文件路径采用简短方式
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            $obj[$indexName]='upload/'.$info->getSaveName();
        }
    }

    /**
     * @param $obj:  保存文件路径的对象
     * @param $indexName 索引名称
     * @param $resourceFiledName 提交的文件表单名称
     */
    public function uploadFile(&$obj,$indexName,$resourceFiledName) {
        if($_FILES[$resourceFiledName]['tmp_name']){
            $file = $this->request->file($resourceFiledName);
            $file->setRule("short"); //文件路径采用简短方式
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            $obj[$indexName]='upload/'.$info->getSaveName();
        }
    }

    //接收提交的ProductClass信息参数
    public function getProductClassForm($insertFlag) {
        $productClass = [
            'productClassId'=> input("productClass_productClassId"), //商品类别编号
            'productClassName'=> input("productClass_productClassName"), //商品类别名称
        ];
        return $productClass;
    }

    //接收提交的ProductInfo信息参数
    public function getProductInfoForm($insertFlag) {
        $productInfo = [
            'productNo'=> input("productInfo_productNo"), //产品编号
            'productClass'=> input("productInfo_productClass_productClassId"), //产品类别
            'productName'=> input("productInfo_productName"), //产品名称
            'price'=> input("productInfo_price"), //产品单价
            'leftCount'=> input("productInfo_leftCount"), //产品库存
            'madeDate'=> input("productInfo_madeDate"), //生产日期
            'productPhoto' => $insertFlag==true?"upload/NoImage.jpg":input("productInfo_productPhoto"), //产品图片
            'productDesc'=> input("productInfo_productDesc"), //产品描述
        ];
        return $productInfo;
    }

    //接收提交的Supplyer信息参数
    public function getSupplyerForm($insertFlag) {
        $supplyer = [
            'supplyerId'=> input("supplyer_supplyerId"), //供应商编号
            'supplyerName'=> input("supplyer_supplyerName"), //供应商名称
            'telephone'=> input("supplyer_telephone"), //供应商电话
            'personName'=> input("supplyer_personName"), //联系人
            'address'=> input("supplyer_address"), //供应商地址
        ];
        return $supplyer;
    }

    //接收提交的CustomerInfo信息参数
    public function getCustomerInfoForm($insertFlag) {
        $customerInfo = [
            'customerId'=> input("customerInfo_customerId"), //客户编号
            'customerName'=> input("customerInfo_customerName"), //客户名称
            'personName'=> input("customerInfo_personName"), //联系人
            'telephone'=> input("customerInfo_telephone"), //联系电话
            'address'=> input("customerInfo_address"), //联系地址
        ];
        return $customerInfo;
    }

    //接收提交的BuyInfo信息参数
    public function getBuyInfoForm($insertFlag) {
        $buyInfo = [
            'buyId'=> input("buyInfo_buyId"), //进货编号
            'productObj'=> input("buyInfo_productObj_productNo"), //进货产品
            'buyDate'=> input("buyInfo_buyDate"), //进货日期
            'price'=> input("buyInfo_price"), //进货单价
            'count'=> input("buyInfo_count"), //进货数量
            'supplyerObj'=> input("buyInfo_supplyerObj_supplyerId"), //供应商
            'personName'=> input("buyInfo_personName"), //负责人
        ];
        return $buyInfo;
    }

    //接收提交的SellInfo信息参数
    public function getSellInfoForm($insertFlag) {
        $sellInfo = [
            'sellId'=> input("sellInfo_sellId"), //销售编号
            'productObj'=> input("sellInfo_productObj_productNo"), //销售产品
            'sellDate'=> input("sellInfo_sellDate"), //销售日期
            'price'=> input("sellInfo_price"), //销售价格
            'count'=> input("sellInfo_count"), //销售数量
            'customerObj'=> input("sellInfo_customerObj_customerId"), //销售客户
            'personName'=> input("sellInfo_personName"), //销售负责人
        ];
        return $sellInfo;
    }

    /** * 公共数据导出实现功能
     * @param $expTitle 导出文件名
     * @param $expCellName 导出文件列名称
     * @param $expTableData 导出数据
     */
    public function export_excel($expTitle,$expCellName,$expTableData) {
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $expTitle . date('_Ymd');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        //这些文件需要下载phpexcel，然后放在vendor文件里面。具体参考上一篇数据导出。
        vendor("PHPExcel.PHPExcel");
        //vendor("PHPExcel.PHPExcel.Writer.IWriter");
        //vendor("PHPExcel.PHPExcel.Writer.Abstract");
        //vendor("PHPExcel.PHPExcel.Writer.Excel5");
        //vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.PHPExcel.IOFactory");
        $objPHPExcel = new \PHPExcel();//方法一
        $cellName = array('A','B', 'C','D', 'E', 'F','G','H','I', 'J', 'K','L','M', 'N', 'O', 'P', 'Q','R','S', 'T','U','V', 'W', 'X','Y', 'Z', 'AA',    'AB', 'AC','AD','AE', 'AF','AG','AH','AI', 'AJ', 'AK', 'AL','AM','AN','AO','AP','AQ','AR', 'AS', 'AT','AU', 'AV','AW', 'AX', 'AY', 'AZ');
        //设置头部导出时间备注
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1');//合并单元格
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle . ' 导出时间:' . date('Y-m-d H:i:s'));
        //设置列名称
        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $expCellName[$i][1]);
            $objPHPExcel->getActiveSheet()->getColumnDimension($cellName[$i])->setWidth(20); //设置每列宽度
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i].'2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($cellName[$i])->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER); //垂直居中对齐
        }
        //赋值
        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                $objPHPExcel->getActiveSheet()->getStyle($cellName[$j].($i + 3))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                if($expCellName[$j][2] == 'photo') {
                    try {
                        // 表格高度
                        $objPHPExcel->getActiveSheet()->getRowDimension($i+3)->setRowHeight(80);
                        // 图片生成
                        $objDrawing = new \PHPExcel_Worksheet_Drawing();
                        $objDrawing->setPath(PUBLIC_PATH.$expTableData[$i][$expCellName[$j][0]]);
                        // 设置宽度高度
                        $objDrawing->setHeight(80);//照片高度
                        $objDrawing->setWidth(80); //照片宽度
                        /*设置图片要插入的单元格*/
                        $objDrawing->setCoordinates($cellName[$j].($i + 3));
                        // 图片偏移距离
                        $objDrawing->setOffsetX(5);
                        $objDrawing->setOffsetY(5);
                        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
                    } catch (\Exception $ex) {}
                } else {
                    $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i + 3), $expTableData[$i][$expCellName[$j][0]]);
                }
            }
        }
        ob_end_clean();//这一步非常关键，用来清除缓冲区防止导出的excel乱码
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//"xls"参考下一条备注
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'  );//"Excel2007"生成2007版本的xlsx，"Excel5"生成2003版本的xls
        $objWriter->save('php://output');
    }
}

