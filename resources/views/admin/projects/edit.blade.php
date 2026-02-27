@extends('layouts.admin.master')

@section('title', 'WorkSphere — Edit Project')

@section('content')
<div class="page active" id="page-edit-project">
    <div class="section-header">
        <div>
            <div class="section-title">Edit Project</div>
            <div class="section-sub">Update project details and settings.</div>
        </div>
        <div class="section-actions">
            <a href="{{ url('/admin/projects') }}" class="btn btn-ghost btn-sm">
                ✕ Cancel
            </a>
        </div>
    </div>

    <div class="card" style="max-width: 800px; margin: 0 auto; padding: 24px;">
        <div id="loading-project" style="text-align:center;padding:40px;">
            <div class="spinner" style="border:4px solid rgba(255,255,255,0.1);border-top:4px solid var(--accent);border-radius:50%;width:30px;height:30px;animation:spin 1s linear infinite;margin:0 auto 10px;"></div>
            <div style="color:var(--text-2);font-size:14px;">Loading project details...</div>
        </div>

        <form id="edit-project-form" onsubmit="handleEditProject(event)" style="display: none;">
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">Project Name *</label>
                <input class="form-input" id="edit-proj-name" required placeholder="e.g. Customer Portal v3" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-1); color: var(--text-1);">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">Description</label>
                <textarea class="form-input" id="edit-proj-desc" rows="4" placeholder="Brief description…" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-1); color: var(--text-1); resize: vertical;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">Status</label>
                    <select class="form-select" id="edit-proj-status" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-1); color: var(--text-1);">
                        <option value="planning">Planning</option>
                        <option value="active">Active</option>
                        <option value="on_hold">On Hold</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 500;">Priority</label>
                    <select class="form-select" id="edit-proj-priority" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-1); color: var(--text-1);">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>

            <div id="form-error" style="color: #ef4444; font-size: 13px; margin-bottom: 16px; display: none; padding: 12px; background: #ef444410; border-radius: 8px;"></div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; border-top: 1px solid var(--border); padding-top: 24px;">
                <a href="{{ url('/admin/projects') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary" id="save-proj-btn" style="padding-left: 24px; padding-right: 24px;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

@push('scripts')
<script>
    const projectId = "{{ $projectId }}";

    async function loadProject() {
        const form = document.getElementById('edit-project-form');
        const loader = document.getElementById('loading-project');

        try {
            const res = await axios.get(`/api/admin/projects/${projectId}`);
            const p = res.data.data;
            
            document.getElementById('edit-proj-name').value      = p.name;
            document.getElementById('edit-proj-desc').value      = p.description ?? '';
            document.getElementById('edit-proj-status').value    = p.status;
            document.getElementById('edit-proj-priority').value  = p.priority ?? 'medium';

            loader.style.display = 'none';
            form.style.display = 'block';
        } catch (e) {
            console.error('Failed to load project:', e);
            alert('Failed to load project data. Returning to list.');
            window.location.href = '/admin/projects';
        }
    }

    async function handleEditProject(e) {
        e.preventDefault();
        const btn = document.getElementById('save-proj-btn');
        const errorDiv = document.getElementById('form-error');
        errorDiv.style.display = 'none';
        btn.disabled = true;
        btn.textContent = 'Saving…';

        try {
            await axios.put(`/api/admin/projects/${projectId}`, {
                name:        document.getElementById('edit-proj-name').value,
                description: document.getElementById('edit-proj-desc').value,
                status:      document.getElementById('edit-proj-status').value,
                priority:    document.getElementById('edit-proj-priority').value,
            });

            window.location.href = '/admin/projects';
        } catch (err) {
            const msg = err.response?.data?.message ?? 'Failed to update project.';
            errorDiv.innerText = msg;
            errorDiv.style.display = 'block';
        } finally {
            btn.disabled = false;
            btn.textContent = 'Save Changes';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadProject();
    });
</script>
@endpush
@endsection
