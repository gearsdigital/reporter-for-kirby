# ⚡ Kirby Issue Reporter

Report issues to your desired issue tracker with ease – directly from the panel! Currently there are two supported platforms: **Github** and **Gitlab**.

It is planned to add *Bitbucket*, *Gitea* and *Trello* soon.

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
| `kirby-reporter.repository` | *The repository to which the issues are reported.*<br><br>Must be a fully qualified url: `https://github.com/gearsdigital/kirby-reporter`|
| `kirby-reporter.token` | *Your personal access token.*<br><br>The obtaining of a personal access token depends on your platform. It is probably a good idea to create a custom user for issue reporting. I'ld also suggest to limit the scope access.<br><br>However, your personal access token is never populated to the client! |
| `kirby-reporter.disabled` | *Setting this option to `true` disables the plugin completely.* |

## Available languages

- German
- English

## Development

*TBD*

## License

MIT
