.. _assertions:

Assertions
==========

This section lists the various assertion methods that are provided by
sub-packages of php-tailors/phpunit-extensions. Assertions may be added to your
test class by including appropriate trait as shown in prerequisite tables
below.

.. _assertions.assertClassPropertiesEqualTo:

assertClassPropertiesEqualTo()
------------------------------

.. list-table:: Prerequisites for assertClassPropertiesEqualTo()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - php-tailors/phpunit-properties
   * - Trait
     - :class:`Tailors\\PHPUnit\\ClassPropertiesEqualToTrait`

Synopsis:

.. code:: php

  function assertClassPropertiesEqualTo(array $expected, string $class[, string $message = ''])

Reports an error identified by ``$message`` if properties of ``$class`` are not
equal to ``$expected`` ones (tested with ``==`` operator).
A property is either a static attribute value or a value returned by class's
static method that is callable without arguments. The method compares only
properties described in ``$expected``, so ``$expected = []`` accepts any
existing ``$class``. If ``$class`` does not exists, the constraint fails as
well.

The arguments are:

- ``$expected`` - an associative array with property names as keys and their
  expected values as values, if a key ends with ``"()"``, then the property is
  assumed to be a method, for example ``$expected = ['foo()' => 'F']`` requires
  method ``foo()`` to return ``'F'``,
- ``$class`` - name of the class to be examined,
- ``$message`` - optional failure message,

The method

.. code:: php

  function assertNotClassPropertiesEqualTo(array $expected, string $class[, string $message = ''])

is the inverse of this.

.. literalinclude:: examples/AssertClassPropertiesEqualToTest.php
   :linenos:
   :caption: Usage of assertClassPropertiesEqualTo()
   :name: assertions.assertClassPropertiesEqualTo.example

.. literalinclude:: examples/AssertClassPropertiesEqualToTest.stdout
   :linenos:
   :language: none

.. _assertions.assertClassPropertiesIdenticalTo:

assertClassPropertiesIdenticalTo()
----------------------------------

.. list-table:: Prerequisites for assertClassPropertiesIdenticalTo()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - php-tailors/phpunit-properties
   * - Trait
     - :class:`Tailors\\PHPUnit\\ClassPropertiesIdenticalToTrait`

Synopsis:

.. code:: php

  function assertClassPropertiesIdenticalTo(array $expected, string $class[, string $message = ''])

Reports an error identified by ``$message`` if properties of ``$class``'s are
not identical to ``$expected`` ones (tested with ``===`` operator).
A property is either a static attribute value or a value returned by
``$class``'s static method that is callable without arguments. The method
compares only properties described in ``$expected``, so ``$expected = []``
accepts any existing ``$class``. If ``$class`` does not exist, the constraint
fails as well.

The arguments are:

- ``$expected`` - an associative array with property names as keys and their
  expected values as values, if a key ends with ``"()"``, then the property is
  assumed to be a method, for example ``$expected = ['foo()' => 'F']`` requires
  method ``foo()`` to return ``'F'``,
- ``$class`` - name of the class to be examined,
- ``$message`` - optional failure message,

The method

.. code:: php

  function assertNotClassPropertiesIdenticalTo(array $expected, string $class[, string $message = ''])

is the inverse of this.

.. literalinclude:: examples/AssertClassPropertiesIdenticalToTest.php
   :linenos:
   :caption: Usage of assertClassPropertiesIdenticalTo()
   :name: assertions.assertClassPropertiesIdenticalTo.example

.. literalinclude:: examples/AssertClassPropertiesIdenticalToTest.stdout
   :linenos:
   :language: none



.. _assertions.assertExtendsClass:

assertExtendsClass()
--------------------

.. list-table:: Prerequisites for assertExtendsClass()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - php-tailors/phpunit-inheritance
   * - Trait
     - :class:`Tailors\\PHPUnit\\ExtendsClassTrait`

Synopsis:

.. code:: php

  function assertExtendsClass(string $parent, mixed $subject[, string $message = ''])

Reports an error identified by ``$message`` if ``$subject`` does not extend the
``$parent`` class. The ``$subject`` may be an ``object`` or a class name as
``string``:

- if ``$subject`` is an ``object``, then its class, as returned by
  ``get_class($subject)``, is examined against ``$parent``, the assertion
  succeeds only if the class extends the ``$parent`` class,
