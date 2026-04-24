<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'vendor',
        'node_modules',
        'var',
        'cache'
    ])
    ->name('*.php');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setRules([

        /*
         * Base: moderat Richtung PSR-12
         */
        '@PSR12' => true,

        /*
         * 🔧 WICHTIG: Anpassungen an deinen Stil
         */

        // Tabs statt Spaces (du nutzt Tabs)
        'indentation_type' => false,

        // Zeilenenden sauber halten
        'line_ending' => true,

        // Namespace nicht anfassen (wegen { })
        'single_line_after_imports' => false,

        // Keine erzwungene Klassennutzung
        'no_unused_imports' => true,

        /*
         * 🧱 Konstanten & defines (dein Stil kompatibel halten)
         */

        // KEIN Uppercase-Zwang (du nutzt lowercase wie "n", "php")
        'constant_case' => false,

        // define() nicht zu const umwandeln
        'native_constant_invocation' => false,

        /*
         * 📏 Formatierung
         */

        // Klammern & Blöcke sauber
        'braces' => [
            'position_after_functions_and_oop_constructs' => 'same',
            'position_after_control_structures' => 'same',
        ],

        // Leerzeilen nach Blöcken
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,

        // Arrays schöner
        'array_syntax' => ['syntax' => 'short'],

        // Spaces nach Kommas
        'whitespace_after_comma_in_array' => true,

        /*
         * 🔁 Control Structures
         */

        'control_structure_braces' => true,
        'control_structure_continuation_position' => true,

        /*
         * 🧼 Cleanup (ungefährlich)
         */

        'no_trailing_whitespace' => true,
        'no_whitespace_in_blank_line' => true,
        'single_blank_line_at_eof' => true,

        /*
         * ⚠️ Bewusst deaktiviert (würde deinen Code "zerstören")
         */

        // würde define/const Verhalten ändern
        'global_namespace_import' => false,

        // würde Struktur zu stark ändern
        'ordered_class_elements' => false,

        // würde Funktionen verschieben
        'function_declaration' => false,

        /*
         * 🧪 Optional (kannst du später aktivieren)
         */

        // 'strict_param' => true,
        // 'declare_strict_types' => true,
    ]);