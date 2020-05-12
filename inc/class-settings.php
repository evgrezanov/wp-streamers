<?php
defined( 'ABSPATH' ) || exit;

class WP_STREAMER_SETTINGS {

  public static $usermeta;
  public static $errors;

  public static function init(){

    add_shortcode( 'streamer-settings', [__CLASS__, 'display_sc_settings'] );

    add_action( 'init', [__CLASS__, 'save_form'] );

    add_action('sb_account_settings_save', [__CLASS__, 'save_personal_data'], 10, 2);

    add_action('sb_account_settings_section', [__CLASS__, 'display_personal_data'], 40, 2);

    add_action('sb_notice', [__CLASS__, 'display_notice']);

  }

  /**
   * Показываем нотисы/алерты в случае проблем с заполнением форм
   */
  public static function display_notice($context = ''){

    if(empty($_POST)){
      return;
    }

    if( ! method_exists(self::$errors,'get_error_messages')){
      return;
    }

    $errors = self::$errors->get_error_messages();

    if( empty( $errors ) ) {
      printf(
        '<div class="alert alert-success" role="alert">%s</div>',
        $text = 'Данные были успешно сохранены 😉'
      );

    } else {
      printf(
        '<div class="alert alert-danger" role="alert">%s</div>',
        $text = self::$errors->get_error_message()
      );
    }

  }

  /**
   * Шаблоны
   *
   * @param $data
   */
  public static function display_personal_data($data){

    require_once plugin_dir_path(__DIR__).'templates/personal-data.php';

  }


  /**
   * Валидация и сохранение данных
   *
   * @param $data
   * @param $user_id
   */
  public static function save_personal_data($data, $user_id){

    if(empty($user_id)){
      return;
    }

    $userdata = [
      'ID' => $user_id
    ];

    // Проверяем на не допустимые значения
    foreach ($data as $key => &$d) {

      $d = wp_strip_all_tags($d);

      if( $key != 'email' && $key != 'user_url' && $key != '_wp_http_referer' &&
        $key != 'user_birthday' && $key != 'passw1' && $key != 'passw2' && $key != 'description'){
        if(preg_match('/(wp-login|wp-admin|\/|\.)/',$d) ) {
          self::$errors->add('1', sprintf('Недопустимые значения в строке %s 😏',$d));
        }
      }

    }

    // Проверка пароля
    if( ! empty($data['passw1']) and isset($data['passw2']) ){
      $password = preg_match('^[A-Za-z0-9\(\!\"\?\$\%\^\&\)]{8,24}$',$data['passw1']);

      if($data['passw1'] !== $data['passw2']){
        self::$errors->add('1', 'Пароль не был изменен, введенные пароли не совпадают.');
      }elseif(!$password){
        self::$errors->add('2', sprintf('Ошибка! Пароль "%s" недостаточно надежен. Ваши SBC под угрозой 😏',$data['passw1']));
      }

      if( empty( self::$errors->get_error_messages() ) ) {
        wp_set_password( $data['passw1'], $user_id );
        $user_info = get_userdata($user_id);
        $auth = wp_authenticate( $user_info->user_email, $data['passw1'] );
      }

    }

    // Имя
    if(! empty(isset($data['first_name']))){
      $first_name = trim($data['first_name']);

      if (! preg_match("/^[a-zа-яё]{2,16}([\s\-]{1}[a-zа-яё]{2,16})?$/ui", $first_name)) {
        self::$errors->add('1', 'Имя не было изменено, имя должно содержать минимум две буквы и не более одного пробела.');
      } else {
        update_user_meta($user_id, 'first_name', $first_name);
        $userdata['first_name'] = $first_name;
      }
    }

    // Фамилия
    if(! empty(isset($data['last_name']))){
      $last_name = trim($data['last_name']);

      if (! preg_match("/^[a-zа-яё]{2,16}([\s\-]{1}[a-zа-яё]{2,16})?$/ui", $last_name)) {
        self::$errors->add('1', 'Фамилия не была изменена, фаимилия должна содержать минимум две буквы и не более одного пробела.');
      } else {
        update_user_meta($user_id, 'last_name', $last_name);
        $userdata['last_name'] = $last_name;
      }

      update_user_meta($user_id, 'last_name', $last_name);
      $userdata['last_name'] = $last_name;
    }

    // Никнэйм
    if( isset($data['user_nickname']) ){
      $user_nickname = $data['user_nickname'];
      if( self::check_current_nicename($user_nickname) ){
        //обработка если $user_nickname не был изменен и ничего сохранять не надо
        $userdata['user_nicename'] = sanitize_title($user_nickname);

      } elseif( self::check_free_nickname($user_nickname) ){

        // Если у пользователя нету старого пароля значит он его не менял а значит можено один раз поменять
        if(get_user_meta($user_id,'nicenames_has_changed',true) == ''){

          //сохраняем старый логин для истории и 301 редиректов
          $userdata_old = get_userdata($user_id);

      	  if(isset($userdata_old->user_nicename)){
            update_user_meta($user_id, 'old_nicenames', $userdata_old->user_nicename);
          }

      	  // Добавляем проверку на измененный пароль
          update_user_meta($user_id, 'nicenames_has_changed', 1);

          $userdata['user_nicename'] = sanitize_title($user_nickname);
          $userdata['display_name'] = $user_nickname;


          //обновляем меты для сохранения совместимости
          //но мб это лишнее
          update_user_meta($user_id, 'nickname', $data['user_nickname']);
          update_user_meta($user_id, 'user_nickname', $data['user_nickname']);

          // тк никнэйм был изменен соберем ссылку для корректного редиректа после обновления данных
          $path = '/people/'.$userdata['user_nicename'].'/my-settings/';
          $redirect = home_url($path);
          
        } else {
          self::$errors->add('1', 'Больше одного раза нельзя менять никнейм 😏');
        }

      } else {
        self::$errors->add('3', 'Никнейм не был изменен, такой адрес уже занят. Попробуйте подобрать другой никнейм.');
      }
    }

    // О себе
    if(isset($data['description']) && !preg_match('/(wp-login|wp-admin|\/)/',$data['description'])){
      update_user_meta($user_id, 'description', $data['description']);
    } else{
      self::$errors->add('1', sprintf('Недопустимые символы в описании %s',$data['description']));
    }

    // email
    if(isset($data['email'])){
      if(is_email($data['email'])){
        $userdata['user_email'] = $data['email'];
        wp_update_user( $userdata );
      } else {
        self::$errors->add('2', 'Email не был изменен. Формат введенного адреса не поддерживется сайтом.');
      }
    }

    if( empty( self::$errors->get_error_messages() ) ) {
      wp_update_user($userdata);
      if (isset($redirect)):
        wp_safe_redirect($redirect);
        exit;
      endif;
    }

  }

