@extends('layouts.employee.master')

@section('title', 'Raise New Ticket — WorkSphere')

@section('content')
<div style="max-width:680px; margin:0 auto; animation: fadeUp 0.4s ease-out;">

    {{-- Back --}}
    <a href="{{ route('employee.tickets.index') }}" style="display:inline-flex;align-items:center;gap:6px;color:var(--text-3);text-decoration:none;font-size:13px;margin-bottom:20px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Tickets
    </a>

    {{-- Form Panel --}}
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;">
        <div style="padding:24px 28px;border-bottom:1px solid var(--border);">
            <h1 style="font-family:'Instrument Serif',serif;font-size:26px;font-weight:400;margin-bottom:4px;">Raise a Support Ticket</h1>
            <p style="color:var(--text-3);font-size:13px;">Our team typically responds within 24 hours.</p>
        </div>

        <div style="padding:28px;">
            <div id="form-error" style="display:none;background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#b91c1c;"></div>
            <div id="form-success" style="display:none;background:#f0fdf4;border:1px solid #86efac;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#15803d;"></div>

            {{-- Category --}}
            <div style="margin-bottom:22px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:10px;">Query Type <span style="color:var(--red);">*</span></label>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                    <div class="cat-option active" data-cat="task" onclick="selectCat('task')" style="border:2px solid var(--accent);border-radius:8px;padding:14px 10px;text-align:center;cursor:pointer;transition:all .15s;background:var(--accent-lt);">
                        <div style="font-weight:600;font-size:13px;color:var(--accent);">Task Related</div>
                        <div style="font-size:11px;color:var(--text-3);margin-top:2px;">Query about a task</div>
                    </div>
                    <div class="cat-option" data-cat="project" onclick="selectCat('project')" style="border:2px solid var(--border);border-radius:8px;padding:14px 10px;text-align:center;cursor:pointer;transition:all .15s;">
                        <div style="font-weight:600;font-size:13px;">Project Related</div>
                        <div style="font-size:11px;color:var(--text-3);margin-top:2px;">Query about a project</div>
                    </div>
                    <div class="cat-option" data-cat="other" onclick="selectCat('other')" style="border:2px solid var(--border);border-radius:8px;padding:14px 10px;text-align:center;cursor:pointer;transition:all .15s;">
                        <div style="font-weight:600;font-size:13px;">Other</div>
                        <div style="font-size:11px;color:var(--text-3);margin-top:2px;">General query</div>
                    </div>
                </div>
                <input type="hidden" id="category" value="task">
            </div>

            {{-- Subject --}}
            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Subject <span style="color:var(--red);">*</span></label>
                <input type="text" id="subject" placeholder="Brief summary of your issue" style="width:100%;border:1px solid var(--border);border-radius:8px;padding:10px 14px;font-size:13px;font-family:inherit;background:var(--bg-1);outline:none;transition:border-color .15s;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
            </div>

            {{-- Description --}}
            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:6px;">Description <span style="color:var(--red);">*</span></label>
                <textarea id="description" rows="5" placeholder="Describe your issue in detail..." style="width:100%;border:1px solid var(--border);border-radius:8px;padding:10px 14px;font-size:13px;font-family:inherit;background:var(--bg-1);outline:none;resize:vertical;transition:border-color .15s;" onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'"></textarea>
            </div>

            {{-- Priority --}}
            <div style="margin-bottom:28px;">
                <label style="display:block;font-size:13px;font-weight:600;margin-bottom:10px;">Priority</label>
                <div style="display:flex;gap:10px;">
                    <label class="prio-opt active" data-prio="low" onclick="selectPrio('low')" style="display:flex;align-items:center;gap:6px;padding:8px 14px;border:1px solid var(--accent);border-radius:6px;cursor:pointer;font-size:13px;background:var(--accent-lt);color:var(--accent);font-weight:500;">
                        <input type="radio" name="priority" value="low" checked style="display:none;"> 🟢 Low
                    </label>
                    <label class="prio-opt" data-prio="medium" onclick="selectPrio('medium')" style="display:flex;align-items:center;gap:6px;padding:8px 14px;border:1px solid var(--border);border-radius:6px;cursor:pointer;font-size:13px;">
                        <input type="radio" name="priority" value="medium" style="display:none;"> 🟡 Medium
                    </label>
                    <label class="prio-opt" data-prio="high" onclick="selectPrio('high')" style="display:flex;align-items:center;gap:6px;padding:8px 14px;border:1px solid var(--border);border-radius:6px;cursor:pointer;font-size:13px;">
                        <input type="radio" name="priority" value="high" style="display:none;"> 🔴 High
                    </label>
                </div>
                <input type="hidden" id="priority" value="low">
            </div>

            {{-- Submit --}}
            <div style="display:flex;gap:10px;">
                <button id="submit-btn" onclick="submitTicket()" class="greeting-btn" style="flex:1;justify-content:center;height:44px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polyline points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Submit Ticket
                </button>
                <a href="{{ route('employee.tickets.index') }}" style="flex:1;display:flex;align-items:center;justify-content:center;height:44px;border:1px solid var(--border);border-radius:var(--radius-sm);font-size:13px;color:var(--text-2);text-decoration:none;background:var(--surface-2);">Cancel</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function selectCat(cat) {
    document.getElementById('category').value = cat;
    document.querySelectorAll('.cat-option').forEach(el => {
        const isActive = el.dataset.cat === cat;
        el.style.border = isActive ? '2px solid var(--accent)' : '2px solid var(--border)';
        el.style.background = isActive ? 'var(--accent-lt)' : '';
        el.querySelector('div').style.color = isActive ? 'var(--accent)' : '';
    });
}

