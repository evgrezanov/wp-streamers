<?php
?>
<div class="row">
    <div class="col-md-12">
        <span class='mb-12'></span>
        <h2>Личные данные</h2>

    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="first_name">Имя</label>
            <input type="text" class="form-control" id="first_name" name="first_name" maxlength="70"
                value="<?= esc_attr($data['first_name'][0]) ?>" required="" placeholder="Имя*">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="last_name">Фамилия</label>
            <input type="text" class="form-control" id="last_name" name="last_name" maxlength="70"
                value="<?= esc_attr($data['last_name'][0]) ?>" required="" placeholder="Фамилия*">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="user_nickname">Никнейм </label>
            <input type="text" class="form-control" id="user_nickname" name="user_nickname" maxlength="70"
                value="<?= esc_attr($data['user_nickname'][0]) ?>" placeholder="Никнейм" required="">
        </div>
    </div>

    <div class="col-md-6">
        <span class="text-muted">Никнейм отображается рядом с вашим именем в вашем профиле. А еще ник формирует
            уникальный адрес (URL) вашего профиля</span>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="description">О себе</label>
            <textarea rows="5" name="description" maxlength="" class="form-control " id="description" wrap="virtual"
                placeholder="Расскажите немного о себе. Почему вы даете прогнозы, какие виды спорта и Лиги больше интересуют?"><?= esc_textarea( $data['description'][0] ); ?></textarea>
        </div>
    </div>
</div>