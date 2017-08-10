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
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
    </head>
    <body>
        <script>
            function send() {
                var m = $("input");
                var method = "";
                var title=$("input[name=title]").val(),
                    vendor=$("input[name=vendor]").val(),
                    price=$("input[name=price]").val(),
                    text=$("input[name=text]").val(),
                    short=$("input[name=short]").val();

                for (i = 0; i < m.length; i++) {
                    if(m[i].checked === true) {
                        method = m[i].value;
                    }
                }

                if(method!=="") {

                    $.ajax({
                        type: method,
                        url: text,
                        data: {
                            title:title,
                            vendor:vendor,
                            price:price,
                            short:short
                        },
                        success: function (msg) {
                            $("#ans").text(msg);

                        }
                    });
                } else {
                    $("#ans").html("Надо выбрать метод");
                }

                $('pre code').each(function(i, block) {
                    hljs.highlightBlock(block);
                });
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
        <pre id="ans"><code class="json">...</code></pre>
        </p>
        <p>
            <button onclick="send();">Отправить запрос</button>
        </p>
        <br/>
            <div style="background-color:#2b2b2b;color:#a9b7c6;font-family:'Courier New';font-size:9,0pt;">
            <p>Примеры запросов:</p>
            <p>GET /api/IBM/ - Получить все товары брэнда IBM</p>
            <p>GET /api/IBM/10 - Получить все бренды с количеством товаров больше 10</p>
            <p>PUT /api/item/ - Добавить новый товар (характеристики вносятся в полях формы)</p>
            <p>PUT /api/brand/ - Добавить новый брэнд(характеристики вносятся в полях формы)</p>
            <p>DELETE /api/brand/IBMS - Удалить брэнд IBMS</p>
            <p>DELETE /api/item/216623 - Удалить товар с id 216623</p>
        </div>
        <p>&nbsp;</p>

    </body>
    </html>