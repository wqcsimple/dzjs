//幻灯片js
var id = 0;
jQuery(".index_focus").hover(function(){
jQuery(this).find(".index_focus_pre,.index_focus_next").stop(true, true).fadeTo("show", 1)
},function(){
jQuery(this).find(".index_focus_pre,.index_focus_next").fadeOut()
});
jQuery(".index_focus").slide({
titCell: ".slide_nav a ",
mainCell: ".bd ul",
delayTime: 500,
interTime: 3500,
prevCell:".index_focus_pre",
nextCell:".index_focus_next",
effect: "fold",
autoPlay: true,
trigger: "click",
startFun:function(i){
    $('.slide_nav').find('a').eq(id).find('div').hide();
    $('.slide_nav').find('a').eq(i).find('div').show();
    id = i;
    jQuery(".index_focus_info").eq(i).find("h3").css("display","block").fadeTo(1000,1);
    jQuery(".index_focus_info").eq(i).find(".text").css("display","block").fadeTo(1000,1);
}
});

