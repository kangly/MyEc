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

namespace lmxdawn\tree;

use lmxdawn\tree\Exceptions\InvalidParentException;
use lmxdawn\tree\Library\Node;
use think\facade\Config;

class Tree
{
    /**
     * 默认配置
     * @var [type]
     */
    protected $_config = [
        'pk'     => 'id',
        'parent' => 'pid',
        'title'  => 'title',
        'child'  => '_child',
        'root_id' => 0,
    ];

    /**
     * @var array
     */
    protected $nodes = [];

    /**
     * @var object 对象实例
     */
    protected static $_instance;

    /**
     *  类架构函数 （私有构造函数，防止外界实例化对象）
     * @param array $options [description] 参数
     */
    private function __construct($options = [])
    {
        // 将应用配置替换默认配置
        if ($tree = Config::has('tree')) {
            $this->_config = array_merge($this->_config, $tree);
        }
        // 将传递过来的参数替换
        if (!empty($options) && is_array($options)){
            $this->_config = array_merge($this->_config, $options);
        }
    }

    /**
     * 私有克隆函数，防止外办克隆对象
     */
    private function __clone() {}

    /**
     * 初始化 （获取单例）
     * @param array $options 参数
     * @return object|static
     */
    public static function getInstance($options = []){
        if (is_null(self::$_instance)) {
            self::$_instance = new static($options);
        }
        return self::$_instance;
    }

    /**
     * 创建树形结构
     * @param array $list
     * @param array $options
     * @return Tree|object
     */
    public static function build(array $list = [],array $options = [])
    {
        self::getInstance($options)->buildTree($list);

        return self::getInstance();
    }

    /**
     * 获取某个节点
     * @param int $id
     * @return mixed
     */
    public function getNodeById($id = 0)
    {
        if (empty($this->nodes[$id])) {
            throw new \InvalidArgumentException("Invalid node primary key $id");
        }
        if (!$id) {
            $id = $this->_config['root_id'];
        }
        return $this->nodes[$id];
    }

    /**
     * 获取根结点
     * @return mixed
     */
    public function getRoot()
    {
        return $this->nodes[$this->_config['root_id']]->getChildren();
    }

    /**
     * 获取格式化后的根结点数组
     * @return array
     */
    public function getRootFormat()
    {
        $result = [];
        $roots  = $this->getRoot();
        foreach ($roots as $key => $node) {
            $v = $node->formatNode();
            $result = array_merge($result, $v);
        }
        return $result;
    }

    /**
     * 获取树的多维数组
     * @param array $array
     * @param int $pid
     * @param int $level
     * @param int $maxLevel
     * @return array
     */
    public function getMerge($array = [], $pid = 0, $level = 1, $maxLevel = 3){
        $list = [];
        if ($level <= $maxLevel){
            foreach ($array as $v) {
                if ($v[$this->_config['parent']] == $pid) {
                    $v['level'] = $level;
                    $v[$this->_config['child']] = $this->getMerge($array, $v['id'], $level + 1, $maxLevel);
                    $list[]     = $v;
                }
            }
        }

        return $list;
    }

    /**
     * 获取整个树
     * @return array
     */
    public function getTree()
    {
        $nodes = [];
        foreach ($this->nodes[$this->_config['root_id']]->getDescendants() as $subnode) {
            $nodes[] = $subnode;
        }
        return $nodes;
    }

    /**
     * 创建树的核心方法
     * @param array $data
     */
    protected function buildTree(array $data)
    {
        $children                             = [];
        $properties                           = [];
        $properties[$this->_config['pk']]     = $this->_config['root_id'];
        $properties[$this->_config['parent']] = null;
        // 创建跟节点
        $this->nodes[$this->_config['root_id']] = $this->createNode($properties);

        foreach ($data as $row) {
            if (is_object($row)) {
                $row = $row->toArray();
            }
            $this->nodes[$row[$this->_config['pk']]] = $this->createNode($row);
            if (empty($children[$row[$this->_config['parent']]])) {
                $children[$row[$this->_config['parent']]] = [$row[$this->_config['pk']]];
            } else {
                $children[$row[$this->_config['parent']]][] = $row[$this->_config['pk']];
            }
        }

        foreach ($children as $pid => $childids) {
            foreach ($childids as $id) {
                if ((string) $pid === (string) $id) {
                    throw new InvalidParentException(
                        "节点ID引用了自己的ID作为父ID"
                    );
                }
                if (isset($this->nodes[$pid])) {
                    $this->nodes[$pid]->addChild($this->nodes[$id]);
                } else {
                    throw new InvalidParentException(
                        "节点ID{$id}使用不存在的父级ID{$pid}"
                    );
                }
            }
        }
    }

    /**
     * 创建节点
     * @param array $properties
     * @return Node
     */
    protected function createNode(array $properties)
    {
        return new Node($properties, $this->_config);
    }
}