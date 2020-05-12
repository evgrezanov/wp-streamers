<?php
defined( 'ABSPATH' ) || exit;

class WP_STREAMER_SIGNUP {

	public static function init(){
        add_shortcode('streamer_signup_form', [__CLASS__, 'signup_form']);
        //add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
    }
    /*public static function assets(){
		wp_enqueue_style(
            'pure-css', 
            WP_STREAMERS_URL . 'asset/pure-min.css'
        );
	}*/
    public static function signup_form(){
        ob_start();
		require_once plugin_dir_path(__DIR__).'templates/signup.php';
		return ob_get_clean();
    }

        private static $page_slug = 'confirmation';
      
        /**
         * @var string
         */
        private static $activation_key;
      
        public static function init() {
          add_action('sb_auth_forms_parts', [__CLASS__, 'template'], 100);
          add_action('wp', [__CLASS__, 'user_activation']);
      
          add_shortcode('sb_activation', [__CLASS__, 'shortcode_activation']);
      
          add_action('rest_api_init', function() {
            register_rest_route('sb/v1', '/user/signup', [
              'methods'  => 'POST',
              'callback' => [__CLASS__, 'user_signup_rest_handler']
            ]);
          });
      
          //add var for JS on front
          add_filter('sb_login_js_data', function($js_data) {
            $js_data['recaptcha_site_key'] = self::$recaptcha_site_key;
      
            return $js_data;
          });
        }
      
        /**
         * user_signup_rest_handler
         */
        public static function user_signup_rest_handler(\WP_REST_Request $request) {
          if ( ! wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
            return new \WP_Error('no_access', 'Данные присланные со сторонней страницы', ['status' => 401]);
          }
      
          $params = $request->get_params();
      
          $recaptcha_token = empty($params['recaptcha-token']) ? '' : $params['recaptcha-token'];
      
          if ( ! self::recaptcha_check_token($recaptcha_token)) {
            // return new \WP_Error('spam_block', 'spam block', 
            //   array(
            //     'status' => 401, 
            //     'message' => 'spam block',
            //     'self::$recaptcha_response' => self::$recaptcha_response
            //   )
            // );
          }
      
          // далее проверим залогинен ли уже юзер, если да - то делать ничего не надо
          if (is_user_logged_in()) {
            wp_send_json_error(['message' => 'Вы уже авторизованы!', 'redirect' => false]);
          }
      
          // если регистрацию выключат в админке - то же не будем ничего делать
          if ( ! get_option('users_can_register')) {
            wp_send_json_error(['message' => 'Регистрация пользователей временно недоступна.', 'redirect' => false]);
          }
      
          if ( ! $user_email = sanitize_email(@$_POST['user_email'])) {
            wp_send_json_error(['message' => 'Email - обязательное поле.', 'redirect' => false]);
          }
          // теперь возьмем все поля и рассуем по переменным
          $user_login = isset($_POST['user_email']) ? $_POST['user_email'] : '';
          // $user_email      = isset($_POST['user_email']) ? $_POST['user_email'] : '';
          $user_first_name = isset($_POST['user_first_name']) ? trim($_POST['user_first_name']) : '';
      
          if ( ! $user_first_name) {
            wp_send_json_error(['message' => 'Имя - обязательное поле.', 'redirect' => false]);
          } else if ( ! preg_match("/^[a-zа-яё]{2,16}([\s\-]{1}?[a-zа-яё]{2,16})?$/ui", $user_first_name)) {
            wp_send_json_error(['message' => 'Имя должно содержать минимум две буквы.', 'redirect' => false]);
          }
      
          $user_last_name = isset($_POST['user_last_name']) ? trim($_POST['user_last_name']) : '';
          if ( ! $user_last_name) {
            wp_send_json_error(['message' => 'Фамилия - обязательное поле.', 'redirect' => false]);
          } else if ( ! preg_match("/^[a-zа-яё]{2,16}([\s\-]{1}?[a-zа-яё]{2,16})?$/ui", $user_last_name)) {
            wp_send_json_error(['message' => 'Фамилия должна содержать минимум две буквы.', 'redirect' => false]);
          }
      
          $use_password = isset($_POST['use_password_checkbox']) ? $_POST['use_password_checkbox'] : '';
          if ($use_password) {
            $pass1 = isset($_POST['pass1']) ? trim($_POST['pass1']) : '';
            if ( ! $pass1) {
              wp_send_json_error(['message' => 'Пароль - обязательное поле.', 'redirect' => false]);
            }
      
            // проверка на допустимые символы и их количество
            if ( ! preg_match('/^[A-Za-z0-9!?$%^&)(]{8,24}$/', $pass1)) {
              wp_send_json_error(['message' => 'Пароль может содержать только буквы латинские алфавита, цифры и символы: (!?$%^&). Длина пароля от 8 до 24 символов.', 'redirect' => false]);
            }
          } else {
            $pass1 = wp_generate_password(12, false);
          }
          $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : false;
      
          // теперь проверим нужные поля на заполненность и валидность
          if ( ! $user_email) {
            wp_send_json_error(['message' => 'Email - обязательное поле.', 'redirect' => false]);
          }
          if ( ! preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $user_email)) {
            wp_send_json_error(['message' => 'Ошибочный формат email', 'redirect' => false]);
          }
      
