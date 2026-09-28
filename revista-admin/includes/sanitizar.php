<?php
/**
 * Limpieza del HTML que llega desde CKEditor 5.
 *
 * El contenido se renderiza tal cual en el portal público, así que antes de
 * guardarlo se filtra contra una lista blanca de etiquetas y atributos.
 * Todo lo demás (script, iframe, style, on* , javascript:) se descarta.
 */

const HTML_ETIQUETAS_PERMITIDAS = [
    'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'sub', 'sup',
    'h2', 'h3', 'h4', 'h5', 'h6',
    'ul', 'ol', 'li', 'blockquote', 'pre', 'code', 'hr',
    'a', 'img', 'figure', 'figcaption',
    'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'caption',
    'span', 'div',
];

const HTML_ATRIBUTOS_PERMITIDOS = [
    'a'      => ['href', 'title', 'target', 'rel'],
    'img'    => ['src', 'alt', 'title', 'width', 'height'],
    'th'     => ['colspan', 'rowspan'],
    'td'     => ['colspan', 'rowspan'],
    'figure' => ['class'],
    'span'   => ['class'],
    'div'    => ['class'],
    'p'      => ['class'],
    'table'  => ['class'],
    'blockquote' => ['class'],
];

function sanitizar_html(string $html): string
{
    $html = trim($html);
    if ($html === '') {
        return '';
    }

    $doc = new DOMDocument('1.0', 'UTF-8');
    $previo = libxml_use_internal_errors(true);

    $envuelto = '<?xml encoding="UTF-8"><div id="raiz-ddp">' . $html . '</div>';
    $doc->loadHTML($envuelto, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    libxml_clear_errors();
    libxml_use_internal_errors($previo);

    $xpath = new DOMXPath($doc);
    $raiz  = $xpath->query('//div[@id="raiz-ddp"]')->item(0);
    if (!$raiz) {
        return '';
    }

    limpiar_nodo($raiz);

    $salida = '';
    foreach ($raiz->childNodes as $hijo) {
        $salida .= $doc->saveHTML($hijo);
    }

    return trim($salida);
}

function limpiar_nodo(DOMNode $nodo): void
{
    // Se recorre al revés porque se eliminan nodos durante el bucle.
    for ($i = $nodo->childNodes->length - 1; $i >= 0; $i--) {
        $hijo = $nodo->childNodes->item($i);

        if ($hijo instanceof DOMComment) {
            $nodo->removeChild($hijo);
            continue;
        }

        if (!$hijo instanceof DOMElement) {
            continue; // texto: se conserva
        }

        $etiqueta = strtolower($hijo->nodeName);

        if (!in_array($etiqueta, HTML_ETIQUETAS_PERMITIDAS, true)) {
            // Se descarta la etiqueta pero se conserva su texto interno.
            while ($hijo->firstChild) {
                $nodo->insertBefore($hijo->firstChild, $hijo);
            }
            $nodo->removeChild($hijo);
            continue;
        }

        $permitidos = HTML_ATRIBUTOS_PERMITIDOS[$etiqueta] ?? [];
        for ($j = $hijo->attributes->length - 1; $j >= 0; $j--) {
            $attr   = $hijo->attributes->item($j);
            $nombre = strtolower($attr->nodeName);

            if (!in_array($nombre, $permitidos, true)) {
                $hijo->removeAttribute($attr->nodeName);
                continue;
            }

            if (($nombre === 'href' || $nombre === 'src') && !url_segura($attr->nodeValue)) {
                $hijo->removeAttribute($attr->nodeName);
            }
        }

        // Los enlaces externos se abren en pestaña nueva sin ceder la ventana.
        if ($etiqueta === 'a' && $hijo->getAttribute('target') === '_blank') {
            $hijo->setAttribute('rel', 'noopener noreferrer');
        }

        limpiar_nodo($hijo);
    }
}

function url_segura(string $url): bool
{
    $url = trim($url);
    if ($url === '') {
        return false;
    }
    // Rutas relativas y anclas siempre valen.
    if ($url[0] === '/' || $url[0] === '#' || str_starts_with($url, './')) {
        return true;
    }
    $esquema = strtolower((string) parse_url($url, PHP_URL_SCHEME));
    return in_array($esquema, ['http', 'https', 'mailto'], true);
}
