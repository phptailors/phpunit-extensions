.. _utilities:

Utilities
=========

.. _utilities.arrayValues:

arrayValues()
-------------


.. list-table:: Prerequisites for arrayValues()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-arrays: ``^1.1 || ^2.1 || ^3.1 || ^4.1 || ^5.1``
   * - Trait
     - :class:`Tailors\\PHPUnit\\ArrayValuesTrait`

Since versions ``*.1`` (``1.1``, ``2.1``, ``3.1``, ...) it's possible to
treat arrays nested somewhere within data structure same way as they are
handled at the top level by constraint such as
:ref:`constraints.arrayValuesEqualTo`, :ref:`constraints.arrayValuesIdenticalTo`.
To achieve this, wrap nested arrays in your constraint specification
(``$expected``) with ``arrayValues()``:

.. code:: php

  $expected = [
       'a' => 'A',
       'nested' => $this->arrayValues([
            'b' => 'B',
       ]),
  ];

When the above specification is used in :ref:`constraints.arrayValuesEqualTo` /
:ref:`constraints.arrayValuesIdenticalTo`, it will match arrays, such as

.. code:: php

  $actual = [
        // ... other optional items at top-level
       'a' => 'A',
        // ... other optional items at top-level
       'nested' => [
            // ... other optional items in nested
            'b' => 'B',
            // ... other optional items in nested
       ],
        // ... other optional items at top-level
  ];

Without ``arrayValues([...])``, the value under ``$actual['nested']`` would be
compared verbatim, that is only the exact array ``['b' => 'B']`` would match:

.. code:: php

  $actual = [
        // ... other optional items at top-level
       'a' => 'A',
        // ... other optional items at top-level
       'nested' => ['b' => 'B'], // other keys not allowed in nested array
        // ... other optional items at top-level
  ];

:ref:`utilities.arrayValues` may be applied at any depth in the expectation
array and may be used recursively

.. code:: php

   $expected = [
        'a' => [
            'b' => $this->arrayValues([
                // ...
            ])
        ],
        'c' => $this->arrayValues([
            'd' => $this->arrayValues([ /* ... */ ])
        ])
   ];

The utility may be used in array-related constraints, such as
:ref:`constraints.arrayValuesEqualTo` as well as other selection-based
constraints (e.g. :ref:`constraints.objectPropertiesEqualTo`). It may be mixed
recursively with other selection-related utilities such as
:ref:`utilities.classProperties` or :ref:`utilities.objectProperties`.

.. _utilities.classProperties:

classProperties()
-----------------


.. list-table:: Prerequisites for classProperties()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-arrays: ``^1.1 || ^2.1 || ^3.1 || ^4.1 || ^5.1``
   * - Trait
     - :class:`Tailors\\PHPUnit\\ClassPropertiesTrait`

Since versions ``*.1`` (``1.1``, ``2.1``, ``3.1``, ...) it's possible to
treat class names nested somewhere in data structure same way as they are
handled at the top level by :ref:`constraints.classPropertiesEqualTo` or
:ref:`constraints.classPropertiesIdenticalTo` constraint. To achieve this, wrap
the specification of class properties nested somewhere in your expectation
specs (``$expected``) with ``classProperties()``:

.. code:: php

  $expected = [
       'a' => 'A',
       'nested()' => $this->classProperties([
            'getMessage()' => 'File not found!',
       ])
  ];

For example, assuming that class ``Foo`` is defined as

.. code:: php

   class Foo {
        public static function nested(): \Exception
        {
            return new \Exception('File not found!');
        }
   }

The above specification of ``$expected`` values will match classes, such as

.. code:: php

 class Bar {
        // ... other optional properties at top-level
       public static $a = 'A';
        // ... other optional properties at top-level
       public static $nested = Foo::class;
        // ... other optional properties at top-level
  };

when compared with :ref:`constraints.classPropertiesEqualTo`.

Without ``classProperties([...])``, the value under ``$actual['nested()']`` would be
compared verbatim, that is only the exact array ``['getMessage()' => 'File not found!"]``
would match.

:ref:`utilities.classProperties` may be applied at any level and may be used recursively

.. code:: php

   $expected = [
        'a' => [
            'b' => $this->classProperties([
                // ...
            ])
        ],
        'c' => $this->classProperties([
            'd' => $this->classProperties([ /* ... */ ])
        ])
   ];

The utility may be used in class-properties-related constraints, such as
:ref:`constraints.classPropertiesEqualTo` as well as other selection-based
constraints (e.g. :ref:`constraints.objectPropertiesEqualTo`). It may be mixed
recursively with other selection-related utilities such as
:ref:`utilities.arrayValues` or :ref:`utilities.objectProperties`.


.. _utilities.objectProperties:

objectProperties()
------------------


.. list-table:: Prerequisites for objectProperties()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-arrays: ``^1.1 || ^2.1 || ^3.1 || ^4.1 || ^5.1``
   * - Trait
     - :class:`Tailors\\PHPUnit\\ClassPropertiesTrait`

Since versions ``*.1`` (``1.1``, ``2.1``, ``3.1``, ...) it's possible to
treat object references nested somewhere in data structure same way as they are
handled at the top level by :ref:`constraints.objectPropertiesEqualTo` or
:ref:`constraints.objectPropertiesIdenticalTo` constraint. To achieve this, wrap
the specification of object properties nested somewhere in your expectation
specs (``$expected``) with ``objectProperties()``:

.. code:: php

  $expected = [
       'a' => 'A',
       'nested()' => $this->objectProperties([
            'getMessage()' => 'File not found!',
       ])
  ];

For example, assuming that class ``Foo`` is defined as

.. code:: php

   class Foo {
        public function nested(): \Exception
        {
            return new \Exception('File not found!');
        }
   }

The above specification of ``$expected`` values will match objects, such as

.. code:: php

 class Bar {
        // ... other optional properties at top-level
       public $a = 'A';
        // ... other optional properties at top-level
       public function nested(): Foo {
            return new Foo();
       }
        // ... other optional properties at top-level
  };

when compared with :ref:`constraints.objectPropertiesEqualTo`.

Without ``objectProperties([...])``, the value under ``$actual['nested()']`` would be
compared verbatim, that is only the exact array ``['getMessage()' => 'File not found!"]``
would match.

:ref:`utilities.objectProperties` may be applied at any level and may be used recursively

.. code:: php

   $expected = [
        'a' => [
            'b' => $this->objectProperties([
                // ...
            ])
        ],
        'c' => $this->objectProperties([
            'd' => $this->objectProperties([ /* ... */ ])
        ])
   ];

The utility may be used in class-properties-related constraints, such as
:ref:`constraints.objectPropertiesEqualTo` as well as other selection-based
constraints (e.g. :ref:`constraints.objectPropertiesEqualTo`). It may be mixed
recursively with other selection-related utilities such as
:ref:`utilities.arrayValues` or :ref:`utilities.objectProperties`.


.. <!--- vim: set syntax=rst spell: -->
