<?php
setcookie('id_user');
unset($_COOKIE['id_user']);
setcookie('id_user', null, -1, '/');
header('Location: index.php');
?>
<script>window.location.href = "index.php";</script>
<?php
exit;
