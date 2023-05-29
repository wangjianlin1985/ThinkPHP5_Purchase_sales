<?php
namespace app\common\model;
use think\Model;

class ProductInfoModel extends Model {
    /*关联表名*/
    protected $table  = 't_productInfo';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    //产品类别复合属性的获取: 多对一关系
    public function productClassF(){
        return $this->belongsTo('ProductClassModel','productClass');
    }

    /*添加产品信息记录*/
    public function addProductInfo($productInfo) {
        $this->insert($productInfo);
    }

    /*更新产品信息记录*/
    public function updateProductInfo($productInfo) {
        $this->update($productInfo);
    }

    /*删除多条产品信息信息*/
    public function deleteProductInfos($productNos){
        $productNoArray = explode(",",$productNos);
        foreach ($productNoArray as $productNo) {
            $this->productNo = $productNo;
            $this->delete();
        }
        return count($productNoArray);
    }
    /*根据主键获取产品信息记录*/
    public function getProductInfo($productNo) {
        $productInfo = ProductInfoModel::where("productNo",$productNo)->find();
        return $productInfo;
    }

    /*按照查询条件分页查询产品信息信息*/
    public function queryProductInfo($productNo, $productClass, $productName, $currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        if($productNo != "") $where['productNo'] = array('like','%'.$productNo.'%');
        if($productClass['productClassId'] != 0) $where['productClass'] = $productClass['productClassId'];
        if($productName != "") $where['productName'] = array('like','%'.$productName.'%');
        $productInfoRs = ProductInfoModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = ProductInfoModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $productInfoRs;
    }

    /*按照查询条件查询所有产品信息记录*/
  public function queryOutputProductInfo( $productNo,  $productClass,  $productName) {
        $where = null;
        if($productNo != "") $where['productNo'] = array('like','%'.$productNo.'%');
        if($productClass['productClassId'] != 0) $where['productClass'] = $productClass['productClassId'];
        if($productName != "") $where['productName'] = array('like','%'.$productName.'%');
        $productInfoRs = ProductInfoModel::where($where)->select();
        return $productInfoRs;
    }

    /*查询所有产品信息记录*/
    public function queryAllProductInfo(){
        $productInfoRs = ProductInfoModel::where(null)->select();
        return $productInfoRs;

    }

}
