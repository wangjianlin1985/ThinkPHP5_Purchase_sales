<?php
namespace app\common\model;
use think\Model;

class SellInfoModel extends Model {
    /*关联表名*/
    protected $table  = 't_sellInfo';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    //销售产品复合属性的获取: 多对一关系
    public function productObjF(){
        return $this->belongsTo('ProductInfoModel','productObj');
    }

    //销售客户复合属性的获取: 多对一关系
    public function customerObjF(){
        return $this->belongsTo('CustomerInfoModel','customerObj');
    }

    /*添加产品销售记录*/
    public function addSellInfo($sellInfo) {
        $this->insert($sellInfo);
    }

    /*更新产品销售记录*/
    public function updateSellInfo($sellInfo) {
        $this->update($sellInfo);
    }

    /*删除多条产品销售信息*/
    public function deleteSellInfos($sellIds){
        $sellIdArray = explode(",",$sellIds);
        foreach ($sellIdArray as $sellId) {
            $this->sellId = $sellId;
            $this->delete();
        }
        return count($sellIdArray);
    }
    /*根据主键获取产品销售记录*/
    public function getSellInfo($sellId) {
        $sellInfo = SellInfoModel::where("sellId",$sellId)->find();
        return $sellInfo;
    }

    /*按照查询条件分页查询产品销售信息*/
    public function querySellInfo($productObj, $sellDate, $customerObj, $currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        if($productObj['productNo'] != 0) $where['productObj'] = $productObj['productNo'];
        if($sellDate != "") $where['sellDate'] = array('like','%'.$sellDate.'%');
        if($customerObj['customerId'] != 0) $where['customerObj'] = $customerObj['customerId'];
        $sellInfoRs = SellInfoModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = SellInfoModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $sellInfoRs;
    }

    /*按照查询条件查询所有产品销售记录*/
  public function queryOutputSellInfo( $productObj,  $sellDate,  $customerObj) {
        $where = null;
        if($productObj['productNo'] != 0) $where['productObj'] = $productObj['productNo'];
        if($sellDate != "") $where['sellDate'] = array('like','%'.$sellDate.'%');
        if($customerObj['customerId'] != 0) $where['customerObj'] = $customerObj['customerId'];
        $sellInfoRs = SellInfoModel::where($where)->select();
        return $sellInfoRs;
    }

    /*查询所有产品销售记录*/
    public function queryAllSellInfo(){
        $sellInfoRs = SellInfoModel::where(null)->select();
        return $sellInfoRs;

    }

}
