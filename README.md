# Kirby Issue Reporter

[![Maintainability](https://img.shields.io/codeclimate/maintainability/gearsdigital/kirby-reporter.svg)](https://codeclimate.com/github/gearsdigital/kirby-reporter)
[![Codefactor](https://img.shields.io/codefactor/grade/github/gearsdigital/kirby-reporter.svg)](https://www.codefactor.io/repository/github/gearsdigital/kirby-reporter)
[![Kirby](https://img.shields.io/badge/kirby-3-brightgreen.svg)](https://getkirby.com/)

Kirby Reporter helps to report *bugs*, *tasks* or *features* to your desired issue tracker – directly from the Panel!

## Supported platforms

- [Gitlab](https://gitlab.com)
- [Github](https://github.com)
- [Bitbucket Cloud](https://bitbucket.org)

> Please open an issue or provide a pull request if you miss something here :)

## Screenshot

![screencapture](https://user-images.githubusercontent.com/965069/60726156-2c803f80-9f3b-11e9-97f8-43b5b71689ab.gif)

## Installation

After you've installed and configured this plugin correctly, open the panel menu and click the newly available menu entry:

⚡ New Issue

### Composer

```
composer require gearsdigital/kirby-reporter
```

### Git submodule

```
git submodule add https://github.com/gearsdigital/kirby-reporter.git site/plugins/kirby-reporter
```

> Hint: Don't forget to run composer install in `site/plugins/kirby-reporter` .

### Download

[Download](https://github.com/gearsdigital/kirby-reporter/releases/latest) and copy this repository to `/site/plugins/kirby-reporter`.

## Options

| Option | Description |
| --- | --- |
| `kirby-reporter.repository` | The repository to which the issues are reported.<br>*Must be a fully qualified url:* `https://github.com/gearsdigital/kirby-reporter`|
| `kirby-reporter.token` | Your personal access token.<br>*Your personal access token is never populated to the client!* |
| `kirby-reporter.disabled` | Setting this option to `true` disables the plugin completely. |

## Configuration

### Customizing the Form

The custom blueprint will override the default blueprint, excluding the `title` which is, apart from translations, _not_ configurable. You can fully customize the Form by using [blueprint fields](https://getkirby.com/docs/guide/blueprints/fields).

To customize the form create a file named `reporter.yml` and save it to `/site/blueprints/reporter/reporter.yml`

```yml
reporter:
  fields:
    steps:
      label: Steps to reproduce
      type: textarea
      help: Please be as precise as possible.
    type:
      label: Issue Type
      type: select
      options:
        - value: bug
          text: Bug
        - value: feature
          text: Feature
        - value: enhancement
          text: Enhancement
```

> It is currently not (and will probably never be) possible to map custom fields to specifc fields of your issue tracker!

#### Default Blueprint

Location: `/site/plugins/kirby-reporter/blueprints/reporter/reporter.yml`

```yml
reporter:
  fields:
    description:
      label: Description
      type: textarea
      help: Please be as precise as possible.
```

### Customizing the Issue Template

Create a file named `reporter.php` and save it to `/site/templates/reporter.php`.

Within the template you have access to an array which contains all submitted fields. Each field consists the `key` and the selected `value`.

```php
/** @var array $fields */
$fields;
```

You can and do whatever you want to adapt the output to your needs using `php`. However, the generated output is always treated as plain text and send as content of the `description` field of your issue tracker.

> Output can be anything your issue tracker can deal with. Markdown might be a good start :)

#### Preview

You can use the preview tab to make sure your template is formatted properly.

#### Default Template

Location: `/site/plugins/kirby-reporter/templates/reporter.php`

```php
## Issue Template

<?= $fields['description']; ?>
```

### Personal Access Token

Personal access tokens are substitute passwords for your account to avoid putting your real password into configuration files. It depends on your platform how to obtain a *personal access token* (or *app password*).

For the sake of simplicity I just refer to the related help docs:

- [Gitlab](https://docs.gitlab.com/ee/user/profile/personal_access_tokens.html)
- [Github](https://help.github.com/en/articles/creating-a-personal-access-token-for-the-command-line)
- [Bitbucket Cloud](https://confluence.atlassian.com/bitbucket/app-passwords-828781300.html)

> It's probably a good idea to create a custom user with limited scope access.

## Available translations

- German
- English

> Pull requests with additonal translations are very much appreciated!

## Development

Run PHP unit tests:
```
npm run test
```

## License

MIT
