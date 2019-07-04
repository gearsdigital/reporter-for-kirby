<?php
require_once __DIR__.'/vendor/autoload.php';

use Kirby\Cms\Blueprint;
use KirbyReporter\Client\CreateClient;
use KirbyReporter\Client\CreateVendor;
use KirbyReporter\Client\ErrorResponse;
use KirbyReporter\Client\PayloadInterceptor;

$pluginState = option('kirby-reporter.disabled');
if ($pluginState || is_null($pluginState)) {
    return false;
}
$url = option('kirby-reporter.repository');
$token = option('kirby-reporter.token');
Kirby::plugin(
    'gearsdigital/kirby-reporter',
    [
        'blueprints'   => [
            'reporter/reporter' => __DIR__.'/blueprints/reporter/reporter.yml',
        ],
        'templates'    => [
            'reporter' => __DIR__.'/templates/reporter.php',
        ],
        'api'          => [
            'routes' => [
                [
                    'pattern' => 'reporter/report',
                    'method'  => 'post',
                    'action'  => function () use ($url, $token) {
                        try {
                            $isPreview = get('preview');
                            $requestBody = kirby()->api()->requestBody();
                            $vendor = new CreateVendor($url);
                            $client = new CreateClient($vendor, $token);
                            $payload = new PayloadInterceptor($requestBody);
                            if ((bool)$isPreview) {
                                return json_encode($payload->renderIssueTemplate());
                            }
                            $response = $client->api->createIssue($payload->get());

                            return json_encode($response);
                        } catch (Exception $e) {
                            $errorResponse = new ErrorResponse($e);

                            return json_encode($errorResponse);
                        }
                    },
                ],
                [
                    'pattern' => 'reporter/fields',
                    'method'  => 'get',
                    'action'  => function () {
                        $blueprint = Blueprint::load('reporter/reporter');

                        return json_encode($blueprint['reporter']['fields']);
                    },
                ],
            ],
        ],
        'translations' => [
            'en' => [
                'view.issue-tracker'                       => 'New Issue',
                'reporter.headline'                        => 'New Issue',
                'reporter.description'                     => 'This is the place to report things that need to be improved or solved. Issues can be bugs, tasks or ideas to be discussed.',
                'reporter.tab.write'                       => 'Write',
                'reporter.tab.preview'                     => 'Preview',
                'reporter.tab.preview.empty'               => 'Nothing to preview',
                'reporter.form.field.title'                => 'Title',
                'reporter.form.success'                    => 'Your problem has been reported successfully and is handled under case number: {issueLink}',
                'reporter.form.issue.link'                 => '<a href="{issueLink}">#{issueId}</a>',
                'reporter.form.button.save'                => 'Report Issue',
                'reporter.form.error.title'                => 'You need to add at least a title.',
                'reporter.form.error.authFailed'           => 'Authentication failed. Please check your "Personal Access Token".',
                'reporter.form.error.repoNotFound'         => 'Repository not found.',
                'reporter.form.error.optionNotFound.url'   => 'Option "kirby-reporter.repository" not defined.',
                'reporter.form.error.optionNotFound.token' => 'Option "kirby-reporter.token" not defined.',
                'reporter.form.error.platform.unsupported' => 'Your Platform is currently not supported.',
            ],
            'de' => [
                'view.issue-tracker'                       => 'Fehler Melden',
                'reporter.headline'                        => 'Fehler Melden',
                'reporter.description'                     => 'Hier können Dinge gemeldet werden die verbessert oder behoben werden müssen. Das können Fehler, Aufgaben oder Ideen sein.',
                'reporter.tab.write'                       => 'Schreiben',
                'reporter.tab.preview'                     => 'Vorschau',
                'reporter.form.field.title'                => 'Titel',
                'reporter.tab.preview.empty'               => 'Keine Vorschau verfügbar',
                'reporter.form.success'                    => 'Ihr Bericht wurde erfolgreich übertragen und wird unter der Fallnummer {issueLink} behandelt.',
                'reporter.form.issue.link'                 => '<a href="{issueLink}">{issueId}</a>',
                'reporter.form.button.save'                => 'Fehler melden',
                'reporter.form.error.title'                => 'Es muss mindestens ein Titel eingegeben werden.',
                'reporter.form.error.authFailed'           => 'Anmeldung Fehlgeschlagen. Bitte prüfen Sie den "Personal Access Token".',
                'reporter.form.error.repoNotFound'         => 'Das Repository wurde nicht gefunden.',
                'reporter.form.error.optionNotFound.url'   => 'Die Option "kirby-reporter.repository" ist nicht vorhanden',
                'reporter.form.error.optionNotFound.token' => 'Option "kirby-reporter.token" ist nicht vorhanden.',
                'reporter.form.error.platform.unsupported' => 'Die Platform wird zur Zeit nicht Unterstützt.',
            ],
        ],
    ]
);
