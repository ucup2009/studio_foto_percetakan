<?php
session_start();
session_unset();
session_destroy();
header("Location: ../index.php");
exit;
?>
<span class="material-symbols-outlined text-[20px]">photo_library</span>