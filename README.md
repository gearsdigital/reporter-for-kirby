
# ⚡ Kirby Issue Reporter

Report issues to your desired issue tracker with ease – directly from the panel!

## Supported platforms
- [x] [Gitlab](https://gitlab.com)
- [x] [Github](https://github.com)
- [x] [Bitbucket Cloud](https://bitbucket.org)
- [ ] [Trello](https://trello.com)
- [ ] [Gitea](https://gitea.io)

Please file an issue if you have specific requirements :)

## Screenshot

![screenshot](https://user-images.githubusercontent.com/965069/60097385-95a1cf00-9753-11e9-8650-34a9b4d0b7c0.png)

## Installation

After you've installed and configured this plugin correctly, open the panel menu and click the newly avaible "*⚡ New Issue*"-Link.

### Download

Download and copy this repository to `/site/plugins/kirby-reporter`.

### Git submodule

```
git submodule add https://github.com/gearsdigital/kirby-reporter.git site/plugins/kirby-reporter
```

### Composer

```
composer require gearsdigital/kirby-reporter
```
## Options
| Option | Description |
| --- | --- |
| `kirby-reporter.repository` | The repository to which the issues are reported.<br>*Must be a fully qualified url:* `https://github.com/gearsdigital/kirby-reporter`|
| `kirby-reporter.token` | Your personal access token.<br>*Your personal access token is never populated to the client!* |
| `kirby-reporter.disabled` | Setting this option to `true` disables the plugin completely. |

## Configuration

### Personal Access Token

Personal access tokens are substitute passwords for your account to avoid putting your real password into configuration files. It depends on your platform how to obtain a *personal access token* (or *app password*).

For the sake of simplicity I just refer to the related help docs:

- [Gitlab](https://docs.gitlab.com/ee/user/profile/personal_access_tokens.html)
- [Github](https://help.github.com/en/articles/creating-a-personal-access-token-for-the-command-line)
- [Bitbucket Cloud](https://confluence.atlassian.com/bitbucket/app-passwords-828781300.html)

It is probably a good idea to create a custom user with limited scope access for issue reporting.

## Available translations

- German
- English

Pull requests with additonal translations are very much appreciated!

## Development

Run PHP unit tests:
```
npm run test
```

## License

MIT
