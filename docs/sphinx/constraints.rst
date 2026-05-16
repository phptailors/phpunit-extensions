.. _constraints:

Constraints
===========

This section lists the various constraint methods provided by sub-packages of
phptailors/phpunit-extensions. The methods may be added to your test class by
including appropriate trait as shown in prerequisite tables below.

.. _constraints.arrayValuesEqualTo:

arrayValuesEqualTo()
--------------------

.. list-table:: Prerequisites for :ref:`constraints.arrayValuesEqualTo`
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

The constraint accepts arrays and ArrayAccess_ objects having values under
certain keys equal to these specified in ``$expected``. The constraint uses
operator ``==`` for comparison.

.. literalinclude:: examples/arrayValuesEqualToTest.php
   :linenos:
   :caption: Usage of :ref:`constraints.arrayValuesEqualTo`
   :name: constraints.arrayValuesEqualTo.example

.. literalinclude:: examples/arrayValuesEqualToTest.stdout
  :linenos:
  :language: none

Since versions ``*.1`` (``1.1``, ``2.1``, ``3.1``, ...) it's possible to nest
recursively specification of array values. For more details, see
:ref:`utilities.arrayValues`.

.. literalinclude:: examples/arrayValuesEqualToRecursiveTest.php
   :linenos:
   :caption: Usage of :ref:`constraints.arrayValuesEqualTo` with :ref:`utilities.arrayValues`
   :name: constraints.arrayValuesEqualToRecursive.example

.. literalinclude:: examples/arrayValuesEqualToRecursiveTest.stdout
  :linenos:
  :language: none

.. _constraints.arrayValuesIdenticalTo:

arrayValuesIdenticalTo()
------------------------

.. list-table:: Prerequisites for :ref:`constraints.arrayValuesIdenticalTo`
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
   :caption: Usage of :ref:`constraints.arrayValuesIdenticalTo`
   :name: constraints.arrayValuesIdenticalTo.example

.. literalinclude:: examples/arrayValuesIdenticalToTest.stdout
  :linenos:
  :language: none

Since versions ``*.1`` (``1.1``, ``2.1``, ``3.1``, ...) it's possible to nest
recursively specification of array values. For more details, see
:ref:`utilities.arrayValues`.

.. literalinclude:: examples/arrayValuesIdenticalToRecursiveTest.php
   :linenos:
   :caption: Usage of :ref:`constraints.arrayValuesIdenticalTo` with nested selectors
   :name: constraints.arrayValuesIdenticalToRecursive.example

.. literalinclude:: examples/arrayValuesIdenticalToRecursiveTest.stdout
  :linenos:
  :language: none


.. _constraints.classPropertiesEqualTo:

classPropertiesEqualTo()
------------------------

.. list-table:: Prerequisites for :ref:`constraints.classPropertiesEqualTo`
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
   :caption: Usage of :ref:`constraints.classPropertiesEqualTo`
   :name: constraints.classPropertiesEqualTo.example

.. literalinclude:: examples/classPropertiesEqualToTest.stdout
  :linenos:
  :language: none

Since versions ``*.1`` (``1.1``, ``2.1``, ``3.1``, ...) it's possible to nest
recursively specifications of class properties. For more details, see
:ref:`utilities.arrayValues`.

.. literalinclude:: examples/classPropertiesEqualToRecursiveTest.php
   :linenos:
   :caption: Usage of :ref:`constraints.classPropertiesEqualTo` with :ref:`utilities.classProperties`
   :name: constraints.classPropertiesEqualToRecursive.example

.. literalinclude:: examples/classPropertiesEqualToRecursiveTest.stdout
  :linenos:
  :language: none


.. _constraints.classPropertiesIdenticalTo:

classPropertiesIdenticalTo()
----------------------------

.. list-table:: Prerequisites for :ref:`constraints.classPropertiesIdenticalTo`
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
   :caption: Usage of :ref:`constraints.classPropertiesIdenticalTo`
   :name: constraints.classPropertiesIdenticalTo.example

.. literalinclude:: examples/classPropertiesIdenticalToTest.stdout
  :linenos:
  :language: none

Since versions ``*.1`` (``1.1``, ``2.1``, ``3.1``, ...) it's possible to nest
recursively specifications of class properties. For more details, see
:ref:`utilities.arrayValues`.

