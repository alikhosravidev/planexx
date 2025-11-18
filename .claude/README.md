# .claude/ Documentation Directory

This directory contains **organized, single-responsibility documentation** for the Planexx Laravel project.

## Purpose
- **Context Window Optimization**: Keep each file focused and concise
- **Easy Navigation**: Organized by architectural layers
- **Quick Reference**: Find exactly what you need without reading everything

## Structure

```
.claude/
├── README.md                   # This file
├── GAP-ANALYSIS.md             # Documentation gaps report
├── quick-start.md              # Get started quickly
├── architecture.md             # Overall architecture
├── domain/                     # Domain Layer
│   ├── entities.md             # Eloquent Models
│   ├── value-objects.md        # Immutable domain values
│   ├── enums.md                # Enumerations
│   └── collections.md          # Custom collections
├── application/                # Application Layer
│   ├── dtos.md                 # Data Transfer Objects
│   ├── mappers.md              # Request → DTO mapping
│   └── services.md             # Business logic (⚠️ needs expansion)
├── infrastructure/             # Infrastructure Layer
│   ├── repositories.md         # Data access (⚠️ needs expansion)
│   ├── database.md             # Migrations & Factories
│   └── observers.md            # Model lifecycle events
├── presentation/               # Presentation Layer
│   ├── controllers.md          # HTTP Controllers
│   ├── transformers.md         # Response transformation
│   └── separation.md           # Logic separation rules
├── api/                        # API Design
│   ├── design.md               # RESTful design principles
│   ├── basics.md               # Response format, status codes
│   └── authentication.md       # Auth mechanisms
├── patterns/                   # Code Patterns
│   ├── coding-standards.md     # PSR-12, naming conventions
│   └── design-patterns.md      # Standard design patterns
├── testing/                    # Testing
│   ├── guide.md                # Testing best practices
│   └── execution.md            # Running tests
├── features/                   # Specific Features
│   └── sorting.md              # Sortable models
├── guidelines/                 # Development Guidelines
│   ├── comments.md             # Comment policy
│   └── docker.md               # Docker environment
├── error-handling.md           # Try-catch rules
└── events.md                   # Event listeners
```

## How to Use

### For Quick Tasks
Start with **`quick-start.md`** for common commands and workflows.

### For Specific Tasks
Use the **"When Working on Specific Tasks"** table in `CLAUDE.md`:

| Task | Read This |
|------|-----------|
| Creating new module | `architecture.md` |
| Working with models | `domain/entities.md` |
| Creating DTOs | `application/dtos.md` |
| Writing services | `application/services.md` |
| Building APIs | `api/design.md` |
| Writing tests | `testing/guide.md` |

### For Deep Dives
Navigate to the appropriate layer directory and read the relevant file.

### For Understanding Gaps
Read **`GAP-ANALYSIS.md`** to see what documentation is missing or incomplete.

## Documentation Principles

Each file follows these principles:
1. **Single Responsibility** - One topic per file
2. **Concise** - Essential information only
3. **Complete** - All necessary details for the topic
4. **Referenced** - Links to original detailed sources
5. **Actionable** - Clear examples and guidelines

## Source Files

Original detailed documentation remains in:
- `.windsurf/rules/` - Comprehensive detailed guides
- `docs/` - Additional specific documentation
- `CLAUDE.md` - Main project index (now concise)

## Maintaining Documentation

When adding new patterns or changing architecture:
1. Update relevant `.claude/` file
2. Keep files concise and focused
3. Update `CLAUDE.md` if new categories added
4. Update `GAP-ANALYSIS.md` if gaps filled

## Contributing

When documenting new patterns:
- Create focused, single-topic files
- Use clear examples
- Reference original sources
- Keep consistent formatting
- Update this README if structure changes

---

**Last Updated**: 2025-11-18
**Purpose**: Context Window Optimization & Developer Productivity