function selectPrio(prio) {
    document.getElementById('priority').value = prio;
    document.querySelectorAll('.prio-opt').forEach(el => {
        const isActive = el.dataset.prio === prio;
        el.style.border = isActive ? '1px solid var(--accent)' : '1px solid var(--border)';
        el.style.background = isActive ? 'var(--accent-lt)' : '';
        el.style.color = isActive ? 'var(--accent)' : '';
        el.style.fontWeight = isActive ? '600' : '400';
    });
}

async function submitTicket() {
    const btn     = document.getElementById('submit-btn');
    const errBox  = document.getElementById('form-error');
    const okBox   = document.getElementById('form-success');
    const subject = document.getElementById('subject').value.trim();
    const desc    = document.getElementById('description').value.trim();
    const cat     = document.getElementById('category').value;
    const prio    = document.getElementById('priority').value;

    errBox.style.display = 'none';
    okBox.style.display  = 'none';

    if (!subject) { errBox.textContent = 'Please enter a subject.'; errBox.style.display = 'block'; return; }
    if (!desc)    { errBox.textContent = 'Please enter a description.'; errBox.style.display = 'block'; return; }

    btn.disabled = true;
    btn.textContent = 'Submitting…';

    try {
        const r = await axios.post(window.APP_URL + '/api/employee/tickets', {
            category:    cat,
            subject:     subject,
            description: desc,
            priority:    prio,
        });

        if (r.data.success) {
            okBox.textContent = '✓ Ticket raised successfully! Redirecting…';
            okBox.style.display = 'block';
            setTimeout(() => window.location.href = '{{ route("employee.tickets.index") }}', 1500);
        } else {
            throw new Error(r.data.message || 'Failed to create ticket.');
        }
    } catch(e) {
        const msg = e.response?.data?.message || e.response?.data?.errors
            ? JSON.stringify(e.response.data.errors)
            : e.message;
        errBox.textContent = 'Error: ' + msg;
        errBox.style.display = 'block';
        btn.disabled = false;
        btn.textContent = 'Submit Ticket';
    }
}
</script>
@endpush
@endsection