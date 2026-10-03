# Laravel Phase 3 — Application Layer

This folder contains a minimal, architecture-aligned implementation of the Phase 3 backend work described in the project specification.

## Scope

Phase 3 is the Application layer. It sits between the pure Domain and the Infrastructure/Persistence adapters.

Responsibilities:

- define inbound ports and command objects
- define outbound repository contracts
- orchestrate business use cases
- enforce transactions through a `UnitOfWork` abstraction
- translate domain errors into application-level orchestration without leaking Laravel or database logic

## Structure

```text
src/
├── Domain/
│   ├── Entities/
│   ├── Exceptions/
│   └── ValueObjects/
├── Application/
│   ├── Ports/
│   │   ├── Inbound/
│   │   └── Outbound/
│   └── UseCases/
└── Infrastructure/
    └── ...
```

## Notes

- Domain remains free of Laravel and persistence concerns.
- Application only depends on Domain and interfaces it defines itself.
- Transaction management is abstracted behind `UnitOfWork` and implemented only in Infrastructure.
