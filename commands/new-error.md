---
description: Add a Fooino error code with docs and tests.
---

Every package exception extends `Fooino\Core\Exceptions\FooinoException`.

To add a `_CODE` method to an existing exception class, or to create a new exception class, use the `exception-architect` agent. The agent owns the code range, the class and method shape, and the test. Never choose a code by hand.
