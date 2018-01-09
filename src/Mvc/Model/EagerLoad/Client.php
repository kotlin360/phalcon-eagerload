<?php
// +----------------------------------------------------------------------
// | EagerClient.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace Xin\Phalcon\Mvc\Model\EagerLoad;

use Phalcon\Di;
use Phalcon\Mvc\Model;

class Client
{
    /** @var  Model[] 待处理数组 */
    public $models = [];

    public $result;

    public $relationAlias;

    public $relations;

    public $isEmpay = false;

    /** @var Model\Manager */
    public $modelsManager;

    public function __construct($data, $relations, $options = [])
    {
        $this->modelsManager = Di::getDefault()->getShared('modelsManager');

        // 初始化数据
        if ($data instanceof Model\Resultset) {
            foreach ($data as $item) {
                $this->models[] = $item;
            }
        }

        if ($data instanceof Model) {
            $this->models = [$data];
        }

        if (is_array($data)) {
            $this->models = $data;
        }

        $this->result = $data;

        // 初始化关系
        if (!is_array($relations)) {
            $relations = [$relations];
        }

        foreach ($relations as $alias) {
            $this->relationAlias[] = strtolower($alias);
        }

        if (count($this->models) === 0) {
            $this->isEmpay = true;
        } else {
            $class = $this->models[0];
            $className = get_class($class);
            foreach ($this->relationAlias as $alias) {
                $this->relations[$alias] = $this->modelsManager->getRelationByAlias($className, $alias);
            }
        }
    }

    public function handle()
    {
        if (!$this->isEmpay) {
            foreach ($this->relationAlias as $relation) {
                $this->handleSingleRelation($relation);
            }
        }
        return $this->result;
    }

    public function handleSingleRelation($alias)
    {
        // 收集所有需要查询用的ID
        $ids = [];
        if (empty($this->relations[$alias])) {
            throw new EagerLoadException("The {$alias} relation is not exist!");
        }

        /** @var Model\Relation $relation */
        $relation = $this->relations[$alias];

        $relField = $relation->getFields();
        $relReferencedModel = $relation->getReferencedModel();
        $relReferencedField = $relation->getReferencedFields();
        $relIrModel = $relation->getIntermediateModel();
        $relIrField = $relation->getIntermediateFields();
        $relIrReferencedField = $relation->getIntermediateReferencedFields();

        $isManyToManyForMany = false;

        foreach ($this->models as $model) {
            $ids[] = $model->readAttribute($relField);
        }

        dd($ids);
        dd($this->relations[$relation]);
    }
}