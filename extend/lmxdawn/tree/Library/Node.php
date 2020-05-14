<?php
// +----------------------------------------------------------------------
// | ThinkPHP 5 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 .
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Byron Sampson <lmxdawn@gmail.com>
// +----------------------------------------------------------------------
namespace lmxdawn\tree\Library;

/**
 * 节点类
 */
class Node
{
    /**
     * 以正确顺序排列的子节点数组
     *
     * @var array
     */
    protected $children = [];

    /**
     * 默认配置
     * @var [type]
     */
    protected $config = [
        'pk'     => 'id',
        'parent' => 'pid',
    ];

    /**
     * 修饰符
     * @var array
     */
    protected $icon = ['│', '├', '└'];

    /**
     * 节点属性索引数组
     * @var array
     */
    protected $properties = [];

    /**
     * 用于树型数组完成递归格式的全局变量
     * @var [type]
     */
    private $formatTree = [];

    /**
     * 构造方法
     * Node constructor.
     * @param array $properties
     * @param array $config
     */
    public function __construct(array $properties, array $config = [])
    {
        if (!empty($config)) {
            $this->config = array_merge($this->config, array_change_key_case($config, CASE_LOWER));
        }
        if (!empty($properties)) {
            $this->properties = array_change_key_case($properties, CASE_LOWER);
        }
    }

    /**
     * 添加到树
     * @param Node $child
     */
    public function addChild(Node $child)
    {
        $this->children[]                           = $child;
        $child->parent                              = $this;
        $child->properties[$this->config['parent']] = $this->properties[$this->config['pk']];
    }

    /**
     * @return array
     */
    public function formatNode()
    {
        $this->toFormatTree($this);
        return $this->formatTree;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * 获取后裔
     * @param bool $includeSelf
     * @return array
     */
    public function getDescendants($includeSelf = false)
    {
        $descendants = $includeSelf ? [$this] : [];
        foreach ($this->children as $childnode) {
            $descendants[] = $childnode;
            if ($childnode->hasChildren()) {
                $descendants = array_merge($descendants, $childnode->getDescendants());
            }
        }
        return $descendants;
    }

    /**
     * 是否存在子节点
     * @return bool
     */
    public function hasChildren()
    {
        return count($this->children) ? true : false;
    }

    /**
     * 返回属性数组
     * @return array
     */
    public function toArray()
    {
        return $this->properties;
    }

    /**
     * 格式化树 - 用于选择框或列表
     * @param $data
     * @param int $level
     */
    protected function toFormatTree($data, $level = 0)
    {
        $title = $this->config['title'];

        if (is_object($data)) {
            //$tmp_str                      = str_repeat("&nbsp;", $level * 4);
            $this->properties['level']      = $level;
            $this->properties['title_show'] = $data->properties[$title];
            if (!$data->children) {
                array_push($this->formatTree, $data->toArray());
            } else {
                array_push($this->formatTree, $data->toArray());
                $this->toFormatTree($data->children, $level + 1); //进行下一层递归
            }
        } else {
            $total  = count($data);
            $number = 1;
            foreach ($data as $key => $val) {
                $tmp_str = str_repeat("&nbsp;", $level * 4);
                if ($total == $number) {
                    $tmp_str .= $this->icon[2];
                } else {
                    $tmp_str .= $this->icon[1];
                }
                $val->properties['level']      = $level;
                $val->properties['title_show'] = $level == 0 ? $val->properties[$title] . "&nbsp;" : $tmp_str . "&nbsp;&nbsp;" . $val->properties[$title] . "&nbsp;";
                if (!$val->children) {
                    array_push($this->formatTree, $val->toArray());
                } else {
                    array_push($this->formatTree, $val->toArray());
                    $this->toFormatTree($val->children, $level + 1); //进行下一层递归
                }
                $number++;
            }
        }
        return;
    }
}