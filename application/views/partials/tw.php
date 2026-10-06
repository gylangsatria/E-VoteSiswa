<script>
tailwind.config = {
    theme: {
        extend: {
            fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
            colors: {
                brand: {
                    50: '#effbf9', 100: '#d5f4ef', 200: '#aee9e0', 300: '#86ddd1',
                    400: '#5ecfc4', 500: '#3EA99F', 600: '#2d8a82', 700: '#256e68',
                    DEFAULT: '#3EA99F'
                }
            }
        }
    }
};
</script>
<script>
document.addEventListener('click', function (e) {
    var opener = e.target.closest('[data-open-dialog]');
    if (opener) {
        var target = document.getElementById(opener.dataset.openDialog);
        if (target) target.showModal();
    }
    var closer = e.target.closest('[data-close-dialog]');
    if (closer) {
        var dialog = closer.closest('dialog');
        if (dialog) dialog.close();
    }
    if (e.target.tagName === 'DIALOG') e.target.close();
    if (!e.target.closest('details')) {
        document.querySelectorAll('details[open]').forEach(function (d) { d.open = false; });
    }
});
</script>
<style type="text/tailwindcss">
@layer components {
    .btn { @apply inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold shadow-sm transition focus:outline-none focus:ring-2 focus:ring-brand-500/30 disabled:cursor-not-allowed disabled:opacity-60; }
    .btn-primary { @apply bg-brand-500 text-white hover:bg-brand-600; }
    .btn-info { @apply bg-sky-600 text-white hover:bg-sky-700; }
    .btn-danger { @apply bg-red-600 text-white hover:bg-red-700; }
    .btn-warning { @apply bg-amber-500 text-white hover:bg-amber-600; }
    .btn-success { @apply bg-emerald-600 text-white hover:bg-emerald-700; }
    .btn-default { @apply border border-slate-300 bg-white text-slate-700 hover:bg-slate-50; }
    .btn-sm { @apply px-3 py-1.5 text-xs; }

    .card { @apply mb-5 rounded-xl border border-slate-200 bg-white shadow-sm; }
    .card-header { @apply flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4; }
    .card-header h2 { @apply text-base font-semibold text-slate-800; }
    .card-body { @apply p-5; }

    .input { @apply w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm transition placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20; }
    .label { @apply mb-1 block text-sm font-medium text-slate-600; }

    .badge { @apply inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold; }
    .badge-primary { @apply bg-brand-100 text-brand-700; }
    .badge-warning { @apply bg-amber-100 text-amber-700; }

    .alert { @apply mb-4 flex items-start gap-3 rounded-lg border px-4 py-3 text-sm; }
    .alert-success { @apply border-emerald-200 bg-emerald-50 text-emerald-700; }
    .alert-danger { @apply border-red-200 bg-red-50 text-red-700; }
    .alert-warning { @apply border-amber-200 bg-amber-50 text-amber-800; }
    .alert-info { @apply border-sky-200 bg-sky-50 text-sky-700; }

    .table-wrap { @apply overflow-x-auto rounded-lg border border-slate-200; }
    .table { @apply w-full min-w-[560px] border-collapse text-left text-sm; }
    .table thead th { @apply border-b border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-semibold uppercase tracking-wide text-slate-500; }
    .table tbody td { @apply border-b border-slate-100 px-3 py-2.5 align-middle text-slate-700; }
    .table tbody tr:last-child td { @apply border-b-0; }
    .table tbody tr:hover { @apply bg-slate-50; }
}
</style>
