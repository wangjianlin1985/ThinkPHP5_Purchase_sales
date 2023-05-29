<?php
namespace app\back\controller;
use think\Request;
use think\Exception;
use app\common\model\SupplyerModel;

class Supplyer extends BackBase {
    protected $supplyerModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->supplyerModel = new SupplyerModel();
    }

    /*添加供应商信息*/
    public function add(){
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
            return view('supplyer/supplyer_add');
        }
    }

    /*跳转到更新界面*/
    public function modifyView() {
        $this->assign("supplyerId",input("supplyerId"));
        return view("supplyer/supplyer_modify");
    }

    /*ajax方式按照查询条件分页查询供应商信息*/
    public function backList() {
        if($this->request->isPost()) {
            if($this->request->param("page") != null)
                $this->currentPage = $this->request->param("page");
            if($this->request->param("rows") != null)
                $this->supplyerModel->setRows($this->request->param("rows"));
            $supplyerName = input("supplyerName")==null?"":input("supplyerName");
            $supplyerRs = $this->supplyerModel->querySupplyer($supplyerName, $this->currentPage);
            $expTableData = [];
            foreach($supplyerRs as $supplyerRow) {
                $expTableData[] = $supplyerRow;
            }
            $data["rows"] = $supplyerRs;
            /*当前查询条件下总记录数*/
            $data["total"] = $this->supplyerModel->recordNumber;
            echo json_encode($data);
        } else {
            return view("supplyer/supplyer_query");
        }
    }

    /*ajax方式查询供应商信息*/
    public function listAll() {
        $supplyerRs = $this->supplyerModel->queryAllSupplyer();
        echo json_encode($supplyerRs);
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

    /*按照查询条件导出供应商信息到Excel*/
    public function outToExcel() {
        $supplyerName = input("supplyerName")==null?"":input("supplyerName");
        $supplyerRs = $this->supplyerModel->queryOutputSupplyer($supplyerName);
        $expTableData = [];
        foreach($supplyerRs as $supplyerRow) {
            $expTableData[] = $supplyerRow;
        }
        $xlsName = "Supplyer";
        $xlsCell = array(
            array('supplyerId','供应商编号','int'),
            array('supplyerName','供应商名称','string'),
            array('telephone','供应商电话','string'),
            array('personName','联系人','string'),
            array('address','供应商地址','string'),
        );//查出字段输出对应Excel对应的列名
        //公共方法调用
        $this->export_excel($xlsName,$xlsCell,$expTableData);
    }

}

