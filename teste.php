<?php
require_once __DIR__."/vendor/autoload.php";

use App\Classes\Usuario;
$u = new Usuario('joice@123','1234');
$u->salvarUsuario('', '', '', [], []);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
