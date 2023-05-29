<?php
namespace app\front\controller;
use think\Request;
use think\Exception;
use app\common\model\SupplyerModel;

class Supplyer extends Base {
    protected $supplyerModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->supplyerModel = new SupplyerModel();
    }

    /*添加供应商信息*/
    public function frontAdd(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $supplyer = $this->getSupplyerForm(true);
            try {
                $this->supplyerModel->addSupplyer($supplyer);
                $message = "供应商添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "供应商添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return $this->fetch('supplyer/supplyer_frontAdd');
        }
    }

    /*前台修改供应商信息*/
    public function frontModify() {
        $this->assign("supplyerId",input("supplyerId"));
        return $this->fetch("supplyer/supplyer_frontModify");
    }

    /*前台按照查询条件分页查询供应商信息*/
    public function frontlist() {
        if($this->request->param("currentPage") != null)
            $this->currentPage = $this->request->param("currentPage");
        $supplyerName = input("supplyerName")==null?"":input("supplyerName");
        $supplyerRs = $this->supplyerModel->querySupplyer($supplyerName, $this->currentPage);
        $this->assign("supplyerRs",$supplyerRs);
        /*获取到总的页码数目*/
        $this->assign("totalPage",$this->supplyerModel->totalPage);
        /*当前查询条件下总记录数*/
        $this->assign("recordNumber",$this->supplyerModel->recordNumber);
        $this->assign("currentPage",$this->currentPage);
        $this->assign("rows",$this->supplyerModel->rows);
        $this->assign("supplyerName",$supplyerName);
        return $this->fetch('supplyer/supplyer_frontlist');
    }

    /*ajax方式查询供应商信息*/
    public function listAll() {
        $supplyerRs = $this->supplyerModel->queryAllSupplyer();
        echo json_encode($supplyerRs);
    }
    /*前台查询根据主键查询一条供应商信息*/
    public function frontshow() {
        $supplyerId = input("supplyerId");
        $supplyer = $this->supplyerModel->getSupplyer($supplyerId);
       $this->assign("supplyer",$supplyer);
        return $this->fetch("supplyer/supplyer_frontshow");
    }

    /*更新供应商信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $supplyer = $this->getSupplyerForm(false);
            try {
                $this->supplyerModel->updateSupplyer($supplyer);
                $message = "供应商更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "供应商更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取供应商对象*/
            $supplyerId = input("supplyerId");
            $supplyer = $this->supplyerModel->getSupplyer($supplyerId);
            echo json_encode($supplyer);
        }
    }

    /*删除多条供应商记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $supplyerIds = input("supplyerIds");
        try {
            $count = $this->supplyerModel->deleteSupplyers($supplyerIds);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

}

