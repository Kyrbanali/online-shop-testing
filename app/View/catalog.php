<div class="products">
    <?php

    foreach ($products as $product)
    {
        echo '
                    <div class="product">
                    <img src="'.$product["img_url"].'">
                    
                    <h1>'.$product["name"].'</h1>
                    
                    <h2>'.$product["description"].'</h2>
                    
                    <p>'.$product["price"].' ₽</p>
                    <div>
                       <button onclick="" type="submit">Добавить в корзину</button>
                    </div>
                </div>
            ';
    }

    ?>
</div>


<style>

    a {
        &:hover{
            border-bottom: 3px red solid ;
        }
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin: 0;

    }

    header {
        margin-top: 30px;
    }

    h1, h2, h3 {
        text-align: center;
        color: #333;
    }

    .product img {
        max-height: 300px;
        max-width: 300px;
    }

    .products {
        width: 820px;
        margin: 0 auto;
        display: inline-flex;
        flex-direction: row;
        flex-wrap: wrap;

    }


    .product {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 300px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .product img {
        height: 100%;
        width: 100%;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .product h2 {
        color: #666;
        font-size: 18px;
    }

    .product p {
        color: #999;
    }


    nav {
        background-color: #f2f2f2;
        padding: 10px;
    }

    ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: space-between;
    }

    li {
        display: inline;
        margin-right: 10px;
    }

    a {
        text-decoration: none;
        color: #333;
        padding: 5px;
    }

    a:hover {
        text-decoration: none;
        background-color: #333;
        color: #fff;
    }
</style>
