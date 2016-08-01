<?php
namespace Home\DataAccess;
/**
 * 操作拉数据版本表(admin_pull_version)
 *
 * @author Two
 */
class PullVersionManager {
    public function getFriendVersion() {
        $Dao = M("pull_version");
        $version = $Dao->find();
        return $version['friend_version'];
    }
}
