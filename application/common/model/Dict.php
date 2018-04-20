<?php

namespace app\common\model;

/**
 * Description of Dict
 *
 * @author wyx
 */
class Dict {

    ////--------------------------- 1.系统分类 ---------------------------------
    //1.1 状态的一般分类
    const STATUS_PRIMARY = 0; //未启用
    const STATUS_NORMAL = 1; //正常
    const STATUS_DISABLED = 2; //禁用
    const STATUS_DELETED = -1; //删除
    //1.2 性别分类
    const SEX_UNKNOWN = 0; //未知
    const SEX_MAN = 1; //男
    const SEX_WOMEN = 2; //女
    //1.3 操作系统分类
    const APP_OS_ANDROID = 1; //安卓 android
    const APP_OS_IOS = 2; //苹果 ios
    ////--------------------------- 2.业务类型 ---------------------------------
    //2.1 营业类型
    const VILLAGE_TYPE_SELF = 1; //直营
    const VILLAGE_TYPE_TOGETHER = 2; //联营
    //2.2 合伙人类型
    const PARTNER_TYPE_SELF = 1; //直营
    const PARTNER_TYPE_JOIN = 2; //加盟
    //2.3 用户站内信通知类型
    const MESSAGE_TYPE_RECHARGE = 1; //充值
    const MESSAGE_TYPE_REFUND = 2; //退款
    const MESSAGE_TYPE_CARD_SEND = 3; //实物卡寄出
    const MESSAGE_TYPE_CARD_ACTIVE = 4; //实物卡激活
    ////--------------------------- 3.业务状态 --------------------------------
    //3.1 实物卡发放状态
    const MEM_CARD_UNACTIVE = 0; //未绑定
    const MEM_CARD_APPLY = 1; //已申请
    const MEM_CARD_SENDING = 2; //邮寄中
    const MEM_CARD_ACTIVED = 3; //已激活
    //3.2 实物卡审核状态
    const MEM_CARD_AUDIT_WAIT = 0; //待审核
    const MEM_CARD_AUDIT_PASS = 1; //通过
    const MEM_CARD_AUDIT_REJECT = 2; //拒绝
    const MEM_CARD_AUDIT_DELETE = -1; //删除
    //3.3 押金缴纳状态
    const DEPOSIT_STATUS_UNPAY = 0; //未缴
    const DEPOSIT_STATUS_PAY = 1; //已缴
    const DEPOSIT_STATUS_REFUNDING = 2; //退款中
    const DEPOSIT_STATUS_REFUND = 3; //已退款
    //3.4 订单状态
    const ORDER_STATUS_USEING = 1; //使用中
    const ORDER_STATUS_UNPAY = 2; //待支付
    const ORDER_STATUS_COMPLETE = 3; //已完成
    const ORDER_STATUS_CANCEL = 4; //已取消
    const ORDER_STATUS_REFUND = 5; //已退款
    //3.5 交易单状态（支付）
    const TRADE_STATUS_UNPAY = 1; //未支付
    const TRADE_STATUS_SUCCESS = 2; //交易成功
    const TRADE_STATUS_FAILED = 3; //交易失败
    const TRADE_STATUS_REFUND = 4; //已退款
    //3.6 交易单状态（退款）
    const REFUND_STATUS_WAITING = 1; //退款中
    const REFUND_STATUS_SUCCESS = 2; //退款完成
    const REFUND_STATUS_FAIL = 3; //退款失败

    /*     * ****** 以下为字典分类详情描述  ********** */

