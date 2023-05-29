<?php
namespace app\back\controller;
use think\Request;
use think\Exception;
use app\common\model\ProductClassModel;
use app\common\model\ProductInfoModel;

class ProductInfo extends BackBase {
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
    public function add(){
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
            return view('productInfo/productInfo_add');
        }
    }

    /*跳转到更新界面*/
    public function modifyView() {
        $this->assign("productNo",input("productNo"));
        return view("productInfo/productInfo_modify");
    }

    /*ajax方式按照查询条件分页查询产品信息信息*/
    public function backList() {
        if($this->request->isPost()) {
            if($this->request->param("page") != null)
                $this->currentPage = $this->request->param("page");
            if($this->request->param("rows") != null)
                $this->productInfoModel->setRows($this->request->param("rows"));
            $productNo = input("productNo")==null?"":input("productNo");
            $productClass["productClassId"] = input("productClass_productClassId")==null?0:input("productClass_productClassId");
            $productName = input("productName")==null?"":input("productName");
            $productInfoRs = $this->productInfoModel->queryProductInfo($productNo, $productClass, $productName, $this->currentPage);
            $expTableData = [];
            foreach($productInfoRs as $productInfoRow) {
                $productInfoRow["productClass"] = $this->productClassModel->getProductClass($productInfoRow["productClass"])["productClassName"];
                $expTableData[] = $productInfoRow;
            }
            $data["rows"] = $productInfoRs;
            /*当前查询条件下总记录数*/
            $data["total"] = $this->productInfoModel->recordNumber;
            echo json_encode($data);
        } else {
            return view("productInfo/productInfo_query");
        }
    }

    /*ajax方式查询产品信息信息*/
    public function listAll() {
        $productInfoRs = $this->productInfoModel->queryAllProductInfo();
        echo json_encode($productInfoRs);
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

    /*按照查询条件导出产品信息信息到Excel*/
    public function outToExcel() {
        $productNo = input("productNo")==null?"":input("productNo");
        $productClass["productClassId"] = input("productClass_productClassId")==null?0:input("productClass_productClassId");
        $productName = input("productName")==null?"":input("productName");
        $productInfoRs = $this->productInfoModel->queryOutputProductInfo($productNo,$productClass,$productName);
        $expTableData = [];
        foreach($productInfoRs as $productInfoRow) {
            $productInfoRow["productClass"] = $this->productClassModel->getProductClass($productInfoRow["productClass"])["productClassName"];
            $expTableData[] = $productInfoRow;
        }
        $xlsName = "ProductInfo";
        $xlsCell = array(
            array('productNo','产品编号','string'),
            array('productClass','产品类别','string'),
            array('productName','产品名称','string'),
            array('price','产品单价','float'),
            array('leftCount','产品库存','int'),
            array('madeDate','生产日期','string'),
            array('productPhoto','产品图片','photo'),
        );//查出字段输出对应Excel对应的列名
        //公共方法调用
        $this->export_excel($xlsName,$xlsCell,$expTableData);
    }

}

