<html>

<head>
<title>Simple Crud</title>
</head>
<body>

<br/>

<?php
$this->load->library('mycrud');

$config['table'] = 'pegawai';
$config['subject'] = 'Pegawai';

$mycrud = new Mycrud();

$mycrud->initialize($config);
$mycrud->render();
?>

</body>
</html>
