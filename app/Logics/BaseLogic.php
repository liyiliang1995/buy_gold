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
        $oData = $this->model->where($where)->where($orWhere)->find($id);
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

    }





}