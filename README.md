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

After you've installed and configured this plugin correctly, open the panel menu and click the newly available menu entry `New Issue`. You can add also add a [section](#section)
to any blueprint.

### Composer

```
composer require gearsdigital/kirby-reporter
```

### Git submodule

```
git submodule add https://github.com/gearsdigital/kirby-reporter.git site/plugins/kirby-reporter
```

### Download

[Download](https://github.com/gearsdigital/kirby-reporter/releases/latest) and copy this repository to `/site/plugins/kirby-reporter`.

## Options

| Option | Description |
| --- | --- |
| `kirby-reporter.repository` | The repository to which the issues are reported.<br>*Must be a fully qualified url:* `https://github.com/gearsdigital/kirby-reporter`|
| `kirby-reporter.token` | Your personal access token (PAT).<br>*Your personal access token is never populated to the client!* |
| `kirby-reporter.enabled` | Must be present and set to `true`, otherwise the plugin will not work |
| `kirby-reporter.bitbucket.user` | Allow to define a custom bitbucket user. For details read #33 |

### Example
```php
// site/config/config.php
return [
    'kirby-reporter.enabled' => true, // set false to disable the plugin
    'kirby-reporter.repository' => 'https://github.com/gearsdigital/kirby-reporter',
    'kirby-reporter.token' => 'c56658e7c03a5995e2e1491e6a3b93fcde6225c9'
];
```

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

You can add any fields you want but you need to adapt the issue template in order to see the fields in your preview.

### Section

This plugin provides also a `section` which can be used like any other section in your blueprints. You can learn about adding sections from the [Kirby Docs](https://getkirby.com/docs/guide/blueprints/layout#adding-sections).

This is a very handy way to collect feedback from anywhere in your panel.

```
# site.yml
sections:
  reporter:
    type: reporter
    headline: Report issue
    description: My custom description
```

### Customizing the Issue Template

Create a file named `reporter.php` and save it to `/site/templates/reporter.php`.

Within the template you have access to an array of all available fields. Each field consists the `key` and the selected `value`.

```php
/** @var array $fields */
$fields;
```

You can and do whatever you want to adapt the output to your needs using `php`. However, the generated output is always treated as plain text and send as content of the `description` field of your issue tracker.

> Output can be anything your issue tracker can deal with. Markdown might be a good start :)

#### Preview

You can use the preview tab to make sure your template is formatted properly. The preview is rendered using your issue template.

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
- Turkish

> Pull requests with additonal translations are very much appreciated!

## Development

Run PHP unit tests:
```
npm run test
```

## License

MIT

## Contributors

[@afbora](https://github.com/afbora)
[@felixhaeberle](https://github.com/felixhaeberle)
