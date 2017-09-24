<?php

namespace Phpactor\WorseReflection\Core\Reflection;

use Phpactor\WorseReflection\Core\ClassName;
use Phpactor\WorseReflection\Core\Docblock;
use Phpactor\WorseReflection\Core\Reflection\Collection\ReflectionMethodCollection;
use Phpactor\WorseReflection\Core\Reflection\ReflectionClass;
use Phpactor\WorseReflection\Core\SourceCode;

abstract class AbstractReflectionClass extends AbstractReflectedNode
{
    abstract public function name(): ClassName;

    abstract protected function methods(): ReflectionMethodCollection;

    abstract public function sourceCode(): SourceCode;

    public function isInterface(): bool
    {
        return $this instanceof ReflectionInterface;
    }

    public function isTrait(): bool
    {
        return $this instanceof ReflectionTrait;
    }

    public function isClass(): bool
    {
        return $this instanceof ReflectionClass;
    }

    public function isConcrete()
    {
        if (false === $this->isClass()) {
            return false;
        }

        return false === $this->isAbstract();
    }

    public function docblock(): Docblock
    {
        return Docblock::fromNode($this->node());
    }
}
