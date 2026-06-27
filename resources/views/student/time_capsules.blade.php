@extends('layouts.app')
@section('content')

<!-- Confetti Blast Trigger for Accomplishments -->
@if(session('success_unlocked'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trigger multi-color confetti blasts
        var duration = 3 * 1000;
        var end = Date.now() + duration;

        (function frame() {
            confetti({
                particleCount: 5,
                angle: 60,
                spread: 55,
                origin: { x: 0 }
            });
            confetti({
                particleCount: 5,
                angle: 120,
                spread: 55,
                origin: { x: 1 }
            });

            if (Date.now() < end) {
                requestAnimationFrame(frame);
            }
        }());
    });
</script>
@endif

<div class="container py-4">
    <!-- Header Block -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #e11d48 0%, #be123c 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 4px 15px rgba(225, 29, 72, 0.35);">
                <i class="fas fa-hourglass-half fa-lg"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Future-Self Goal Capsules</h4>
                <p class="text-muted small mb-0">Lock your letters and target milestones. Complete them to earn 2.5x XP multipliers!</p>
            </div>
        </div>
        <!-- XP and Stats Deck -->
        <div class="d-flex align-items-center gap-3 bg-white p-2.5 rounded-pill shadow-sm border px-4">
            <div class="border-end pe-3 text-center">
                <span class="x-small text-muted fw-bold d-block text-uppercase">Current XP</span>
                <strong class="text-primary fs-5"><i class="fas fa-fire text-warning me-1"></i> {{ $user->xp }} XP</strong>
            </div>
            <div class="border-end pe-3 text-center">
                <span class="x-small text-muted fw-bold d-block text-uppercase">Active Staked</span>
                <strong class="text-danger fs-5 font-monospace">{{ $totalStaked }} XP</strong>
            </div>
            <div class="text-center">
                <span class="x-small text-muted fw-bold d-block text-uppercase">Total Earned</span>
                <strong class="text-success fs-5 font-monospace">+{{ $totalEarned }} XP</strong>
            </div>
        </div>
    </div>

    <!-- Alert Notices -->
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 shadow-sm bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('success_unlocked'))
        <div class="alert alert-info border-0 rounded-4 shadow-lg bg-info text-white p-4 mb-4" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;">
            <div class="d-flex align-items-start gap-3">
                <div class="bg-white text-info rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; flex-shrink: 0;">
                    <i class="fas fa-trophy fs-4 text-warning"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1"><i class="fas fa-sparkles text-warning me-1"></i> Goal Realized & Staking Released!</h5>
                    <p class="mb-0 text-white-50">{{ session('success_unlocked')['message'] }}</p>
                    @if(session('success_unlocked')['xp_reward'] > 0)
                        <div class="mt-2.5"><span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold shadow-sm font-monospace" style="font-size:0.85rem;"><i class="fas fa-gift me-1"></i> Received: +{{ session('success_unlocked')['xp_reward'] }} XP Reward (2.5x Multiplier)</span></div>
                    @endif
                </div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-3 bg-danger text-white">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Deposit Goal Capsule Form -->
        <div class="col-md-5">
            <div class="glass-card p-4 border-0 shadow-sm bg-white h-100">
                <h5 class="fw-bold mb-3"><i class="fas fa-lock text-rose me-2" style="color: #e11d48;"></i> Forge New Goal Capsule</h5>
                <p class="small text-muted mb-4">Forge a goal contract. The system locks your secret message and reflections. Achieve the parameters to unlock your words and redeem staked XP.</p>
                
                <form action="/time-capsule/store" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Goal Title *</label>
                        <input type="text" name="title" class="form-control bg-light border-0" placeholder="e.g. Master React & Node.js" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Unlock Triggers *</label>
                        <select name="lock_type" id="lock_type_select" class="form-select bg-light border-0" onchange="toggleLockFields()" required>
                            <option value="date">📅 Calendar Lock (Unlock on a Future Date)</option>
                            <option value="level">👑 Level Lock (Target Student Level)</option>
                            <option value="xp">🔥 XP Milestone Lock (Accumulate Target XP)</option>
                            <option value="course">🎓 Course Completion Lock (100% Course Progress)</option>
                        </select>
                    </div>

                    <!-- Target Fields Container -->
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <!-- Date trigger field -->
                        <div id="div_trigger_date" class="trigger-fields">
                            <label class="form-label small fw-bold text-muted">Select Target Unlock Date *</label>
                            <input type="date" name="unlock_date" id="input_unlock_date" class="form-control bg-white border" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            <div class="form-text small text-muted">The capsule remains sealed until this calendar date.</div>
                        </div>

                        <!-- Level trigger field -->
                        <div id="div_trigger_level" class="trigger-fields" style="display:none;">
                            <label class="form-label small fw-bold text-muted">Enter Target Level *</label>
                            <input type="number" name="target_level" id="input_target_level" class="form-control bg-white border" min="{{ ($user->level ?? 1) + 1 }}" placeholder="e.g. 5">
                            <div class="form-text small text-muted">Unlocks when your profile level is &ge; this value. (Current: Level {{ $user->level }})</div>
                        </div>

                        <!-- XP trigger field -->
                        <div id="div_trigger_xp" class="trigger-fields" style="display:none;">
                            <label class="form-label small fw-bold text-muted">Enter Target XP *</label>
                            <input type="number" name="target_xp" id="input_target_xp" class="form-control bg-white border" min="{{ ($user->xp ?? 0) + 10 }}" placeholder="e.g. 2500">
                            <div class="form-text small text-muted">Unlocks when your XP is &ge; this value. (Current: {{ $user->xp }} XP)</div>
                        </div>

                        <!-- Course trigger field -->
                        <div id="div_trigger_course" class="trigger-fields" style="display:none;">
                            <label class="form-label small fw-bold text-muted">Select Target Course *</label>
                            <select name="target_course_id" id="input_target_course_id" class="form-select bg-white border">
                                <option value="">-- Select Enrolled Course --</option>
                                @foreach($courses as $c)
                                    <option value="{{ $c->id }}">{{ $c->title }}</option>
                                @endforeach
                            </select>
                            <div class="form-text small text-muted">Unlocks when this course progress hits 100%.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Letter to Future Self *</label>
                        <textarea name="secret_message" rows="3" class="form-control bg-light border-0" placeholder="Write advice, reflections, or dreams to your future self..." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted d-flex justify-content-between">
                            <span>XP Staking Pledge</span>
                            <span class="text-rose fw-bold" id="stake_multiplier_preview">Reward: +0 XP</span>
                        </label>
                        <input type="number" name="staked_xp" id="staked_xp_input" class="form-control bg-light border-0 font-monospace" min="0" max="{{ $user->xp }}" value="0" oninput="updateStakeMultiplier()" required>
                        <div class="form-text small text-muted">Staking XP is optional. Successful unlocks return **2.5x pledged XP** (e.g. Stake 100 XP &rarr; Receive 250 XP back). If deleted, staked XP is burned!</div>
                    </div>

                    <button type="submit" class="btn w-100 rounded-pill text-white fw-bold shadow-sm py-2" style="background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);"><i class="fas fa-lock me-2"></i> SEAL TICKET CONTRACT</button>
                </form>
            </div>
        </div>

        <!-- Locked Goal Vault Grid -->
        <div class="col-md-7">
            <div class="glass-card p-4 border-0 shadow-sm bg-white h-100 d-flex flex-column">
                <h5 class="fw-bold mb-4"><i class="fas fa-archive text-rose me-2" style="color: #e11d48;"></i> Staked Lock Vault</h5>
                
                @if($capsules->count() > 0)
                    <div class="row g-3 overflow-y-auto flex-grow-1" style="max-height: 480px;">
                        @foreach($capsules as $cap)
                        <div class="col-12">
                            <div class="p-3 border rounded-3 position-relative overflow-hidden" 
                                 style="background: #ffffff; border-left: 4px solid {{ $cap->status === 'unlocked' ? '#10b981' : '#e11d48' }} !important; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                                
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ $cap->title }}</h6>
                                        <span class="x-small text-muted"><i class="far fa-calendar-alt me-1"></i> Locked: {{ $cap->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div>
                                        @if($cap->status === 'unlocked')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-2.5 py-1 rounded-pill fw-bold"><i class="fas fa-unlock me-1"></i> Unlocked</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2.5 py-1 rounded-pill fw-bold"><i class="fas fa-lock me-1"></i> Sealed</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Trigger details badge -->
                                <div class="mb-3">
                                    @if($cap->lock_type === 'date')
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-0.5" style="font-size:0.75rem;"><i class="fas fa-clock me-1"></i> Unlock Date: {{ \Carbon\Carbon::parse($cap->unlock_date)->format('d M Y') }}</span>
                                    @elseif($cap->lock_type === 'level')
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-0.5" style="font-size:0.75rem;"><i class="fas fa-crown me-1"></i> Level Target: {{ $cap->target_level }}</span>
                                    @elseif($cap->lock_type === 'xp')
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-0.5" style="font-size:0.75rem;"><i class="fas fa-fire me-1"></i> XP Target: {{ $cap->target_xp }}</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-0.5" style="font-size:0.75rem;"><i class="fas fa-graduation-cap me-1"></i> Complete: {{ $cap->targetCourse->title ?? 'Course' }}</span>
                                    @endif

                                    @if($cap->staked_xp > 0)
                                        <span class="badge bg-warning bg-opacity-15 text-warning-dark border border-warning px-2 py-0.5 ms-1" style="font-size:0.75rem; color: #b45309;"><i class="fas fa-fire text-warning me-1"></i> Staked: {{ $cap->staked_xp }} XP</span>
                                    @endif
                                </div>

                                <!-- Message content or Blur preview -->
                                <div class="p-3 bg-light rounded-3 mb-3 position-relative">
                                    @if($cap->status === 'unlocked')
                                        <div class="small fw-bold text-success mb-1"><i class="fas fa-envelope-open text-success me-1"></i> Decrypted Letter to Self:</div>
                                        <p class="small text-secondary mb-0 font-italic">{{ $cap->secret_message }}</p>
                                    @else
                                        <div class="user-select-none filter-blur text-center py-2" style="filter: blur(5px); opacity: 0.4;">
                                            This message is cryptographically locked and encrypted under the goal contract parameters. Completing the target parameters releases the decryption keys.
                                        </div>
                                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                                            <i class="fas fa-shield-alt text-rose fs-4"></i>
                                            <div class="x-small fw-bold text-rose mt-1">ENCRYPTED</div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <form action="/time-capsule/{{ $cap->id }}" method="POST" onsubmit="return confirm('Are you sure you want to discard this capsule? @if($cap->status === 'locked' && $cap->staked_xp > 0) WARNING: Staked {{ $cap->staked_xp }} XP will be burned! @endif')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none px-0 font-semibold">
                                                <i class="far fa-trash-can me-1"></i> Discard
                                            </button>
                                        </form>
                                    </div>
                                    <div>
                                        @if($cap->status === 'locked')
                                            <form action="/time-capsule/{{ $cap->id }}/unlock" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-rose rounded-pill px-3 py-1.5 fw-bold text-white shadow-sm" style="background-color: #e11d48;">
                                                    <i class="fas fa-key me-1"></i> Verify Unlock
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-outline-success rounded-pill px-3 py-1.5 fw-bold" onclick="showAttestationSlip({{ json_encode($cap) }})">
                                                <i class="fas fa-award me-1"></i> Attestation Slip
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center opacity-50 py-5 my-auto">
                        <i class="fas fa-hourglass-empty fa-3x text-muted mb-3"></i>
                        <h6 class="fw-bold text-secondary">No Goal Capsules Found</h6>
                        <p class="small text-muted">Create a capsule on the left to lock goals for your future self.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Goal Attestation Modal (Printable) -->
<div class="modal fade" id="goalAttestationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: #ffffff;">
            <div style="height: 6px; background: linear-gradient(90deg, #e11d48 0%, #be123c 100%);"></div>
            <div class="modal-header border-0 bg-light p-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-rose bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; color: #be123c; background: #ffe4e6;">
                        <i class="fas fa-award fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Goal Attestation Certificate</h5>
                        <small class="text-muted fw-semibold">BAPS SVM Smart-Contract Goal Attestor</small>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4" id="attestationPrintArea">
                <div class="border rounded-4 p-4 position-relative" style="border: 2px solid #e2e8f0 !important; background: radial-gradient(circle at 100% 100%, #fafafa 0%, #ffffff 100%);">
                    <!-- Certificate background motif -->
                    <div class="position-absolute start-50 top-50 translate-middle opacity-5 pointer-events-none text-center" style="font-size: 8rem; color: #be123c; z-index: 1;">
                        <i class="fas fa-dharmachakra"></i>
                    </div>

                    <div class="position-relative text-center" style="z-index: 2;">
                        <h5 class="fw-bold text-uppercase tracking-wider text-rose" style="letter-spacing: 2px; color: #be123c;">Certificate of Goal Attainment</h5>
                        <div class="small text-muted mb-4">BAPS Swaminarayan Vidyamandir | Smart Learning Systems</div>
                        
                        <div class="my-4">
                            <p class="lead text-dark mb-2">This certifies that Student</p>
                            <h3 class="fw-bold text-primary mb-1">{{ $user->name }}</h3>
                            <p class="small text-muted mb-4 font-monospace">Enrollment No: {{ $user->enrollment_no }}</p>
                        </div>
                        
                        <div class="p-3 bg-light rounded-3 border mb-4 text-start mx-auto" style="max-width: 600px;">
                            <span class="x-small text-muted fw-bold d-block text-uppercase mb-1">Staked Goal Parameter Met:</span>
                            <h6 class="fw-bold text-dark" id="slip_goal_title">Goal Title</h6>
                            <p class="small text-secondary mb-0" id="slip_goal_desc">Unlock Condition achieved successfully.</p>
                        </div>

                        <div class="p-3 border border-rose rounded-3 bg-rose bg-opacity-5 text-start mx-auto mb-4" style="max-width: 600px; background: rgba(225,29,72,0.02);">
                            <span class="x-small text-rose fw-bold d-block text-uppercase mb-1"><i class="fas fa-envelope-open me-1"></i> Decrypted Future-Self Letter:</span>
                            <p class="small text-dark mb-0 font-italic" id="slip_goal_message">Letter content...</p>
                        </div>

                        <div class="row justify-content-center g-3 mb-4">
                            <div class="col-6 col-md-4">
                                <div class="p-2.5 bg-light rounded-3 border text-center">
                                    <span class="x-small text-muted d-block font-semibold">STAKED MULTIPLIER</span>
                                    <strong class="text-success font-monospace" id="slip_goal_staked">2.5x Reward</strong>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="p-2.5 bg-light rounded-3 border text-center">
                                    <span class="x-small text-muted d-block font-semibold">VERIFIED DATE</span>
                                    <strong class="text-dark" id="slip_goal_date">May 25, 2026</strong>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 border-top pt-4">
                            <div class="d-flex justify-content-around align-items-center">
                                <div>
                                    <div style="height: 45px; display: flex; align-items: center; justify-content: center;">
                                        {!! \App\Models\Staff::generateSignatureSvg('BAPS SVM Goal System') !!}
                                    </div>
                                    <div style="width: 120px; height: 1px; background-color: #cbd5e1; margin-top: 4px; margin-bottom: 2px;"></div>
                                    <span class="text-muted" style="font-size: 0.65rem;">System Attestor</span>
                                </div>
                                <div>
                                    <div style="height: 45px; display: flex; align-items: center; justify-content: center;">
                                        {!! \App\Models\Staff::generateSignatureSvg('Dr Ramesh Chandra Pandya') !!}
                                    </div>
                                    <div style="width: 120px; height: 1px; background-color: #cbd5e1; margin-top: 4px; margin-bottom: 2px;"></div>
                                    <span class="text-muted" style="font-size: 0.65rem;">Academic Dean Approval</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4 text-muted x-small" style="font-size: 0.65rem; word-break: break-all;">
                            Cryptographically attestation slip generated on the BAPS SVM Academic LMS. 
                            <br>
                            <strong>SHA256: <span id="slip_goal_hash">BAPS-SHA-GOAL-HASH</span></strong>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-rose rounded-pill px-4 text-white fw-bold shadow-sm" style="background-color: #e11d48;" onclick="printAttestationSlip()">
                    <i class="fas fa-print me-2"></i> Print Slip
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleLockFields() {
    const lockType = document.getElementById('lock_type_select').value;
    const fields = document.querySelectorAll('.trigger-fields');
    
    // Hide all
    fields.forEach(f => f.style.display = 'none');
    
    // Disable required flags
    document.getElementById('input_unlock_date').required = false;
    document.getElementById('input_target_level').required = false;
    document.getElementById('input_target_xp').required = false;
    document.getElementById('input_target_course_id').required = false;

    // Show correct field
    if (lockType === 'date') {
        document.getElementById('div_trigger_date').style.display = 'block';
        document.getElementById('input_unlock_date').required = true;
    } else if (lockType === 'level') {
        document.getElementById('div_trigger_level').style.display = 'block';
        document.getElementById('input_target_level').required = true;
    } else if (lockType === 'xp') {
        document.getElementById('div_trigger_xp').style.display = 'block';
        document.getElementById('input_target_xp').required = true;
    } else if (lockType === 'course') {
        document.getElementById('div_trigger_course').style.display = 'block';
        document.getElementById('input_target_course_id').required = true;
    }
}

function updateStakeMultiplier() {
    const stakeInput = document.getElementById('staked_xp_input');
    const preview = document.getElementById('stake_multiplier_preview');
    const val = parseInt(stakeInput.value) || 0;
    
    const reward = Math.floor(val * 2.5);
    preview.textContent = "Reward: +" + reward + " XP";
}

function showAttestationSlip(cap) {
    document.getElementById('slip_goal_title').textContent = cap.title;
    
    let conditionText = '';
    if (cap.lock_type === 'date') {
        conditionText = 'Goal locked until calendar date ' + new Date(cap.unlock_date).toLocaleDateString('en-IN') + ' completed.';
    } else if (cap.lock_type === 'level') {
        conditionText = 'Goal locked until profile level ' + cap.target_level + ' reached.';
    } else if (cap.lock_type === 'xp') {
        conditionText = 'Goal locked until profile XP score ' + cap.target_xp + ' accumulated.';
    } else {
        conditionText = 'Goal locked until target course finished with 100% progress.';
    }
    document.getElementById('slip_goal_desc').textContent = conditionText;
    document.getElementById('slip_goal_message').textContent = cap.secret_message;
    document.getElementById('slip_goal_staked').textContent = cap.staked_xp > 0 ? '+' + Math.floor(cap.staked_xp * 2.5) + ' XP Reward' : 'Goal Attained';
    
    document.getElementById('slip_goal_date').textContent = new Date(cap.updated_at).toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
    
    // Hash generator
    let str = "BAPS-GOAL-" + cap.id + "-" + cap.title + "-" + cap.user_id;
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        hash = (hash << 5) - hash + str.charCodeAt(i);
        hash |= 0;
    }
    let hashStr = 'BAPS-GOAL-' + Math.abs(hash).toString(16).toUpperCase() + Math.abs(hash * 37).toString(16).toUpperCase();
    document.getElementById('slip_goal_hash').textContent = hashStr;
    
    var modal = new bootstrap.Modal(document.getElementById('goalAttestationModal'));
    modal.show();
}

function printAttestationSlip() {
    var printContents = document.getElementById('attestationPrintArea').innerHTML;
    var style = document.createElement('style');
    style.innerHTML = `
        @media print {
            body * {
                visibility: hidden;
            }
            #attestationPrintArea, #attestationPrintArea * {
                visibility: visible;
            }
            #attestationPrintArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    `;
    document.head.appendChild(style);
    window.print();
    document.head.removeChild(style);
}

// Initialise
document.addEventListener('DOMContentLoaded', function() {
    toggleLockFields();
    updateStakeMultiplier();
});
</script>

<style>
.filter-blur {
    user-select: none;
}
.btn-rose {
    background: #e11d48;
    color: white;
}
.btn-rose:hover {
    background: #be123c;
    color: white;
}
</style>
@endsection
