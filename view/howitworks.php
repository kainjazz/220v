<?php
/**
 * Created by PhpStorm.
 * User: Koshpaev SV
 * Date: 09.08.2017
 * Time: 22:54
 */

?>
 <!DOCTYPE html>
    <html lang="rus">
    <head>
        <meta charset="UTF-8">
        <title>АстроЦентр Ландра</title>
        <script
            src="http://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous">
         </script>
    </head>
    <body>
        <script>
            function send() {
                $.ajax({
                    type:"PUT",
                    url: 'index.php/api/method',
                    data: {
                        name: "Вася",
                        lastname: "Пупкин"
                    },
                    success: function(msg){
                        $("#ans").html(JSON.stringify(msg));
                    }
                });
            }
        </script>
        <div id="ans">

        </div>
        <button onclick="send();">Отправить запрос</button>
    </body>
    </html>