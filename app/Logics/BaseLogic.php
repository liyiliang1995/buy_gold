<?php
/**
 * Created by PhpStorm.
 * User: youxingxiang
 * Date: 2019/7/24
 * Time: 9:45 AM
 */
namespace App\Logics;
use Closure;
use Illuminate\Support\Facades\DB;
class BaseLogic {
    /**
     * @var
     */
    protected $model;
    /**
     * @var
     */
    protected $oModelResult;
    /**
     * @var
     */

    /**
     * BaseLogic constructor.
     * @param $model
     * @param Closure|null $callback
     */
    public function __construct($model, Closure $callback = null)
    {
        $this->model = $model;
        if ($callback instanceof Closure) {
            $callback($this);
        }
    }

    /**
     * @return object
     */
    public function getModel():object
    {
        return $this->model;
    }

    /**
     * @param string $id
     * @param array $aData
     * @param array $aWhereData
     * @return bool
     * @throws \Throwable
     */
    public function update(string $id, array $aData = [],array $aWhereData = []):bool
    {
        $bSaveRes = false;
        $where   = $this->getWhere($aWhereData);
        $this->oModelResult = $this->model->where($where)->findOrFail($id);
        DB::transaction(function () use ($aData,&$bSaveRes,$id) {
            if (method_exists($this->model,'beforeUpdate')) {
                call_user_func([$this->model,'beforeUpdate'],$this->oModelResult);
            }
            $aUpdates = $this->prepare($aData);
            foreach ($aUpdates as $column => $value) {
                /* @var Model $this->model */
                $this->oModelResult->setAttribute($column, is_null($value)?'':$value);
            }
            $bSaveRes = $this->oModelResult->save();

            if (method_exists($this->model,'afterUpdate')) {
                call_user_func([$this->model,'afterUpdate'],$this->oModelResult);
            }
        });
        return $bSaveRes;
    }

    /**
     * @param array $aData
     * @return bool
     * @throws \Throwable
     */
    public function store(array $aData = []):bool
    {
        $bSaveRes = false;
        DB::transaction(function ()use($aData,&$bSaveRes) {
            if (method_exists($this->model,'beforeInsert')) {
                call_user_func([$this->model,'beforeInsert'],$aData);
            }
            foreach ($aData as $column => $value) {
                /* @var Model $this->model */
                $this->model->setAttribute($column, is_null($value)?'':$value);
            }
            $bSaveRes =  $this->model->save();
            if (method_exists($this->model,'afterInsert')) {
                call_user_func([$this->model,'afterInsert']);
            }
        });
        return $bSaveRes;
    }

    /**
     * @param string $id
     * @param array $aWhereData
     * @return bool
     * @throws \Throwable
     */
    public function destroy(string $id,$aWhereData = [])
    {
        $bSaveRes = false;
        if (!empty($id)) {
            $ids = explode(',',$id);
        }
        $where = $this->getWhere($aWhereData);
        DB::transaction(function ()use($where,$ids,&$bSaveRes) {
            if (method_exists($this->model,'beforeDelete')) {
                call_user_func([$this->model,'beforeDelete'],$ids);
            }
            $bSaveRes = $this->model->where($where)->whereIn('id',$ids)->delete();
        });
        return $bSaveRes;
    }

    /**
     * @param array $aWhereData
     * @return int
     */
    public function count(array $aWhereData = []):int
    {
        $where   = $this->getWhere($aWhereData);
        $orWhere = $this->getOrWhere($aWhereData);
        $iData = $this->model->where($where)->where($orWhere)->count();
        return $iData ?? 0;
    }

    /**
     * @param int $id
     * @param array $aWhereData
     * @return mixed
     */
    public function find(int $id,array $aWhereData = [])
    {
        $where   = $this->getWhere($aWhereData);
        $orWhere = $this->getOrWhere($aWhereData);
        $oData = $this->model->where($where)->where($orWhere)->findOrFail($id);
        return $oData;
    }

    /**
     * @param array $aWhere
     * @return Closure
     */
    public function getWhere(array $aWhere):Closure
    {

        $where = function ($query) use ($aWhere) {
            /**
             * 当前项目归属谁
             */
            if (method_exists($this->model,'parentFlag') && !empty($this->model->parentFlag())) {
                foreach ($this->model->parentFlag() as $pk=> $pv) {
                    $query->where($pk,$pv);
                }
            }
            // and where
            if (method_exists($this->model,'getAndFieds') && $aWhere &&
                $this->model->getAndFieds()
            ) {
                foreach ($aWhere as $field => $value) {
                    if (in_array($field,$this->model->getAndFieds()) && isset($value)) {
                        $query->where($field,$value);
                    }

                }
            }
        };
        return $where;
    }

