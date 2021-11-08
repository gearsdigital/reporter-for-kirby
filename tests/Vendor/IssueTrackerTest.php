<?php
/**
 * @noinspection MissingReturnTypeInspection
 * @noinspection MethodShouldBeFinalInspection
 */

use KirbyReporter\Exception\OptionNotFoundException;
use KirbyReporter\Exception\UnsupportedPlatformException;
use KirbyReporter\Vendor\IssueTracker;
use PHPUnit\Framework\TestCase;

class IssueTrackerTest extends TestCase
{

    public function test_create_vendor()
    {
        $vendor = new IssueTracker('https://github.com/gearsdigital/kirby-reporter', 'a3bKJSHp3Er3VuyP6Wc');
        $this->assertEquals('github', $vendor->getName());
        $this->assertEquals('gearsdigital', $vendor->getUser());
        $this->assertEquals('gearsdigital', $vendor->getOwner());
        $this->assertEquals('kirby-reporter', $vendor->getRepository());
        $this->assertEquals('a3bKJSHp3Er3VuyP6Wc', $vendor->getToken());
        $this->assertEquals('https://github.com/gearsdigital/kirby-reporter', $vendor->getUrl());
    }

    public function test_exception_platform_not_supported()
    {
        $this->expectException(UnsupportedPlatformException::class);
        $this->expectExceptionMessage('reporter.form.error.platform.unsupported');
        $this->expectExceptionCode(400);
        new IssueTracker('https://lorem.com/gearsdigital/kirby-reporter', 'a3bKJSHp3Er3VuyP6Wc');
    }

    public function test_exception_option_url_not_found()
    {
        $this->expectException(OptionNotFoundException::class);
        $this->expectExceptionMessage('reporter.form.error.optionNotFound.url');
        $this->expectExceptionCode(400);
        new IssueTracker(null, 'a3bKJSHp3Er3VuyP6Wc');
    }

    public function test_exception_option_token_not_found()
    {
        $this->expectException(OptionNotFoundException::class);
        $this->expectExceptionMessage('reporter.form.error.optionNotFound.token');
        $this->expectExceptionCode(400);
        new IssueTracker('https://github.com/gearsdigital/kirby-reporter', '');
    }

    public function test_override_owner_with_user()
    {
        $vendor = new IssueTracker('https://github.com/gearsdigital/kirby-reporter', 'a3bKJSHp3Er3VuyP6Wc', 'custom-user');
        $this->assertEquals('custom-user', $vendor->getUser());
        $this->assertEquals('gearsdigital', $vendor->getOwner());
    }
}
