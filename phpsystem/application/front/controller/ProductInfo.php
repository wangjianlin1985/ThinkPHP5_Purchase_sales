<?php
namespace app\front\controller;
use think\Request;
use think\Exception;
use app\common\model\ProductClassModel;
use app\common\model\ProductInfoModel;

class ProductInfo extends Base {
    protected $productClassModel;
    protected $productInfoModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->productClassModel = new ProductClassModel();
        $this->productInfoModel = new ProductInfoModel();
    }

    /*添加产品信息信息*/
    public function frontAdd(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $productInfo = $this->getProductInfoForm(true);
            $this->uploadPhoto($productInfo,"productPhoto","productPhotoFile"); //处理产品图片上传
            try {
                $this->productInfoModel->addProductInfo($productInfo);
                $message = "产品信息添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "产品信息添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return $this->fetch('productInfo/productInfo_frontAdd');
        }
    }

    /*前台修改产品信息信息*/
    public function frontModify() {
        $this->assign("productNo",input("productNo"));
        return $this->fetch("productInfo/productInfo_frontModify");
    }

    /*前台按照查询条件分页查询产品信息信息*/
    public function frontlist() {
        if($this->request->param("currentPage") != null)
            $this->currentPage = $this->request->param("currentPage");
        $productNo = input("productNo")==null?"":input("productNo");
        $productClass["productClassId"] = input("productClass_productClassId")==null?0:input("productClass_productClassId");
        $productName = input("productName")==null?"":input("productName");
        $productInfoRs = $this->productInfoModel->queryProductInfo($productNo, $productClass, $productName, $this->currentPage);
        $this->assign("productInfoRs",$productInfoRs);
        /*获取到总的页码数目*/
        $this->assign("totalPage",$this->productInfoModel->totalPage);
        /*当前查询条件下总记录数*/
        $this->assign("recordNumber",$this->productInfoModel->recordNumber);
        $this->assign("currentPage",$this->currentPage);
        $this->assign("rows",$this->productInfoModel->rows);
        $this->assign("productNo",$productNo);
        $this->assign("productClass",$productClass);
        $this->assign("productClassList",$this->productClassModel->queryAllProductClass());
        $this->assign("productName",$productName);
        return $this->fetch('productInfo/productInfo_frontlist');
    }

    /*ajax方式查询产品信息信息*/
    public function listAll() {
        $productInfoRs = $this->productInfoModel->queryAllProductInfo();
        echo json_encode($productInfoRs);
    }
    /*前台查询根据主键查询一条产品信息信息*/
    public function frontshow() {
        $productNo = input("productNo");
        $productInfo = $this->productInfoModel->getProductInfo($productNo);
       $this->assign("productInfo",$productInfo);
        return $this->fetch("productInfo/productInfo_frontshow");
    }

    /*更新产品信息信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $productInfo = $this->getProductInfoForm(false);
            $this->uploadPhoto($productInfo,"productPhoto","productPhotoFile"); //处理产品图片上传
            try {
                $this->productInfoModel->updateProductInfo($productInfo);
                $message = "产品信息更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "产品信息更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取产品信息对象*/
            $productNo = input("productNo");
            $productInfo = $this->productInfoModel->getProductInfo($productNo);
            echo json_encode($productInfo);
        }
    }

    /*删除多条产品信息记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $productNos = input("productNos");
        try {
            $count = $this->productInfoModel->deleteProductInfos($productNos);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

}

