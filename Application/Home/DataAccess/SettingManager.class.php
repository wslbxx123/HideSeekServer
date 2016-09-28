<?php
namespace Home\DataAccess;
/**
 * 操作设置表(admin_setting)
 *
 * @author Two
 */
class SettingManager {
    public function getSetting() {
        $Dao = M("setting");
        $setting = $Dao->find();
        return $setting;
    }
}
