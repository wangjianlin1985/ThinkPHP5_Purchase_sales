<?php
namespace app\front\controller;
use think\Request;
use think\Exception;
use app\common\model\ProductClassModel;

class ProductClass extends Base {
    protected $productClassModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->productClassModel = new ProductClassModel();
    }

    /*添加商品类别信息*/
    public function frontAdd(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $productClass = $this->getProductClassForm(true);
            try {
                $this->productClassModel->addProductClass($productClass);
                $message = "商品类别添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "商品类别添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return $this->fetch('productClass/productClass_frontAdd');
        }
    }

    /*前台修改商品类别信息*/
    public function frontModify() {
        $this->assign("productClassId",input("productClassId"));
        return $this->fetch("productClass/productClass_frontModify");
    }

    /*前台按照查询条件分页查询商品类别信息*/
    public function frontlist() {
        if($this->request->param("currentPage") != null)
            $this->currentPage = $this->request->param("currentPage");
        $productClassRs = $this->productClassModel->queryProductClass($this->currentPage);
        $this->assign("productClassRs",$productClassRs);
        /*获取到总的页码数目*/
        $this->assign("totalPage",$this->productClassModel->totalPage);
        /*当前查询条件下总记录数*/
        $this->assign("recordNumber",$this->productClassModel->recordNumber);
        $this->assign("currentPage",$this->currentPage);
        $this->assign("rows",$this->productClassModel->rows);
        return $this->fetch('productClass/productClass_frontlist');
    }

    /*ajax方式查询商品类别信息*/
    public function listAll() {
        $productClassRs = $this->productClassModel->queryAllProductClass();
        echo json_encode($productClassRs);
    }
    /*前台查询根据主键查询一条商品类别信息*/
    public function frontshow() {
        $productClassId = input("productClassId");
        $productClass = $this->productClassModel->getProductClass($productClassId);
       $this->assign("productClass",$productClass);
        return $this->fetch("productClass/productClass_frontshow");
    }

    /*更新商品类别信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $productClass = $this->getProductClassForm(false);
            try {
                $this->productClassModel->updateProductClass($productClass);
                $message = "商品类别更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "商品类别更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取商品类别对象*/
            $productClassId = input("productClassId");
            $productClass = $this->productClassModel->getProductClass($productClassId);
            echo json_encode($productClass);
        }
    }

    /*删除多条商品类别记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $productClassIds = input("productClassIds");
        try {
            $count = $this->productClassModel->deleteProductClasss($productClassIds);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

}