.. literalinclude:: examples/classPropertiesIdenticalToRecursiveTest.php
   :linenos:
   :caption: Usage of :ref:`constraints.classPropertiesIdenticalTo` with :ref:`utilities.classProperties`
   :name: constraints.classPropertiesIdenticalToRecursive.example

.. literalinclude:: examples/classPropertiesIdenticalToRecursiveTest.stdout
  :linenos:
  :language: none


.. _constraints.extendsClass:

extendsClass()
--------------

.. list-table:: Prerequisites for :ref:`constraints.extendsClass`
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
   :caption: Usage of :ref:`constraints.extendsClass`
   :name: constraints.extendsClass.example

.. literalinclude:: examples/extendsClassTest.stdout
  :linenos:
  :language: none

.. _constraints.hasMethod:

hasMethod()
-----------

.. list-table:: Prerequisites for :ref:`constraints.hasMethod`
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-methods
   * - Trait
     - :class:`Tailors\\PHPUnit\\HasMethodTrait`

Synopsis:

.. code:: php

  function hasMethod(array $methodSpec)

Creates :class:`Tailors\\PHPUnit\\Constraint\\HasMethod` constraint.

The constraint accepts values having a method that matches ``$methodSpec``.
The ``$methodSpec`` specifies matching method via its name and modifiers
(optional).

.. note::

    The constraint only matches a value being an object, class, trait or
    interface. For any other values it always returns false.

The syntax for ``$methodSpec`` string is the following

.. code:: abnf

    methodSpec  :=  [modifiers "function"] name
                |   name

    name        :=  [_a-zA-Z][_0-9a-zA-Z]*

    modifiers   :=  [abstract] [final] [access] [static]    ; order arbitrary

    abstract    :=  "abstract"
                |   "!abstract"

    final       :=  "final"
                |   "!final"

    access      :=  "public"
                |   "!public"
                |   "protected"
                |   "!protected"

    static      :=  "static"
                |   "!static"


The only allowed combination of ``abstract`` and ``final`` is ``"!abstract"``
+ ``"!final"``. All other combinations are treated as syntax error.

Presence of a modifier (such as ``"final"``) requires the modifier to be
present in a matched method. Presence of negated modifier (such as
``"!final"``) requires the modifier to be absent in a matched method. Absence
of a modifier in ``$methodSpec`` means that it may be either present or absent
in a matched method.

.. literalinclude:: examples/hasMethodTest.php
   :linenos:
   :caption: Usage of :ref:`constraints.hasMethod`
   :name: constraints.hasMethod.example

.. literalinclude:: examples/hasMethodTest.stdout
  :linenos:
  :language: none



.. _constraints.hasPregCaptures:

hasPregCaptures()
-----------------

.. list-table:: Prerequisites for :ref:`constraints.hasPregCaptures`
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

Boolean expectations (``['foo' => true]`` or ``['foo' => false]``) work
properly only with arrays obtained from ``preg_match()`` invoked with
``PREG_UNMATCHED_AS_NULL`` flag.

.. literalinclude:: examples/hasPregCapturesTest.php
   :linenos:
   :caption: Usage of :ref:`constraints.hasPregCaptures`
   :name: constraints.hasPregCaptures.example

.. literalinclude:: examples/hasPregCapturesTest.stdout
  :linenos:
  :language: none


.. _constraints.implementsInterface:

implementsInterface()
---------------------

.. list-table:: Prerequisites for :ref:`constraints.implementsInterface`
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
   :caption: Usage of :ref:`constraints.implementsInterface`
   :name: constraints.implementsInterface.example

.. literalinclude:: examples/implementsInterfaceTest.stdout
  :linenos:
  :language: none

.. _constraints.ksortedArrayEqualTo:

ksortedArrayEqualTo()
---------------------

.. list-table:: Prerequisites for :ref:`constraints.ksortedArrayEqualTo`
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-arrays
   * - Trait
     - :class:`Tailors\\PHPUnit\\KsortedArrayEqualToTrait`

Synopsis:

.. code:: php

  function ksortedArrayEqualTo(array $expected)

