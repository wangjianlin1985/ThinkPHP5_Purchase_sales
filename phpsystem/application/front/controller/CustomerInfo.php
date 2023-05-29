<?php
namespace app\front\controller;
use think\Request;
use think\Exception;
use app\common\model\CustomerInfoModel;

class CustomerInfo extends Base {
    protected $customerInfoModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->customerInfoModel = new CustomerInfoModel();
    }

    /*添加客户信息信息*/
    public function frontAdd(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $customerInfo = $this->getCustomerInfoForm(true);
            try {
                $this->customerInfoModel->addCustomerInfo($customerInfo);
                $message = "客户信息添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "客户信息添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return $this->fetch('customerInfo/customerInfo_frontAdd');
        }
    }

    /*前台修改客户信息信息*/
    public function frontModify() {
        $this->assign("customerId",input("customerId"));
        return $this->fetch("customerInfo/customerInfo_frontModify");
    }

    /*前台按照查询条件分页查询客户信息信息*/
    public function frontlist() {
        if($this->request->param("currentPage") != null)
            $this->currentPage = $this->request->param("currentPage");
        $customerName = input("customerName")==null?"":input("customerName");
        $personName = input("personName")==null?"":input("personName");
        $customerInfoRs = $this->customerInfoModel->queryCustomerInfo($customerName, $personName, $this->currentPage);
        $this->assign("customerInfoRs",$customerInfoRs);
        /*获取到总的页码数目*/
        $this->assign("totalPage",$this->customerInfoModel->totalPage);
        /*当前查询条件下总记录数*/
        $this->assign("recordNumber",$this->customerInfoModel->recordNumber);
        $this->assign("currentPage",$this->currentPage);
        $this->assign("rows",$this->customerInfoModel->rows);
        $this->assign("customerName",$customerName);
        $this->assign("personName",$personName);
        return $this->fetch('customerInfo/customerInfo_frontlist');
    }

    /*ajax方式查询客户信息信息*/
    public function listAll() {
        $customerInfoRs = $this->customerInfoModel->queryAllCustomerInfo();
        echo json_encode($customerInfoRs);
    }
    /*前台查询根据主键查询一条客户信息信息*/
    public function frontshow() {
        $customerId = input("customerId");
        $customerInfo = $this->customerInfoModel->getCustomerInfo($customerId);
       $this->assign("customerInfo",$customerInfo);
        return $this->fetch("customerInfo/customerInfo_frontshow");
    }

    /*更新客户信息信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $customerInfo = $this->getCustomerInfoForm(false);
            try {
                $this->customerInfoModel->updateCustomerInfo($customerInfo);
                $message = "客户信息更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "客户信息更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取客户信息对象*/
            $customerId = input("customerId");
            $customerInfo = $this->customerInfoModel->getCustomerInfo($customerId);
            echo json_encode($customerInfo);
        }
    }

    /*删除多条客户信息记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $customerIds = input("customerIds");
        try {
            $count = $this->customerInfoModel->deleteCustomerInfos($customerIds);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

}

