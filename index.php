<?php

use Kirby\Cms\App as Kirby;
use Kirby\Cms\Blueprint;
use KirbyReporter\Client\CreateClient;
use KirbyReporter\Client\CreateVendor;
use KirbyReporter\Client\ErrorResponse;
use KirbyReporter\Client\PayloadInterceptor;

@include_once __DIR__ . '/vendor/autoload.php';

if (empty(option('kirby-reporter.enabled', false)) === true) {
    return false;
}

$url = option('kirby-reporter.repository');
$token = option('kirby-reporter.token');

Kirby::plugin('gearsdigital/kirby-reporter', [
    'areas' => [
        'reporter' => function () {
            return [
                'label' => t('reporter.headline'),
                'icon' => 'bolt',
                'menu' => true,
                'link' => 'reporter',
                'views' => [
                    [
                        'pattern' => 'reporter',
                        'action' => function () {
                            return [
                                'component' => 'k-reporter-view',
                                'title' => t('reporter.headline')
                            ];
                        }
                    ]
                ]
            ];
        }
    ],
    'blueprints' => [
        'reporter/reporter' => __DIR__ . '/blueprints/reporter/reporter.yml',
    ],
    'templates' => [
        'reporter' => __DIR__ . '/templates/reporter.php',
    ],
    'sections' => [
        'reporter' => [],
    ],
    'api' => [
        'routes' => [
            [
                'pattern' => 'reporter/report',
                'method' => 'post',
                'action' => function () use ($url, $token) {
                    try {
                        $isPreview = get('preview');
                        $requestBody = kirby()->request()->body()->data();
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
                'method' => 'get',
                'action' => function () {
                    $blueprint = Blueprint::load('reporter/reporter');

                    return json_encode($blueprint['reporter']['fields']);
                },
            ],
        ],
    ],
    'translations' => [
        'en' => [
            'reporter.headline' => 'New Issue',
            'reporter.description' => 'This is the place to report things that need to be improved or solved. Issues can be bugs, tasks or ideas to be discussed.',
            'reporter.tab.write' => 'Write',
            'reporter.tab.preview' => 'Preview',
            'reporter.tab.preview.empty' => 'Nothing to preview',
            'reporter.form.field.title' => 'Title',
            'reporter.form.success' => 'Your problem has been reported successfully and is handled under case number: {issueLink}',
            'reporter.form.issue.link' => '<a href="{issueLink}">#{issueId}</a>',
            'reporter.form.button.save' => 'Report Issue',
            'reporter.form.error.title' => 'You need to add at least a title.',
            'reporter.form.error.authFailed' => 'Authentication failed. Please check your "Personal Access Token".',
            'reporter.form.error.repoNotFound' => 'Repository not found.',
            'reporter.form.error.optionNotFound.url' => 'Option "kirby-reporter.repository" not defined.',
            'reporter.form.error.optionNotFound.token' => 'Option "kirby-reporter.token" not defined.',
            'reporter.form.error.platform.unsupported' => 'Your Platform is currently not supported.',
        ],
        'de' => [
            'reporter.headline' => 'Fehler Melden',
            'reporter.description' => 'Hier können Dinge gemeldet werden die verbessert oder behoben werden müssen. Das können Fehler, Aufgaben oder Ideen sein.',
            'reporter.tab.write' => 'Schreiben',
            'reporter.tab.preview' => 'Vorschau',
            'reporter.form.field.title' => 'Titel',
            'reporter.tab.preview.empty' => 'Keine Vorschau verfügbar',
            'reporter.form.success' => 'Ihr Bericht wurde erfolgreich übertragen und wird unter der Fallnummer {issueLink} behandelt.',
            'reporter.form.issue.link' => '<a href="{issueLink}">{issueId}</a>',
            'reporter.form.button.save' => 'Fehler melden',
            'reporter.form.error.title' => 'Es muss mindestens ein Titel eingegeben werden.',
            'reporter.form.error.authFailed' => 'Anmeldung Fehlgeschlagen. Bitte prüfen Sie den "Personal Access Token".',
            'reporter.form.error.repoNotFound' => 'Das Repository wurde nicht gefunden.',
            'reporter.form.error.optionNotFound.url' => 'Die Option "kirby-reporter.repository" ist nicht vorhanden',
            'reporter.form.error.optionNotFound.token' => 'Option "kirby-reporter.token" ist nicht vorhanden.',
            'reporter.form.error.platform.unsupported' => 'Die Platform wird zur Zeit nicht Unterstützt.',
        ],
        'tr' => [
            'reporter.headline' => 'Hata Bildir',
            'reporter.description' => 'Geliştirilmesi veya çözülmesi gereken şeyleri rapor edebileceğiniz yer burasıdır. Bunlar tartışılması gereken hatalar, görevler veya fikirler olabilir.',
            'reporter.tab.write' => 'Yaz',
            'reporter.tab.preview' => 'Önizleme',
            'reporter.tab.preview.empty' => 'Önizleme yapacak bir şey yok',
            'reporter.form.field.title' => 'Başlık',
            'reporter.form.success' => 'Hata başarıyla bildirildi ve ilgili konu numarası altında ele alındı: {issueLink}',
            'reporter.form.issue.link' => '<a href="{issueLink}">#{issueId}</a>',
            'reporter.form.button.save' => 'Hata Raporla',
            'reporter.form.error.title' => 'En azından bir başlık eklemelisin.',
            'reporter.form.error.authFailed' => 'Kimlik doğrulama başarısız oldu. Lütfen "Kişisel Erişim Simgenizi" kontrol edin.',
            'reporter.form.error.repoNotFound' => 'Depo bulunamadı.',
            'reporter.form.error.optionNotFound.url' => 'Seçenek "kirby-reporter.repository" tanımlanmadı.',
            'reporter.form.error.optionNotFound.token' => 'Seçenek "kirby-reporter.token" tanımlanmadı.',
            'reporter.form.error.platform.unsupported' => 'Platformunuz şu anda desteklenmiyor.',
        ],
    ],
]);
