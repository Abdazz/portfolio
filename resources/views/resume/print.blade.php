<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $data->locale) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $data->profile?->full_name ?? config('app.name') }} — {{ __('resume.title') }}</title>

    <style>
        /* ── Base reset ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { font-size: 11pt; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #1a1a2e;
            background: #ffffff;
            line-height: 1.5;
        }

        /* ── Print page setup ── */
        @page { size: A4; margin: 18mm 16mm; }

        @media print {
            body { background: #fff; }
            .no-print { display: none !important; }
            section { page-break-inside: avoid; }
        }

        /* ── Utility ── */
        a { color: inherit; text-decoration: none; }
        ul { list-style: none; }
    </style>
</head>
<body>

    {{-- Template partial fills the body --}}
    @include($templateView, ['data' => $data])

</body>
</html>
