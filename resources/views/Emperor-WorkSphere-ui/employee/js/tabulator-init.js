/**
 * tabulator-init.js — Employee Panel
 *
 * Auto-discovers every <table data-tabulator> on the page and
 * initialises Tabulator on it with:
 *   • Column sorting (click header)
 *   • Column resizing (drag column edge)
 *   • Column reordering (drag column header)
 *
 * ALL default Tabulator CSS is overridden by tabulator-custom.css.
 * HTML in cells is preserved via formatter:"html".
 *
 * Special handling:
 *   - .task-table: checkbox column (empty th) is non-sortable & narrow
 *   - .history-table: colspan rows (weekend/missing) are handled gracefully
 */

(function () {
    'use strict';

    /**
     * Build column definitions from <thead>.
     */
    function buildColumns(thead) {
        const ths = thead.querySelectorAll('th');
        return Array.from(ths).map(function (th, idx) {
            const title = th.textContent.trim();
            const field = 'col_' + idx;

            // Checkbox column (empty th) and Actions — not sortable
            const noSort = (title === '' || title.toLowerCase() === 'actions' || title.toLowerCase() === 'action');

            // Checkbox cell narrow width
            const isCheckbox = (title === '');

            const width = th.getAttribute('data-width');

            return {
                title: title,
                field: field,
                formatter: 'html',
                headerSort: !noSort,
                resizable: !isCheckbox,
                width: width ? parseInt(width, 10) : (isCheckbox ? 40 : undefined),
                widthGrow: (isCheckbox || width) ? 0 : 1,
                minWidth: isCheckbox ? 40 : 60,
                headerHozAlign: 'left',
                hozAlign: 'left',
            };
        });
    }

    /**
     * Build row data from <tbody>.
     * Colspan rows (e.g. "Office Closed" spanning cols 2-3) are normalised
     * by replicating the colspan cell's HTML across its spanned fields.
     */
    function buildData(tbody, colCount) {
        const rows = tbody.querySelectorAll('tr');
        return Array.from(rows).map(function (tr) {
            const cells = Array.from(tr.querySelectorAll('td'));
            const row = {};
            let cellIdx = 0;

            for (let col = 0; col < colCount; col++) {
                if (cells[cellIdx]) {
                    const span = parseInt(cells[cellIdx].getAttribute('colspan') || '1', 10);
                    const html = cells[cellIdx].innerHTML;
                    for (let s = 0; s < span; s++) {
                        if (col + s < colCount) {
                            row['col_' + (col + s)] = s === 0 ? html : ''; // only first spanned col shows content
                        }
                    }
                    col += span - 1; // advance col pointer past spanned columns
                    cellIdx++;
                } else {
                    row['col_' + col] = '';
                }
            }
            return row;
        });
    }

    /**
     * Initialise Tabulator on a single <table> element.
     */
    function initTable(table) {
        if (!table.querySelector('thead') || !table.querySelector('tbody')) {
            return;
        }

        const thead = table.querySelector('thead');
        const tbody = table.querySelector('tbody');
        const columns = buildColumns(thead);
        const data = buildData(tbody, columns.length);

        new Tabulator(table, {
            data: data,
            columns: columns,

            layout: 'fitDataStretch',

            movableColumns: true,
            resizableColumns: true,
            resizableColumnFit: false,

            pagination: table.hasAttribute('data-tabulator-pagination') ? 'local' : false,
            paginationSize: parseInt(table.getAttribute('data-tabulator-page-size') || '10', 10),

            selectable: false,
            renderHorizontal: 'basic',
            renderVertical: 'basic',

            headerSortElement: function () { return ''; },

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
