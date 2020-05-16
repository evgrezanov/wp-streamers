<?php
function ld_registration_form( $email, $ld_status, $ld_secondname, $ld_name, $ld_patronymic, $ld_city, $ld_specialization_array) {
	?>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="reg_form" name="reg_form">
    <?php wp_nonce_field('Hn9rU3ek0rG8rb','bH37nfG7ej5G0F3'); ?>
    <div>
        <label class="zag">E-mail <strong>*</strong>
            <br>
            <input class="form-control" type="email" name="email" required="required" placeholder="E-mail"
                value="<?php echo ( isset( $_POST['email']) ? $email : null ); ?>">
        </label>
    </div>
    <div>
        <label class="zag">Статус <strong>*</strong>
            <br>
            <select required="required" name="ld_status">
                <option>Выберите статус</option>
                <option <?php selected( $ld_status, "lawyer" ); ?> value="lawyer">Юрист</option>
                <option <?php selected( $ld_status, "advocate" ); ?> value="advocate">Адвокат</option>
                <option <?php selected( $ld_status, "notary" ); ?> value="notary">Нотариус</option>
                <option <?php selected( $ld_status, "bankruptcy_commissioner" ); ?> value="bankruptcy_commissioner">
                    Арбитражный управляющий</option>
            </select>
        </label>
    </div>

    <div>
        <span class="zag">Ваша специализация <strong>*</strong></span>
        <br>
        <?php
	    ld_view_chekbox($ld_specialization_array);
	    ?>
    </div>
    <div>
        <label class="zag">Город <strong>*</strong>
            <br>
            <?php
		    ld_city_selectbox($ld_city);
		    ?>
        </label>
    </div>
    <div>
        <label class="zag">Фамилия <strong>*</strong>
            <br>
            <input class="form-control" type="text" name="ld_secondname" required="required" placeholder="Фамилия"
                value="<?php echo ( isset( $_POST['ld_secondname']) ? $ld_secondname : null ); ?>">
        </label>
    </div>
    <div>
        <label class="zag">Имя <strong>*</strong>
            <br>
            <input class="form-control" type="text" name="ld_name" required="required" placeholder="Имя"
                value="<?php echo ( isset( $_POST['ld_name']) ? $ld_name : null ); ?>">
        </label>
    </div>
    <div>
        <label class="zag">Отчество <strong>*</strong>
            <br>
            <input class="form-control" type="text" name="ld_patronymic" required="required" placeholder="Отчество"
                value="<?php echo ( isset( $_POST['ld_patronymic']) ? $ld_patronymic : null ); ?>">
        </label>
    </div>
    <br>
    <div class="g-recaptcha" data-sitekey="6LeEMiITAAAAADBf9SEBHOQVZ7ZMHm6bfvYFk8T3"></div>
    <br>
    <input type="submit" class="btn btn_green" name="submit" value="Регистрация" />
</form>
<?php
}




