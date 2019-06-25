<?php

use Gearsdigital\KirbyReporter\ResponseError;
use Gearsdigital\KirbyReporter\Platform;
use Gearsdigital\KirbyReporter\Gateway;

$pluginState = option('kirby-reporter.disabled');
if ($pluginState || is_null($pluginState)) {
    return false;
}

Kirby::plugin('gearsdigital/kirby-reporter', [
    'api' => [
        'routes' => [
            [
                'pattern' => 'kirby-reporter',
                'method' => 'post',
                'action' => function () {
                    $url = option('kirby-reporter.repository');
                    $token = option('kirby-reporter.token');
                    $platform = new Platform($url, $token);
                    $gateway = new Gateway($platform);

                    try {
                        $response = $gateway->api->createIssue(kirby()->api()->requestBody());
                        return json_encode($response);
                    } catch (Exception $e) {
                        $errorResponse = new ResponseError();
                        $errorResponse->setStatus($e->getCode());
                        $errorResponse->setPlatform($platform->platform);
                        $errorResponse->setRepo($platform->owner.DS.$platform->repository);

                        return json_encode($errorResponse);
                    }
                }
            ]
        ]
    ],
    'translations' => [
        'en' => [
            'view.issue-tracker' => 'New Issue',
            'reporter.headline' => 'New Issue',
            'reporter.description' => 'This is the place to report things that need to be improved or solved. Issues can be bugs, tasks or ideas to be discussed.',
            'reporter.form.field.title' => 'Title',
            'reporter.form.success' => 'Your problem has been reported successfully and is handled under case number: {issueLink}',
            'reporter.form.issue.link' => '<a href="{issueLink}">{issueId}</a>',
            'reporter.form.button.save' => 'Report Issue',
            'reporter.form.field.description' => 'Description',
            'reporter.form.field.description.help' => 'Please be as precise as possible :)',
            'reporter.form.error.title' => 'You need to add at least a title.',
            'reporter.form.error.authFailed' => 'Authentication failed. Please check your "Personal Access Token".',
            'reporter.form.error.repoNotFound' => 'Repository not found.',
            'reporter.form.error.optionNotFound.url' => 'Option "kirby-reporter.repository" not defined.',
            'reporter.form.error.optionNotFound.token' => 'Option "kirby-reporter.token" not defined.',
            'reporter.form.error.platform.unsupported' => 'Your Platform is currently not supported.'
        ],
        'de' => [
            'view.issue-tracker' => 'Fehler Melden',
            'reporter.headline' => 'Fehler Melden',
            'reporter.description' => 'Hier können Dinge gemeldet werden die verbessert oder behoben werden müssen. Das können Fehler, Aufgaben oder Ideen sein.',
            'reporter.form.field.title' => 'Titel',
            'reporter.form.success' => 'Ihr Bericht wurde erfolgreich übertragen und wird unter der Fallnummer {issueLink} behandelt.',
            'reporter.form.issue.link' => '<a href="{issueLink}">{issueId}</a>',
            'reporter.form.button.save' => 'Fehler melden',
            'reporter.form.field.description' => 'Beschreibung',
            'reporter.form.field.description.help' => 'Bitte beschreiben Sie den Fehler so genau wie möglich :)',
            'reporter.form.error.title' => 'Es muss mindestens ein Titel eingegeben werden.',
            'reporter.form.error.authFailed' => 'Anmeldung Fehlgeschlagen. Bitte prüfen Sie den "Personal Access Token".',
            'reporter.form.error.repoNotFound' => 'Das Repository wurde nicht gefunden.',
            'reporter.form.error.optionNotFound.url' => 'Die Option "kirby-reporter.repository" ist nicht vorhanden',
            'reporter.form.error.optionNotFound.token' => 'Option "kirby-reporter.token" ist nicht vorhanden.',
            'reporter.form.error.platform.unsupported' => 'Die Platform wird zur Zeit nicht Unterstützt.'
        ]
    ],
]);