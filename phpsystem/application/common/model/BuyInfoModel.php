<?php
namespace app\common\model;
use think\Model;

class BuyInfoModel extends Model {
    /*关联表名*/
    protected $table  = 't_buyInfo';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    //进货产品复合属性的获取: 多对一关系
    public function productObjF(){
        return $this->belongsTo('ProductInfoModel','productObj');
    }

    //供应商复合属性的获取: 多对一关系
    public function supplyerObjF(){
        return $this->belongsTo('SupplyerModel','supplyerObj');
    }

    /*添加产品进货记录*/
    public function addBuyInfo($buyInfo) {
        $this->insert($buyInfo);
    }

    /*更新产品进货记录*/
    public function updateBuyInfo($buyInfo) {
        $this->update($buyInfo);
    }

    /*删除多条产品进货信息*/
    public function deleteBuyInfos($buyIds){
        $buyIdArray = explode(",",$buyIds);
        foreach ($buyIdArray as $buyId) {
            $this->buyId = $buyId;
            $this->delete();
        }
        return count($buyIdArray);
    }
    /*根据主键获取产品进货记录*/
    public function getBuyInfo($buyId) {
        $buyInfo = BuyInfoModel::where("buyId",$buyId)->find();
        return $buyInfo;
    }

    /*按照查询条件分页查询产品进货信息*/
    public function queryBuyInfo($productObj, $buyDate, $supplyerObj, $currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        if($productObj['productNo'] != 0) $where['productObj'] = $productObj['productNo'];
        if($buyDate != "") $where['buyDate'] = array('like','%'.$buyDate.'%');
        if($supplyerObj['supplyerId'] != 0) $where['supplyerObj'] = $supplyerObj['supplyerId'];
        $buyInfoRs = BuyInfoModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = BuyInfoModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $buyInfoRs;
    }

    /*按照查询条件查询所有产品进货记录*/
  public function queryOutputBuyInfo( $productObj,  $buyDate,  $supplyerObj) {
        $where = null;
        if($productObj['productNo'] != 0) $where['productObj'] = $productObj['productNo'];
        if($buyDate != "") $where['buyDate'] = array('like','%'.$buyDate.'%');
        if($supplyerObj['supplyerId'] != 0) $where['supplyerObj'] = $supplyerObj['supplyerId'];
        $buyInfoRs = BuyInfoModel::where($where)->select();
        return $buyInfoRs;
    }

    /*查询所有产品进货记录*/
    public function queryAllBuyInfo(){
        $buyInfoRs = BuyInfoModel::where(null)->select();
        return $buyInfoRs;

    }

}
