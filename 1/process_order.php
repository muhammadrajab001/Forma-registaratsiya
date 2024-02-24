<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['order_submitted'])) {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $hidden_field = $_POST['hidden_field'];

        if (!preg_match("/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/", $phone)) {
            die("Некорректный номер телефона");
        }

        $data = [
            'stream_code' => 'iu244',
            'client' => [
                'name' => $name,
                'phone' => $phone,
            ],
            'sub1' => $hidden_field,
        ];

        $url = 'https://order.drcash.sh/v1/order';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer NWJLZGEWOWETNTGZMS00MZK4LWFIZJUTNJVMOTG0NJQXOTI3',
        ];
        $options = [
            'http' => [
                'header' => implode("\r\n", $headers),
                'method' => 'POST',
                'content' => json_encode($data),
            ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result !== false) {
            $_SESSION['order_submitted'] = true;
            header("Location: thank_you.php");
            exit();
        } else {
            die("Ошибка при отправке заказа");
        }
    } else {
        die("Вы уже отправили заказ");
    }
} else {
    die("Доступ запрещен");
}
?>
