<?
header('Content-Type: text/plain;'); // хэйдер возвращение данных
set_time_limit(0); // заставляет работать php-скрипт как демон
ob_implicit_flush(true); // отправляет информацию вне зависимости от того, закончил ли работать php-скрипт или нет
$sockets = array(); // все сокеты
$handshakes = array(); // массив рукопожатий (отдаём информацию о том, что это не просто сокет, а сокет по стандарту WebSocket

$sockets["server"] = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);  // создаём главный сокет-сервер, к которому будут обращаться клиенты
socket_bind($sockets["server"], "localhost", 10001); // прикрепляем главный сокет-сервер к адресу ws://localhost:10001/%server%.php
socket_set_option($sockets["server"],SOL_SOCKET,SO_REUSEADDR,1); // разрешаем использовать один порт для нескольких соединений
socket_listen($sockets["server"], 20); // ставим лимит подключений (20)

while (true) { // скрипт работает постоянно
    $changed_sockets = $sockets;
    $num_sockets = socket_select($changed_sockets, $write=NULL,$exceptions=NULL,NULL); // проверим все сокеты на изменение статуса по сравнению с прошлым разом
    foreach($changed_sockets as $socket) { // просмотрим каждый из сокетов, который изменил свой статус
        if($socket == $sockets["server"]) { // значит серверу пришли данные о установлении связи между клиентом и сервером
            if (($client = socket_accept($sockets["server"])) >= 0) $sockets[] = $client; // если удалось установить связь, то добавляем в список всех сокетов
        } else {
            $index = array_search($socket, $sockets);
            $len = @socket_recv($socket,$buffer,4096,0); // получаем информацию от сокета
            if($len == 0) { // если статус сокета изменился, а данных нет, то значит сокет закрылся и мы должны убрать его из списка
                unset($sockets[$index]);
                unset($handshakes[$index]);
                socket_close($socket);
            }
            else if(!isset($handshakes[$index])) { // говорили ли мы уже этому подключению, что мы - WebSocket?
                $buffer = substr($buffer,strpos($buffer,"Sec-WebSocket-Key: ")+19);
                $accept = base64_encode(sha1(substr($buffer,0,strpos($buffer,"\r\n")) . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));
                $upgrade  = "HTTP/1.1 101 Switching Protocols\r\n" .
                    "Upgrade: websocket\r\n" .
                    "Connection: Upgrade\r\n" .
                    "Sec-WebSocket-Accept: {$accept}\r\n\r\n" .
                    "Sec-Websocket-Extensions=x-webkit-deflate-frame"  . chr(0);

                socket_write($socket,$upgrade,strlen($upgrade)); // отправляем пакет о нас
                $handshakes[$index] = true; // теперь сказали
            }
            else {
                echo 'buf:'.$buffer."\r\n";
            }
        }
    }
}
?>