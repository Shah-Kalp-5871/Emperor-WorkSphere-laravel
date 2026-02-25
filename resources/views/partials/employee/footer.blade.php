<script>
function toggleCheck(el) {
  el.classList.toggle('done');
  const name = el.closest('.task-item').querySelector('.task-name');
  name.classList.toggle('striked');
}
function saveLog() {
  const btn = document.getElementById('log-btn');
  btn.textContent = 'Saving…'; btn.disabled = true;
  setTimeout(() => {
    btn.textContent = 'Save Work Log'; btn.disabled = false;
    document.getElementById('log-saved').style.display = 'flex';
    const s = document.getElementById('log-status');
    s.textContent = 'Submitted'; s.style.background = 'var(--accent-lt)';
    s.style.color = 'var(--accent)'; s.style.borderColor = 'var(--accent-lt)';
  }, 900);
}
function switchPanel() {
  window.location.href = '/admin/dashboard';
}
</script>
<script src="https://unpkg.com/tabulator-tables@6.3.0/dist/js/tabulator.min.js"></script>
@vite('resources/js/employee/tabulator-init.js')
