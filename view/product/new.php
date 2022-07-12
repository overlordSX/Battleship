<?php
$headerView = new View();
$headerView->generateView(
    'view/layouts/header.php',
    ['title' => "Форма добалвения товара"]
);
?>

    <div class="container">
        <div class="row">
            <form action="/catalog/product/new" method="post">
                <div class="row-3">
                    <input type=" text" name="name" placeholder="Название товара" maxlength="200">
                </div>
                <div class="row-3">
                    <input type="text" name="price" placeholder="Цена товара">
                </div>
                <div class="row-3">
                    <textarea rows="10" cols="45" name="description" maxlength="500"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Создать</button>
                <a href="/"><button type="button" class="btn btn-outline-secondary">Главная</button></a>


            </form>
        </div>
    </div>


<?php
$footerView = new View();
$footerView->generateView(
    'view/layouts/footer.php'
);
?>