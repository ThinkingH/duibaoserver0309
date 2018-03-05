

//定义公司swiper
var swiper = new Swiper('.Shops-container', {
    slidesPerView: 3,
    paginationClickable: true,
    spaceBetween: 10
});


///初始化
$(function () {
    if ($(".Shops-container .swiper-slide .on").length == 0)
    {
        $(".Shops-container .swiper-slide a").first().addClass("on");
    }


    var $obj = $(".Shops-container .swiper-slide .on").parent();
    var index = $obj.index() - 1;
    swiper.slideTo(index, 0, false);

    ///初始化加载默认的公司现货资源
    InitSupplyListData();

    //选中公司事件
    $(".Shops-container .swiper-slide a").click(function () {
        $(".Shops-container .swiper-slide a").removeClass("on");
        $(this).addClass("on");
      
        /*window.location.href = '/supply/company?companyId=' + $(this).attr("companyid");*/
      //  InitSupplyListData();
    });


});


function InitSupplyListData() {
    var companyid = $(".Shops-container .swiper-slide .on").attr("companyid");
   // var url = '/supply/getgoodssupplyincompany?companyId=' + companyid;
//    ./interface/companyinit.php
    var url = './interface/companyinit.php';

    LoadSupply(url, 10, '4');
}
