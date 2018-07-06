<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/7/5 17:32
 * 描  述:
 */

namespace app\api\lib\enum;


class OrderStatusEnum
{
    //  订单状态枚举
    //  待支付
    const UNPAID = 1;
    //  已支付
    const PAID = 2;
    //  已发货
    const DELIVERED = 3;
    //  已支付，但库存不足
    const PAID_BUT_OUT_OF = 4;

}