Creates :class:`Tailors\\PHPUnit\\Constraint\\KsortedArrayEqualTo` constraint.

The constraint accepts arrays that are equal to ``$expected`` when key-sorted.

.. literalinclude:: examples/ksortedArrayEqualToTest.php
   :linenos:
   :caption: Usage of :ref:`constraints.ksortedArrayEqualTo`
   :name: constraints.ksortedArrayEqualTo.example

.. literalinclude:: examples/ksortedArrayEqualToTest.stdout
  :linenos:
  :language: none


.. _constraints.ksortedArrayIdenticalTo:

ksortedArrayIdenticalTo()
-------------------------

.. list-table:: Prerequisites for :ref:`constraints.ksortedArrayIdenticalTo`
   :width: 100%
   :widths: 25 75
   :header-rows: 0

   * - Package
     - phptailors/phpunit-arrays
   * - Trait
     - :class:`Tailors\\PHPUnit\\KsortedArrayIdenticalToTrait`

Synopsis:

.. code:: php

  function ksortedArrayIdenticalTo(array $expected)

Creates :class:`Tailors\\PHPUnit\\Constraint\\KsortedArrayIdenticalTo` constraint.

The constraint accepts arrays identical to ``$expected`` when key-sorted.

.. literalinclude:: examples/ksortedArrayIdenticalToTest.php
   :linenos:
   :caption: Usage of :ref:`constraints.ksortedArrayIdenticalTo`
   :name: constraints.ksortedArrayIdenticalTo.example

.. literalinclude:: examples/ksortedArrayIdenticalToTest.stdout
  :linenos:
  :language: none


.. _constraints.objectPropertiesEqualTo:

objectPropertiesEqualTo()
-------------------------

.. list-table:: Prerequisites for :ref:`constraints.objectPropertiesEqualTo`
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
   :caption: Usage of :ref:`constraints.objectPropertiesEqualTo`
   :name: constraints.objectPropertiesEqualTo.example

.. literalinclude:: examples/objectPropertiesEqualToTest.stdout
  :linenos:
  :language: none

Since versions ``*.1`` (``1.1``, ``2.1``, ``3.1``, ...) it's possible to nest
recursively specifications of object properties. For more details, see
:ref:`utilities.arrayValues`.

.. literalinclude:: examples/objectPropertiesEqualToRecursiveTest.php
   :linenos:
   :caption: Usage of :ref:`constraints.objectPropertiesEqualTo` with :ref:`utilities.objectProperties`
   :name: constraints.objectPropertiesEqualToRecursive.example

.. literalinclude:: examples/objectPropertiesEqualToRecursiveTest.stdout
  :linenos:
  :language: none



.. _constraints.objectPropertiesIdenticalTo:

objectPropertiesIdenticalTo()
-----------------------------

.. list-table:: Prerequisites for :ref:`constraints.objectPropertiesIdenticalTo`
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
   :caption: Usage of :ref:`constraints.objectPropertiesIdenticalTo`
   :name: constraints.objectPropertiesIdenticalTo.example

.. literalinclude:: examples/objectPropertiesIdenticalToTest.stdout
  :linenos:
  :language: none


Since versions ``*.1`` (``1.1``, ``2.1``, ``3.1``, ...) it's possible to nest
recursively specifications of object properties. For more details, see
:ref:`utilities.arrayValues`.

.. literalinclude:: examples/objectPropertiesIdenticalToRecursiveTest.php
   :linenos:
   :caption: Usage of :ref:`constraints.objectPropertiesIdenticalTo` with :ref:`utilities.objectProperties`
   :name: constraints.objectPropertiesIdenticalToRecursive.example

.. literalinclude:: examples/objectPropertiesIdenticalToRecursiveTest.stdout
  :linenos:
  :language: none

.. _constraints.usesTrait:

usesTrait()
-----------

.. list-table:: Prerequisites for :ref:`constraints.usesTrait`
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
   :caption: Usage of :ref:`constraints.usesTrait`
   :name: constraints.usesTrait.example

.. literalinclude:: examples/usesTraitTest.stdout
  :linenos:
  :language: none

.. _ArrayAccess:  https://www.php.net/manual/en/class.arrayaccess.php

.. <!--- vim: set syntax=rst spell: -->
