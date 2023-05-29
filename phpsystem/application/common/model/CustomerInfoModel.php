<?php
namespace app\common\model;
use think\Model;

class CustomerInfoModel extends Model {
    /*关联表名*/
    protected $table  = 't_customerInfo';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    /*添加客户信息记录*/
    public function addCustomerInfo($customerInfo) {
        $this->insert($customerInfo);
    }

    /*更新客户信息记录*/
    public function updateCustomerInfo($customerInfo) {
        $this->update($customerInfo);
    }

    /*删除多条客户信息信息*/
    public function deleteCustomerInfos($customerIds){
        $customerIdArray = explode(",",$customerIds);
        foreach ($customerIdArray as $customerId) {
            $this->customerId = $customerId;
            $this->delete();
        }
        return count($customerIdArray);
    }
    /*根据主键获取客户信息记录*/
    public function getCustomerInfo($customerId) {
        $customerInfo = CustomerInfoModel::where("customerId",$customerId)->find();
        return $customerInfo;
    }

    /*按照查询条件分页查询客户信息信息*/
    public function queryCustomerInfo($customerName, $personName, $currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        if($customerName != "") $where['customerName'] = array('like','%'.$customerName.'%');
        if($personName != "") $where['personName'] = array('like','%'.$personName.'%');
        $customerInfoRs = CustomerInfoModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = CustomerInfoModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $customerInfoRs;
    }

    /*按照查询条件查询所有客户信息记录*/
  public function queryOutputCustomerInfo( $customerName,  $personName) {
        $where = null;
        if($customerName != "") $where['customerName'] = array('like','%'.$customerName.'%');
        if($personName != "") $where['personName'] = array('like','%'.$personName.'%');
        $customerInfoRs = CustomerInfoModel::where($where)->select();
        return $customerInfoRs;
    }

    /*查询所有客户信息记录*/
    public function queryAllCustomerInfo(){
        $customerInfoRs = CustomerInfoModel::where(null)->select();
        return $customerInfoRs;

    }

}
