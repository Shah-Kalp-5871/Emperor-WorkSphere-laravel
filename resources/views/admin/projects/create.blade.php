@extends('layouts.admin.master')

@section('title', 'WorkSphere — New Project')

@section('content')
<div class="page active" id="page-new-project">
    <div class="section-header">
        <div>
            <div class="section-title">New Project</div>
            <div class="section-sub">Create a new workspace for your team.</div>
        </div>
        <div class="section-actions">
            <a href="{{ url('/admin/projects') }}" class="btn btn-ghost btn-sm">
                ✕ Cancel
            </a>
        </div>
    </div>

    <div class="card" style="max-width: 800px; margin: 0 auto; padding: 24px;">
        <form id="create-project-form" onsubmit="handleCreateProject(event)">
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">Project Name *</label>
                <input class="form-input" id="proj-name" required placeholder="e.g. Customer Portal v3" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-1); color: var(--text-1);">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">Description</label>
                <textarea class="form-input" id="proj-desc" rows="4" placeholder="Brief description…" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-1); color: var(--text-1); resize: vertical;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">Status</label>
                    <select class="form-select" id="proj-status" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-1); color: var(--text-1);">
                        <option value="planning">Planning</option>
                        <option value="active">Active</option>
                        <option value="on_hold">On Hold</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">Priority</label>
                    <select class="form-select" id="proj-priority" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-1); color: var(--text-1);">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">Add Members</label>
                <select class="form-select" id="project-members-select" multiple style="height: 150px; width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-1); color: var(--text-1);">
                    <!-- Options will be loaded via JS -->
                </select>
                <small style="color: var(--text-3); font-size: 11px; margin-top: 6px; display: block;">Hold Ctrl (Windows) or Cmd (Mac) to select multiple members.</small>
            </div>

            <div id="form-error" style="color: #ef4444; font-size: 13px; margin-bottom: 16px; display: none; padding: 12px; background: #ef444410; border-radius: 8px;"></div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; border-top: 1px solid var(--border); padding-top: 24px;">
                <a href="{{ url('/admin/projects') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary" id="create-proj-btn" style="padding-left: 24px; padding-right: 24px;">Create Project</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ── Load employees ─────────────────────────────────────────
    async function loadEmployees() {
        const select = document.getElementById('project-members-select');
        try {
            const res = await axios.get('/api/admin/employees?per_page=200');
            const employees = res.data.data?.data ?? res.data.data ?? [];
            employees.forEach(emp => {
                const opt = document.createElement('option');
                opt.value = emp.id;
                opt.textContent = `${emp.user?.name ?? 'Unknown'} (${emp.employee_code})`;
                select.appendChild(opt);
            });
        } catch (e) {
            console.error('Failed to load employees:', e);
        }
    }

    // ── CREATE ─────────────────────────────────────────────────────────────
    async function handleCreateProject(e) {
        e.preventDefault();
        const btn = document.getElementById('create-proj-btn');
        const errorDiv = document.getElementById('form-error');
        errorDiv.style.display = 'none';
        btn.disabled = true;
        btn.textContent = 'Creating…';

        const employee_ids = Array.from(
            document.getElementById('project-members-select').selectedOptions
        ).map(o => parseInt(o.value));

        try {
            await axios.post('/api/admin/projects', {
                name:         document.getElementById('proj-name').value,
                description:  document.getElementById('proj-desc').value,
                status:       document.getElementById('proj-status').value,
                priority:     document.getElementById('proj-priority').value,
                employee_ids,
            });

            // Redirect back to projects index
            window.location.href = '/admin/projects';
        } catch (err) {
            const msg = err.response?.data?.message
                ?? err.response?.data?.errors
                ?? 'Failed to create project.';
            errorDiv.innerText = typeof msg === 'object' ? Object.values(msg).flat().join(' ') : msg;
            errorDiv.style.display = 'block';
        } finally {
            btn.disabled = false;
            btn.textContent = 'Create Project';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadEmployees();
    });
</script>
@endpush
@endsection
