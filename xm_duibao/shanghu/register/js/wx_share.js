    var imgUrl = '';   
    var lineLink = '';
    var descContent = '';
    var shareTitle = '';
    var appId = '';
    var timestamp = '';
    var nonceStr = '';
    var signature = '';
    //通过js获取分享所需要的配置信息
    $.ajax({
        url: '/passport/action/wx_share.php',
        async: false,
        data: {url:window.location.href},
        type: 'post',
        dataType : "json",
        success: function(data)
        {
          imgUrl = data.imgUrl;
          lineLink = data.lineLink;
          descContent = data.descContent;
          shareTitle = data.shareTitle;
          appId = data.appId;
          timestamp = data.timestamp;
          nonceStr = data.nonceStr;
          signature = data.signature;
        }
    });

  wx.config({
    debug: false,
    appId: appId,
    timestamp: timestamp,
    nonceStr: nonceStr,
    signature: signature,
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
      'onMenuShareTimeline',
      'onMenuShareAppMessage',
      'onMenuShareQQ',
      'onMenuShareWeibo'
    ]
  });

  // 在这里调用 API
  wx.ready(function () {
        //分享到朋友圈
         wx.onMenuShareTimeline({
             title: shareTitle, // 分享标题
             desc: descContent, // 分享描述
             link: lineLink, // 分享链接
             imgUrl: imgUrl, // 分享图标
             success: function () { 
                  //分享成功
             },
             cancel: function () { 
                  //分享失败   
              }
         });

        //分享给朋友
         wx.onMenuShareAppMessage({
                title: shareTitle, // 分享标题
                desc: descContent, // 分享描述
                link: lineLink, // 分享链接
                imgUrl: imgUrl, // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () { 
                    //分享成功
                },
                cancel: function () { 
                    //分享失败   
                }
            });

            //分享到QQ
           wx.onMenuShareQQ({
                title: shareTitle, // 分享标题
                desc: descContent, // 分享描述
                link: lineLink, // 分享链接
                imgUrl: imgUrl, // 分享图标
                success: function () { 
                    //分享成功
                },
                cancel: function () { 
                    //分享失败
                }
            });

           //分享到腾讯微博
           wx.onMenuShareWeibo({
                title: shareTitle, // 分享标题
                desc: descContent, // 分享描述
                link: lineLink, // 分享链接
                imgUrl: imgUrl, // 分享图标
                success: function () { 
                    //分享成功
                },
                cancel: function () { 
                    //分享失败
                }
            });
        
  });