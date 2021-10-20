<?php
/**
 * @noinspection MissingReturnTypeInspection
 * @noinspection MethodShouldBeFinalInspection
 */

namespace KirbyReporter\Mixins;

use KirbyReporter\Vendor\Vendor;
use PHPUnit\Framework\TestCase;

class VendorTest extends TestCase
{

    public function test_create_vendor()
    {
        $vendor = new Vendor('https://github.com/gearsdigital/kirby-reporter', 'a3bKJSHp3Er3VuyP6Wc');
        $this->assertEquals('github', $vendor->name);
        $this->assertEquals('gearsdigital', $vendor->user);
        $this->assertEquals('gearsdigital', $vendor->owner);
        $this->assertEquals('kirby-reporter', $vendor->repository);
        $this->assertEquals('a3bKJSHp3Er3VuyP6Wc', $vendor->token);
        $this->assertEquals('https://github.com/gearsdigital/kirby-reporter', $vendor->url);
    }

    public function test_exception_platform_not_supported()
    {
        $this->expectExceptionMessage('reporter.form.error.platform.unsupported');
        new Vendor('https://lorem.com/gearsdigital/kirby-reporter', 'a3bKJSHp3Er3VuyP6Wc');
    }

    public function test_override_owner_with_user()
    {
        $vendor = new Vendor('https://github.com/gearsdigital/kirby-reporter', 'a3bKJSHp3Er3VuyP6Wc', 'custom-user');
        $this->assertEquals('custom-user', $vendor->user);
        $this->assertEquals('gearsdigital', $vendor->owner);
    }
}
