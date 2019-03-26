<?php
/*
Plugin Name: MW WP Form Slack Notifier
Description: MW WP Form の投稿をSlackに飛ばすプラグイン
Author: Rimepi Kunimoto
Version: 0.1
*/
add_action('init', 'MWWPFormSlackNotifier::init');
class MWWPFormSlackNotifier
{
    // プラグインの接頭辞
    const MWFPSLACK_SLUG = "mw-wp-form-slack_";

    static function init()
    {
        return new self();
    }

    function __construct()
    {
        if (is_admin() && is_user_logged_in()) {
            // メニュー追加
            add_action("admin_menu", [$this, "add_admin_menu"]);
        }

        // MWWPForm のアクションフック
        
        $opt = get_option(self::MWFPSLACK_SLUG . 'options');
        // 有効でかつ各設定が設定されている場合のみアクションフックを追加する
        if(isset($opt['enable']) && isset($opt['slackurl']) && isset($opt['formkkey']) && $opt['enable'] == 1 ){
            add_action( 'mwform_before_send_admin_mail_mw-wp-form-' . $opt['formkkey'], [$this,  "beforeAdminEmail"], 10, 2 );
        }
    }

    function add_admin_menu() {
        add_menu_page("MWWPFormSlackNotifier", "MWWPFormSlack", "administrator", self::MWFPSLACK_SLUG . "setting", [$this,  "write_setting_page"]);
    }

    function write_setting_page() {
        if ( isset($_POST[self::MWFPSLACK_SLUG . 'options'])) {
            check_admin_referer(self::MWFPSLACK_SLUG . 'options');
            $opt = $_POST[self::MWFPSLACK_SLUG . 'options'];
            $opt['enable'] = isset($_POST[self::MWFPSLACK_SLUG . 'options']['enable']) ? 1: 0;
            update_option(self::MWFPSLACK_SLUG . 'options', $opt);
            ?><div class="updated fade"><p><strong><?php _e('Options saved.'); ?></strong></p></div><?php
        }
        ?>
        <div class="wrap">
        <h1>MWWPForm Slack通知設定</h1>
        <p>MWWPForm をSlackに通知できます。</p>
        <form action="" method="post">
            <?php
            wp_nonce_field(self::MWFPSLACK_SLUG . 'options');
            $opt = get_option(self::MWFPSLACK_SLUG . 'options');
            $slackurl = isset($opt['slackurl']) ? $opt['slackurl']: null;
            $slackbotname = isset($opt['slackbotname']) ? $opt['slackbotname']: "MWWPFormSlackNotifier";
            $formkkey = isset($opt['formkkey']) ? $opt['formkkey']: null;
            $enable = isset($opt['enable']) ? $opt['enable']: 0;
            ?> 
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="inputtext">Enable</label></th>
                    <td><input name="<?php  echo self::MWFPSLACK_SLUG . 'options' ?>[enable]" type="checkbox" id="checkbox" value="true" <?php if($enable) echo 'checked="checked"' ?>  class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="inputtext">Target Form Key</label></th>
                    <td><input name="<?php  echo self::MWFPSLACK_SLUG . 'options' ?>[formkkey]" type="text" id="inputtext" value="<?php  echo $formkkey ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="inputtext">Slack Webhook URL</label></th>
                    <td><input name="<?php  echo self::MWFPSLACK_SLUG . 'options' ?>[slackurl]" type="text" id="inputtext" value="<?php  echo $slackurl ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="inputtext">Slack Bot Name</label></th>
                    <td><input name="<?php  echo self::MWFPSLACK_SLUG . 'options' ?>[slackbotname]" type="text" id="inputtext" value="<?php  echo $slackbotname ?>" class="regular-text" /></td>
                </tr>
            </table>
            <p class="submit"><input type="submit" name="Submit" class="button-primary" value="変更を保存" /></p>
        </form>
        </div>
        <?php
    }

    function sendSlack($msg) {
        $opt = get_option(self::MWFPSLACK_SLUG . 'options');
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode(array(
                        'username' => $opt['slackbotname'],
                        'text' => $msg,
                    )),
            )
        );
        file_get_contents($opt['slackurl'], false, stream_context_create($options));
    }

    function beforeAdminEmail( $Mail_admin, $Data ) {
        // Slackに通知
        $this->sendSlack($Mail_admin->body);
    }
}
?>