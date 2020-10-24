.. _constraints:

Constraints
===========

This section lists the various constraint methods provided by sub-packages of
phptailors/phpunit-extensions. The methods may be added to your test class by
including appropriate trait as shown in prerequisite tables below.

.. _constraints.arrayValuesEqualTo:

arrayValuesEqualTo
------------------

.. list-table:: Prerequisites for arrayValuesEqualTo()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-arrays
   * - Trait
     - :class:`Tailors\\PHPUnit\\ArrayValuesEqualToTrait`

Synopsis:

.. code:: php

  function arrayValuesEqualTo(array $expected)

Creates :class:`Tailors\\PHPUnit\\Constraint\\ArrayValuesEqualTo` constraint.

The constraint accepts arrays and ArrayAccess_ instances having selected values
equal to ``$expected``.

.. literalinclude:: examples/arrayValuesEqualToTest.php
   :linenos:
   :caption: Usage of arrayValuesEqualTo()
   :name: constraints.arrayValuesEqualTo.example

.. literalinclude:: examples/arrayValuesEqualToTest.stdout
  :linenos:
  :language: none

The constraint may be used recursively, i.e. it may be used to require given
nested value to be an array with prescribed properties.


.. _constraints.arrayValuesIdenticalTo:

arrayValuesIdenticalTo
----------------------

.. list-table:: Prerequisites for arrayValuesIdenticalTo()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-arrays
   * - Trait
     - :class:`Tailors\\PHPUnit\\ArrayValuesIdenticalToTrait`

Synopsis:

.. code:: php

  function arrayValuesIdenticalTo(array $expected)

Creates :class:`Tailors\\PHPUnit\\Constraint\\ArrayValuesIdenticalTo` constraint.

The constraint accepts arrays and ArrayAccess_ instances having selected values
identical to ``$expected``.

.. literalinclude:: examples/arrayValuesIdenticalToTest.php
   :linenos:
   :caption: Usage of arrayValuesIdenticalTo()
   :name: constraints.arrayValuesIdenticalTo.example

.. literalinclude:: examples/arrayValuesIdenticalToTest.stdout
  :linenos:
  :language: none

The constraint may be used recursively, i.e. it may be used to require a nested
value to be an array with prescribed values.


.. _constraints.classPropertiesEqualTo:

classPropertiesEqualTo
-----------------------

.. list-table:: Prerequisites for classPropertiesEqualTo()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-properties
   * - Trait
     - :class:`Tailors\\PHPUnit\\ClassPropertiesEqualToTrait`

Synopsis:

.. code:: php

  function classPropertiesEqualTo(array $expected)

Creates :class:`Tailors\\PHPUnit\\Constraint\\ClassPropertiesEqualTo` constraint.

The constraint accepts classes having selected properties equal to
``$expected``.

.. literalinclude:: examples/classPropertiesEqualToTest.php
   :linenos:
   :caption: Usage of classPropertiesEqualTo()
   :name: constraints.classPropertiesEqualTo.example

.. literalinclude:: examples/classPropertiesEqualToTest.stdout
  :linenos:
  :language: none

The constraint may be used recursively, i.e. it may be used to require given
property to be a class with prescribed properties.


.. _constraints.classPropertiesIdenticalTo:

classPropertiesIdenticalTo
---------------------------

.. list-table:: Prerequisites for classPropertiesIdenticalTo()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-properties
   * - Trait
     - :class:`Tailors\\PHPUnit\\ClassPropertiesIdenticalToTrait`

Synopsis:

.. code:: php

  function classPropertiesIdenticalTo(array $expected)

Creates :class:`Tailors\\PHPUnit\\Constraint\\ClassPropertiesIdenticalTo` constraint.

The constraint accepts classes having selected properties identical to
``$expected``.

.. literalinclude:: examples/classPropertiesIdenticalToTest.php
   :linenos:
   :caption: Usage of classPropertiesIdenticalTo()
   :name: constraints.classPropertiesIdenticalTo.example

.. literalinclude:: examples/classPropertiesIdenticalToTest.stdout
  :linenos:
  :language: none

The constraint may be used recursively, i.e. it may be used to require given
property to be a class with prescribed properties.


.. _constraints.extendsClass:

extendsClass
------------

.. list-table:: Prerequisites for extendsClass()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-inheritance
   * - Trait
     - :class:`Tailors\\PHPUnit\\ExtendsClassTrait`

Synopsis:

.. code:: php

  function extendsClass(string $parent)

Creates :class:`Tailors\\PHPUnit\\Constraint\\ExtendsClass` constraint.

The constraint accepts objects (and classes) that extend ``$parent`` class. The
examined ``$subject`` may be an ``object`` or a class name as ``string``:

- if the ``$subject`` is an ``object``, then this object's class, as returned
  by ``get_class($subject)``, is examined against ``$parent``, the constraint
  returns ``true`` only if the class extends the ``$parent`` class,
- otherwise, the necessary conditions for the constraint to be satisfied are
  that

  - ``$subject`` is a string,
  - ``class_exists($subject)`` is ``true``, and
  - the ``$subject`` class extends the ``$parent`` class.

