<?php
namespace Home\Common\Param;
/**
 * 情况信息参数
 *
 * @author Two
 */
class MessageParam {
    const NOT_LOGIN = "用户未登录";
    
    #region 用户信息
    const SUCCESS = "获得数据成功";
    const PHONE_OR_PASSWORD_WRONG = "手机密码错误";
    const PHONE_OR_PASSWORD_EMPTY = "手机密码为空";
    const NICKNAME_EMPTY = "昵称为空";
    const FAIL_UPLOAD_PHOTO = "上传照片失败";
    const USER_ALREADY_EXIST = "用户已经存在";
    const SEX_EMPTY = "性别为空";
    const CHANNEL_ID_EMPTY = "设备识别ID值为空";
    #endregion
    
    #region 商场信息
    const VERSION_OR_MIN_ID_EMPTY = "版本号或者记录最小ID值为空";
    const STORE_ID_EMPTY = "商品ID值为空";
    const COUNT_EMPTY = "商品数量为空";
    const ORDER_ID_EMPTY = "订单ID值为空";
    const ORDER_ID_WRONG = "订单编号错误";
    const REWARD_ID_EMPTY = "奖品ID值为空";
    #endregion
    
    #region 地图信息
    const GOAL_ID_OR_TYPE_EMPTY = "目标ID或目标类型为空";
    const LATITUDE_OR_LONGITUDE_EMPTY = "经纬度不能为空";
    const GOAL_ID_OR_ROLE_EMPTY = "目标ID或用户角色为空";
    const GOAL_DISAPPEAR = "目标已经消失";
    const VERSION_EMPTY = "版本号为空";
    const ORIENTATION_EMPTY = "方向值不能为空";
    const GOAL_ID_EMPTY = "目标ID为空";
    #endregion
    
    #region 朋友信息
    const SEARCH_WORD_EMPTY = "查询字符串为空";
    const FRIEND_ID_EMPTY = "朋友ID为空";
    const FAIL_SEND_MESSAGE = "发送消息失败";
    #endregion
}
