<?php
namespace app\back\controller;
use think\Request;
use think\Exception;
use app\common\model\CustomerInfoModel;

class CustomerInfo extends BackBase {
    protected $customerInfoModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->customerInfoModel = new CustomerInfoModel();
    }

    /*添加客户信息信息*/
    public function add(){
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
            return view('customerInfo/customerInfo_add');
        }
    }

    /*跳转到更新界面*/
    public function modifyView() {
        $this->assign("customerId",input("customerId"));
        return view("customerInfo/customerInfo_modify");
    }

    /*ajax方式按照查询条件分页查询客户信息信息*/
    public function backList() {
        if($this->request->isPost()) {
            if($this->request->param("page") != null)
                $this->currentPage = $this->request->param("page");
            if($this->request->param("rows") != null)
                $this->customerInfoModel->setRows($this->request->param("rows"));
            $customerName = input("customerName")==null?"":input("customerName");
            $personName = input("personName")==null?"":input("personName");
            $customerInfoRs = $this->customerInfoModel->queryCustomerInfo($customerName, $personName, $this->currentPage);
            $expTableData = [];
            foreach($customerInfoRs as $customerInfoRow) {
                $expTableData[] = $customerInfoRow;
            }
            $data["rows"] = $customerInfoRs;
            /*当前查询条件下总记录数*/
            $data["total"] = $this->customerInfoModel->recordNumber;
            echo json_encode($data);
        } else {
            return view("customerInfo/customerInfo_query");
        }
    }

    /*ajax方式查询客户信息信息*/
    public function listAll() {
        $customerInfoRs = $this->customerInfoModel->queryAllCustomerInfo();
        echo json_encode($customerInfoRs);
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

    /*按照查询条件导出客户信息信息到Excel*/
    public function outToExcel() {
        $customerName = input("customerName")==null?"":input("customerName");
        $personName = input("personName")==null?"":input("personName");
        $customerInfoRs = $this->customerInfoModel->queryOutputCustomerInfo($customerName,$personName);
        $expTableData = [];
        foreach($customerInfoRs as $customerInfoRow) {
            $expTableData[] = $customerInfoRow;
        }
        $xlsName = "CustomerInfo";
        $xlsCell = array(
            array('customerId','客户编号','int'),
            array('customerName','客户名称','string'),
            array('personName','联系人','string'),
            array('telephone','联系电话','string'),
            array('address','联系地址','string'),
        );//查出字段输出对应Excel对应的列名
        //公共方法调用
        $this->export_excel($xlsName,$xlsCell,$expTableData);
    }

}

