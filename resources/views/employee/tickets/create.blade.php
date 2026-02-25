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

                <!-- Dynamic Fields based on category -->
                <div id="taskFields" class="category-fields" style="{{ old('category', 'task') == 'task' ? 'display: block;' : 'display: none;' }}">
                    <div class="form-group">
                        <label class="form-label">Select Task <span style="color: var(--red);">*</span></label>
                        <select name="task_id" class="form-control" id="taskSelect">
                            <option value="">Choose a task...</option>
                            @foreach($tasks ?? [] as $task)
                                <option value="{{ $task->id }}" {{ old('task_id') == $task->id ? 'selected' : '' }}>
                                    {{ $task->title }} ({{ $task->project->name ?? 'No Project' }})
                                </option>
                            @endforeach
                        </select>
                        @error('task_id')
                            <span style="color: var(--red); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div id="projectFields" class="category-fields" style="{{ old('category') == 'project' ? 'display: block;' : 'display: none;' }}">
                    <div class="form-group">
                        <label class="form-label">Select Project <span style="color: var(--red);">*</span></label>
                        <select name="project_id" class="form-control" id="projectSelect" onchange="loadProjectTasks(this.value)">
                            <option value="">Choose a project...</option>
                            @foreach($projects ?? [] as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <span style="color: var(--red); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" id="projectTaskGroup" style="{{ old('project_id') ? 'display: block;' : 'display: none;' }}">
                        <label class="form-label">Select Task (Optional)</label>
                        <select name="project_task_id" class="form-control" id="projectTaskSelect">
                            <option value="">Choose a task within this project...</option>
                            @if(old('project_id') && isset($projectTasks))
                                @foreach($projectTasks as $task)
                                    <option value="{{ $task->id }}" {{ old('project_task_id') == $task->id ? 'selected' : '' }}>
                                        {{ $task->title }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div id="otherFields" class="category-fields" style="{{ old('category') == 'other' ? 'display: block;' : 'display: none;' }}">
                    <!-- No additional fields for other category -->
                </div>

                <!-- Subject Field -->
                <div class="form-group">
                    <label class="form-label">Subject <span style="color: var(--red);">*</span></label>
                    <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" 
                           placeholder="Brief summary of your query" required>
                    @error('subject')
                        <span style="color: var(--red); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description Field -->
                <div class="form-group">
                    <label class="form-label">Description <span style="color: var(--red);">*</span></label>
                    <textarea name="description" class="form-control" rows="6" 
                               placeholder="Please provide detailed information about your query..." required>{{ old('description') }}</textarea>
                    @error('description')
                        <span style="color: var(--red); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Priority Field -->
                <div class="form-group">
                    <label class="form-label">Priority</label>
                    <div class="priority-selector">
                        <label class="priority-option {{ old('priority', 'low') == 'low' ? 'active' : '' }}" onclick="selectPriority('low')">
                            <input type="radio" name="priority" value="low" {{ old('priority', 'low') == 'low' ? 'checked' : '' }}>
                            <div class="priority-dot low"></div>
                            Low
                        </label>
                        <label class="priority-option {{ old('priority') == 'medium' ? 'active' : '' }}" onclick="selectPriority('medium')">
                            <input type="radio" name="priority" value="medium" {{ old('priority') == 'medium' ? 'checked' : '' }}>
                            <div class="priority-dot medium"></div>
                            Medium
                        </label>
                        <label class="priority-option {{ old('priority') == 'high' ? 'active' : '' }}" onclick="selectPriority('high')">
                            <input type="radio" name="priority" value="high" {{ old('priority') == 'high' ? 'checked' : '' }}>
                            <div class="priority-dot high"></div>
                            High
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
                    <button type="submit" class="greeting-btn" style="flex: 1; height: 44px; justify-content: center;">
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
// Category selection
function selectCategory(category) {
    // Update hidden input
    document.getElementById('categoryInput').value = category;
    
    // Update UI
    document.querySelectorAll('.type-option').forEach(opt => {
        opt.classList.remove('active');
    });
    
    const activeOpt = document.querySelector(`.type-option[data-type="${category}"]`);
    if (activeOpt) activeOpt.classList.add('active');
    
    // Show/hide relevant fields
    document.querySelectorAll('.category-fields').forEach(field => {
        field.style.display = 'none';
    });
    
    if (category === 'task') {
        document.getElementById('taskFields').style.display = 'block';
    } else if (category === 'project') {
        document.getElementById('projectFields').style.display = 'block';
    } else if (category === 'other') {
        document.getElementById('otherFields').style.display = 'block';
    }
}

// Priority selection
function selectPriority(priority) {
    // Update UI
    document.querySelectorAll('.priority-option').forEach(opt => {
        opt.classList.remove('active');
        opt.querySelector('input').checked = false;
    });
    
    const activeOpt = event.currentTarget.closest('.priority-option');
    if (activeOpt) {
        activeOpt.classList.add('active');
        activeOpt.querySelector('input').checked = true;
    }
}

// Load project tasks via AJAX
function loadProjectTasks(projectId) {
    const taskGroup = document.getElementById('projectTaskGroup');
    const taskSelect = document.getElementById('projectTaskSelect');
    
    if (!projectId) {
        taskGroup.style.display = 'none';
        return;
    }
    
    // Show loading state
    taskSelect.innerHTML = '<option value="">Loading tasks...</option>';
    taskGroup.style.display = 'block';
    
    // Simulation
    setTimeout(() => {
        if (projectId == 1) {
            taskSelect.innerHTML = `
                <option value="">Choose a task...</option>
                <option value="101">Design Database Schema</option>
                <option value="102">Implement API Endpoints</option>
                <option value="103">Create Documentation</option>
            `;
        } else if (projectId == 2) {
            taskSelect.innerHTML = `
                <option value="">Choose a task...</option>
                <option value="201">Frontend Development</option>
                <option value="202">Testing & QA</option>
                <option value="203">Deployment Setup</option>
            `;
        } else {
            taskSelect.innerHTML = `
                <option value="">Choose a task...</option>
                <option value="301">Requirement Analysis</option>
                <option value="302">UI/UX Design</option>
            `;
        }
    }, 500);
}

// Form validation
document.getElementById('ticketForm').addEventListener('submit', function(e) {
    const category = document.getElementById('categoryInput').value;
    
    if (category === 'task') {
        const taskSelect = document.getElementById('taskSelect');
        if (!taskSelect.value) {
            e.preventDefault();
            alert('Please select a task');
            taskSelect.focus();
        }
    } else if (category === 'project') {
        const projectSelect = document.getElementById('projectSelect');
        if (!projectSelect.value) {
            e.preventDefault();
            alert('Please select a project');
            projectSelect.focus();
        }
    }
});

// File input enhancement
document.getElementById('fileInput')?.addEventListener('change', function(e) {
    const fileListDiv = document.getElementById('fileList');
    fileListDiv.innerHTML = '';
    
    if (this.files.length > 0) {
        Array.from(this.files).forEach(file => {
            const item = document.createElement('div');
            item.style.display = 'flex';
            item.style.alignItems = 'center';
            item.style.gap = '6px';
            item.innerHTML = `
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                    <polyline points="13 2 13 9 20 9"/>
                </svg>
                ${file.name} (${(file.size / 1024).toFixed(1)} KB)
            `;
            fileListDiv.appendChild(item);
        });
    }
});

// Initialize based on old values
document.addEventListener('DOMContentLoaded', function() {
    const oldCategory = '{{ old('category', 'task') }}';
    selectCategory(oldCategory);
    
    const oldProjectId = '{{ old('project_id') }}';
    if (oldProjectId) {
        loadProjectTasks(oldProjectId);
    }
});
</script>
@endpush
@endsection