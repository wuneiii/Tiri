<div class="parNode">
    <p class="parNodeTitle expand">系统控件</p>
    <ul style="display: none;">
        <li class="page"><a href="<?php echo U('scrollPic' , 'list')?>" target="mainFrame">全站滚动图片</a></li>
    </ul>

</div>



<script type="text/javascript">
    $(function(){
        $('.parNodeTitle').click(function(){
            $(this).parent().find('ul').toggle();
            if($(this).attr('class') == 'parNodeTitle expand'){
                $(this).removeClass('parNodeTitle expand');
                $(this).addClass('parNodeTitle on');
            }else{
                $(this).removeClass('parNodeTitle on');
                $(this).addClass('parNodeTitle expand');
            }
        });

    });



</script>
