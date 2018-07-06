**接口**<br>

`/api/:version/banner/:id - 获取id对应的Banner信息`<br>

`/api/:version/theme/:id  - 根据参数获取对应的专题信息及其下商品信息`<br>
`/api/:version/theme/?ids=:ids1,:ids2,:ids3  - 根据参数获取对应的专题组信息`<br>

`/api/:version/category/all  - 获取所有的商品分类信息`<br>

`/api/:version/products/by_category?id=:id  - 根据当前的分类信息获取对应的商品信息`<br>
`/api/:version/products/recent?count=:id  - 根据参数获取最近的商品`<br>
`/api/:version/products/:id'  - 根据商品主键获取某一商品的详细信息`<br>

`/api/:version/token/user  - 获取当前用户的token令牌`<br>

`/api/:version/address  - 用户提交个人地址信息`<br>

`/api/v1/order - 订单提交`<br>
`/api/v1/pay/pre_order - 发起微信支付请求`<br>
