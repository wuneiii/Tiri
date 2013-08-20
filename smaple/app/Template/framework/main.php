<?php
    $this -> render('framework/header.php');
?>
<div id="mainBody">
    <table>
        <tr>
            <td id="leftMenu" width="138px">
                <?php
                    $this->render('framework/leftMenu.php');
                ?>
            </td>
            <td id="rightBody" valign="top" width="90%">
                <table>
                    <tr>
                        <td id="navBar">
                            <?php echo AdminUtil::printNavBar();?>
                        </td>
                    </tr>
                    <tr>
                        <td id="frameBox">
                        <iframe id="mainFrame" name="mainFrame" style="width: 100%; height: 540px; border: 1px solid #fff; padding: 5px;"></iframe>      
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<?php
    $this -> render('framework/footer.php');
?>
