<?php
if (@session_start() == false) {
    session_destroy();
    session_name('S1sTem@RsIsT3m@#$%$@pP');
    session_start();
}
echo "<script language=Javascript> location.href=\"principalrs.php?page=rspermisos_index&mod=1\";</script>";