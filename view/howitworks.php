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
        <title>220v</title>
        <script
            src="http://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous">
         </script>
    </head>
    <body>
        <script>
            function send() {
                var m = $("input");
                var method = "";
                var title=$("input[name=title]").val(),
                    vendor=$("input[name=vendor]").val(),
                    price=$("input[name=price]").val(),
                    short=$("input[name=short]").val();

                for (i = 0; i < m.length; i++) {
                    if(m[i].checked === true) {
                        method = m[i].value;
                    }
                }

                if(method!=="") {
                    $.ajax({
                        type: method,
                        url: '/api/method',
                        data: {
                            title:title,
                            vendor:vendor,
                            price:price,
                            short:short
                        },
                        success: function (msg) {
                            $("#ans").html(JSON.stringify(msg));
                        }
                    });
                } else {
                    $("#ans").html("Надо выбрать метод");
                }
            }
        </script>
        <input type="radio" name="method" value="POST">POST<br/>
        <input type="radio" name="method" value="GET">GET<br/>
        <input type="radio" name="method" value="PUT">PUT<br/>
        <input type="radio" name="method" value="DELETE">DELETE<br/>
        <p>
            <input type="text" placeholder="Заголовок" name="title">
            <input type="text" placeholder="Брэнд" name="vendor">
            <input type="text" placeholder="Цена" name="price">
            <input type="text" placeholder="Краткое описание" name="short">
        </p>
        <p><input type="text" name="text" value="/api/"></p>
        <p>
            <div id="ans">

            </div>
        </p>
        <p>
            <button onclick="send();">Отправить запрос</button>
        </p>
    </body>
    </html>