          // теперь проверим все ли ок с паролями
          if (strlen($pass1) < 4) {
            wp_send_json_error(['message' => 'Слишком короткий пароль', 'redirect' => false]);
          }
          if (false !== strpos(wp_unslash($pass1), "\\")) {
            wp_send_json_error(['message' => 'Пароль не может содержать обратные слеши "\\"', 'redirect' => false]);
          }
      
          $userdata = [
            'user_pass'            => $pass1,
            'user_login'           => $user_login,
            'role'                 => 'member',
            'user_email'           => $user_email,
            'first_name'           => $user_first_name,
            'last_name'            => $user_last_name,
            'show_admin_bar_front' => false,
          ];
      
          $userdata = apply_filters('sb_signup_user', $userdata);
          if (empty($userdata)) {
            wp_send_json_error(['message' => 'Пользователь заблокирован.', 'redirect' => false]);
          }
      
          $user_id = wp_insert_user($userdata);
      
          // если есть ошибки
          if (is_wp_error($user_id) && $user_id->get_error_code() == 'existing_user_email') {
            wp_send_json_error(['message' => 'Пользователь с таким email уже существует.', 'redirect' => false]);
          } elseif (is_wp_error($user_id) && $user_id->get_error_code() == 'existing_user_login') {
            wp_send_json_error(['message' => 'Пользователь с таким логином уже существует.', 'redirect' => false]);
          } elseif (is_wp_error($user_id) && $user_id->get_error_code() == 'empty_user_login') {
            wp_send_json_error(['message' => 'Логин только латиницей.', 'redirect' => false]);
          } elseif (is_wp_error($user_id)) {
            wp_send_json_error(['message' => $user_id->get_error_code(), 'redirect' => false]);
          }
      
          // Меняем логин и никнейм
          global $wpdb;
          $wpdb->update($wpdb->users, ['user_login' => $user_id], ['ID' => $user_id]);
          $wpdb->update($wpdb->users, ['user_nicename' => 'id' . $user_id], ['ID' => $user_id]);
      
          // сгенерим случайную строку
          $code = wp_generate_password(15, false, false);
          $code = strtolower($code);
          // $code  = password_hash($user_id . time(), PASSWORD_DEFAULT);
      
          // создадим ссылку на активацию, подразумевается что на странице с урлом /activate/ у вас сработает механизм активации
          $activation_link = home_url() . '/confirmation/?key=' . $code . '&userid=' . $user_id;
      
          // теперь запишем эту случайную строку в мета поля юзера, если это поле не пустое - значит пользователь еще не активировался
          add_user_meta($user_id, 'has_to_be_activated', $code, true);
      
      
          if (has_action('new_registration_notice_action_hook')) {
            do_action('new_registration_notice_action_hook', $user_id, $activation_link, $pass1);
          }
      
