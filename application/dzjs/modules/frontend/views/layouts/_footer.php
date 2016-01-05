<?php
use fayfox\models\Option;
use fayfox\models\Analyst;

?>
<!-- footer -->
<div class="foot" style="clear:both">
    <div class="footin">
        <h1><img src="<?php echo $this->staticFile('images/footerlogo.png') ?>"></h1>
        <div class="fp">
            <p><?php echo Option::get('phone') ?> <a href="" style="color:#313131">更多联系方式</a></p>
            <p><?php echo Option::get('copyright') ?> </p>
            <p>今日访问量：<?php echo Analyst::model()->getPV() ?> 总访问量：<?php echo Analyst::model()->getAllPV() ?></p>
        </div>

    </div>
</div>

<script type="text/javascript" src="<?php echo $this->url() ?>js/custom/analyst.min.js"></script>
<script>
    $(function(){
        _fa.init();
    });
    /* 加入收藏代码 */
    function AddFavorite(sURL, sTitle) {
        try {
            window.external.addFavorite(sURL, sTitle)
        } catch (e) {
            try {
                window.sidebar.addPanel(sTitle, sURL, "")
            } catch (e) {
                alert("加入收藏失败，请使用Ctrl+D进行添加")
            }
        }
    }
</script>

