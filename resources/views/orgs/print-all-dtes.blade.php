<!DOCTYPE html>
<html>
<head>
    <title>Impresión de DTEs</title>
    <style>
        .page-break {
            page-break-after: always;
        }
        table {
    table-layout: fixed;
    word-wrap: break-word;
}
    </style>
    <style>
@media print {
    body {
        font-size: 11px; /* Reduce el tamaño general */
        margin: 0;
        padding: 0;
    }

    .page {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
        padding: 10mm; /* márgenes seguros para impresión */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }

    th, td {
        padding: 4px;
        border: 1px solid #000;
    }

    /* Evita que los elementos de una fila se partan entre páginas */
    tr {
        page-break-inside: avoid;
    }

    /* Elimina elementos innecesarios como botones o menús */
    .no-print {
        display: none !important;
    }
}
</style>

</head>
<body onload="window.print()">
    @foreach($order_items as $item)
        @php
            $readingId = $item->reading_id;
        @endphp

        <iframe src="{{ route('orgs.multiBoletaPrint', ['id' => $org->id, 'reading_id' => $readingId]) }}"
                style="width: 100%; height: 1200px; border: none;"></iframe>

        <div class="page-break"></div>
    @endforeach
</body>
</html>
