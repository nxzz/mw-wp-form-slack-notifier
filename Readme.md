# MW WP Form Slack Notifier
## これは？
MW WP Form の投稿をSlackに飛ばすプラグインです。
MW WP Form の管理者へのメール内容をそのままSlackのIncoming WebHooksに投げます。

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