<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/11 16:07
 * 描  述:
 */

namespace app\api\model;


class Theme extends BaseModel
{
    //  创建需要隐藏的字段信息
    protected $hidden = [
      'delete_time',
      'topic_img_id',
      'head_img_id',
      'update_time'
    ];

    //  将调用数据库的方法写入到模型文件中来
    public static function getThemeByID($ids)
    {
        $result = self::with('topicImg,headImg')
            ->select($ids);
        return $result;
    }

    /**
     *  创建Theme表和Image表的topic图关联关系
     *  这里属于一对一的关系所以使用belongsTo关联关系
     */
    public function topicImg()
    {
        return $this->belongsTo('Image','topic_img_id','id');
    }

    /**
     *  创建Theme表和Image表的head图关联关系
     *  这里属于一对一的关系所以使用belongsTo关联关系
     */
    public function headImg()
    {
        return $this->belongsTo('Image','head_img_id','id');
    }
}