.. literalinclude:: examples/extendsClassTest.php
   :linenos:
   :caption: Usage of extendsClass()
   :name: constraints.extendsClass.example

.. literalinclude:: examples/extendsClassTest.stdout
  :linenos:
  :language: none

.. _constraints.hasPregCaptures:

hasPregCaptures
---------------

.. list-table:: Prerequisites for hasPregCaptures()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-regexp
   * - Trait
     - :class:`Tailors\\PHPUnit\\HasPregCapturesTrait`

Synopsis:

.. code:: php

  function hasPregCaptures(array $expected)

Creates :class:`Tailors\\PHPUnit\\Constraint\\HasPregCaptures` constraint.

The constraint accepts arrays of matches returned from ``preg_match()`` having
capture groups as specified in ``$expected``. Only entries present in
``$expected`` are checked, so ``$expected = []`` accepts any array. Special
values may be used in the expectations:

- ``['foo' => false]`` asserts that group ``'foo'`` was not captured,
- ``['foo' => true]`` asserts that group ``'foo'`` was captured,
- ``['foo' => 'FOO']`` asserts that group ``'foo'`` was captured and its value equals ``'FOO'``.

Boolean expectations (``['foo' => true]`` or ``['foo' => false]`` work properly
only with arrays obtained from ``preg_match()`` invoked with
``PREG_UNMATCHED_AS_NULL`` flag.

.. literalinclude:: examples/hasPregCapturesTest.php
   :linenos:
   :caption: Usage of hasPregCaptures()
   :name: constraints.hasPregCaptures.example

.. literalinclude:: examples/hasPregCapturesTest.stdout
  :linenos:
  :language: none


.. _constraints.implementsInterface:

implementsInterface
-------------------

.. list-table:: Prerequisites for implementsInterface()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-inheritance
   * - Trait
     - :class:`Tailors\\PHPUnit\\ImplementsInterfaceTrait`

Synopsis:

.. code:: php

  function implementsInterface(array $interface)

Creates :class:`Tailors\\PHPUnit\\Constraint\\ImplementsInterface` constraint.

The constraint accepts objects (and classes/interfaces) that implement given
``$interface``.

.. literalinclude:: examples/implementsInterfaceTest.php
   :linenos:
   :caption: Usage of implementsInterface()
   :name: constraints.implementsInterface.example

.. literalinclude:: examples/implementsInterfaceTest.stdout
  :linenos:
  :language: none


.. _constraints.objectPropertiesEqualTo:

objectPropertiesEqualTo
-----------------------

.. list-table:: Prerequisites for objectPropertiesEqualTo()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-properties
   * - Trait
     - :class:`Tailors\\PHPUnit\\ObjectPropertiesEqualToTrait`

Synopsis:

.. code:: php

  function objectPropertiesEqualTo(array $expected)

Creates :class:`Tailors\\PHPUnit\\Constraint\\ObjectPropertiesEqualTo` constraint.

The constraint accepts objects having selected properties equal to
``$expected``.

.. literalinclude:: examples/objectPropertiesEqualToTest.php
   :linenos:
   :caption: Usage of objectPropertiesEqualTo()
   :name: constraints.objectPropertiesEqualTo.example

.. literalinclude:: examples/objectPropertiesEqualToTest.stdout
  :linenos:
  :language: none

The constraint may be used recursively, i.e. it may be used to require given
property to be an object with prescribed properties.


.. _constraints.objectPropertiesIdenticalTo:

objectPropertiesIdenticalTo
---------------------------

.. list-table:: Prerequisites for objectPropertiesIdenticalTo()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-properties
   * - Trait
     - :class:`Tailors\\PHPUnit\\ObjectPropertiesIdenticalToTrait`

Synopsis:

.. code:: php

  function objectPropertiesIdenticalTo(array $expected)

Creates :class:`Tailors\\PHPUnit\\Constraint\\ObjectPropertiesIdenticalTo` constraint.

The constraint accepts objects having selected properties identical to
``$expected``.

.. literalinclude:: examples/objectPropertiesIdenticalToTest.php
   :linenos:
   :caption: Usage of objectPropertiesIdenticalTo()
   :name: constraints.objectPropertiesIdenticalTo.example

.. literalinclude:: examples/objectPropertiesIdenticalToTest.stdout
  :linenos:
  :language: none

The constraint may be used recursively, i.e. it may be used to require given
property to be an object with prescribed properties.

.. _constraints.usesTrait:

usesTrait
---------

.. list-table:: Prerequisites for usesTrait()
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-inheritance
   * - Trait
     - :class:`Tailors\\PHPUnit\\UsesTraitTrait`

Synopsis:

.. code:: php

  function usesTrait(array $trait)

Creates :class:`Tailors\\PHPUnit\\Constraint\\UsesTrait` constraint.

The constraint accepts objects (and classes) that use given ``$trait``.

.. literalinclude:: examples/usesTraitTest.php
   :linenos:
   :caption: Usage of usesTrait()
   :name: constraints.usesTrait.example

.. literalinclude:: examples/usesTraitTest.stdout
  :linenos:
  :language: none

.. _ArrayAccess:  https://www.php.net/manual/en/class.arrayaccess.php

.. <!--- vim: set syntax=rst spell: -->
