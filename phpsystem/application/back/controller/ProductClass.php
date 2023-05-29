<?php
namespace app\back\controller;
use think\Request;
use think\Exception;
use app\common\model\ProductClassModel;

class ProductClass extends BackBase {
    protected $productClassModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->productClassModel = new ProductClassModel();
    }

    /*添加商品类别信息*/
    public function add(){
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
            return view('productClass/productClass_add');
        }
    }

    /*跳转到更新界面*/
    public function modifyView() {
        $this->assign("productClassId",input("productClassId"));
        return view("productClass/productClass_modify");
    }

    /*ajax方式按照查询条件分页查询商品类别信息*/
    public function backList() {
        if($this->request->isPost()) {
            if($this->request->param("page") != null)
                $this->currentPage = $this->request->param("page");
            if($this->request->param("rows") != null)
                $this->productClassModel->setRows($this->request->param("rows"));
            $productClassRs = $this->productClassModel->queryProductClass($this->currentPage);
            $expTableData = [];
            foreach($productClassRs as $productClassRow) {
                $expTableData[] = $productClassRow;
            }
            $data["rows"] = $productClassRs;
            /*当前查询条件下总记录数*/
            $data["total"] = $this->productClassModel->recordNumber;
            echo json_encode($data);
        } else {
            return view("productClass/productClass_query");
        }
    }

    /*ajax方式查询商品类别信息*/
    public function listAll() {
        $productClassRs = $this->productClassModel->queryAllProductClass();
        echo json_encode($productClassRs);
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

    /*按照查询条件导出商品类别信息到Excel*/
    public function outToExcel() {
        $productClassRs = $this->productClassModel->queryOutputProductClass();
        $expTableData = [];
        foreach($productClassRs as $productClassRow) {
            $expTableData[] = $productClassRow;
        }
        $xlsName = "ProductClass";
        $xlsCell = array(
            array('productClassId','商品类别编号','int'),
            array('productClassName','商品类别名称','string'),
        );//查出字段输出对应Excel对应的列名
        //公共方法调用
        $this->export_excel($xlsName,$xlsCell,$expTableData);
    }

}

