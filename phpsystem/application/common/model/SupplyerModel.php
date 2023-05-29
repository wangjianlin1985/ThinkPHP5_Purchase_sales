<?php
namespace app\common\model;
use think\Model;

class SupplyerModel extends Model {
    /*关联表名*/
    protected $table  = 't_supplyer';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    /*添加供应商记录*/
    public function addSupplyer($supplyer) {
        $this->insert($supplyer);
    }

    /*更新供应商记录*/
    public function updateSupplyer($supplyer) {
        $this->update($supplyer);
    }

    /*删除多条供应商信息*/
    public function deleteSupplyers($supplyerIds){
        $supplyerIdArray = explode(",",$supplyerIds);
        foreach ($supplyerIdArray as $supplyerId) {
            $this->supplyerId = $supplyerId;
            $this->delete();
        }
        return count($supplyerIdArray);
    }
    /*根据主键获取供应商记录*/
    public function getSupplyer($supplyerId) {
        $supplyer = SupplyerModel::where("supplyerId",$supplyerId)->find();
        return $supplyer;
    }

    /*按照查询条件分页查询供应商信息*/
    public function querySupplyer($supplyerName, $currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        if($supplyerName != "") $where['supplyerName'] = array('like','%'.$supplyerName.'%');
        $supplyerRs = SupplyerModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = SupplyerModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $supplyerRs;
    }

    /*按照查询条件查询所有供应商记录*/
  public function queryOutputSupplyer( $supplyerName) {
        $where = null;
        if($supplyerName != "") $where['supplyerName'] = array('like','%'.$supplyerName.'%');
        $supplyerRs = SupplyerModel::where($where)->select();
        return $supplyerRs;
    }

    /*查询所有供应商记录*/
    public function queryAllSupplyer(){
        $supplyerRs = SupplyerModel::where(null)->select();
        return $supplyerRs;

    }

}
