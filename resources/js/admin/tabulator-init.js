/**
 * tabulator-init.js — Admin Panel
 *
 * Auto-discovers every <table data-tabulator> on the page and
 * initialises Tabulator on it with:
 *   • Column sorting (click header)
 *   • Column resizing (drag column edge)
 *   • Column reordering (drag column header)
 *
 * ALL default Tabulator CSS is overridden by tabulator-custom.css.
 * HTML in cells (badges, avatars, buttons) is preserved via formatter:"html".
 * No Tabulator built-in theme is loaded (neutralised by custom CSS).
 */

(function () {
    'use strict';

    /**
     * Build column definitions from a <thead> row.
     * Each <th> becomes one column. We use formatter:"html" so all
     * existing inline HTML (badges, avatars, buttons) renders correctly.
     */
    function buildColumns(thead) {
        const ths = thead.querySelectorAll('th');
        return Array.from(ths).map(function (th, idx) {
            const title = th.textContent.trim();
            const field = 'col_' + idx;

            // "Actions" column and empty checkbox columns should not be sortable
            const noSort = (title === '' || title.toLowerCase() === 'actions');

            return {
                title: title,
                field: field,
                formatter: 'html',
                headerSort: !noSort,
                resizable: true,
                widthGrow: 1,
                minWidth: 60,
                headerHozAlign: 'left',
                hozAlign: 'left',
            };
        });
    }

    /**
     * Build row data from <tbody> rows.
     * Each cell's innerHTML is stored so all HTML content is preserved.
     */
    function buildData(tbody, colCount) {
        const rows = tbody.querySelectorAll('tr');
        return Array.from(rows).map(function (tr) {
            const cells = tr.querySelectorAll('td');
            const row = {};
            for (let i = 0; i < colCount; i++) {
                row['col_' + i] = cells[i] ? cells[i].innerHTML : '';
            }
            return row;
        });
    }

    /**
     * Initialise Tabulator on a single <table> element.
     */
    function initTable(table) {
        if (!table.querySelector('thead') || !table.querySelector('tbody')) {
            return; // skip non-standard tables
        }

        const thead = table.querySelector('thead');
        const tbody = table.querySelector('tbody');
        const columns = buildColumns(thead);
        const data = buildData(tbody, columns.length);

        // Preserve the containing element's width so the Tabulator div
        // fits the same space the original <table> occupied.
        const wrapper = table.parentElement;

        new Tabulator(table, {
            data: data,
            columns: columns,

            // Layout: columns stretch to fill container width
            layout: 'fitDataStretch',

            // Interactivity
            movableColumns: true,
            resizableColumns: true,
            resizableColumnFit: false,

            // No pagination by default; enable per-table via data-tabulator-pagination
            pagination: table.hasAttribute('data-tabulator-pagination') ? 'local' : false,
            paginationSize: parseInt(table.getAttribute('data-tabulator-page-size') || '10', 10),

            // No built-in row hover (we handle it in CSS)
            selectable: false,

            // No virtual DOM rendering needed for small tables
            renderHorizontal: 'basic',
            renderVertical: 'basic',

            // Hide Tabulator's default sort icon injection
            headerSortElement: function (column, dir) { return ''; },

            // After table renders, re-attach click handlers that existed on
            // buttons inside cells (Tabulator re-creates DOM, so delegated events
            // on .card / body still work, but we also re-dispatch any onclick attrs)
            rowFormatter: function (row) {
                // Re-enable onclick attributes on buttons inside cells
                const el = row.getElement();
                el.querySelectorAll('[onclick]').forEach(function (btn) {
                    // onclick attr is preserved via innerHTML — no extra work needed
                });
            },

            // Suppress Tabulator's "no data" placeholder
            placeholder: '',
        });
    }

    /**
     * Run after DOM is ready.
     */
    function run() {
        if (typeof Tabulator === 'undefined') {
            console.warn('[tabulator-init] Tabulator not loaded.');
            return;
        }

        document.querySelectorAll('table[data-tabulator]').forEach(function (table) {
            initTable(table);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', run);
    } else {
        run();
    }
})();