          return new \WP_REST_Response(
            [
              'success'  => true, //скорее всего не нужный легаси
              'message'  => 'Пожалуйста, проверьте почту <strong>' . sanitize_email($user_email) . '</strong> и подтвердите ее с помощью ссылки в письме. После этого профиль будет активирован.',
              'redirect' => false,
            ]
          );
        }
      
      
        public static function user_activation() {
      
          if ( ! is_page(self::$page_slug)) {
            return;
          }
      
          $activation_data = self::get_data();
      
          if (empty($activation_data['user']) || ! empty($activation_data['error'])) {
            return;
          }
      
          // Авторизуем пользователя
          wp_clear_auth_cookie();
          wp_set_auth_cookie($activation_data['user']->ID, true);
      
          // Чтобы отработали необходимые коллбэки, если нужно
          do_action('wp_login', $activation_data['user']->user_login, $activation_data['user']);
        }
      
        /**
         * @return array
         */
        public static function get_data() {
          $data = [];
      
          if (is_user_logged_in()) {
            $data['message'] = sprintf('<p>Вы уже активированы и авторизованы. Можете перейти на главную <a href="%s">страницу</a></p>', site_url());
            $data['error']   = sprintf('<p>Вы уже активированы и авторизованы. Можете перейти на главную <a href="%s">страницу</a></p>', site_url());
      
            return $data;
          }
      
          // если не залогинен возьмем юзер ид и случайную строку
          $user_id = isset($_GET['userid']) ? (int) $_GET['userid'] : '';
          $key     = isset($_GET['key']) ? $_GET['key'] : '';
          if ( ! $user_id || ! $key) {
            $data['message'] = sprintf('<p>%s</p>', 'Не переданы параметры активации');
            $data['error']   = sprintf('<p>%s</p>', 'Не переданы параметры активации');
      
            return $data;
          }
      
          // получаем случайную строку по ид юзера
          $code = get_user_meta($user_id, 'has_to_be_activated', true);
      
          if ($code != $key && self::$activation_key !== $key) {
            $data['message'] = sprintf('<p>%s</p>', 'Данные активации не верны или вы уже активированы');
            $data['error']   = sprintf('<p>%s</p>', 'Данные активации не верны или вы уже активированы');
      
            return $data;
          }
      
          // Получим логин пользователя
          $data['user'] = get_userdata($user_id);
      
          self::$activation_key = $key;
      
          // удаляем эту строку у юзера
          delete_user_meta($user_id, 'has_to_be_activated');
      
          /**
           * Событие активации юзера
           *
           * @var int $user_id
           */
          do_action('sb_user_activated', $user_id);
      
          $data['message'] = 'Все хорошо, активация прошла успешно. Через 3 секунды Вы будут перенаправлены на <a href="' . home_url() . '">главную</a>';
      
          // отправим данные о достижении цели в метрику
          //Авторизуемся
          // $user = wp_signon(array('user_login' => $data['user']->user_login));
          // wp_set_current_user($user->ID, $user->user_login);
      
          return $data;
        }
      
        /**
         * render shortcode for activation
         */
        public static function shortcode_activation() {
      
          $data = self::get_data();
      
          ?>

<div class="activation_message">
    <?= $data['message'] ?>


</div>

<?php if (empty($data['error'])) : ?>

<script>
setTimeout(() => window.location.href = '/', 3000);
</script>

<?php
      
          endif;
      
        }
      
        /**
         * render template
         */
        public static function template() { ?>
<!--форма регистрации-->
<form name="registrationform" id="registrationform" method="post" class="userform" action="" style="display: none">

    <p class="h1">Регистрация</p>

    <div class="response"></div>

    <div class="clmn-wp">

        <?php do_action('register_form'); ?>

    </div>

    <div class="form-group">
        <input class="form-control" type="text" name="user_first_name" id="user_first_name" placeholder="Имя"
            pattern="^[a-zA-Zа-яА-ЯёЁ]{2,16}([\s\-]{1}[a-zA-Zа-яА-ЯёЁ]{2,16})?$"
            title="Не менее двух букв и не более одного пробела" required>
    </div>

    <div class="form-group">
        <input class="form-control" type="text" name="user_last_name" id="user_last_name" placeholder="Фамилия"
            pattern="^[a-zA-Zа-яА-ЯёЁ]{2,16}([\s\-]{1}[a-zA-Zа-яА-ЯёЁ]{2,16})?$"
            title="Не менее двух букв и не более одного пробела" required>
    </div>

    <div class="form-group">
        <input class="form-control" type="email" name="user_email" id="user_email" placeholder="Email"
            autocomplete="username email" required>
    </div>

    <!--скрытое поле пароля-->
    <div class="form-group" id="reg_password">
        <input class="form-control" type="password" name="pass1" id="pass1" placeholder="Пароль"
            autocomplete="current-password">
    </div>
    <!--Использовать пароль?-->
    <div class="use_password clmn-wop aux-links">
        <input id="check_to_use_password" class="use_password_checkbox checkbox" type="checkbox"
            name="use_password_checkbox" checked="false">
        <label for="check_to_use_password">
            <?php esc_html_e('Задать свой пароль', 'socialbet'); ?>
        </label>
    </div>

    <input class="btn btn-secondary" type="submit" value="<?php esc_html_e('Зарегистрироваться', 'socialbet'); ?>">
    <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="action" value="register_me">

    <?php do_action('sb_users_form_register_hidden_fields'); ?>

    <!--social login-->
    <div class="social-box">
        <p><span>или войти через</span></p>
        <?php do_action('login_form'); ?>
    </div>

    <!-- обязательное поле, по нему запустится нужная функция -->
    <div class="clmn-wp bottom-text">

        <p class="text-muted">
            <?php esc_html_e('Уже зарегистрированы?', 'socialbet'); ?>
            <span class="md-close modal5popup md-trigger" data-modal="modal-4" id="modal4popup">
                <?php esc_html_e('Войти', 'socialbet'); ?>
            </span>
        </p>

    </div>

</form>

<?php
        }
}
WP_STREAMER_SIGNUP::init();