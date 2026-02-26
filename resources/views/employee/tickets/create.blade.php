@extends('layouts.employee.master')

@section('title', 'Raise New Ticket — WorkSphere')

@section('content')
<div style="max-width: 700px; margin: 0 auto; animation: fadeUp 0.4s ease-out;">
    <!-- Back Button -->
    <div style="margin-bottom: 24px;">
        <a href="{{ route('employee.tickets.index') }}" style="display: inline-flex; align-items: center; gap: 6px; color: var(--text-3); text-decoration: none; font-size: 13px; font-weight: 500; transition: color 0.2s;" 
           onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-3)'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            Back to Tickets
        </a>
    </div>

    <!-- Main Form Panel -->
    <div class="ticket-form-panel">
        <!-- Header -->
        <div class="ticket-form-header">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: var(--accent-lt); display: flex; align-items: center; justify-content: center; color: var(--accent);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 16v-4"/>
                        <path d="M12 8h.01"/>
                    </svg>
                </div>
                <h1 style="font-family: 'Instrument Serif', serif; font-size: 28px; font-weight: 400;">Raise a Query</h1>
            </div>
            <p style="color: var(--text-3); font-size: 13.5px; margin-left: 52px;">Select the type of query and provide details. Our team will respond within 24 hours.</p>
        </div>
        
        <!-- Form Body -->
        <div class="ticket-form-body">
            <form action="#" method="POST" id="ticketForm">
                @csrf
                
                <!-- Category Selection with Icons -->
                <label class="form-label">Query Type <span style="color: var(--red);">*</span></label>
                <div class="type-selector" style="margin-bottom: 30px;">
                    <div class="type-option {{ old('category', 'task') == 'task' ? 'active' : '' }}" data-type="task" onclick="selectCategory('task')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom: 8px;">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                        <div style="font-weight: 500; font-size: 14px; margin-bottom: 2px;">Task Related</div>
                        <div style="font-size: 11px; color: var(--text-3);">Query about specific task</div>
                    </div>
                    <div class="type-option {{ old('category') == 'project' ? 'active' : '' }}" data-type="project" onclick="selectCategory('project')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom: 8px;">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h5z"/>
                        </svg>
                        <div style="font-weight: 500; font-size: 14px; margin-bottom: 2px;">Project Related</div>
                        <div style="font-size: 11px; color: var(--text-3);">Query about project</div>
                    </div>
                    <div class="type-option {{ old('category') == 'other' ? 'active' : '' }}" data-type="other" onclick="selectCategory('other')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom: 8px;">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 16v-4"/>
                            <path d="M12 8h.01"/>
                        </svg>
                        <div style="font-weight: 500; font-size: 14px; margin-bottom: 2px;">Other</div>
                        <div style="font-size: 11px; color: var(--text-3);">General queries</div>
                    </div>
                </div>

                <input type="hidden" name="category" id="categoryInput" value="{{ old('category', 'task') }}">

                <div id="taskFields" class="category-fields" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">Select Task <span style="color: var(--red);">*</span></label>
                        <select name="task_id" class="form-control" id="taskSelect">
                            <option value="">Choose a task...</option>
                        </select>
                    </div>
                </div>

                <div id="projectFields" class="category-fields" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">Select Project <span style="color: var(--red);">*</span></label>
                        <select name="project_id" class="form-control" id="projectSelect">
                            <option value="">Choose a project...</option>
                        </select>
                    </div>
                </div>

                <div id="otherFields" class="category-fields" style="display: none;"></div>

                <!-- Subject Field -->
                <div class="form-group">
                    <label class="form-label">Subject <span style="color: var(--red);">*</span></label>
                    <input type="text" name="subject" id="subject" class="form-control" placeholder="Brief summary of your query" required>
                </div>

                <!-- Description Field -->
                <div class="form-group">
                    <label class="form-label">Description <span style="color: var(--red);">*</span></label>
                    <textarea name="description" id="description" class="form-control" rows="6" placeholder="Please provide detailed information about your query..." required></textarea>
                </div>

                <!-- Priority Field -->
                <div class="form-group">
                    <label class="form-label">Priority</label>
                    <div class="priority-selector">
                        <label class="priority-option active" data-priority="low" onclick="selectPriority('low')">
                            <input type="radio" name="priority" value="low" checked style="display:none">
                            <div class="priority-dot low"></div> Low
                        </label>
                        <label class="priority-option" data-priority="medium" onclick="selectPriority('medium')">
                            <input type="radio" name="priority" value="medium" style="display:none">
                            <div class="priority-dot medium"></div> Medium
                        </label>
                        <label class="priority-option" data-priority="high" onclick="selectPriority('high')">
                            <input type="radio" name="priority" value="high" style="display:none">
                            <div class="priority-dot high"></div> High
                        </label>
                    </div>
                </div>

                <!-- Attachments -->
                <div class="form-group">
                    <label class="form-label">Attachments (Optional)</label>
                    <div class="file-upload-container" onclick="document.getElementById('fileInput').click()">
                        <input type="file" name="attachments[]" multiple style="display: none;" id="fileInput">
                        <div style="color: var(--accent); display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <span style="font-weight: 500;">Choose Files or Drag & Drop</span>
                        </div>
                        <div style="font-size: 12px; color: var(--text-3); margin-top: 8px;">Max 10MB per file</div>
                        <div id="fileList" class="file-list"></div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div style="display: flex; gap: 15px; margin-top: 35px;">
                    <button type="submit" class="greeting-btn" id="submitBtn" style="flex: 1; height: 44px; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polyline points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                        Submit Ticket
                    </button>
                    <button type="button" class="greeting-btn" style="background: var(--surface-2); color: var(--text-2); flex: 1; height: 44px; justify-content: center; border: 1px solid var(--border);" 
                            onclick="window.location.href='{{ route('employee.tickets.index') }}'">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Help Section -->
    <div style="margin-top: 20px; padding: 16px; background: var(--surface-2); border-radius: var(--radius); border: 1px solid var(--border);">
        <div style="display: flex; gap: 12px; align-items: flex-start;">
            <div style="color: var(--accent);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4"/>
                    <path d="M12 8h.01"/>
                </svg>
            </div>
            <div>
                <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 4px;">Need help?</h4>
                <p style="font-size: 12px; color: var(--text-3);">Our support team typically responds within 24 hours. For urgent issues, please contact your project manager directly.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let formData = { projects: [], tasks: [] };

    // Fetch dependencies
    async function loadFormData() {
        try {
            const res = await axios.get('/api/employee/tickets/form-data');
            formData = res.data.data;
            
            const pSelect = document.getElementById('projectSelect');
            formData.projects.forEach(p => {
                pSelect.innerHTML += `<option value="${p.id}">${p.name}</option>`;
            });

            const tSelect = document.getElementById('taskSelect');
            formData.tasks.forEach(t => {
                tSelect.innerHTML += `<option value="${t.id}">${t.title}</option>`;
            });

            // Initial state
            selectCategory('task');
        } catch (err) { console.error('Form Data Error:', err); }
    }

    // Category selection
    function selectCategory(category) {
        document.getElementById('categoryInput').value = category;
        document.querySelectorAll('.type-option').forEach(opt => opt.classList.remove('active'));
        const activeOpt = document.querySelector(`.type-option[data-type="${category}"]`);
        if (activeOpt) activeOpt.classList.add('active');
        
        document.querySelectorAll('.category-fields').forEach(field => field.style.display = 'none');
        const fieldId = category === 'task' ? 'taskFields' : (category === 'project' ? 'projectFields' : 'otherFields');
        document.getElementById(fieldId).style.display = 'block';
    }

    // Priority selection
    function selectPriority(priority) {
        document.querySelectorAll('.priority-option').forEach(opt => {
            opt.classList.remove('active');
            opt.querySelector('input').checked = false;
        });
        const activeOpt = document.querySelector(`.priority-option[data-priority="${priority}"]`);
        if (activeOpt) {
            activeOpt.classList.add('active');
            activeOpt.querySelector('input').checked = true;
        }
    }

    // Submit form
    document.getElementById('ticketForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.textContent = 'Submitting...';

        const payload = {
            category: document.getElementById('categoryInput').value,
            subject: document.getElementById('subject').value,
            description: document.getElementById('description').value,
            priority: document.querySelector('input[name="priority"]:checked').value,
            task_id: document.getElementById('taskSelect').value || null,
            project_id: document.getElementById('projectSelect').value || null
        };

        try {
            await axios.post('/api/employee/tickets', payload);
            window.location.href = '{{ route('employee.tickets.index') }}';
        } catch (error) {
            alert('Failed to submit ticket: ' + (error.response?.data?.message || 'Check fields'));
            btn.disabled = false;
            btn.textContent = 'Submit Ticket';
        }
    });

    document.addEventListener('DOMContentLoaded', loadFormData);
</script>
@endpush
@endsection