function ld_registration_validation( $username, $email, $ld_status, $ld_secondname, $ld_name, $ld_patronymic, $ld_captcha, $ld_city, $ld_specialization_array)  {
	global $reg_errors;

	$reg_errors = new WP_Error;

	// секретный ключ капчи
	$secret = "6LeEMiITAAAAAJGEO6wSN_FnuUfDD5q4g0DbxEbF";

	// пустой ответ
	$response = null;

	// проверка секретного ключа
	$reCaptcha = new ReCaptcha($secret);

	//проверка капчи
	if ($ld_captcha) {
		$response = $reCaptcha->verifyResponse(
	        $_SERVER["REMOTE_ADDR"],
	        $ld_captcha
	    );
	}

	if (!($response != null && $response->success)) {
    	$reg_errors->add('captha_none', 'Капча не введена или введена неверно!');
    }

	/*почта*/
	if ( empty( $email ) ) {
    	$reg_errors->add('email_none', 'Адрес электронной почты обязателен для заполнения!');
	}

	if ( !is_email( $email ) ) {
    	$reg_errors->add( 'email_invalid', 'Вы ввели недопустимый адрес электронной почты!' );
	}

	if ( email_exists( $email ) ) {
    	$reg_errors->add( 'email', 'Данный адрес электронной почты уже используется!' );
	}
	/* статус*/
	if ( empty( $ld_status ) ) {
    	$reg_errors->add('ld_status_none', 'Статус обязателен для заполнения!');
	}

	if ( $ld_status != 'lawyer' and $ld_status != 'advocate' and $ld_status != 'notary' and $ld_status != 'bankruptcy_commissioner' ) {
    	$reg_errors->add('ld_status_noncorrect', 'Статус заполнен некорректно!');
	}
	/*ld_secondname*/
	if ( empty( $ld_secondname ) ) {
    	$reg_errors->add('ld_secondname_none', 'Фамилия обязательна для заполнения!');
	}
	/*ld_name*/
	if ( empty( $ld_name ) ) {
    	$reg_errors->add('ld_name_none', 'Имя обязательно для заполнения!');
	}
	/*ld_patronymic*/
	if ( empty( $ld_patronymic ) ) {
    	$reg_errors->add('ld_patronymic_none', 'Отчество обязательно для заполнения!');
	}
	/*ld_city*/
	if ( empty( $ld_city ) ) {
    	$reg_errors->add('ld_city_none', 'Город обязателен для заполнения!');
	}
	/*ld_specialization_array*/
	if ( empty( $ld_specialization_array ) ) {
    	$reg_errors->add('ld_specialization_array_none', 'Специализация обязательна для заполнения!');
	}

	if ( is_wp_error( $reg_errors ) ) {

		$er = '0';

	    foreach ( $reg_errors->get_error_messages() as $error ) {

	    	if( $er >= '1') continue;
	    	$er = $er + 1;

	        echo '<div class="bs-callout-danger"><h4>Ошибка!</h4><p>';
	        echo $error;
	        echo '</p></div>';

	    }

	}
}

function ld_complete_registration($username, $email, $ld_status, $ld_secondname, $ld_name, $ld_patronymic, $ld_city, $ld_specialization_array) {

    global $reg_errors, $username, $email, $ld_status, $ld_secondname, $ld_name, $ld_patronymic, $ld_city, $ld_specialization_array;

	if ( 1 > count( $reg_errors->get_error_messages() ) ) {

    	$random_password = wp_generate_password(15, false); /// генерируем пароль

		$new_user_id = wp_create_user( $username, $random_password, $email ); // регестрируем нового пользователя

		$userdata = array(
			'ID' => $new_user_id,
			'role' => 'lawyer' // (строка) роль пользвателя
		);

		wp_update_user($userdata); //присваеваем пользователю роль.

		//wp_new_user_notification( $new_user_id, $random_password); //отправляем письмо администратору и пользователю.

		$ld_unique_string = md5($username.$new_user_id);

		update_user_meta( $new_user_id, 'ld_unique_string', $ld_unique_string );  // записываем уникальную строку для активации почты

		update_user_meta( $new_user_id, 'ld_mail_confirm', '0' ); // записываем в мета поле 0, что значит что поста еще не подтвержденна

		update_user_meta( $new_user_id, 'ld_status', sanitize_text_field($ld_status) );

		update_user_meta( $new_user_id, 'ld_secondname', sanitize_text_field($ld_secondname) );

		update_user_meta( $new_user_id, 'ld_name', sanitize_text_field($ld_name) );

		update_user_meta( $new_user_id, 'ld_patronymic', sanitize_text_field($ld_patronymic) );

		update_user_meta( $new_user_id, 'ld_city', sanitize_text_field($ld_city) );

		update_user_meta( $new_user_id, 'ld_specialization', $ld_specialization_array);

		// удалим фильтры, которые могут изменять заголовок $headers
		remove_all_filters( 'wp_mail_from' );
		remove_all_filters( 'wp_mail_from_name' );

		$ld_link_mail = '<a href="'.home_url().'/?do='.$new_user_id.'&code='.$ld_unique_string.'">'.home_url().'/?do='.$new_user_id.'&code='.$ld_unique_string.'</a>';

		ob_start();
		?>
Здравствуйте, <?php echo $ld_secondname.' '.$ld_name.' '.$ld_patronymic; ?>.<br>

Вы зарегистрировались на сайте law-divorce.ru<br><br>

Ваш логин: <?php echo $username; ?><br>

Ваш пароль: <?php echo $random_password; ?><br><br>

Подтвердите свой e-mail, перейдя по ссылке: <?php echo $ld_link_mail; ?><br>

Письмо создано автоматически, отвечать на него не нужно. Если вы получили данное письмо по ошибке, просто проигнорируйте
его.
<?php

		$ld_mail = ob_get_clean();

		$headers[] = 'From: law-divorce.ru <s@law-divorce.ru>';  ////// заголовки поправить
		$headers[] = 'content-type: text/html';

		wp_mail( $email, 'Пожалуйста, подтвердите регистрацию на сайте', $ld_mail, $headers);

		echo '<div class="bs-callout-success"><h4>Регистрация успешно завершена!</h4><p>Спасибо! Для входа в личный кабинет вам необходимо подтвердить регистрацию! На ваш электронный адрес отправлено письмо со ссылкой для подтверждения. Чтобы подтвердить свой адрес, откройте ссылку из письма. В противном случае вы не сможете получить доступ к личному кабинету и функциям сайта</p></div>';

        //Авторизируем нового пользователя
		$creds = array();
        $creds['user_login'] = $username;
        $creds['user_password'] = $random_password;
        $creds['remember'] = true;

        $user = wp_signon( $creds, false );
	}
}