    //STATUS 分类详情
    protected static $STATUS_CONFIG = array(
        self::STATUS_PRIMARY  => array(
            'id'     => self::STATUS_PRIMARY,
            'name'   => 'primary',
            'filter' => '1'
        ),
        self::STATUS_NORMAL   => array(
            'id'     => self::STATUS_NORMAL,
            'name'   => 'normal',
            'filter' => '1'
        ),
        self::STATUS_DISABLED => array(
            'id'     => self::STATUS_DISABLED,
            'name'   => 'disabled',
            'filter' => '1'
        ),
        self::STATUS_DELETED  => array(
            'id'     => self::STATUS_DELETED,
            'name'   => 'deleted',
            'filter' => ''
        )
    );
    //SEX 分类详情
    protected static $SEX_CONFIG = array(
        self::SEX_UNKNOWN => array(
            'id'   => self::SEX_UNKNOWN,
            'name' => '未知'
        ),
        self::SEX_MAN     => array(
            'id'   => self::SEX_MAN,
            'name' => '男'
        ),
        self::SEX_WOMEN   => array(
            'id'   => self::SEX_WOMEN,
            'name' => '女'
        )
    );
    //APP_OS 操作系统分类详情
    protected static $APP_OS_CONFIG = array(
        self::APP_OS_ANDROID => array(
            'id'   => self::APP_OS_ANDROID,
            'name' => 'andriod'
        ),
        self::APP_OS_IOS     => array(
            'id'   => self::APP_OS_IOS,
            'name' => 'ios'
        )
    );
    //VILLAGE_TYPE 分类详情
    protected static $VILLAGE_TYPE_CONFIG = array(
        self::VILLAGE_TYPE_SELF     => array(
            'id'   => self::VILLAGE_TYPE_SELF,
            'name' => '直营'
        ),
        self::VILLAGE_TYPE_TOGETHER => array(
            'id'   => self::VILLAGE_TYPE_TOGETHER,
            'name' => '联营'
        )
    );
    //PARTNER_TYPE 分类详情
    protected static $PARTNER_TYPE_CONFIG = array(
        self::PARTNER_TYPE_SELF => array(
            'id'   => self::PARTNER_TYPE_SELF,
            'name' => '直营合伙人'
        ),
        self::PARTNER_TYPE_JOIN => array(
            'id'   => self::PARTNER_TYPE_JOIN,
            'name' => '加盟合伙人'
        )
    );
    //MEM_CARD 实物卡发放状态
    protected static $MEM_CARD_CONFIG = array(
        self::MEM_CARD_UNACTIVE => array(
            'id'   => self::MEM_CARD_UNACTIVE,
            'name' => 'unactive'
        ),
        self::MEM_CARD_APPLY    => array(
            'id'   => self::MEM_CARD_APPLY,
            'name' => 'apply'
        ),
        self::MEM_CARD_SENDING  => array(
            'id'   => self::MEM_CARD_SENDING,
            'name' => 'sending'
        ),
        self::MEM_CARD_ACTIVED  => array(
            'id'   => self::MEM_CARD_ACTIVED,
            'name' => 'active'
        )
    );
    //MEM_CARD_AUDIT 实物卡审核状态
    protected static $MEM_CARD_AUDIT_CONFIG = array(
        self::MEM_CARD_AUDIT_WAIT   => array(
            'id'     => self::MEM_CARD_AUDIT_WAIT,
            'name'   => 'wait_audit',
            'filter' => ''
        ),
        self::MEM_CARD_AUDIT_PASS   => array(
            'id'     => self::MEM_CARD_AUDIT_PASS,
            'name'   => 'passed',
            'filter' => '1'
        ),
        self::MEM_CARD_AUDIT_REJECT => array(
            'id'     => self::MEM_CARD_AUDIT_REJECT,
            'name'   => 'rejected',
            'filter' => '1'
        ),
        self::MEM_CARD_AUDIT_DELETE => array(
            'id'     => self::MEM_CARD_AUDIT_DELETE,
            'name'   => 'deleted',
            'filter' => ''
        )
    );
    //DEPOSIT_STATUS 退款状态
    protected static $DEPOSIT_STATUS_CONFIG = array(
        self::DEPOSIT_STATUS_UNPAY     => array(
            'id'   => self::DEPOSIT_STATUS_UNPAY,
            'name' => '未缴纳'
        ),
        self::DEPOSIT_STATUS_PAY       => array(
            'id'   => self::DEPOSIT_STATUS_PAY,
            'name' => '已缴纳'
        ),
        self::DEPOSIT_STATUS_REFUNDING => array(
            'id'   => self::DEPOSIT_STATUS_REFUNDING,
            'name' => '退款中'
        ),
        self::DEPOSIT_STATUS_REFUND    => array(
            'id'   => self::DEPOSIT_STATUS_REFUND,
            'name' => '已退款'
        )
    );
    //ORDER_STATUS 订单状态
    protected static $ORDER_STATUS_CONFIG = array(
        self::ORDER_STATUS_USEING   => array(
            'id'    => self::ORDER_STATUS_USEING,
            'name'  => '使用中',
            'color' => 'locked'
        ),
        self::ORDER_STATUS_UNPAY    => array(
            'id'    => self::ORDER_STATUS_UNPAY,
            'name'  => '待支付',
            'color' => 'disabled'
        ),
        self::ORDER_STATUS_COMPLETE => array(
            'id'    => self::ORDER_STATUS_COMPLETE,
            'name'  => '已完成',
            'color' => 'normal'
        ),
        self::ORDER_STATUS_CANCEL   => array(
            'id'    => self::ORDER_STATUS_CANCEL,
            'name'  => '已取消',
            'color' => 'primary'
        ),
        self::ORDER_STATUS_REFUND   => array(
            'id'    => self::ORDER_STATUS_REFUND,
            'name'  => '已退款',
            'color' => 'primary'
        )
    );
    //TRADE_STATUS 交易单状态（支付）
    protected static $TRADE_STATUS_CONFIG = array(
        self::TRADE_STATUS_UNPAY   => array(
            'id'    => self::TRADE_STATUS_UNPAY,
            'name'  => '未支付',
            'color' => 'primary'
        ),
        self::TRADE_STATUS_SUCCESS => array(
            'id'    => self::TRADE_STATUS_SUCCESS,
            'name'  => '交易成功',
            'color' => 'normal'
        ),
        self::TRADE_STATUS_FAILED  => array(
            'id'    => self::TRADE_STATUS_FAILED,
            'name'  => '交易失败',
            'color' => 'deleted'
        ),
        self::TRADE_STATUS_REFUND  => array(
            'id'    => self::TRADE_STATUS_REFUND,
            'name'  => '已退款',
            'color' => 'disabled'
        )
    );
    //REFUND_STATUS 交易单状态（退款）
    protected static $REFUND_STATUS_CONFIG = array(
        self::REFUND_STATUS_WAITING => array(
            'id'    => self::REFUND_STATUS_WAITING,
            'name'  => '退款中',
            'color' => 'primary'
        ),
        self::REFUND_STATUS_SUCCESS => array(
            'id'    => self::REFUND_STATUS_SUCCESS,
            'name'  => '退款成功',
            'color' => 'normal'
        ),
        self::REFUND_STATUS_FAIL    => array(
            'id'    => self::REFUND_STATUS_FAIL,
            'name'  => '退款失败',
            'color' => 'deleted'
        )
    );

