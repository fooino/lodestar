## Package delta — core

`fooino/core` is the generic, portable foundation; a bug here cascades everywhere.

### Domains

| Domain | Interface | Handler | Manager | Facade |
|---|---|---|---|---|
| JSON | `Jsonable` | `FooinoJsonHandler` | `JsonManager` | `Json` |
| Date | `Dateable` | `FooinoDateHandler` | `DateManager` | `Date` |
| Math | `Mathable` | `FooinoMathHandler` | `MathManager` | `Math` |