  /**
   * Проверяет совпадает ли $user_nickname с текущими данными юзера
   *
   * @param  string $user_nickname - никнем для проверки
   * @return bool true если совпадает, и false если никнейм новый
   */
  public static function check_current_nicename($user_nickname = ''){
    if(empty($user_nickname)){
      return false;
    }

    $user_nicename = sanitize_title($user_nickname);

    $current_user = wp_get_current_user();

    if(empty($current_user->user_nicename)){
      return false;
    }

    if($current_user->user_nicename != $user_nicename){
      return false;
    }

    return true;
  }

  /**
   * Проверяет доступность адреса для никнейма
   *
   * @param  string $user_nickname - никнем для проверки
   * @return bool можно продолжать сохранение переданного никнейма или нет
   */
  public static function check_free_nickname($user_nickname = ''){
    if(empty($user_nickname)){
      return false;
    }

    $user_nicename = sanitize_title($user_nickname);


    $pages = get_posts('post_type=>page&pagename=' . $user_nicename);

    if( ! empty($pages)){
      return false;
    }

    if ( get_user_by( 'slug', $user_nicename ) ) {
      return false;
    }

    return true;

  }

  /**
   * Выводим форму через шорткод
   */
  public static function display_sc_settings(){

    ob_start();

    if(!is_user_logged_in()){
      printf(
        '<div class="alert alert-danger" role="alert">%s</div>',
        'Хакер чтоли? Эту страницу может видеть только владелец профиля. Давайте будем играть честно.'
      );
      return ob_get_clean();
    }

    require_once plugin_dir_path(__DIR__).'templates/personalarea.php';

    return ob_get_clean();
  }

  /**
   * Сохраняем данные формы
   */
  public static function save_form(){

    if(empty($_POST)){
      return;
    }

    if( ! isset($_POST['sb_user_settings_nonce']) or ! wp_verify_nonce( $_POST['sb_user_settings_nonce'], 'sb_user_settings' ) ){
      return;
    }

    $user_id = get_current_user_id();
    if(empty($user_id)){
      return;
    }

    //Container for adding errors and display to user interface
    self::$errors = new \WP_Error();

    do_action('sb_account_settings_save', $_POST, $user_id);

  }

}

WP_STREAMER_SETTINGS::init();