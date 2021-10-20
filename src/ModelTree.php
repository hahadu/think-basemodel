<?php

namespace Hahadu\ThinkBaseModel;
use think\model\relation\HasMany;
use think\model\relation\BelongsTo;
use Hahadu\DataHandle\Data;
trait ModelTree
{

    /**
     * @var array
     */
    protected static $branchOrder = [];

    /**
     * @var string
     */
    protected $parentColumn = 'pid';

    /**
     * @var string
     */
    protected $titleColumn = 'title';

    /**
     * @var string
     */
    protected $orderColumn = 'order';

    /**
     * @var \Closure
     */
    protected $queryCallback;

    /******
     * @return HasMany
     */
    public function children():HasMany
    {
        return $this->hasMany(static::class, $this->parentColumn);
    }

    /******
     * @return BelongsTo
     */
    public function parent():BelongsTo
    {
        return $this->belongsTo(static::class, $this->parentColumn);
    }

    /**
     * @return string
     */
    public function getParentColumn()
    {
        return $this->parentColumn;
    }

    /**
     * Set parent column.
     *
     * @param string $column
     */
    public function setParentColumn($column)
    {
        $this->parentColumn = $column;
    }

    /**
     * Get title column.
     *
     * @return string
     */
    public function getTitleColumn()
    {
        return $this->titleColumn;
    }

    /**
     * Set title column.
     *
     * @param string $column
     */
    public function setTitleColumn($column)
    {
        $this->titleColumn = $column;
    }

    /**
     * Get order column name.
     *
     * @return string
     */
    public function getOrderColumn()
    {
        return $this->orderColumn;
    }

    /**
     * Set order column.
     *
     * @param string $column
     */
    public function setOrderColumn($column)
    {
        $this->orderColumn = $column;
    }



}