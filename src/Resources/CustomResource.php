<?php


namespace SmallRuralDog\LaravelCustom\Resources;


trait CustomResource
{
    protected $withoutFields = [];//当前模型字段设置
    protected $RelateWithoutFields = [];//关联模型字段设置
    protected $hide = true;
    protected $rs_type = 'default';


    public function getUser()
    {
        return auth('api')->user();
    }

    public function type(string $request)
    {
        $this->rs_type = $request;
        return $this;
    }

    public function hide(array $fields)
    {
        $this->withoutFields = $fields;
        return $this;
    }

    public function show(array $fields)
    {
        $this->withoutFields = $fields;
        $this->hide = false;
        return $this;
    }

    public function relate(array $fields)
    {
        $this->RelateWithoutFields = $fields;
        return $this;
    }

    public function get_relate_data(string $r_name, $res)
    {
        $array = array_get($this->RelateWithoutFields, $r_name, []);
        $collect = collect($array);
        $type = $collect->shift() ?? false;
        $field = $collect->all();
        if ($type) {
            $res = $res->$type($field);
        }
        return $res;
    }

    public function ckFields($name)
    {
        $state = true;
        if (empty($this->withoutFields)) {
            $state = true;
        }
        if ($this->hide && !empty($this->withoutFields) && in_array($name, $this->withoutFields)) {
            $state = false;
        }
        if (!$this->hide && !empty($this->withoutFields) && !in_array($name, $this->withoutFields)) {
            $state = false;
        }
        return $state;

    }

    protected function filterFields($array)
    {
        if (!$this->hide) {
            return collect($array)->only($this->withoutFields)->toArray();
        }
        return collect($array)->forget($this->withoutFields)->toArray();
    }

}