    /**
     * @param $aWhere
     * @return Closure
     */
    public function getOrWhere($aWhere) :Closure
    {
        $where = function ($query) use ($aWhere) {
            // or where
            if (method_exists($this->model,'getOrFields') && !empty($aWhere['search']) &&
                $this->model->getOrFields()
            ) {
                foreach ($this->model->getOrFields() as $field) {
                    $query->orWhere($field, "like", "%" . $aWhere['search'] . "%");
                }
            }
        };
        return $where;
    }

    /**
     * @param array $aData
     */
    protected function prepare($aData = [])
    {
        return $aData;
    }

    /**
     * @param array $aWhereData
     * @return object
     */
    public function query(array $aWhereData = []):object
    {
        $where   = $this->getWhere($aWhereData);
        $orWhere = $this->getOrWhere($aWhereData);
        if (!empty($aWhereData['_sort'])) {
            $aOrder =  explode(',', $aWhereData['_sort']);
        } else {
            $aOrder = ['id','desc'];
        }
//        DB::enableQueryLog();
        $aData = $this->model->where($where)->where($orWhere)->orderBy($aOrder[0],$aOrder[1])->paginate($this->getPage());
//        dd((DB::getQueryLog()));
        return $aData;
    }

    /**
     * @return int
     */
    public function getPage():int
    {
        if (property_exists($this->model,'query_page')) {
            $page = $this->model->query_page;
        } else {
            $page = config("czf.page.default");
        }
        return $page;
    }

    /**
     * @param int $iType 1 自动领取金币消耗 2求购金币获得
     * @param int $iUserId
     * @param int $iEnergy 能量值
     * @return BuyGoldDetail
     * @能量流水 type=3 代表能量
     */
    public function getBuyGoldEnergyFlowDetail(int $iType,int $iUserId,int $iEnergy,string $sOther = ''):object
    {
        $oEnergyModel = new \App\EnergyFlow([
            // 业务类型 1 自动领取金币消耗 2求购金币获得
            'type' => $iType,
            'energy' => $iEnergy,
            'user_id' => $iUserId,
            'other' => $sOther,
        ]);
        $oEnergyModel->save();
        $flow_id = $oEnergyModel->id;
        return $this->getCreateDetailFlow($flow_id,3);
    }

    /**
     * @param  int $iIsIncome 是否收入 0 支出 1收入
     * @param int $iType
     * 业务类型
     * 1 用户消费
     * 2 用户出售
     * 3 用户求购
     * 4 领取金币
     * 5 返回金币池
     * 6 代理注册扣除
     * 7 代理扣除增加
     * 8 15天为登陆扣除
     * 9 后台充值增加
     * 10 后台充值减少
     * 11 彻底燃烧
     * 12 购物金币流向金币池
     * 13 购物消耗金币流向股东
     * @param int $iUserId
     * @param float $fGold
     * @return object
     */
    public function getBuyGoldGoldFlowDetail(int $iIsIncome,int $iType,int $iUserId,float $fGold,string $sOther = ''):Closure
    {
        $oGoldModel = new \App\GoldFlow([
            // $iType 业务类型 1 用户消费 2 用户出售 3 用户求购 4领取金币 5返回金币池 6代理注册扣除 7代理扣除增加 8 15天为登陆扣除
            'type' => $iType,
            'gold' => $fGold,
            'user_id' => $iUserId,
            'is_income' => $iIsIncome,
            'other' => $sOther,
        ]);
        $oGoldModel->save();
        $flow_id = $oGoldModel->id;
        return $this->getCreateDetailFlow($flow_id,1);
    }
    /**
     * @param int $iType 业务类型 1消费获得 2 出售金币消耗
     * @param int $iUserId
     * @param int $iIntegral 积分值
     * @param string $sOther
     * @return object
     */
    public function getBuyGoldIntegralFlowDetail(int $iType,int $iUserId,int $iIntegral,string $sOther = ''):Closure
    {
        $oIntegralModel = new \App\IntegralFlow([
            // 业务类型 1消费获得 2 出售金币消耗
            'type' => $iType,
            'integral' => $iIntegral,
            'user_id' => $iUserId,
            'other' => $sOther,
        ]);
        $oIntegralModel->save();
        $flow_id = $oIntegralModel->id;
        return $this->getCreateDetailFlow($flow_id,2);
    }

    /**
     * @param int $flow_id
     * @param $iType 流水单号类型 1金币流水 2积分流水 3能量流水
     * @return Closure
     */
    public function getCreateDetailFlow(int $flow_id,int $iType):Closure
    {
        $callback = function (string $sClassName)use($flow_id,$iType){
            return new $sClassName([
                // 流水单号类型 1金币流水 2积分流水 3能量流水
                'type'=> $iType,
                'flow_id' => $flow_id,
            ]);
        };
        return $callback;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        if (method_exists($this->model,$name)) {
            $jResult = call_user_func([$this->model, $name],...$arguments);
            return $jResult;
        } else {
            abort(404);
        }

    }





}