- otherwise, the necessary conditions for the assertion to succeed are that

  - ``$subject`` is a string,
  - ``class_exists($subject)`` is ``true``, and
  - the ``$subject`` class extends the ``$parent`` class.

The method

.. code:: php

  function assertNotExtendsClass(string $parent, mixed $subject[, string $message = ''])

is the inverse of this.

.. literalinclude:: examples/AssertExtendsClassTest.php
   :linenos:
   :caption: Usage of assertExtendsClass()
   :name: assertions.assertExtendsClass.example

.. literalinclude:: examples/AssertExtendsClassTest.stdout
   :linenos:
   :language: none


.. _assertions.assertHasPregCaptures:

assertHasPregCaptures()
-----------------------

.. list-table:: Prerequisites for assertHasPregCaptures()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - php-tailors/phpunit-pcre
   * - Trait
     - :class:`Tailors\\PHPUnit\\HasPregCapturesTrait`

Synopsis:

.. code:: php

  function assertHasPregCaptures(array $expected, array $matches[, string $message = ''])

Reports an error identified by ``$message`` if PCRE captures found in
``$matches`` (an array supposedly returned from `preg_match()`_) do not agree
with the expectations prescribed in the ``$expected`` array. The method
verifies only groups described in ``$expected``, so ``$expected = []``
accepts any array of ``$matches``. Expectations are formulated as follows:

- ``$expected = ['foo' => true]`` requires ``$matches['foo']`` to be present,
- ``$expected = ['foo' => false]`` requires ``$matches['foo']`` to be absent,
- ``$expected = ['foo' => 'FOO']`` requires that ``$matches['foo'] === 'FOO'``,

A capture group ``foo`` is considered absent if:

- ``$matches['foo']`` is not set, or
- ``$matches['foo'] === null``, or
- ``$matches['foo'] === [null, ...]``.

.. note::

    The presence/absence checks work only with ``$matches`` returned from
    `preg_match()`_ when invoked with the ``PREG_UNMATCHED_AS_NULL`` flag.

The method

.. code:: php

  function assertNotHasPregCaptures(array $expected, array $matches[, string $message = ''])

is the inverse of this.

.. literalinclude:: examples/AssertHasPregCapturesTest.php
   :linenos:
   :caption: Usage of assertHasPregCaptures()
   :name: assertions.assertHasPregCaptures.example

.. literalinclude:: examples/AssertHasPregCapturesTest.stdout
   :linenos:
   :language: none


.. _assertions.assertImplementsInterface:

assertImplementsInterface()
---------------------------

.. list-table:: Prerequisites for assertImplementsInterface()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - php-tailors/phpunit-inheritance
   * - Trait
     - :class:`Tailors\\PHPUnit\\ImplementsInterfaceTrait`

Synopsis:

.. code:: php

  function assertImplementsInterface(string $interface, mixed $subject[, string $message = ''])

Reports an error identified by ``$message`` if ``$subject`` does not implement
the ``$interface``. The ``$subject`` may be an ``object`` or a class/interface
name as ``string``:

- if ``$subject`` is an ``object``, then its class, as returned by
  ``get_class($subject)``, is examined against ``$interface``, the assertion
  succeeds only if the class implements the ``$interface``,
- otherwise, the necessary conditions for the assertion to succeed are that

  - ``$subject`` is a string,
  - ``class_exists($subject)`` is ``true`` or ``interface_exists($subject)`` is
    ``true``, and
  - the ``$subject`` implements the ``$interface``.

The method

.. code:: php

  function assertNotImplementsInterface(string $interface, mixed $subject[, string $message = ''])

is the inverse of this.

.. literalinclude:: examples/AssertImplementsInterfaceTest.php
   :linenos:
   :caption: Usage of assertImplementsInterface()
   :name: assertions.assertImplementsInterface.example

.. literalinclude:: examples/AssertImplementsInterfaceTest.stdout
   :linenos:
   :language: none


.. _assertions.assertObjectPropertiesEqualTo:

assertObjectPropertiesEqualTo()
-------------------------------

.. list-table:: Prerequisites for assertObjectPropertiesEqualTo()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - php-tailors/phpunit-properties
   * - Trait
     - :class:`Tailors\\PHPUnit\\ObjectPropertiesEqualToTrait`

Synopsis:

