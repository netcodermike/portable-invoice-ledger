# Portable Invoice Ledger

Generate itemized invoice JSON using integer cents, explicit tax basis points, and validated quantities.

**Language:** PHP · **License:** MIT · **Data:** Local by default

## What it does

- Integer-cent invoice calculation
- Explicit tax basis points and discounts
- Optional locked JSONL ledger with duplicate-ID prevention

## Requirements

PHP 8.2 or newer.

## Install, validate, and run

```sh
php app.php --self-test
php app.php examples/invoice.json
```

Run `--help` where supported, or follow the examples in [docs/usage.md](docs/usage.md). Input files are never overwritten unless explicitly documented.

## Usage and examples

See [docs/usage.md](docs/usage.md) for a walkthrough and [docs/architecture.md](docs/architecture.md) for implementation details. Tests are deterministic and do not require an exchange, paid API, external model, microphone, or private data.

## Boundaries

Calculation and recordkeeping only; no payment processing or tax-rule determination. Tax rate/currency must be supplied by the user.

## Privacy

No analytics or accounts are built in. Local file tools process only the paths you supply. For model-server and weather apps, the interface and usage guide identify the destination and the data sent. Do not commit personal input files, model credentials, or generated recordings.

## Development

Run the validation commands above before submitting changes. GitHub Actions runs these checks on pushes and pull requests. See the workflow for the reproducible toolchain. A successful workflow verifies the tested paths; manual audio-device behavior is documented separately.
