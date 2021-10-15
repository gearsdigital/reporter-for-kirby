<?php

namespace KirbyReporter\Mixins;

use Exception;
use KirbyReporter\Client\CreateVendor;
use PHPUnit\Framework\TestCase;

class CreateVendorTest extends TestCase
{

    public function test_extract_name_from_url()
    {
        $vendor = new CreateVendor('https://github.com/gearsdigital/kirby-reporter');
        $this->assertEquals('github', $vendor->name);
    }

    public function test_extract_user_from_url()
    {
        $vendor = new CreateVendor('https://github.com/gearsdigital/kirby-reporter');
        $this->assertEquals('gearsdigital', $vendor->owner);
    }

    public function test_extract_repository_from_url()
    {
        $vendor = new CreateVendor('https://github.com/gearsdigital/kirby-reporter');
        $this->assertEquals('kirby-reporter', $vendor->repository);
    }

    public function test_exception_platform_not_supported()
    {
        $this->expectExceptionMessage('reporter.form.error.platform.unsupported');
        new CreateVendor('https://lorem.com/gearsdigital/kirby-reporter');
    }

    /**
     * @dataProvider valueProvider
     *
     * @param $value
     *
     * @throws Exception
     */
    public function test_exception_on_missing_url($value)
    {
        $this->expectExceptionMessage('reporter.form.error.optionNotFound.url');
        new CreateVendor($value);
    }

    public function valueProvider()
    {
        return [
            'empty-string' => [''],
            'plain-string' => ['lorem'],
            'empty-array' => [[]],
            'empty-null' => [null],
            'empty-false' => [false]
        ];
    }

}
