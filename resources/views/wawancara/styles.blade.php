<style>
:root {
    --bg: #f5f1ed;
    --card: #ffffff;
    --muted: #ebe5df;

    --ink: #2f2f2f;
    --ink-muted: #7a726b;

    --accent: #9f8b7d;
    --accent-dark: #8b786b;

    --warn: #d9a441;
    --bad: #d16b6b;
    --good: #7fa37f;

    --border: #ddd4cd;
}

* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
    scroll-padding-top: 16px;
}

body {
    margin: 0;
    background: var(--bg);
    font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu,
        "Helvetica Neue", Arial, sans-serif;
    color: var(--ink);
    line-height: 1.5;
}

/* ======================
   LAYOUT
====================== */

.wrap {
    max-width: 1100px;
    margin: 16px auto;
    padding: 0 16px;
}

.card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 18px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, .05);
}

header {
    display: flex;
    gap: 16px;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    padding: 18px 20px;
    border-bottom: 1px solid var(--border);
}

.title {
    display: flex;
    gap: 12px;
    align-items: center;
}

.badge {
    font-size: 12px;
    background: #ede5df;
    color: #6e5f53;
    padding: 4px 10px;
    border-radius: 999px;
    font-weight: 700;
}

.grid {
    display: grid;
    gap: 14px;
}

.g2 {
    grid-template-columns: repeat(2, 1fr);
}

.g3 {
    grid-template-columns: repeat(3, 1fr);
}

.box {
    background: #faf8f6;
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 14px;
}

/* ======================
   FORM
====================== */

label {
    font-size: 13px;
    font-weight: 600;
    color: var(--ink);
    margin-bottom: 6px;
    display: block;
}

input,
select,
textarea {
    width: 100%;
    background: white;
    border: 1px solid var(--border);
    color: var(--ink);
    padding: 10px 12px;
    border-radius: 10px;
    outline: none;
    font-size: 15px;
    transition: .2s ease;
}

textarea {
    min-height: 88px;
}

input:focus,
select:focus,
textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(159, 139, 125, .15);
}

.btn:focus-visible {
    outline: none;
}

/* ======================
   BUTTON
====================== */

.row {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.btn {
    appearance: none;
    border: 1px solid var(--border);
    background: white;
    color: var(--ink);
    padding: 10px 14px;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    transition: .2s;
}

.btn:hover {
    transform: translateY(-1px);
}

.btn.primary {
    background: linear-gradient(
        135deg,
        #b6a497,
        #8f7c6f
    );
    border: none;
    color: white;
}

.btn.ghost {
    background: transparent;
}

.btn.warn {
    background: linear-gradient(
        135deg,
        #e6bb67,
        #d38a4a
    );
    border: none;
    color: white;
}

.btn.slim {
    padding: 8px 10px;
    font-size: 12px;
    border-radius: 8px;
}

/* ======================
   TABLE
====================== */

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 12px 10px;
    text-align: left;
    border-bottom: 1px solid #ece6e0;
}

.table th {
    background: #f8f5f2;
    font-weight: 600;
    color: var(--ink);
}

.table tr:hover {
    background: #faf8f6;
}

/* ======================
   KPI
====================== */

.kpi {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
}

.kpi .pill {
    padding: 10px 12px;
    border-radius: 999px;
    background: #f6f3f0;
    border: 1px solid var(--border);
    font-weight: 700;
}

.pill.good {
    background: #dff0df;
    color: #3d6b3d;
}

.pill.warn {
    background: #f9edcf;
    color: #9a6a00;
}

.pill.bad {
    background: #f5dddd;
    color: #a23f3f;
}

/* ======================
   TYPOGRAPHY
====================== */

.section-title {
    font-weight: 700;
    font-size: 16px;
    margin-bottom: 10px;
}

.muted {
    color: var(--ink-muted);
}

/* ======================
   FOOTER
====================== */

footer {
    padding: 16px;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

/* ======================
   PRINT AREA
====================== */

#printArea {
    background: white;
    color: #111;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
}

#printArea .table th,
#printArea .table td {
    border-bottom: 1px solid #eee;
}

#printArea .muted {
    color: #555;
}

#printArea hr {
    border: none;
    border-top: 1px solid #ddd;
}

#printArea #p_catatan {
    white-space: pre-wrap;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 10px;
    min-height: 70px;
}

/* ======================
   NOTIFICATION
====================== */

#notification {
    position: fixed;
    bottom: -100px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--accent);
    color: white;
    padding: 12px 20px;
    border-radius: 999px;
    font-weight: 600;
    box-shadow: 0 4px 20px rgba(0,0,0,.2);
    transition: .4s;
    z-index: 100;
}

#notification.show {
    bottom: 20px;
}

#notification.bad {
    background: var(--bad);
}

/* ======================
   MODAL
====================== */

dialog {
    background: white;
    border: 1px solid var(--border);
    border-radius: 16px;
    color: var(--ink);
    width: 90%;
    max-width: 600px;
    padding: 24px;
}

dialog::backdrop {
    background: rgba(0,0,0,.45);
    backdrop-filter: blur(3px);
}

dialog .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    font-size: 18px;
    font-weight: 700;
}

dialog h4 {
    margin-top: 16px;
    margin-bottom: 8px;
    color: var(--accent-dark);
    border-bottom: 1px solid var(--border);
    padding-bottom: 4px;
}

dialog ul {
    padding-left: 20px;
}

dialog li {
    margin-bottom: 8px;
}

/* ======================
   PAGINATION
====================== */

.pagination-wrap svg {
    width: 14px !important;
    height: 14px !important;
}

.pagination-wrap nav span,
.pagination-wrap nav a {
    font-size: 12px !important;
}

.pagination-wrap nav {
    transform: scale(.95);
}

/* ======================
   RESPONSIVE
====================== */

@media (max-width: 768px) {
    .g2 {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {

    .g3 {
        grid-template-columns: 1fr;
    }

    header {
        flex-direction: column;
        align-items: flex-start;
    }

    .wrap {
        margin: 0;
        padding: 0;
    }

    .card {
        border-radius: 0;
        border-left: 0;
        border-right: 0;
    }

    .g2 {
        padding: 12px;
    }
}

/* ======================
   PRINT
====================== */

@media print {

    body {
        background: white;
        color: black;
    }

    #app,
    footer,
    #modalHelp,
    #modalPanduan,
    #notification {
        display: none !important;
    }

    .wrap {
        margin: 0;
        padding: 0;
    }

    #printArea {
        display: block !important;
        border: none;
        box-shadow: none;
    }
}
</style>