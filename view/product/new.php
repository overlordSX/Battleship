<?
View::generateView(
    'view/layouts/header.php',
    ['title' => "Форма добалвения товара"]
);
?>

    <div class="container pt-4">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="row pb-1">
                    <?
                    $fieldNames =
                        [
                            'name' => 'Название товара',
                            'price' => 'Цена товара',
                            'description' => 'Описание товара'
                        ];

                    if (isset($fieldsErrors)) { ?>
                        <div class="row justify-content-start">
                            <div class="col-8">
                                <? foreach ($fieldsErrors as $field => $message) { ?>
                                    <div class="badge bg-danger text-wrap mt-2">
                                        <p class="fs-6 m-0"><?= $fieldNames[$field] . ': ' . $message ?></p>
                                    </div>
                                    <?
                                } ?>
                            </div>
                        </div>
                        <?
                    }
                    ?>
                </div>
                <form action="" method="post">
                    <div class="row pb-1">
                        <label id="labelName"><?= $fieldNames['name'] ?></label>
                        <input class="form-control" id="labelName" type="text" name="name" minlength="3" maxlength="200"
                               required
                               placeholder="<?= $fieldNames['name'] ?>"
                               value="<?= $trimPost['name'] ?? '' ?>">
                    </div>
                    <div class="row pb-1">
                        <label id="labelPrice"><?= $fieldNames['price'] ?></label>
                        <input class="form-control" name="price" id="labelPrice" type="number" min="1"
                               max="999999999999" required
                               placeholder="<?= $fieldNames['price'] ?>"
                               value="<?= $trimPost['price'] ?? '' ?>">
                    </div>
                    <div class="row pb-3">
                        <label id="labelDescription"><?= $fieldNames['description'] ?></label>
                        <textarea class="form-control" rows="10" name="description" id="labelDescription" minlength="2"
                                  maxlength="500" required
                                  placeholder="<?= $fieldNames['description'] ?>"
                        ><?= $trimPost['description'] ?? '' ?></textarea>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-3">
                            <div class="row pb-3">
                                <button type="submit" class="btn btn-primary">Добавить</button>
                            </div>
                            <div class="row">
                                <a href="/" role="button" class="btn btn-outline-secondary">Главная</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

<?
View::generateView(
    'view/layouts/footer.php'
);
?>