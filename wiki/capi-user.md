# User API Document


作者：[Jier](mailto:naturalwill999@gmail.com)

本文档用于描述UCQA的用户接口


----------


## 索引

* [获取头像](#获取头像)
* [获取注册验证码](#获取注册验证码)
* [注册](#注册)
* [登录](#登录)
* [注销](#注销)
* [上传头像](#上传头像)
* [修改用户资料](#修改用户资料)

------

* [弃用的接口](#弃用的接口)
	* [获取上传头像的参数](#获取上传头像的参数)
	* [上传头像（旧）](#上传头像（旧）)
	

----------


## 接口说明

----------

### 获取头像
域名/capi/cp.php?ac=avatar&m_auth=3e33iJ8teEsWVs0BqrMWKbnJhrFfz0GgFKVM4z0M%2B9P3jJ2N5oTtgkg1zxpA8unc7YGAsmrS3A1pi6kjrYX9&get_avatar=true&avatar_size=small

#### 请求参数
	* ac -- 固定参数，必须为 avatar
	* get_avatar --固定参数，必须为 true
	* avatar_size -- 头像大小，可以为'big', 'middle', 'small'
	* m_auth -- API密钥，由登录后返回

#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success: 代表成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回两个数据
		* data[avatar_url] -- 头像地址			

#### 样例
	{
		"code": 0,
		"data": {
			"avatar_url": "http://localhost/uc/ucenter/data/avatar/000/00/00/01_avatar_small.jpg"
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}
	
[↑返回顶部](#索引)

----------

### 获取注册验证码

域名/capi/do.php?ac=register&op=seccode

#### 请求参数
	* 操作类型 -- op, 必须为seccode

#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, rest_success:代表成功, rest_fail:代表失败
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回两个数据
		* data[seccode_auth] -- 返回的验证码key，在注册时需要传入
		* data[seccode] -- 验证码

#### 样例
	{
		"code": 0,
		"data": {
			"seccode_auth": "134aqUzRfCZE4so67mO%2FsLOS0Cy8ZmQYuQ5vM06Ll4cm",
			"seccode": "CQ9B"
		},
		"msg": "rest_success",
		"action": "rest_success"
	}
[↑返回顶部](#索引)


----------

### 注册

域名/capi/do.php?ac=register&registersubmit=true&username=test1&password=123&password2=123&seccode=CQ9B&m_auth=134aqUzRfCZE4so67mO%2FsLOS0Cy8ZmQYuQ5vM06Ll4cm

#### 请求参数
	* 操作参数 -- registersubmit, 必须为true
	* 用户名 -- username
	* 用户输入的第一次密码 -- password
	* 用户输入的确认密码 -- password2
	* 用户输入的验证码 -- seccode
	* 生成验证码返回的key -- m_auth

#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, registered:代表注册成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回两个数据
		* m_auth -- API密钥, 每次调用接口，需要提供此key以验证用户
		* space -- 用户空间信息
			* groupid -- 所在用户组（级别）
			* credit -- 金币(这里代表注册增加的金币)
			* experience -- 经验(这里代表注册增加的经验)
			* username -- 用户名
			* name -- 实名
			* namestatus -- 是否实名
			* videostatus -- 是否视频认证
			* friendnum -- 好友数
			* viewnum -- 浏览次数
			* notenum -- 通知数
			* addfriendnum -- 关注数
			* doingnum -- 心情数
			* lastpost -- 最新提交时间
			* lastlogin -- 最新登录时间
			* attachsize -- 空间大小
			* flag -- 是否被禁
			* newpm -- 是否有新通知
			* avatar -- 个人头像
			* bwztnum -- 发布的咨询数
			* winnum -- 赢的次数
			* lostnum -- 输的次数
			* voternum -- 参加咨询的次数

#### 样例
	{
		"code": 0,
		"data": {
			"space": {
				"uid": "6",
				"sex": "0",
				"email": "test1@ucqa.cn",
				"newemail": "",
				"emailcheck": "0",
				"mobile": "",
				"qq": "",
				"msn": "",
				"msnrobot": "",
				"msncstatus": "0",
				"videopic": "",
				"birthyear": "0",
				"birthmonth": "0",
				"birthday": "0",
				"blood": "",
				"marry": "0",
				"birthprovince": "",
				"birthcity": "",
				"resideprovince": "",
				"residecity": "",
				"note": "",
				"spacenote": "",
				"authstr": "",
				"theme": "",
				"nocss": "0",
				"menunum": "0",
				"css": "",
				"privacy": {
					"view": {
						"index": "0",
						"profile": "0",
						"friend": "0",
						"wall": "0",
						"feed": "0",
						"mtag": "0",
						"event": "0",
						"doing": "0",
						"blog": "0",
						"album": "0",
						"share": "0",
						"poll": "0"
					},
					"feed": {
						"doing": 1,
						"blog": 1,
						"upload": 1,
						"share": 1,
						"poll": 1,
						"joinpoll": 1,
						"thread": 1,
						"post": 1,
						"mtag": 1,
						"event": 1,
						"join": 1,
						"friend": 1,
						"comment": 1,
						"show": 1,
						"spaceopen": 1,
						"credit": 1,
						"invite": 1,
						"task": 1,
						"profile": 1,
						"album": 1,
						"click": 1
					}
				},
				"friend": "",
				"feedfriend": "",
				"sendmail": "",
				"magicstar": "0",
				"magicexpire": "0",
				"timeoffset": "",
				"groupid": "0",
				"credit": "25",
				"experience": "15",
				"username": "test1",
				"name": "",
				"namestatus": "0",
				"videostatus": "0",
				"domain": "",
				"friendnum": "0",
				"viewnum": "0",
				"notenum": "0",
				"addfriendnum": "0",
				"mtaginvitenum": "0",
				"eventinvitenum": "0",
				"myinvitenum": "0",
				"pokenum": "0",
				"doingnum": "0",
				"blognum": "0",
				"bwztnum": "0",
				"albumnum": "0",
				"threadnum": "0",
				"pollnum": "0",
				"eventnum": "0",
				"sharenum": "0",
				"dateline": "1435161273",
				"updatetime": "0",
				"lastsearch": "0",
				"lastpost": "0",
				"lastlogin": "1435161273",
				"lastsend": "0",
				"attachsize": "0",
				"addsize": "0",
				"addfriend": "0",
				"flag": "0",
				"newpm": "0",
				"avatar": "0",
				"regip": "222.16.97.129",
				"ip": "222016097",
				"mood": "0",
				"self": 1,
				"friends": [],
				"allnotenum": 0
			},
			"m_auth": "204daENB5ey1olPXDZ1ZSNPUW0%2B4M7rNmMpZ4dM9nhaTrX2FVSZgoIxLrv%2BPSsgwJnZfUEsls6rYoJGQHQog"
		},
		"msg": "注册成功了，进入个人空间",
		"action": "registered"
	}
[↑返回顶部](#索引)


----------


###登录

域名/capi/do.php?ac=login&username=myname&password=mypasswd&loginsubmit=true

域名/capi/do.php?ac=login&m_auth=******** （获取登录信息）

#### 请求参数
	* ac -- login
	* username -- 用户名
	* password -- 密码
	* loginsubmit -- 必须为true

#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, login_success:代表登录成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回两个数据
		* m_auth -- API密钥, 每次调用接口，需要提供此key以验证用户
		* uhash -- 注销时删除session验证
		* formhash -- 提交表单时验证
		* space -- 用户空间信息
			* uid -- 用户id
			* groupid -- 所在用户组（级别）
			* credit -- 金币
			* experience -- 经验
			* username -- 用户名
			* name -- 实名
			* namestatus -- 是否实名
			* videostatus -- 是否视频认证
			* friendnum -- 好友数
			* viewnum -- 浏览次数
			* notenum -- 通知数
			* addfriendnum -- 关注数
			* doingnum -- 心情数
			* lastpost -- 最新提交时间
			* lastlogin -- 最新登录时间
			* attachsize -- 空间大小
			* flag -- 是否被禁
			* newpm -- 是否有新通知
			* avatar -- 个人头像
			* bwztnum -- 发布的咨询数
			* winnum -- 赢的次数
			* lostnum -- 输的次数
			* voternum -- 参加咨询的次数
			* reward -- 操作增加的金币分和经验
			* avatar_url -- 用户头像URL
			* sex_org -- 性别代码
			* sex -- 性别
			* age -- 年龄
            * allnotenum -- 未读消息数量

#### 样例
	{
		"code": 0,
		"data": {
			"m_auth": "4e64L1WXZV%2B0TDmRgCsOe050x1WCD5EGhnZpxlUx7RcoTEA5w10e9dVL1wSLvnSCuv4nFRqBGKOCmf%2F37AKP",
			"uhash": "e8fee782f2e7ddce363edd0979dbd4f8",
			"formhash": "31bce83a",
			"space": {
				"uid": "1",
				"groupid": "1",
				"credit": "85",
				"experience": "75",
				"username": "root",
				"name": "李四",
				"namestatus": "1",
				"videostatus": "0",
				"domain": "",
				"friendnum": "0",
				"viewnum": "2",
				"notenum": "0",
				"addfriendnum": "0",
				"mtaginvitenum": "0",
				"eventinvitenum": "0",
				"myinvitenum": "0",
				"pokenum": "0",
				"doingnum": "0",
				"blognum": "0",
				"albumnum": "0",
				"threadnum": "0",
				"pollnum": "0",
				"eventnum": "0",
				"sharenum": "0",
				"dateline": "1447915083",
				"updatetime": "1447927867",
				"lastsearch": "0",
				"lastpost": "1447927756",
				"lastlogin": "1448159933",
				"lastsend": "0",
				"attachsize": "0",
				"addsize": "0",
				"addfriend": "0",
				"flag": "1",
				"newpm": "0",
				"avatar": "0",
				"regip": "unknown",
				"ip": "0",
				"mood": "0",
				"bwztnum": "3",
				"avatar_url": "http://localhost/uc/ucenter/images/noavatar_small.gif",
				"sex_org": "2",
				"sex": "女",
				"age": 15
			}
		},
		"msg": "登录成功了，现在引导您进入登录前页面 \\1",
		"action": "login_success"
	}
[↑返回顶部](#索引)


----------


### 注销
域名/capi/cp.php?ac=common&op=logout&uhash=00fab750526786312980ece58dff4f12
#### 请求参数
	
	GET:
		* ac -- 必须为 common
		* op -- 必须为 logout
		* uhash -- 登陆时返回
		* m_auth -- API密钥
		
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, security_exit:代表注销成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据
		* unset_session -- 是否移除了session
		* ucsynlogout -- 与ucenter通讯状态
#### 样例
	{
		"code": 0,
		"data": {
			"unset_session": TRUE,
			"ucsynlogout": ''
		},
		"msg": "你已经安全退出了\\1",
		"action": "security_exit"
	}



[↑返回顶部](#索引)

------------

### 上传头像
域名/capi/cp.php?ac=avatar&m_auth=84767REOO3tklWfZvbP%2B8sC78TFNtAZQed7yVorS6MxZKEJS4j5TB1YaaBCjqgrKFwcP3VR4VqdH3XjToQDl&avatarsubmit=ture

#### 请求参数
	GET：
		ac -- avatar
		avatarsubmit -- true
		m_auth -- API密钥，必须
	POST：
		Filedata -- File:图片文件
		
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success: 代表成功
	* msg -- 错误信息, 详细参见附录
	* data[] -- 结果, json数组
		avatar_url -- 头像地址
		
#### 样例

	{
		"code": 0,
		"data": {
			"avatar_url": "http://localhost/uc/ucenter/data/avatar/000/00/00/03_avatar_middle.jpg"
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}

[↑返回顶部](#索引)

------------


### 修改用户资料
域名/capi/cp.php?ac=profile&m_auth=a635o41f9WgOQx3ygOJaBRwVKcl7XAuwdUcdiJu7%2ByVTExIeXLhePqoMkkDoSWuVB36gup6KIgFGOXpGnIRY&op=base
#### 请求参数
	
	GET:
		ac -- 必须为 profile
		op -- 必须为 base
		m_auth -- API密钥
		
	POST：
		profilesubmit -- 必须为true
		formhash -- formhash
		name -- doctor
		sex -- 性别代码，性别一旦设定将不能修改，1:男, 2:女
		birthyear -- 出生年份
		birthmonth -- 出生月份
		birthday -- 出生日期
		------ 以下为可选参数 ------
		friend[birth] -- 生日是否可见，0:全站可见（默认），1:好友可见，3:仅自己可见
		birthprovince -- 家乡省份，如：广东
		birthcity -- 家乡城市，如：东莞
		friend[birthcity] -- 家乡城市是否可见，0:全站可见（默认），1:好友可见，3:仅自己可见
		resideprovince -- 所在省份，如：广东
		residecity -- 所在城市，如：东莞
		friend[residecity] -- 所在地是否可见，0:全站可见（默认），1:好友可见，3:仅自己可见
		
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, update_on_successful_individuals:代表成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组
	
#### 样例
	{
		"code": 0,
		"data": [],
		"msg": "个人资料更新成功了",
		"action": "update_on_successful_individuals"
	}

[↑返回顶部](#索引)


----------

## 弃用的接口


----------

### 获取上传头像的参数
域名/capi/cp.php?ac=avatar&m_auth=5a58LvoIqG0UUCukH2Sj9tHd%2BcZqI3QcKTZ%2BDHfI0qDX5fvU19kTeBS7ZJyLqVz2z4m5v9ffwI4UMyxsgpmn

#### 请求参数	
	GET：
		ac -- avatar
		m_auth -- API密钥，必须
		
#### 返回字段
	* code -- 错误码, 0: 代表成功, 1: 代表失败
	* action -- 错误类型, do_success: 代表成功
	* msg -- 错误信息, 详细参见附录
	* data -- 结果, json数组, 本操作返回一个数据
		* uc_avatar -- 用于上传头像的参数
		
#### 样例
	{
		"code": 0,
		"data": {
			"uc_avatar": {
				"UC_API": "http://localhost/uc/ucenter",
				"appid": "1",
				"input": "6410ZJ9UhzfpHStT%2BWS%2Btjr7dtKULIJq%2BIx0g8IzeLGnqA60t4Yeq%2BLg70ESF9rJd6JK9zqFxU9pwJrLkqfrfIwG5GkdkRTfjs%2BZ9lHAOlFRMQJCyy9OI0k",
				"agent": "bd620a3bfdaadebe49156e19e6b4aebb",
				"ucapi": "http%3A%2F%2Flocalhost%2Fuc%2Fucenter",
				"avatartype": "virtual"
			}
		},
		"msg": "进行的操作完成了",
		"action": "do_success"
	}

[↑返回顶部](#索引)

------------


### 上传头像（旧）
域名/ucenter/index.php?a=uploadavatar4m&m=user&agent=bd620a3bfdaadebe49156e19e6b4aebb&avatartype=virtual&input=6410ZJ9UhzfpHStT%2BWS%2Btjr7dtKULIJq%2BIx0g8IzeLGnqA60t4Yeq%2BLg70ESF9rJd6JK9zqFxU9pwJrLkqfrfIwG5GkdkRTfjs%2BZ9lHAOlFRMQJCyy9OI0k&appid=1&inajax=1

#### 请求参数	
	GET：
		m -- 必须为user
		a -- 必须为uploadavatar4m
		inajax -- 必须为1
		appid -- 通过获取上传头像的参数获得
		input -- 通过获取上传头像的参数获得
		agent -- 通过获取上传头像的参数获得
		avatartype -- 通过获取上传头像的参数获得
	POST：
		Filedata -- File:图片
		
#### 返回字段
	* code -- 错误码, 0: 代表成功
	* action -- 错误类型, do_success: 代表成功
	* data -- 结果, json数组, 本操作返回一个数据	
		
#### 样例
	{
		"code": 0,
		"data": [],
		"action": "do_success"
	}
		


[↑返回顶部](#索引)

----------
