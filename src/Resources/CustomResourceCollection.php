<?php


namespace SmallRuralDog\LaravelCustom\Resources;


trait CustomResourceCollection
{
    protected $withoutFields = [];//当前模型字段设置
    protected $RelateWithoutFields = [];//关联模型字段设置
    private $hide = true;
    protected $type = 'default';


    public function type(string $request)
    {
        $this->type = $request;
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

    public function toArray($request)
    {
        if (!empty($this->pageMeta())) {
            return [
                'data' => $this->collection->map(function ($item) {
                    $item->type($this->type);
                    $item->relate($this->RelateWithoutFields);
                    if (!$this->hide) {
                        $item->show($this->withoutFields);
                    } else {
                        $item->hide($this->withoutFields);
                    }
                    return $item;
                }),
                'meta' => $this->when(!empty($this->pageMeta()), $this->pageMeta())
            ];
        } else {
            return $this->collection->map(function ($item) {
                $item->type($this->type);
                $item->relate($this->RelateWithoutFields);
                if (!$this->hide) {
                    $item->show($this->withoutFields);
                } else {
                    $item->hide($this->withoutFields);
                }
                return $item;
            });
        }

    }

    //定义这个方法主要用于分页，单用josn返回的时候是没有 links 和 meta 的
    public function pageMeta()
    {
        try {
            return [
                'current_page' => (int)$this->resource->currentPage(),
                'last_page' => (int)$this->resource->lastPage(),
                'per_page' => (int)$this->resource->perPage(),
                'total' => (int)$this->resource->total(),
            ];
        } catch (\BadMethodCallException $exception) {
            return [];
        }
    }

}