.. code:: php

  function assertObjectPropertiesEqualTo(array $expected, object $object[, string $message = ''])

Reports an error identified by ``$message`` if ``$object``'s properties are not
equal to ``$expected`` ones (tested with ``==`` operator).
A property is either an attribute value or a value returned by object's method
that is callable without arguments. The method compares only properties
described in ``$expected``, so ``$expected = []`` accepts any ``$object``.

The arguments are:

- ``$expected`` - an associative array with property names as keys and their
  expected values as values, if a key ends with ``"()"``, then the property is
  assumed to be a method, for example ``$expected = ['foo()' => 'F']`` requires
  method ``foo()`` to return ``'F'``,
- ``$object`` - an object to be examined,
- ``$message`` - optional failure message,

The method

.. code:: php

  function assertNotObjectPropertiesEqualTo(array $expected, object $object[, string $message = ''])

is the inverse of this.

.. literalinclude:: examples/AssertObjectPropertiesEqualToTest.php
   :linenos:
   :caption: Usage of assertObjectPropertiesEqualTo()
   :name: assertions.assertObjectPropertiesEqualTo.example

.. literalinclude:: examples/AssertObjectPropertiesEqualToTest.stdout
   :linenos:
   :language: none

.. _assertions.assertObjectPropertiesIdenticalTo:

assertObjectPropertiesIdenticalTo()
-----------------------------------

.. list-table:: Prerequisites for assertObjectPropertiesIdenticalTo()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - php-tailors/phpunit-properties
   * - Trait
     - :class:`Tailors\\PHPUnit\\ObjectPropertiesIdenticalToTrait`

Synopsis:

.. code:: php

  function assertObjectPropertiesIdenticalTo(array $expected, object $object[, string $message = ''])

Reports an error identified by ``$message`` if ``$object``'s properties are not
identical with ``$expected`` ones (tested with ``===`` operator).
A property is either an attribute value or a value returned by object's method
that is callable without arguments. The method compares only properties
described in ``$expected``, so ``$expected = []`` accepts any ``$object``.

The arguments are:

- ``$expected`` - an associative array with property names as keys and their
  expected values as values, if a key ends with ``"()"``, then the property is
  assumed to be a method, for example ``$expected = ['foo()' => 'F']`` requires
  method ``foo()`` to return ``'F'``,
- ``$object`` - an object to be examined,
- ``$message`` - optional failure message,

The method

.. code:: php

  function assertNotObjectPropertiesIdenticalTo(array $expected, array $matches[, string $message = ''])

is the inverse of this.

.. literalinclude:: examples/AssertObjectPropertiesIdenticalToTest.php
   :linenos:
   :caption: Usage of assertObjectPropertiesIdenticalTo()
   :name: assertions.assertObjectPropertiesIdenticalTo.example

.. literalinclude:: examples/AssertObjectPropertiesIdenticalToTest.stdout
   :linenos:
   :language: none

.. _assertions.assertUsesTrait:

assertUsesTrait()
-----------------

.. list-table:: Prerequisites for assertUsesTrait()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - php-tailors/phpunit-inheritance
   * - Trait
     - :class:`Tailors\\PHPUnit\\UsesTraitTrait`


Synopsis:

.. code:: php

  function assertUsesTrait(string $trait, mixed $subject[, string $message = ''])

Reports an error identified by ``$message`` if ``$subject`` does not use the
``$trait``. The ``$subject`` may be an ``object`` or a class name as ``string``:

- if ``$subject`` is an ``object``, then its class, as returned by
  ``get_class($subject)``, is examined against ``$trait``, the assertion
  succeeds only if the class uses the ``$trait``,
- otherwise, the necessary conditions for the assertion to succeed are that

  - ``$subject`` is a string,
  - ``class_exists($subject)`` is ``true``, and
  - the ``$subject`` implements the ``$trait``.

The method

.. code:: php

  function assertNotUsesTrait(string $trait, mixed $subject[, string $message = ''])

is the inverse of this.

.. literalinclude:: examples/AssertUsesTraitTest.php
   :linenos:
   :caption: Usage of assertUsesTrait()
   :name: assertions.assertUsesTrait.example

.. literalinclude:: examples/AssertUsesTraitTest.stdout
   :linenos:
   :language: none

.. _preg_match(): https://www.php.net/manual/en/function.preg-match.php

.. <!--- vim: set syntax=rst spell: -->
