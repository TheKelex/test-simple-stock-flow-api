# Laravel Phase 4 — Infrastructure and Persistence

This folder contains the reference implementation for the backend persistence layer and the runtime wiring required by the project.

## Scope

Phase 4 covers:

- database schema via migrations
- seeders for categories and admin bootstrap data
- infrastructure repositories and mappers
- transaction abstraction
- security and storage adapters
- runtime wiring for Docker-based execution

## Structure

```text
app/laravel-phase-4/
├── backend/
│   ├── app/
│   ├── database/
│   ├── Dockerfile
│   └── .env.example
```