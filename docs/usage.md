# Using Portable Invoice Ledger

## Quick start

Run `php app.php invoice.json`. Values are integer cents; `tax_bps: 825` means 8.25%. Optional `--ledger history.jsonl` appends under a lock and rejects repeated IDs. Zero tax must be explicit; no tax jurisdiction is inferred.

## Supported workflow

1. Integer-cent invoice calculation.
2. Explicit tax basis points and discounts.
3. Optional locked JSONL ledger with duplicate-ID prevention.

## Validation and troubleshooting

Run the commands in the README. Invalid inputs produce explicit errors rather than silently changing the requested operation. Browser audio requires a user gesture; microphone apps require HTTPS or localhost and permission. For file tools, supply supported input formats and writable output directories.

## Limitations

Calculation and recordkeeping only; no payment processing or tax-rule determination. Tax rate/currency must be supplied by the user.

Tests use synthetic fixtures. These tests do not certify hardware compatibility, real-world forecast quality, trained-model accuracy, or production suitability for every input.
