<!DOCTYPE html>
<html>
<head>
    <title>Impresión de DTEs</title>
    <style>
          .boleta-web-iframe {
            width: 100%;
            height: 1200px; /* Altura fija para la vista web, como solicitaste */
            border: none;
        }

@media print {
            @page {
                size: A2 portrait;
                margin: 0;
                padding: 0;
            }
            .boleta-iframe-container {
                width: 410mm; /* A2 width (420mm) - 10mm margins */
                height: 584mm; /* A2 height (594mm) - 10mm margins */
                max-height: 584mm;
                padding: 5mm;
                margin: 5mm auto;
            }

            @page {
                size: A3 portrait;
                margin: 0;
                padding: 0;
            }
            .boleta-iframe-container {
                width: 287mm; /* A3 width (297mm) - 10mm margins */
                height: 410mm; /* A3 height (420mm) - 10mm margins */
                max-height: 410mm;
                padding: 5mm;
                margin: 5mm auto;
            }

             @page {
                size: A4 portrait;
                margin: 0;
                padding: 0;
            }
            .boleta-iframe-container {
                /* These dimensions should reflect the usable print area on A4 */
                width: 200mm; /* A4 width (210mm) - 10mm margins */
                height: 287mm; /* A4 height (297mm) - 10mm margins */
                max-height: 287mm;
                padding: 5mm; /* Inner padding for the iframe container itself */
                margin: 5mm auto; /* Centered with 5mm outer margins */
            }


    @page { size: A5 portrait; margin: 0; padding: 0; }
    .boleta-iframe-container {
        /* A5: 148mm x 210mm. Dejamos un margen externo de 5mm en cada lado. */
        width: 138mm; /* 148 - 2*5 = 138mm */
        height: 200mm; /* 210 - 2*5 = 200mm */
        max-height: 200mm;
        padding: 2mm; /* Reducimos el padding interno del contenedor del iframe */
        margin: 5mm auto; /* Margen externo de 5mm para A5 */
    }

    @page { size: A6 portrait; margin: 0; padding: 0; }
    .boleta-iframe-container {
        /* A6: 105mm x 148mm. Dejamos un margen externo de 4mm en cada lado. */
        width: 97mm; /* 105 - 2*4 = 97mm */
        height: 140mm; /* 148 - 2*4 = 140mm */
        max-height: 140mm;
        padding: 2mm; /* Reducimos el padding interno del contenedor del iframe */
        margin: 4mm auto; /* Margen externo de 4mm para A6 */
    }
}
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iframes = document.querySelectorAll('iframe');
            let loadedCount = 0;

            iframes.forEach(iframe => {
                iframe.onload = function() {
                    try {
                        // Get the height of the content inside the iframe
                        // This only works if the iframe content is from the same origin (same domain)
                        const iframeDoc = iframe.contentWindow.document;
                        const body = iframeDoc.body;
                        const html = iframeDoc.documentElement;

                        // Calculate scroll height considering both body and html elements
                        const height = Math.max(
                            body.scrollHeight, body.offsetHeight,
                            html.clientHeight, html.scrollHeight, html.offsetHeight
                        );

                        // Set the iframe's height to its content height
                        iframe.style.height = (height + 20) + 'px'; // Add a small buffer

                        // For debugging:
                        // console.log(`Iframe ${iframe.src} loaded, content height: ${height}px`);

                    } catch (e) {
                        // This error typically means cross-origin iframe content
                        // console.error("Could not access iframe content (likely cross-origin):", e);
                        // Fallback: If cross-origin, keep a default height or let print CSS handle it
                        iframe.style.height = 'auto'; // Let print media query handle height
                    } finally {
                        loadedCount++;
                        if (loadedCount === iframes.length) {
                            // All iframes loaded, now print
                            window.print();
                        }
                    }
                };

                // Add a timeout in case onload doesn't fire due to cached content or other issues
                // This is a fallback to ensure print() is called
                setTimeout(() => {
                    loadedCount++;
                    if (loadedCount === iframes.length) {
                        window.print();
                    }
                }, 5000); // Wait up to 5 seconds for iframe to load, then print
            });

            // Fallback for cases where no iframes or onload issues
            if (iframes.length === 0) {
                window.print();
            }
        });
    </script>
</head>
<body>
    @foreach($order_items as $item)
        @php
            $readingId = $item->reading_id;
        @endphp

        <div class="boleta-iframe-container">
            <iframe src="{{ route('orgs.multiBoletaPrint', ['id' => $org->id, 'reading_id' => $readingId]) }}"
                    style="width: 100%; border: none;"></iframe>
            {{-- ELIMINAMOS <div class="page-break"></div> DE AQUÍ --}}
        </div>
    @endforeach
</body>
</html>