    /**
     * [获取字典配置]
     * @param string $name     字典配置名称
     * @param string $filter   过滤条件部 其它-按过滤条件取
     * @return [array]
     */
    public static function getMagicConfig($name, $filter = 'all') {
        $config = strtoupper($name) . '_CONFIG';
        $returnConfig = self::$$config;
        if ($filter !== 'all') {
            foreach ($returnConfig as $key => &$_one) {
                if (isset($_one['filter']) && $_one['filter'] !== $filter) {
                    unset($returnConfig[$key]);
                }
            }
        }
        return $returnConfig;
    }

    /**
     * [获取字典配置] 用于构建Select/Radio/Checkbox选择列表
     * @param string $name     字典配置名称
     * @param string $filter   过滤条件：all-取全部 其它-按过滤条件取
     * @return [array]
     */
    public static function getMagicData($name, $filter = 'all') {
        $config = strtoupper($name) . '_CONFIG';
        $returnConfig = self::$$config;
        $returnData = array();
        foreach ($returnConfig as $key => &$_one) {
            if (isset($_one['filter']) && $_one['filter'] !== $filter) {
                continue;
            }
            $returnData[$key] = lang($_one['name']);
        }
        return $returnData;
    }

    /**
     * [获取字典标题]
     * @param string $name  字典配置名称
     * @param string $key   字典KEY值
     * @return [array]
     */
    public static function getMagicTitle($name, $key, $field = 'name') {
        $config = strtoupper($name) . '_CONFIG';
        $returnConfig = self::$$config;
        return isset($returnConfig[$key]) ? $returnConfig[$key][$field] : '';
    }

}
