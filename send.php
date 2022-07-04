<?php
$fio = $_POST['fio'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$question = $_POST['question'];
$to = "dsoloveychik@yandex.ru";

mail($to, "Обратная связь", "ФИО:".$fio.". E-mail: ".$email.". Телефон: ".$phone.". Вопрос: $question", "From: ".$to);