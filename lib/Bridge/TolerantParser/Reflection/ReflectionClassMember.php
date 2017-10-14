<?php

namespace Phpactor\WorseReflection\Bridge\TolerantParser\Reflection;

use Phpactor\WorseReflection\Core\Position;
use Phpactor\WorseReflection\Bridge\TolerantParser\AbstractReflectionClass;

interface ReflectionClassMember
{
    public function position(): Position;

    public function declaringClass(): AbstractReflectionClass;

    public function class(): AbstractReflectionClass;
}