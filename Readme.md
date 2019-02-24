# MW WP Form Slack Notifier
## これは？
MW WP Form の投稿をSlackに飛ばすプラグインです。

MW WP Form の管理者へのメール内容をそのままSlackのIncoming WebHooksで送信します。

投稿されるフォーマットを変更したい場合は、MW WP Form 側で、管理者宛メール設定の本文フォーマットを変更してください。

## 未対応な部分
* ファイルや画像の処理には対応していません。
  * あくまで、メール本文をSlackに送信するだけです。
* 複数のフォームには対応していません。


## 設定

### Enable
通知の有効・無効切り替えです。

### Target Form Key
通知する MW WP Form の フォームKeyです。
フォーム識別子が以下の場合、 `135` を入力してください。
```
[mwform_formkey key="135"]
```

### Slack Webhook URL	
SlackのWebhookのURLです。
例: `https://hooks.slack.com/services/***/***/***`

### Slack Bot Name
Slackに通知する際の投稿者名です。