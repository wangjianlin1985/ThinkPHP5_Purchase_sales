<?php
namespace app\front\controller;
use think\Controller;

class Base extends Controller
{
    protected $currentPage = 1;
    protected $request = null;

    //向客户端输出ajax响应结果
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

}

