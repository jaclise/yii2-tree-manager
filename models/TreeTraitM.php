<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2016
 * @package   yii2-tree-manager
 * @version   1.0.6
 */

namespace jaclise\tree\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use jaclise\tree\TreeView;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * Trait that must be used by the Tree model
 */
trait TreeTraitM
{
    /**
     * @var array the list of boolean value attributes
     */
    public static $boolAttribs = [
        'active',
        'selected',
        'disabled',
        'readonly',
        'visible',
        'collapsed',
        'movable_u',
        'movable_d',
        'movable_r',
        'movable_l',
        'removable',
        'removable_all'
    ];

    /**
     * @var array the default list of boolean attributes with initial value = `false`
     */
    public static $falseAttribs = [
        'selected',
        'disabled',
        'readonly',
        'collapsed',
        'removable_all'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return ''; // ensure you return a valid table name in your extended class
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $treeQuery = isset(self::$treeQueryClass) ? self::$treeQueryClass : TreeQuery::classname();
        return new $treeQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function createQuery()
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $treeQuery = isset(self::$treeQueryClass) ? self::$treeQueryClass : TreeQuery::classname();
        return new $treeQuery(['modelClass' => get_called_class()]);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
      $treeStructure=[
            'treeAttribute' => 'root',
            'leftAttribute' => 'lft',
            'rightAttribute' => 'rgt',
            'depthAttribute' => 'lvl',
        ];
      $settings = ['class' => NestedSetsBehavior::className()] + $treeStructure;
      return ['tree' => $settings];
    }

    /**
     * Removes a node
     *
     * @param bool $softDelete whether to soft delete or hard delete
     * @param bool $currNode whether to update the current node value also
     *
     * @return bool status of activation/inactivation
     */
    public function removeNode($softDelete = true, $currNode = true)
    {

        $children = $this->children()->all();
        foreach ($children as $child) {
          $child->delete();
        }
        return true;

    }

    public function rules()
    {
        /**
         * @var Tree $this
         */
         $dataStructure=[
            'keyAttribute' => 'id',
            'nameAttribute' => 'name',
            'codeAttribute' => 'code',
            'iconAttribute' => 'icon',
            'iconTypeAttribute' => 'icon_type'
        ];
        $nameAttribute =$codeAttribute= $iconAttribute = $iconTypeAttribute = null;
        extract($dataStructure);
        $attribs = array_merge([$nameAttribute, $iconAttribute, $iconTypeAttribute], static::$boolAttribs);
        $rules = [
            [[$nameAttribute,$codeAttribute], 'required'],
            [$attribs, 'safe']
        ];
        if ($this->encodeNodeNames) {
            $rules[] = [
                $nameAttribute,
                'filter',
                'filter' => function ($value) {
                    return Html::encode($value);
                }
            ];
        }
        if ($this->purifyNodeIcons) {
            $rules[] = [
                $iconAttribute,
                'filter',
                'filter' => function ($value) {
                    return HtmlPurifier::process($value);
                }
            ];
        }
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        /** @noinspection PhpUndefinedClassConstantInspection */
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * Initialize default values
     */
    public function initDefaults()
    {
        /**
         * @var Tree $this
         */
        $iconTypeAttribute = null;
        $this->setDefault($iconTypeAttribute, TreeView::ICON_CSS);
        foreach (static::$boolAttribs as $attr) {
            $val = in_array($attr, static::$falseAttribs) ? false : true;
            $this->setDefault($attr, $val);
        }
    }

    /**
     * Sets default value of a model attribute
     *
     * @param string $attr the attribute name
     * @param mixed  $val the default value
     */
    protected function setDefault($attr, $val)
    {
        if (empty($this->$attr)) {
            $this->$attr = $val;
        }
    }

    /**
     * Parses an attribute value if set - else returns the default
     *
     * @param string $attr the attribute name
     * @param mixed  $default the attribute default value
     *
     * @return mixed
     */
    protected function parse($attr, $default = true)
    {
        return isset($this->$attr) ? $this->$attr : $default;
    }

    /**
     * Validate if the node is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->parse('active');
    }

    /**
     * Validate if the node is selected
     *
     * @return bool
     */
    public function isSelected()
    {
        return $this->parse('selected', false);
    }

    /**
     * Validate if the node is visible
     *
     * @return bool
     */
    public function isVisible()
    {
        return $this->parse('visible');
    }

    /**
     * Validate if the node is readonly
     *
     * @return bool
     */
    public function isReadonly()
    {
        return $this->parse('readonly');
    }

    /**
     * Validate if the node is disabled
     *
     * @return bool
     */
    public function isDisabled()
    {
        return $this->parse('disabled');
    }

    /**
     * Validate if the node is collapsed
     *
     * @return bool
     */
    public function isCollapsed()
    {
        return $this->parse('collapsed');
    }

    /**
     * Validate if the node is movable
     *
     * @param string $dir the direction, one of 'u', 'd', 'l', or 'r'
     *
     * @return bool
     */
    public function isMovable($dir)
    {
        $attr = "movable_{$dir}";
        return $this->parse($attr);
    }

    /**
     * Validate if the node is removable
     *
     * @return bool
     */
    public function isRemovable()
    {
        return $this->parse('removable');
    }

    /**
     * Validate if the node is removable with descendants
     *
     * @return bool
     */
    public function isRemovableAll()
    {
        return $this->parse('removable_all');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $module = TreeView::module();
        $keyAttribute = $nameAttribute = $leftAttribute = $rightAttribute = $depthAttribute = null;
        $treeAttribute = $iconAttribute = $iconTypeAttribute = null;
        extract($module->treeStructure + $module->dataStructure);
        $labels = [
            $keyAttribute => Yii::t('kvtree', 'ID'),
            $nameAttribute => Yii::t('kvtree', 'Name'),
            $leftAttribute => Yii::t('kvtree', 'Left'),
            $rightAttribute => Yii::t('kvtree', 'Right'),
            $depthAttribute => Yii::t('kvtree', 'Depth'),
            $iconAttribute => Yii::t('kvtree', 'Icon'),
            $iconTypeAttribute => Yii::t('kvtree', 'Icon Type'),
            'active' => Yii::t('kvtree', 'Active'),
            'selected' => Yii::t('kvtree', 'Selected'),
            'disabled' => Yii::t('kvtree', 'Disabled'),
            'readonly' => Yii::t('kvtree', 'Read Only'),
            'visible' => Yii::t('kvtree', 'Visible'),
            'collapsed' => Yii::t('kvtree', 'Collapsed'),
            'movable_u' => Yii::t('kvtree', 'Movable Up'),
            'movable_d' => Yii::t('kvtree', 'Movable Down'),
            'movable_l' => Yii::t('kvtree', 'Movable Left'),
            'movable_r' => Yii::t('kvtree', 'Movable Right'),
            'removable' => Yii::t('kvtree', 'Removable'),
            'code'      => Yii::t('kvtree','Code'),
            'removable_all' => Yii::t('kvtree', 'Removable (with children)')
        ];
        if (!$treeAttribute) {
            $labels[$treeAttribute] = Yii::t('kvtree', 'Root');
        }
        return $labels;
    }
}
