<?php

use yii\db\Migration;
use yii\db\Schema;

class m160229_125352_create_tree_table extends Migration
{
    public function up()
    {
        // MySql table options
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        // table tree
        $this->createTable(
            '{{%tree}}',
            [
                'id' => Schema::TYPE_PK. ' comment "Unique tree node identifier"',
                'root' => Schema::TYPE_INTEGER . '(11) comment "Tree root identifier"',
                'lft' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "Nested set left property"',
                'rgt' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT "Nested set right property"',
                'lvl' => Schema::TYPE_INTEGER . '(5) NOT NULL COMMENT "Nested set level / depth"',
                'name' => Schema::TYPE_STRING . '(60) NOT NULL COMMENT "The tree node name / label"',
                'code' => Schema::TYPE_STRING . '(20) NOT NULL COMMENT "the tree code"',
                'icon' => Schema::TYPE_STRING . '(255) COMMENT "The icon to use for the node"',
                'icon_type' => Schema::TYPE_INTEGER .'(1) NOT NULL Default 1 COMMENT "Icon Type: 1 = CSS Class, 2 = Raw Markup" ',
                'active' => Schema::TYPE_INTEGER .'(1) NOT NULL default True COMMENT "Whether the node is active (will be set to false on deletion)" ',
                'selected' => Schema::TYPE_INTEGER .'(1) NOT NULL default false COMMENT "Whether the node is selected/checked by default"',
                'disabled' => Schema::TYPE_INTEGER .'(1) NOT NULL default false COMMENT "Whether the node is enabled" ',
                'readonly' => Schema::TYPE_INTEGER .'(1) NOT NULL default false COMMENT "Whether the node is read only (unlike disabled - will allow toolbar actions)" ',
                'visible' => Schema::TYPE_INTEGER .'(1) NOT NULL default true COMMENT "Whether the node is visible" ',
                'collapsed' => Schema::TYPE_INTEGER .'(1) NOT NULL default false COMMENT "Whether the node is collapsed by default" ',
                'movable_u' => Schema::TYPE_INTEGER .'(1) NOT NULL default true COMMENT "Whether the node is movable one position up" ',
                'movable_d' => Schema::TYPE_INTEGER .'(1) NOT NULL default true COMMENT "Whether the node is movable one position down" ',
                'movable_l' => Schema::TYPE_INTEGER .'(1) NOT NULL default true COMMENT "Whether the node is movable to the left (from sibling to parent)" ',
                'movable_r' => Schema::TYPE_INTEGER .'(1) NOT NULL default true COMMENT "Whether the node is movable to the right (from sibling to child)" ',
                'removable' => Schema::TYPE_INTEGER .'(1) NOT NULL default true COMMENT "Whether the node is removable (any children below will be moved as siblings before deletion)" ',
                'removable_all' => Schema::TYPE_INTEGER .'(1) NOT NULL default false COMMENT "Whether the node is removable along with descendants" ',
            ],
            $tableOptions
        );

        //primary key
        $this->createIndex('tbl_tree_NK1','{{%tree}}','root');
        $this->createIndex('tbl_tree_NK2','{{%tree}}','lft');
        $this->createIndex('tbl_tree_NK3','{{%tree}}','rgt');
        $this->createIndex('tbl_tree_NK4','{{%tree}}','lvl');
        $this->createIndex('tbl_tree_NK5','{{%tree}}','active');

        $this->execute("INSERT INTO {{%tree}} (id, root, lft, rgt, lvl, name, icon, icon_type, active, selected, disabled, readonly, visible, collapsed, movable_u, movable_d, movable_l, movable_r, removable, removable_all, code) values

                        (1, 1, 1, 2, 0, '岗位分类', '', true, true, false, false, false, true, false, false, false, false, false, false, false, 'A_'),
                        (2, 2, 1, 2, 0, '企业分类', '', true, true, false, false, false, true, false, false, false, false, false, false, false, 'B_'),
                        (3, 3, 1, 2, 0, '应聘行业;', '', true, true, false, false, false, true, false, false, false, false, false, false, false, 'C_'),
                        (4, 4, 1, 2, 0, '教育程度', '', true, true, false, false, false, true, false, false, false, false, false, false, false, 'D_'),
                        (5, 5, 1, 2, 0, '所在部门', '', true, true, false, false, false, true, false, false, false, false, false, false, false, 'E_'),
                        (6, 6, 1, 2, 0, '语言', '', true, true, false, false, false, true, false, false, false, false, false, false, false, 'F_'),
                        (7, 7, 1, 2, 0, '经营模式', '', true, true, false, false, false, true, false, false, false, false, false, false, false, 'G_'),
                        (8, 8, 1, 2, 0, '公司规模', '', true, true, false, false, false, true, false, false, false, false, false, false, false, 'H_'),
                        (9, 9, 1, 2, 0, '公司性质', '', true, true, false, false, false, true, false, false, false, false, false, false, false, 'I_'),
                        (10, 10, 1, 2, 0, '熟练程度', '', true, true, false, false, false, true, false, false, false, false, false, false, false, 'J_'),
                        (100, 100, 1, 8, 0, '工作性质', '', true, true, false, false, false, true, false, false, false, false, false, false, false, 'K_'),
                        (11, 11, 1, 2, 0, '工作经验', '', true, true, false, false, false, true, false, false, false, false, false, false, false, 'L_'),
                        (12, 12, 1, 2, 0, '职位月薪', '', true, true, false, false, false, true, false, false, false, false, false, false, false, 'M_')
                        ");
    }

    public function down()
    {
        $this->dropTable('{{%tree}}');
        return true;
    }

}
