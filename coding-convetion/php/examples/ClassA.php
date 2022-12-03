<?php

declare(strict_types=1);

namespace Vendor;

use Vendor\{
    ClassB,
    SubVendor\ClassC
};
use function Vendor\Functions\{
    sayHello,
    sayHi
};

require 'ClassB.php';
require 'ClassC.php';
require 'Functions.php';

class ClassA
{
    public function useSayHello(): void
    {
        ClassB::hello();
        ClassC::hello();
        echo sayHi();
        echo sayHello();
    }
}

$classA = new ClassA();
$classA->useSayHello();
