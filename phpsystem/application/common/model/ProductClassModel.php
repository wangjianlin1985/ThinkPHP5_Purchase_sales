<?php
namespace app\common\model;
use think\Model;

class ProductClassModel extends Model {
    /*关联表名*/
    protected $table  = 't_productClass';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    /*添加商品类别记录*/
    public function addProductClass($productClass) {
        $this->insert($productClass);
    }

    /*更新商品类别记录*/
    public function updateProductClass($productClass) {
        $this->update($productClass);
    }

    /*删除多条商品类别信息*/
    public function deleteProductClasss($productClassIds){
        $productClassIdArray = explode(",",$productClassIds);
        foreach ($productClassIdArray as $productClassId) {
            $this->productClassId = $productClassId;
            $this->delete();
        }
        return count($productClassIdArray);
    }
    /*根据主键获取商品类别记录*/
    public function getProductClass($productClassId) {
        $productClass = ProductClassModel::where("productClassId",$productClassId)->find();
        return $productClass;
    }

    /*按照查询条件分页查询商品类别信息*/
    public function queryProductClass($currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        $productClassRs = ProductClassModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = ProductClassModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $productClassRs;
    }

    /*按照查询条件查询所有商品类别记录*/
  public function queryOutputProductClass() {
        $where = null;
        $productClassRs = ProductClassModel::where($where)->select();
        return $productClassRs;
    }

    /*查询所有商品类别记录*/
    public function queryAllProductClass(){
        $productClassRs = ProductClassModel::where(null)->select();
        return $productClassRs;

    }

}
