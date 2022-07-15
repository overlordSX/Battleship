<?php
/**
 * @var string $title
 * @var string $activeLink
 */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>
<body>
<header class="p-3 bg-white text-dark border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-start">
            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <?
                $activeStyle = "nav-link px-2 text-secondary";
                $notActiveStyle = "nav-link px-2 text-black";
                $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                $navLinks =
                    [
                        'Каталог' => '/catalog',
                        'Админка' => '/admin'
                    ];

                foreach ($navLinks as $title => $href) { ?>
                    <li><a href="<?= $href ?>" class="<?= $currentPath === $href ? $activeStyle : $notActiveStyle?>"><?= $title ?></a></li>
                <? } ?>
            </ul>
            <div class="text-end">
                <a role="button" class="btn btn-warning" href="/catalog/product/new">Добавить Товар</a>
            </div>
        </div>
    </div>
</header>