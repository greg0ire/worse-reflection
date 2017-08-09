<?php

namespace Phpactor\WorseReflection\Tests\Integration\Reflection;

use Phpactor\WorseReflection\Tests\Integration\IntegrationTestCase;
use Phpactor\WorseReflection\Core\Reflection\ReflectionTrait;
use Phpactor\WorseReflection\Core\Reflection\ReflectionConstant;
use Phpactor\WorseReflection\Core\ClassName;

class ReflectionTraitTest extends IntegrationTestCase
{
    /**
     * @dataProvider provideReflectionTrait
     */
    public function testReflectTrait(string $source, string $class, \Closure $assertion)
    {
        $class = $this->createReflector($source)->reflectClass(ClassName::fromString($class));
        $assertion($class);
    }

    public function provideReflectionTrait()
    {
        return [
            'It reflects a trait' => [
                <<<'EOT'
<?php

trait Barfoo
{
}
EOT
                ,
                'Barfoo',
                function ($class) {
                    $this->assertEquals('Barfoo', (string) $class->name()->short());
                    $this->assertInstanceOf(ReflectionTrait::class, $class);
                    $this->assertTrue($class->isTrait());
                },
            ],
            'It reflects a classes traits' => [
                <<<'EOT'
<?php
trait Barfoo
{
}

trait Bazbar
{
}

class Foobar
{
    use Barfoo;
    use Bazbar;
}
EOT
                ,
                'Foobar',
                function ($class) {
                    $traits = $class->traits();
                    $this->assertCount(2, $traits);
                    $trait = $traits[0];
                    $this->assertInstanceOf(ReflectionTrait::class, $trait);
                },
            ],
            'It reflect trait methods' => [
                <<<'EOT'
<?php

trait Barfoo
{
    public function foobar()
    {
    }
}
EOT
                ,
                'Barfoo',
                function ($class) {
                    $this->assertEquals('Barfoo', (string) $class->name()->short());
                    $this->assertEquals(['foobar'], $class->methods()->keys());
                },
            ],
            'Trait properties' => [
                <<<'EOT'
<?php

trait Int1
{
    protected $foobar;
    protected $barfoo;
}
EOT
                ,
                'Int1',
                function ($class) {
                    $this->assertCount(2, $class->properties());
                    $this->assertEquals('foobar', $class->properties()->first()->name());
                },
            ],
        ];
    }
}
