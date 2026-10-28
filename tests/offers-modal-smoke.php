<?php

$post_id = 42;
$offers_meta = array(
    'offers_sections' => array(
        array('section_id' => 'fishing', 'cards' => array(
            array('title' => 'Рыбалка', 'desc' => 'Краткий текст', 'link' => '', 'conditions' => array()),
        )),
        array('section_id' => 'spa', 'cards' => array(
            array('title' => 'SPA', 'desc' => 'Краткий текст', 'modal_text' => 'Подробный текст', 'link' => '#cta', 'conditions' => array()),
        )),
    ),
    'acc_offers' => array('cards' => array(
        array('title' => 'Дом', 'desc' => 'Описание дома', 'link' => '/offer/', 'conditions' => array()),
    )),
);

function get_the_ID() { global $post_id; return $post_id; }
function get_post_meta($id, $key, $single = true) { global $offers_meta; return $offers_meta[$key] ?? ''; }
function esc_attr($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function esc_html($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function esc_url($value) { return esc_attr($value); }
function sanitize_title($value) { return strtolower(trim(preg_replace('/[^a-z0-9-]+/i', '-', $value), '-')); }
function wpautop($value) { return '<p>' . str_replace("\n\n", '</p><p>', $value) . '</p>'; }

ob_start();
$GLOBALS['offers_block_id'] = 'fishing';
include __DIR__ . '/../includes/offers-current.php';
$GLOBALS['offers_block_id'] = 'spa';
include __DIR__ . '/../includes/offers-current.php';
include __DIR__ . '/../includes/offers-acc.php';
$html = ob_get_clean();

$document = new DOMDocument();
libxml_use_internal_errors(true);
$document->loadHTML('<?xml encoding="UTF-8">' . $html);
libxml_clear_errors();
$xpath = new DOMXPath($document);
$cards = $xpath->query('//*[@data-offers-modal]');
$modals = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " offers-modal ")]');
if (3 !== $cards->length || 3 !== $modals->length) throw new RuntimeException('Each offer must render a card and a modal.');

$ids = array();
foreach ($cards as $card) {
    $target = $card->getAttribute('data-offers-modal');
    if (isset($ids[$target])) throw new RuntimeException('Offer modal IDs are not unique.');
    $ids[$target] = true;
    if (1 !== $xpath->query('//*[@id="' . $target . '"]')->length) throw new RuntimeException('Offer card has no matching modal.');
}
foreach ($modals as $modal) {
    if (0 !== $xpath->query('ancestor::section', $modal)->length) throw new RuntimeException('Offer modal is nested inside a section.');
}
if (false === strpos($html, 'Подробный текст') || false === strpos($html, 'data-offers-modal-book')) {
    throw new RuntimeException('Modal details or booking action are missing.');
}
if (false === strpos($html, 'href="/offer/"')) throw new RuntimeException('Explicit offer URL was not preserved.');

echo "Offers modal rendering: OK\n";
