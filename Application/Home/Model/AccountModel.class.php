<?php
namespace Home\Model;
use Think\Model\RelationModel;

class AccountModel extends RelationModel{
    protected $tableName = 'account';
    
    protected $_link = array(
        'record'=>array(
            'mapping_type'=> self::MANY_TO_MANY,//注意：这里跟thinkPHP3.1的区别
            'parent_key'=> 'PK_ID',
            'foreign_key'=> 'account_id',
            'class_name'=>'record',
            'mapping_name'=>'record',
            'relation_foreign_key'=>'account_id',
//            'mapping_fields'=>'bigimg',
//            'as_fields'=>'bigimg:goods_img',
            ),
        );
}
