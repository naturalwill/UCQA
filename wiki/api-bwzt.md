# bwzt API Document


作者：[Jier](mailto:naturalwill999@gmail.com)

本文档用于描述UCQA的咨询接口


----------


## 索引

* 下行接口
	* [查询咨询分类](#查询咨询分类)
	* [查询咨询列表](#查询咨询列表)
	* [查看咨询及评论](#查看咨询及评论)
	* [查看通知列表](#查看通知列表)
	
* 上行接口
	* [上传图片](#上传图片)
	* [获取咨询发布选项](#获取咨询发布选项)
	* [发布或编辑咨询](#发布或编辑咨询)
	* [更改咨询状态](#更改咨询状态)
	* [获取要编辑的咨询](#获取要编辑的咨询)
	* [删除咨询](#删除咨询)
	* [发表评论](#发表评论)
	* [获取要编辑的评论](#获取要编辑的评论)
	* [提交编辑的评论](#提交编辑的评论)
	* [删除评论](#删除评论)
	* [添加分类](#添加分类)
	* [编辑分类](#编辑分类)
	* [删除分类](#删除分类)
	* [添加编辑删除科室](#添加编辑删除科室)
	* [举报](#举报)
	
----------


## 接口说明


### 下行接口

----------

### 查询咨询分类

域名/index.php/api/space?do=bwzt&view=class

#### 请求参数
	* do -- 固定参数, 必须为bwzt
	* view -- 查询参数
		* class -- 查询咨询分类
		* all -- 查询所有咨询
		* me -- 查询自己发布的咨询
		
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success:代表成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组
		* bwztclassarr -- 咨询分类数组
			* bwztclassid	-- 咨询分类id
			* bwztclassname -- 咨询分类名称
		* bwztdivisionarr -- 科室分类数组
			* bwztdivisionid -- 科室分类id
			* bwztdivisionname -- 科室分类名称

#### 样例
	{
		"code": 0,
		"data": {
			"bwztclassarr": {
				"1": "青少年近视",
				"2": "防盲治盲",
				"3": "飞秒激光治疗近视",
				"4": "清光眼",
				"5": "白内障"
			},
			"bwztdivisionarr": {
				"1": "眼科"
			}
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}
	
[↑返回顶部](#索引)

----------
	
		
### 查询咨询列表

域名/index.php/api/space?uid=1&do=bwzt&view=me&page=0&m_auth=e6f9pQaUDUJ81fP0AaIGX9jlq0WmRMi2kCWjImZc

#### 请求参数
	* do -- 固定参数, 必须为bwzt
	* view -- 查询参数
		* me -- 查询自己发布的咨询
		* all -- 查询所有咨询
	* orderby -- 排序方式，可选参数：
		* dateline -- 时间
		* hot -- 人气
		* replynum -- 评论
		* viewnum -- 点击
	* uid -- 指定用户id的咨询，当view=all时无效
	* bwztclassid -- 咨询分类id(可选)
	* bwztdivisionid -- 科室分类id(可选)
	* page -- 第几页（可选）
	* day -- 按日期筛选
	* m_auth -- API密钥, 由登录后返回
	
#### 样例
	域名/index.php/api/space?uid=1&do=bwzt&bwztclassid=1&view=me //我的咨询按咨询类型分类
	域名/index.php/api/space?uid=1&do=bwzt&bwztdivisionid=1&view=me //我的咨询按科室分类
	域名/index.php/api/space?do=bwzt&view=all //推荐阅读的咨询
	域名/index.php/api/space?do=bwzt&view=all&orderby=dateline //最新发表的咨询
	域名/index.php/api/space?do=bwzt&view=all&orderby=hot&day=7 //最近7天的人气排行
	域名/index.php/api/space?do=bwzt&view=all&orderby=replynum&day=7 //最近7天的评论排行
	域名/index.php/api/space?do=bwzt&view=all&orderby=viewnum&day=7 //最近7天的查看排行

#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, rest_success:代表成功, rest_fail:代表失败
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回两个数据
		* bwzt -- 咨询列表， 条目字段如下
			* bwztid -- 咨询id
			* uid -- 发布咨询的用户id
			* username -- 发布咨询的用户名
			* subject -- 咨询标题
			* message -- 咨询正文
			* bwztclassid -- 咨询分类id
			* bwztdivisionid -- 科室分类id
			* sex -- 性别
			* age -- 年龄
			* pic -- 图片地址
			* viewnum -- 浏览次数
			* replynum -- 回复次数
			* hot -- 热度
			* dateline -- 时间
            * status -- 咨询状态，0:打开（默认），1:关闭
			* name -- 姓名
			* pics -- 图片数组
				* picurl -- 图片地址
				* title -- 图片标题
			* avatar_url -- 用户头像URL
		* count -- 返回列表条目数, 便用遍历
		* totalcount -- 所有咨询总数

#### 样例
	{
		"code": 0,
		"data": {
			"list": [
				{
					"message": "Post测试的正文",
					"target_ids": "",
					"magiccolor": "0",
					"bwztid": "5",
					"topicid": "0",
					"uid": "1",
					"username": "uchome",
					"subject": "Post测试",
					"bwztclassid": "1",
					"bwztdivisionid": "1",
					"sex": "男",
					"age": "18",
					"viewnum": "0",
					"replynum": "0",
					"hot": "0",
					"dateline": "1439106367",
					"pic": "",
					"picflag": "0",
					"noreply": "0",
					"friend": "0",
					"password": "",
					"click_1": "0",
					"click_2": "0",
					"click_3": "0",
					"click_4": "0",
					"click_5": "0"，
					"status": "0",
					"pics": [
						{
							"picurl": "attachment/201510/29/1_1446095112d22d.jpg",
							"title": ""
						},
						{
							"picurl": "attachment/201510/29/1_1446095113sSSO.jpg",
							"title": ""
						}
					],
					"avatar_url": "http://localhost/uc/ucenter/data/avatar/000/00/00/01_avatar_small.jpg",
				},
				{
					"message": "Post测试的正文",
					"target_ids": "",
					"magiccolor": "0",
					"bwztid": "1",
					"topicid": "0",
					"uid": "1",
					"username": "uchome",
					"subject": "Post测试",
					"bwztclassid": "1",
					"bwztdivisionid": "1",
					"sex": "男",
					"age": "18",
					"viewnum": "0",
					"replynum": "0",
					"hot": "0",
					"dateline": "1439106107",
					"pic": "",
					"picflag": "0",
					"noreply": "0",
					"friend": "0",
					"password": "",
					"click_1": "0",
					"click_2": "0",
					"click_3": "0",
					"click_4": "0",
					"click_5": "0"，
					"status": "0",
					"pics": [],
					"avatar_url": "http://localhost/uc/ucenter/data/avatar/000/00/00/01_avatar_small.jpg",
				}
			],
			"count": 2,
			"totalcount": 21
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}
[↑返回顶部](#索引)

----------
### 查看通知列表
域名/index.php/api/space?do=notice&m_auth=9fe4pZuKf%2FGM%2BqdD2EDLIydmhpLg3g69O%2Fdi8CoE649%2BS0FoRx3%2FD9oQ4dLKAUKhhsTPJLsaHqV1Qm3mZ9xT

#### 请求参数
	* do -- 必须为 notice
	* page -- 页码（可选）
	* m_auth -- API密钥, 由登录后返回

#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success: 代表成功
	* msg -- 错误信息, 详细参见附录
	* data[] -- 结果, json数组, 本操作返回两个数据
		notices[] -- 通知
			pages -- 通知总页数
			count -- 本页通知数目
			list[] -- 通知列表
				message -- 正文
				name -- 姓名
				id -- 2
				uid -- 1
				type -- bwztcomment
				isnew -- 是否为新消息, 1代表新消息 (原为new，因与java关键字冲突改为isnew)
				authorid -- 用户id
				author -- 用户名
				note -- 提醒内容
				dateline -- 时间
				isfriend -- 是否朋友, 1代表是朋友
				link -- 链接地址
				do -- 操作类型
				{do}id -- do操作对应的id
				avatar_url -- 用户头像URL

#### 样例
	{
		"code": 0,
		"data": {
			"notices": {
				"pages": 1,
				"count": 2,
				"list": [
					{					
						"name": "doctor",
						"id": "2",
						"uid": "1",
						"type": "bwztcomment",
						"isnew": "0",
						"authorid": "2",
						"author": "doctor",
						"note": "评论了你的咨询 眼睛有点干",
						"dateline": "1449848160",
						"isfriend": 1,
						"style": "",
						"link": "space.php?uid=1&do=bwzt&id=4&cid=2",
						"message": "没多大问题了吧，看了医生之后。",
						"do": "bwzt",
						"bwztid": "102"
					},
					{
						"name": "doctor",
						"id": "1",
						"uid": "1",
						"type": "bwztcomment",
						"isnew": "0",
						"authorid": "2",
						"author": "doctor",
						"note": "评论了你的咨询 眼睛有点干",
						"dateline": "1449842584",
						"isfriend": 1,
						"style": "",
						"link": "space.php?uid=1&do=bwzt&id=4&cid=1",
						"message": "没多大问题了吧，看了医生之后。",
						"do": "bwzt",
						"bwztid": "102"
					}
				]
			}
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}
	
[↑返回顶部](#索引)


----------
### 查看咨询及评论
域名/index.php/api/space?uid=1&do=bwzt&id=2&m_auth=b930qwnWGCaz35IUVDy5F10lkYZgsumt0sQfKAC8YnyB2D4faldYz%2FqB53folCIE7HPuvwHG%2BBItAn5pIXOf

#### 请求参数
	* do -- 必须为bwzt
	* uid -- 用户id
	* id -- 咨询ID
	* page -- 评论的第几页，评论每一页显示30条（可选）
	* m_auth -- API密钥, 由登录后返回

#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success: 代表成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回两个数据
		* bwzt -- 咨询正文， 条目字段如下
			* bwztid -- 咨询id : 
			* uid -- 发布咨询的用户id
			* username -- 发布咨询的用户名
			* subject -- 咨询标题
			* message -- 咨询正文
			* bwztclassid -- 咨询分类id
			* bwztdivisionid -- 科室分类id
			* sex -- 性别
			* age -- 年龄
			* pic -- 图片地址
			* viewnum -- 浏览次数
			* replynum -- 回复次数
			* hot -- 热度
			* dateline -- 时间
            * status -- 咨询状态，0:打开（默认），1:关闭
			* name -- 姓名
			* avatar_url -- 用户头像URL
			* pics -- 图片数组
				* picurl -- 图片地址
				* title -- 图片标题
			* replylist -- 评论列表
				* cid -- 评论id
				* refercid -- 参考评论的id
				* uid -- 用户id
				* id -- 咨询id
				* idtype -- 描述id的类型
				* authorid -- 评论者id
				* author -- 评论者用户名
				* name -- 姓名
				* dateline -- 时间
				* message -- 评论内容
				* avatar_url -- 用户头像URL
			* comment -- 评论该咨询的参数	
				* commentsubmit -- 为true
				* formhash -- 防伪验证码
				* id -- 咨询ID
				* idtype -- bwztid
				* message -- 评论内容
				* refer -- 来源页

#### 样例
	{
		"code": 0,
		"data": {
			"bwzt": {
				"bwztid": "2",
				"uid": "1",
				"tag": [],
				"message": "咨询测试2正文",
				"postip": "unknown",
				"related": [],
				"relatedtime": "0",
				"target_ids": "",
				"hotuser": "",
				"magiccolor": "0",
				"magicpaper": "0",
				"magiccall": "0",
				"topicid": "0",
				"username": "uchome",
				"subject": "咨询测试2标题",
				"bwztclassid": "1",
				"bwztdivisionid": "1",
				"sex": "女",
				"age": "23",
				"viewnum": "0",
				"replynum": "2",
				"hot": "0",
				"dateline": "1439773906",
				"pic": "",
				"picflag": "0",
				"noreply": "0",
				"friend": "0",
				"password": "",
				"click_1": "0",
				"click_2": "0",
				"click_3": "0",
				"click_4": "0",
				"click_5": "0",
				"status": "0",
				"pics": [
					{
						"picurl": "attachment/201510/29/1_1446095112d22d.jpg",
						"title": ""
					},
					{
						"picurl": "attachment/201510/29/1_1446095113sSSO.jpg",
						"title": ""
					}
				],
				"replylist": [
					{
						"cid": "13",
						"uid": "1",
						"id": "2",
						"idtype": "bwztid",
						"authorid": "1",
						"author": "uchome",
						"ip": "unknown",
						"dateline": "1439773921",
						"message": "评论测试2",
						"magicflicker": "0"
					},
					{
						"cid": "14",
						"uid": "1",
						"id": "2",
						"idtype": "bwztid",
						"authorid": "1",
						"author": "uchome",
						"ip": "unknown",
						"dateline": "1439773936",
						"message": "评论测试2第二条",
						"magicflicker": "0"
					}
				],
				"comment": {
					"commentsubmit": true,
					"formhash": "f11624dc",
					"id": "2",
					"idtype": "bwztid",
					"message": "",
					"refer": ""
				},
				"avatar_url": "http://localhost/uc/ucenter/data/avatar/000/00/00/01_avatar_small.jpg",
			}
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}
	
[↑返回顶部](#索引)

----------

### 上行接口

----------

###上传图片

#### 请求参数
	
	GET:
		* ac -- 必须为 upload
		* m_auth -- API密钥, 每次调用接口，需要提供此key以验证用户
	POST:	
		* op -- 操作类型，必须为 uploadphoto2
		* uid -- 上传用户的id
		* topicid -- 必须为 0
		* albumid -- 必须为 0
		* attach -- 要上传的文件
		* uploadsubmit2 -- 必须为 true
        * pic_title -- 图片描述


#### 样例
	Request URL:https://localhost/index.php/api/cp?ac=upload
	Request Method:POST

	================================
	Request Headers

	Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8
	Accept-Encoding:gzip, deflate
	Accept-Language:zh-CN,zh;q=0.8
	Cache-Control:max-age=0
	Connection:keep-alive
	Content-Length:40941
	Content-Type:multipart/form-data; boundary=----WebKitFormBoundaryLHNkp9l4m4HilUEB
	Cookie:uchome_auth=***; uchome_loginuser=***; uchome_sendmail=1; uchome_checkpm=1
	Host:localhost
	Origin:https://localhost
	Referer:https://localhost/index.php/api/cp?ac=upload&albumid=1
	Upgrade-Insecure-Requests:1
	User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36
	================================
	Query String Parameters

	ac:upload
	m_auth:***
	================================
	Request Payload

	------WebKitFormBoundaryLHNkp9l4m4HilUEB
	Content-Disposition: form-data; name="attach"; filename="图片2.jpg"
	Content-Type: image/jpeg


	------WebKitFormBoundaryLHNkp9l4m4HilUEB
	Content-Disposition: form-data; name="pic_title"


	------WebKitFormBoundaryLHNkp9l4m4HilUEB
	Content-Disposition: form-data; name="uploadsubmit"

	true
	------WebKitFormBoundaryLHNkp9l4m4HilUEB
	Content-Disposition: form-data; name="albumid"

	1
	------WebKitFormBoundaryLHNkp9l4m4HilUEB
	Content-Disposition: form-data; name="topicid"

	0
	------WebKitFormBoundaryLHNkp9l4m4HilUEB
	Content-Disposition: form-data; name="formhash"

	31bce83a
	------WebKitFormBoundaryLHNkp9l4m4HilUEB


#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, login_success:代表登录成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据
		* data[pic] -- 上传成功的图片内容，具体条目如下:
			* 上传的用户id -- uid
			* 上传的用户名 -- username
			* 上传时间 -- dateline
			* 上传文件名 -- filename
			* 图片标题 -- title, 默认为空
			* 图片类型 -- type
			* 图片大小 -- size
			* 图片服务端文件名 -- filepath
			* 是否放在远端图像服务器 -- remote
			* 图片id -- picid, <em>重要</em>，当发布咨询时需要关联
			* 是否生成了缩略图 -- thumb, 1代表生成了，0代表没有
			* 缩略图服务端路径 -- pic

#### 样例
	{
	    "code": 0,
	    "data": {
	        "pic": {
	            "albumid": 2,
	            "uid": "6",
	            "username": "test1",
	            "dateline": "1435371306",
	            "filename": "54a8b87ff3040.png",
	            "postip": "222.16.97.91",
	            "title": "",
	            "type": "image/png",
	            "size": 84245,
	            "filepath": "201506/27/6_1435371306ShiS.png",
	            "thumb": 1,
	            "remote": 0,
	            "topicid": 0,
	            "picid": 22,
	            "pic": "attachment/201506/27/6_1435371306ShiS.png.thumb.jpg"
	        }
	    },
	    "msg": "进行的操作完成了",
	    "action": "do_success"
	}
[↑返回顶部](#索引)

----------

### 获取咨询发布选项
域名/index.php/api/cp?ac=bwzt&m_auth=4a41FwaDJCK%2FR4gSeeoLmMgcIQI86mrZroOHitN%2BXdcnlC5bywMW7A%2B%2Bpy4K%2FOmKg07toCgmG3JbmIAZIROc

#### 请求参数
	ac -- 必须为bwzt
	m_auth -- API密钥
	
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success:代表获取成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据
		* bwzt -- 咨询发布选项，具体条目如下:
			subject	-- 标题
			message	-- 正文
			formhash -- 防伪验证码
			tag	-- 标签
			target_names -- 好友名，用空格进行分割
			bwztclassarr -- 咨询分类数组
				bwztclassid	-- 咨询分类id
				bwztclassname -- 咨询分类名称
			bwztdivisionarr -- 科室分类数组
				bwztdivisionid -- 科室分类id
				bwztdivisionname -- 科室分类名称
	
#### 样例
	{
		"code": 0,
		"data": {
			"bwzt": {
				"subject": "",
				"message": "",
				"tag": "",
				"target_names": "",
				"formhash": "f11624dc",
				"bwztclassarr": {
					"1": {
						"bwztclassid": "1",
						"bwztclassname": "咨询分类1"
					}
				},
				"bwztdivisionarr": {
					"1": {
						"bwztdivisionid": "1",
						"bwztdivisionname": "科室一"
					}
				}
			}
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}

[↑返回顶部](#索引)

--------

###发布或编辑咨询

域名/index.php/api/cp?ac=bwzt&bwztid=&m_auth=e97eRR%2FZvF7RBvY1MGbQsNoxDeKD9%2Fo84mmwscQMZcL1Dulc5z%2FLEA6rR2zqxaiqAoLQHfcNLytjO21mWiwJ

#### 请求参数
	GET：
		ac -- 必须为bwzt
		bwztid -- 咨询ID: 新添加咨询时必须为空，修改咨询时需要附上咨询ID
		m_auth -- API密钥，必须
	POST:
		subject	-- 标题（必须）
		bwztclassid	-- 咨询分类id（必须），新建分类的格式为：“new:分类名”，如： bwztclassid=new:咨询分类一
		bwztdivisionid	科室分类id（必须），新建方法同上
		sex	-- 性别（必须）
		age	年龄（必须）
		message	-- 正文（必须）
		makefeed -- 产生动态，默认为1（必须）
		bwztsubmit -- 必须为true
		formhash -- 防伪验证码（必须），[获取方法](#获取咨询发布选项)
		topicid	-- 默认为0
		tag	-- 标签
		noreply -- 值为1时禁止评论，默认不发送此值
		friend	-- 隐私设置（必须）：0为全站用户可见，1为好友可见，2为指定好友可见（selectgroup或者target_names必须有一个有值），3为仅自己可见，4为凭密码查看（必须有password参数）
		password -- friend=4时，必须
		selectgroup	-- friend=2时，生效，好友组id
		target_names -- friend=2时，生效，可以填写多个好友名，请用空格进行分割
		hot	-- 热度
		picids[x] -- 图片，x替换为图片id，值为图片的顺序
		

#### 请求样例

	Query String Parameters
	
	ac:bwzt
	bwztid:

	Request Payload
	
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="bwztclassid"
	
	0
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="subject"
	
	标题测试
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="bwztdivisionid"
	
	0
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="sex"
	
	女
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="age"
	
	12
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="message"
	
	正文测试
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="tag"
	
	
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="friend"
	
	0
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="password"
	
	
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="selectgroup"
	
	
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="target_names"
	
	
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="hot"
	
	
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="makefeed"
	
	1
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="bwztsubmit"
	
	true
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="topicid"
	
	0
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="formhash"
	
	31bce83a
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="picids[19]"
	
	0
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW
	Content-Disposition: form-data; name="picids[20]"
	
	1
	------WebKitFormBoundaryOPSbWGA3RqAvr4KW--

#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success:代表获取成功
	* msg -- 错误信息, 详细参见附录
	* url -- 发布的咨询地址
	
#### 样例
	{
		"code": 0,
		"data": {
			"url": "space.php?uid=1&do=bwzt&id=7"
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}

[↑返回顶部](#索引)


--------

###更改咨询状态

域名/index.php/api/cp?ac=bwzt&bwztid=4&m_auth=4bd2anyxsGyGscicuJazpIH1SvbFv40X5HG5yscg%2FQ%2FFB%2BZJqufM0kkmMLMqOh21XGOLAoXJDsnSwdAk6jKx&bwztsubmit=true&status=1&op=alterstatus

#### 请求参数
	GET：
		ac -- 必须为 bwzt
		bwztid -- 咨询ID: 新添加咨询时必须为空，修改咨询时需要附上咨询ID
		op -- 必须为 alterstatus
		m_auth -- API密钥，必须
		bwztsubmit -- 必须为true
		status -- 咨询状态

#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success:代表获取成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据
		username -- 用户名
		uid -- 用户id
		bwztid -- 咨询ID
		status -- 咨询状态

	
#### 样例
	{
		"code": 0,
		"data": {
			"status": 1,
			"uid": "1",
			"username": "root",
			"bwztid": "4"
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}

[↑返回顶部](#索引)

------

###获取要编辑的咨询

域名/index.php/api/cp?ac=bwzt&bwztid=7&m_auth=bdae9kj409tgKKlWhdwS0YuAeIhAhA%2FdjdlK8HmAfZfsoqy7mK1avotfeBMvJ%2FpnoytIonvJ54730H%2BLqTmJ&op=edit

#### 请求参数
	GET：
		ac -- 必须为bwzt
		bwztid -- 咨询ID（必须）
		op -- 必须为edit
		m_auth -- API密钥，必须
	
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success:代表获取成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据
		bwzt -- 要编辑的咨询的内容，具体条目如下:
			subject	-- 标题
			bwztclassid	-- 咨询分类id，新建分类的格式为：“new:分类名”，如： bwztclassid=new:咨询分类一
			bwztdivisionid -- 科室分类id，新建方法同上
			sex	-- 性别
			age	年龄
			message	-- 正文
			makefeed -- 产生动态，默认为1
			bwztsubmit -- 必须为true
			formhash -- 防伪验证码
			topicid	-- 默认为0
			tag	-- 标签
			noreply -- 值为1时禁止评论，默认不发送此值
			friend	-- 隐私设置：0为全站用户可见，1为好友可见，2为指定好友可见（selectgroup或者target_names必须有一个有值），3为仅自己可见，4为凭密码查看（必须有password参数）
			password -- friend=4时，必须
			selectgroup	-- friend=2时，生效，好友组id
			target_names -- friend=2时，生效，可以填写多个好友名，请用空格进行分割
			hot	-- 热度
			status -- 咨询状态
	
#### 样例
	{
		"code": 0,
		"data": {
			"bwzt": {
				"bwztid": "11",
				"uid": "6",
				"tag": "",
				"message": "咨询正文",
				"postip": "127.0.0.1",
				"related": "",
				"relatedtime": "0",
				"target_ids": "",
				"hotuser": "7",
				"magiccolor": "0",
				"magicpaper": "0",
				"magiccall": "0",
				"topicid": "0",
				"username": "wujinwen",
				"subject": "咨询标题",
				"bwztclassid": "2",
				"bwztdivisionid": "1",
				"sex": "女",
				"age": "44",
				"viewnum": "16",
				"replynum": "1",
				"hot": "1",
				"dateline": "1448555156",
				"pic": "6_1448555147dj9h.jpg.thumb.jpg",
				"picflag": "1",
				"noreply": "0",
				"friend": "0",
				"password": "",
				"click_1": "0",
				"click_2": "0",
				"click_3": "0",
				"click_4": "0",
				"click_5": "0",
				"status": "0",
				"pics": "[{\"picurl\":\"attachment/6_1448555147dj9h.jpg\",\"title\":\"\"},{\"picurl\":\"attachment/6_1448555148V2hH.jpg\",\"title\":\"\"},{\"picurl\":\"attachment/6_1448555149r3qn.jpg\",\"title\":\"\"},{\"picurl\":\"attachment/6_1448555150HYjy.jpg\",\"title\":\"\"},{\"picurl\":\"attachment/6_1448555152Dp3Y.jpg\",\"title\":\"\"},{\"picurl\":\"attachment/6_1448555153MyFM.jpg\",\"title\":\"\"},{\"picurl\":\"attachment/6_14485551549093.jpg\",\"title\":\"\"},{\"picurl\":\"attachment/6_1448555154P5d4.jpg\",\"title\":\"\"},{\"picurl\":\"attachment/6_1448555156xaA8.jpg\",\"title\":\"\"}]",
				"target_names": "",
				"formhash": "f50760f2",
				"bwztclassarr": [],
				"bwztdivisionarr": []
			}
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}

[↑返回顶部](#索引)

###删除咨询

域名/index.php/api/cp?ac=bwzt&bwztid=6&op=delete&m_auth=3997wALXtNOZCxi7P9kR%2FCkc%2BoBTZBWeQtPwezIR%2Fj8pl86fHn4FkECqasu4CspKi4J%2FCipOauO%2F9ktnBW%2F1&deletesubmit=true

#### 请求参数
	GET：
		ac -- 必须为bwzt
		bwztid -- 咨询ID（必须）
		op -- 必须为delete
		m_auth -- API密钥，必须
		deletesubmit -- 只有为true时，才会真正触发删除操作，为空时会返回当前id的咨询的信息
	
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success:代表删除成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据
		* data[url] -- 跳转的url
	
#### 样例
	{
		"code": 0,
		"data": {
			"url": "space.php?uid=1&do=bwzt&view=me"
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}

[↑返回顶部](#索引)

----------

### 发表评论
域名/index.php/api/cp?ac=comment&inajax=1&m_auth=

#### 请求参数	
	GET：
		ac -- 必须为comment
		inajax -- 1 (可选)
		m_auth -- API密钥，必须
	POST: 
		commentsubmit -- 必须为true
		formhash -- 防伪验证码（必须），[获取方法](#查看咨询及评论)
		id -- 咨询ID
		cid -- 引用的评论的ID，非引用为空
		idtype -- bwztid
		message	-- 评论内容
		
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success: 代表成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据
		* commentid -- 评论id
		
#### 样例
	{
		"code": 0,
		"data": {
			"commentid": 13,
			"refer": null
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}
		
[↑返回顶部](#索引)

----------------

### 获取要编辑的评论
域名/index.php/api/cp?ac=comment&op=edit&cid=4&m_auth=

#### 请求参数	
	GET：
		ac -- 必须为comment
		op -- 操作，必须为edit
		m_auth -- API密钥，必须
		cid -- 评论id
		
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success: 代表成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据
		* data[comment]
			* cid -- 评论id
			* uid -- 用户id
			* id -- 咨询id
			* idtype -- 描述id的类型
			* authorid -- 评论者id
			* author -- 评论者用户名
			* dateline -- 时间
			* message -- 评论内容
		
#### 样例
	{
		"code": 0,
		"data": {
			"comment": {
				"cid": "4",
				"uid": "1",
				"id": "1",
				"idtype": "bwztid",
				"authorid": "1",
				"author": "uchome",
				"ip": "unknown",
				"dateline": "1439205588",
				"message": "评论测试的正文",
				"magicflicker": "0"
			}
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}

------------

### 提交编辑的评论
域名/index.php/api/cp?ac=comment&op=edit&cid=4&m_auth=f46cPIqrSHirBDMnMLNE6DLPG9oh5zRelB3cKMD0jKZfzA%2BDmW6WzXwuda%2FGxY8WSq3EDD5zwNqq8o4Iwmjh

#### 请求参数	
	GET：
		ac -- 必须为comment
		cid -- 评论id
		op -- 操作，必须为edit
		m_auth -- API密钥，必须
	POST: 
		editsubmit -- 必须为true
		formhash -- 防伪验证码（必须）
		message	-- 评论内容
		refer -- 来源页(可选)
		
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success: 代表成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据
		* data[refer] -- 来源页
		
#### 样例
	{
		"code": 0,
		"data": {
			"refer": null
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}
		
[↑返回顶部](#索引)

------------

### 删除评论
域名/index.php/api/cp?ac=comment&op=edit&cid=4&m_auth=f46cPIqrSHirBDMnMLNE6DLPG9oh5zRelB3cKMD0jKZfzA%2BDmW6WzXwuda%2FGxY8WSq3EDD5zwNqq8o4Iwmjh

#### 请求参数	
	GET：
		ac -- 必须为comment
		cid -- 评论id
		op -- 操作，必须为delete
		m_auth -- API密钥，必须
		deletesubmit -- 必须为true
		
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success: 代表成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据
		* data[refer] -- 来源页
		
#### 样例
	{
		"code": 0,
		"data": {
			"refer": null
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}
		
[↑返回顶部](#索引)

------------

### 添加分类
域名/index.php/api/cp?ac=bwztclass&op=add&m_auth=0cdbMjWPoEXLF0PKkduyU6JlOTfX7CF3xjpWpOQdFJlZpEs0NmsCVq74lBh%2BH%2FTHCbFO21U5yMfwSnEajIUn&bwztclassname=青少年近视

#### 请求参数	
	GET：
		ac -- bwztclass
		op -- 操作，必须为 add
		bwztclassname -- 分类名称
		m_auth -- API密钥，必须
		
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success: 代表成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据
		* data[bwztclass] -- 添加的分类信息
			* bwztclassid -- id
			* bwztclassname -- 分类名称
			* uid -- 创建者id
			* dateline -- 创建时间
		
#### 样例
	{
		"code": 0,
		"data": {
			"bwztclass": {
				"bwztclassid": "1",
				"bwztclassname": "青少年近视",
				"uid": "1",
				"dateline": "1445177304"
			}
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}
		
[↑返回顶部](#索引)

------------

### 编辑分类
域名/index.php/api/cp?ac=bwztclass&op=edit&bwztclassid=1&bwztclassname=newclassname&editsubmit=true&m_auth=f46cPIqrSHirBDMnMLNE6DLPG9oh5zRelB3cKMD0jKZfzA%2BDmW6WzXwuda%2FGxY8WSq3EDD5zwNqq8o4Iwmjh

#### 请求参数	
	GET：
		ac -- bwztclass
		op -- 操作，必须为 edit
		bwztclassid -- 分类id
		bwztclassname -- 分类名称
		editsubmit -- 必须为true，为空时只是显示当前分类信息而非修改
		m_auth -- API密钥，必须
		
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success: 代表成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据	
		
#### 样例
	{
		"code": 0,
		"data": [],
		"msg": "进行的操作完成了",
		"action": "do_success"
	}
		
[↑返回顶部](#索引)

------------

### 删除分类
域名/index.php/api/cp?ac=bwztclass&op=delete&bwztclassid=1&deletesubmit=true&m_auth=f46cPIqrSHirBDMnMLNE6DLPG9oh5zRelB3cKMD0jKZfzA%2BDmW6WzXwuda%2FGxY8WSq3EDD5zwNqq8o4Iwmjh

#### 请求参数	
	GET：
		ac -- bwztclass
		op -- 操作，必须为 delete
		bwztclassid -- 分类id
		deletesubmit -- 必须为true，为空时只是显示当前分类信息而非删除
		m_auth -- API密钥，必须
		
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success: 代表成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据	
		
#### 样例
	{
		"code": 0,
		"data": [],
		"msg": "进行的操作完成了",
		"action": "do_success"
	}
		
[↑返回顶部](#索引)

------------

### 添加编辑删除科室

添加/编辑/删除科室所有操作和分类类似，只需要将 bwztclass 换成 bwztdivision 即可。

[↑返回顶部](#索引)

------------

### 举报
域名/index.php/api/cp?ac=common&op=report&idtype=comment&id=397&m_auth=f46cPIqrSHirBDMnMLNE6DLPG9oh5zRelB3cKMD0jKZfzA%2BDmW6WzXwuda%2FGxY8WSq3EDD5zwNqq8o4Iwmjh
#### 请求参数	
	GET：
		ac -- common
		op -- 操作，必须为 report
		idtype -- 要举报的类型, bwztid 举报咨询, comment 为举报评论		
		m_auth -- API密钥，必须
		id -- 要举报的内容的ID
	POST:
		reportsubmit -- 必须为 true
		reason -- 举报原因
#### POST样例
	<form method="post" id="reportform_363" name="reportform_363" action="cp.php?ac=common&amp;op=report&amp;idtype=comment&amp;id=363">
	  <table>
		<tbody>
		  <tr>
			<td>
			  感谢您能协助我们一起管理站点，我们会对您的举报尽快处理。
			  <br>
			  请填写举报理由(最多150个字符):
			  <br>
			  <textarea id="reason" name="reason" cols="72" rows="3">
			  </textarea>
			</td>
		  </tr>
		  <tr>
			<td>
			  <input type="hidden" name="reportsubmit" value="true" />
			  <input type="submit" />
			</td>
		  </tr>
		</tbody>
	  </table>
	</form>

#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, report_success: 代表成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据	
		
#### 样例
	{
		"code": 0,
		"data": [],
		"msg": "感谢您的举报，我们会尽快处理",
		"action": "report_success"
	}
		
[↑返回顶部](#索引)

------------
