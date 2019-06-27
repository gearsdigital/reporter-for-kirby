<?php
declare(strict_types=1);

namespace KirbyReporter\Mixins;

use PHPUnit\Framework\TestCase;

final class ArrayTransformatorTest extends TestCase
{
    /** @var ArrayTransformator|PHPUnit_Framework_MockObject_MockObject */
    private $arrayTransformatorTraitMock;

    protected function setUp(): void
    {
        $this->arrayTransformatorTraitMock = $this->getMockForTrait(ArrayTransformator::class);
    }

    protected function tearDown(): void
    {
        $this->arrayTransformatorTraitMock = null;
    }

    /**
     * @dataProvider arrayProvider
     *
     * @param $expected
     * @param $data
     * @param $map
     */
    public function testTransform($expected, $data, $map)
    {
        $this->assertEquals($expected, $this->arrayTransformatorTraitMock->transform($data, $map));
    }

    public function arrayProvider()
    {
        return [
            [[], [], []],
            [['lastname'], ['lastname'], []],
            [['lastname' => 'peter'], ['lastname' => 'peter'], []],
            [['name' => 'peter'], ['lastname' => 'peter'], ['lastname' => 'name']],
            [
                ['name' => 'peter', 'street' => 'downing street 10'],
                ['lastname' => 'peter', 'street' => 'downing street 10'],
                ['lastname' => 'name'],
            ],
            [
                ['name' => 'peter', 'road' => 'downing street 10'],
                ['lastname' => 'peter', 'street' => 'downing street 10'],
                ['lastname' => 'name', 'street' => 'road'],
            ],
        ];
    }
}