function ld_custom_registration_function() {
												//проверка скрытых полей формы
	if (!( empty($_POST) || !wp_verify_nonce($_POST['bH37nfG7ej5G0F3'],'Hn9rU3ek0rG8rb') )) {

	        // проверка безопасности введенных данных
	        global $reg_errors, $username, $email, $ld_status, $ld_secondname, $ld_name, $ld_patronymic, $ld_city, $ld_specialization_array;
	        $username      =   sanitize_user( $_POST['email'] );
	        $email         =   sanitize_email( $_POST['email'] );
	        $ld_status     =   $_POST['ld_status'];
	        $ld_secondname =   $_POST['ld_secondname'];
	        $ld_name       =   $_POST['ld_name'];
	        $ld_patronymic =   $_POST['ld_patronymic'];
	        $ld_captcha    =   $_POST["g-recaptcha-response"];
	        $ld_city       =   $_POST["ld_city"];
	        $ld_specialization_array = $_POST['ld_specialization'];

	        ld_registration_validation($username, $email, $ld_status, $ld_secondname, $ld_name, $ld_patronymic, $ld_captcha, $ld_city, $ld_specialization_array);

	        // вызов function ld_complete_registration, чтобы создать пользователя
	       	ld_complete_registration($username, $email, $ld_status, $ld_secondname, $ld_name, $ld_patronymic, $ld_city, $ld_specialization_array);

	       	if ( 1 <= count( $reg_errors->get_error_messages() ) ) {
	       		ld_registration_form(isset($email)?$email:'', isset($ld_status)?$ld_status:'', isset($ld_secondname)?$ld_secondname:'', isset($ld_name)?$ld_name:'', isset($ld_patronymic)?$ld_patronymic:'', isset($ld_city)?$ld_city:'', isset($ld_specialization_array)?$ld_specialization_array:'');
	       	}
    } else {
    	ld_registration_form(isset($email)?$email:'', isset($ld_status)?$ld_status:'', isset($ld_secondname)?$ld_secondname:'', isset($ld_name)?$ld_name:'', isset($ld_patronymic)?$ld_patronymic:'', isset($ld_city)?$ld_city:'', isset($ld_specialization_array)?$ld_specialization_array:'');
    }

}

// Регистрируем новый шорткод: [ld_registration]

add_shortcode( 'ld_registration', 'ld_custom_registration_shortcode' );

function ld_custom_registration_shortcode() {
    ob_start();
    ld_custom_registration_function();
    return ob_get_clean();
}

//подтверждение почты
add_action( 'after_setup_theme', 'ld_confirm_mail_shortcode' );

function ld_confirm_mail_shortcode() {
	if( $_GET['do'] AND $_GET['code'] ) {
		$confirm = get_user_meta( $_GET['do'], 'ld_mail_confirm', true );
		if ($confirm == '0') {
			$confirm_code = get_user_meta( $_GET['do'], 'ld_unique_string', true );
			if ($confirm_code == $_GET['code']) {
				update_user_meta( $_GET['do'], 'ld_mail_confirm', '1' );
				wp_redirect( home_url().'/wp-admin/admin.php?page=your_information'); // тут вставить ссылку со страницей с сообщением об успешной активации
				exit;
			}
		}
	}
}