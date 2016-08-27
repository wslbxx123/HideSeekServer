<?php
namespace Home\Common\Param;
/**
 * 情况代码参数
 *
 * @author Two
 */
class CodeParam {
    const SUCCESS = "10000";
    const NOT_LOGIN = "11000";
    
    #region 用户信息
    const PHONE_OR_PASSWORD_WRONG = "10001";
    const PHONE_OR_PASSWORD_EMPTY = "10002";
    const NICKNAME_EMPTY = "10003";
    const FAIL_UPLOAD_PHOTO = "10004";
    const USER_ALREADY_EXIST = "10005";
    const SEX_EMPTY = "10018";
    const CHANNEL_ID_EMPTY = "10022";
    #endregion
    
    #region 商场信息
    const VERSION_OR_MIN_ID_EMPTY = "10006";
    const STORE_ID_EMPTY = "10007";
    const COUNT_EMPTY = "10008";
    const ORDER_ID_EMPTY = "10009";
    const ORDER_ID_WRONG = "10010";
    const REWARD_ID_EMPTY = "10011";
    #endregion
    
    #region 地图信息
    const GOAL_ID_OR_TYPE_EMPTY = "10012";
    const LATITUDE_OR_LONGITUDE_EMPTY = "10013";
    const GOAL_ID_OR_ROLE_EMPTY = "10014";
    const GOAL_DISAPPEAR = "10015";
    const VERSION_EMPTY = "10016";
    const ORIENTATION_EMPTY = "10017";
    const GOAL_ID_EMPTY = "10021";
    #endregion
    
    #region 朋友信息
    const SEARCH_WORD_EMPTY = "10019";
    const FRIEND_ID_EMPTY = "10020";
    const FAIL_SEND_MESSAGE = "10023";
    #endregion
}
