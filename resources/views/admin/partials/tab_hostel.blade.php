<div class="tab-pane fade" id="tab-hostel" role="tabpanel">
    <style>
        .hostel-menu-grid .action-btn {
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            background: #ffffff;
            border: 1px solid var(--baps-border, #e2e8f0);
            cursor: pointer;
        }
        .hostel-menu-grid .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-color: var(--baps-saffron, #f97316);
        }
        .hostel-menu-grid .action-btn.active-hostel-tab {
            background: var(--baps-saffron, #f97316) !important;
            color: #ffffff !important;
            border-color: var(--baps-saffron, #f97316) !important;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.25);
        }
        .hostel-menu-grid .action-btn.active-hostel-tab i {
            color: #ffffff !important;
        }
        .workspace-card {
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid var(--baps-border, #e2e8f0);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
        }
        body.dark-mode .hostel-menu-grid .action-btn {
            background: #1e293b;
            border-color: #334155;
            color: #cbd5e1;
        }
        body.dark-mode .hostel-menu-grid .action-btn:hover {
            border-color: var(--baps-saffron, #f97316);
            color: var(--baps-saffron, #f97316);
        }
        body.dark-mode .hostel-menu-grid .action-btn.active-hostel-tab {
            background: var(--baps-saffron, #f97316) !important;
            color: #ffffff !important;
        }
        body.dark-mode .workspace-card {
            background: #1e293b;
            border-color: #334155;
        }
        .room-map-cell {
            padding: 1.2rem;
            border-radius: 12px;
            text-align: center;
            font-weight: bold;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .room-map-cell:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .room-available {
            background-color: #ecfdf5;
            color: #059669;
            border-color: #a7f3d0;
        }
        .room-full {
            background-color: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }
        body.dark-mode .room-available {
            background-color: rgba(16, 185, 129, 0.1);
            color: #34d399;
            border-color: rgba(16, 185, 129, 0.2);
        }
        body.dark-mode .room-full {
            background-color: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.2);
        }
        .star-rating {
            font-size: 1.25rem;
            color: #e2e8f0;
            cursor: pointer;
        }
        .star-rating .fa-star.active {
            color: #fbbf24;
        }
    </style>

    <!-- Main Hostel Header -->
    <div class="content-card mb-4" style="border-top: 4px solid var(--baps-saffron, #f97316);">
        <div class="content-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="content-card-title m-0">
                <i class="fas fa-hotel text-primary me-2"></i> Hostel Management Hub
            </h5>
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">Live Control Dashboard</span>
        </div>
        <div class="card-body p-4">
            <!-- 14 buttons (12 original + 2 new) -->
            <div class="row g-3 hostel-menu-grid mb-4">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <button class="action-btn shadow-sm" onclick="activateHostelTab('room-allocation', this)">
                        <i class="fas fa-door-open text-info me-2 fs-5"></i> Room Allocation
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <button class="action-btn shadow-sm" onclick="activateHostelTab('mess-menu', this)">
                        <i class="fas fa-utensils text-warning me-2 fs-5"></i> Mess Menu
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <button class="action-btn shadow-sm" onclick="activateHostelTab('night-attendance', this)">
                        <i class="fas fa-clipboard-check text-success me-2 fs-5"></i> Night Attendance
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <button class="action-btn shadow-sm" onclick="activateHostelTab('outpass-approvals', this)">
                        <i class="fas fa-ticket-alt text-danger me-2 fs-5"></i> Outpass Approvals
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <button class="action-btn shadow-sm" onclick="activateHostelTab('laundry-registry', this)">
                        <i class="fas fa-tshirt text-primary me-2 fs-5"></i> Laundry Registry
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <button class="action-btn shadow-sm" onclick="activateHostelTab('complaints', this)">
                        <i class="fas fa-exclamation-triangle text-danger me-2 fs-5"></i> Complaints
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <button class="action-btn shadow-sm" onclick="activateHostelTab('fee-payments', this)">
                        <i class="fas fa-rupee-sign text-success me-2 fs-5"></i> Fee Payments
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <button class="action-btn shadow-sm" onclick="activateHostelTab('inventory', this)">
                        <i class="fas fa-bed text-secondary me-2 fs-5"></i> Inventory
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <button class="action-btn shadow-sm" onclick="activateHostelTab('cleaning-schedule', this)">
                        <i class="fas fa-broom text-info me-2 fs-5"></i> Cleaning Schedule
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <button class="action-btn shadow-sm" onclick="activateHostelTab('visitor-log', this)">
                        <i class="fas fa-user-clock text-warning me-2 fs-5"></i> Visitor Log
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <button class="action-btn shadow-sm" onclick="activateHostelTab('disciplinary-log', this)">
                        <i class="fas fa-gavel text-danger me-2 fs-5"></i> Disciplinary Log
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <button class="action-btn shadow-sm" onclick="activateHostelTab('event-calendar', this)">
                        <i class="fas fa-calendar-alt text-primary me-2 fs-5"></i> Event Calendar
                    </button>
                </div>
                <!-- 2 NEW Options -->
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <button class="action-btn shadow-sm border border-warning" onclick="activateHostelTab('mess-feedback', this)">
                        <i class="fas fa-star text-warning me-2 fs-5"></i> Mess Feedback & Rating
                    </button>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <button class="action-btn shadow-sm border border-success" onclick="activateHostelTab('hostel-staff-roster', this)">
                        <i class="fas fa-users-cog text-success me-2 fs-5"></i> Hostel Staff Roster
                    </button>
                </div>
            </div>

            <!-- Workspace Panel (Holds the active tab dashboard) -->
            <div class="workspace-card p-4" id="hostel-workspace-panel" style="min-height: 400px;">
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-hotel fs-1 text-secondary opacity-50 mb-3"></i>
                    <h5>Select a Hostel Management module above to view and execute records.</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Room Detail View -->
    <div class="modal fade" id="hostelRoomDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title fw-bold" id="roomDetailsTitle">Room Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-users text-primary me-2"></i> Current Occupants</h6>
                    <ul class="list-group list-group-flush mb-4" id="roomOccupantsList"></ul>
                    
                    <h6 class="fw-bold mb-3"><i class="fas fa-user-plus text-success me-2"></i> Assign Student to Room</h6>
                    <form id="roomAssignForm" onsubmit="event.preventDefault(); submitRoomAssignment();">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Student Name</label>
                            <input type="text" class="form-control" id="assign_student_name" placeholder="e.g. Rahul Patel" required>
                        </div>
                        <input type="hidden" id="assign_room_id">
                        <button type="submit" class="btn btn-success w-100 py-2 rounded-pill shadow-sm fw-bold">
                            <i class="fas fa-plus me-1"></i> Add Occupant
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // State management controller for Hostel Hub
    const HostelHub = {
        data: {},
        activeTab: '',

        init: function() {
            // Load state from localStorage or seed defaults
            const stored = localStorage.getItem('hostel_hub_records');
            if (stored) {
                this.data = JSON.parse(stored);
            } else {
                this.seedDefaults();
            }
        },

        seedDefaults: function() {
            this.data = {
                rooms: [
                    { id: '101', block: 'Block A', floor: '1st Floor', capacity: 4, occupants: ['Vasant Patel', 'Smit Dave', 'Rahul Verma'] },
                    { id: '102', block: 'Block A', floor: '1st Floor', capacity: 4, occupants: ['Priya Sharma'] },
                    { id: '103', block: 'Block A', floor: '1st Floor', capacity: 4, occupants: ['Deepak Mehta', 'Karan Shah'] },
                    { id: '201', block: 'Block B', floor: '2nd Floor', capacity: 3, occupants: ['Sanjay Amin', 'Nirav Patel'] },
                    { id: '202', block: 'Block B', floor: '2nd Floor', capacity: 3, occupants: [] },
                    { id: '203', block: 'Block B', floor: '2nd Floor', capacity: 3, occupants: ['Gopal Vyas'] }
                ],
                messMenu: {
                    Monday: { breakfast: 'Idli Sambhar & Chutney', lunch: 'Roti, Gujarati Shaak, Dal Fry, Jeera Rice', tea: 'Masala Tea & Parle-G', dinner: 'Khichdi, Kadhi, Ringan Bharta' },
                    Tuesday: { breakfast: 'Poha & Jalebi', lunch: 'Puri, Chole Masala, Veg Pulao, Raita', tea: 'Lemon Tea & Digestives', dinner: 'Bhakri, Sev Tameta Shaak, Butter Milk' },
                    Wednesday: { breakfast: 'Aloo Paratha & Curd', lunch: 'Roti, Paneer Tikka Masala, Yellow Dal, Chawal', tea: 'Ginger Tea & Samosa', dinner: 'Vagharli Khichdi, Papad, Dahi' },
                    Thursday: { breakfast: 'Upma & Sheera', lunch: 'Roti, Mix Kathol, Kadhi, Steam Rice', tea: 'Coffee & Salted Biscuits', dinner: 'Rotla, Ringna no Oro, Jaggery' },
                    Friday: { breakfast: 'Methi Thepla & Pickle', lunch: 'Puri, Aloo Dum, Dal Fry, Peas Pulao', tea: 'Mint Tea & Kachori', dinner: 'Dal Dhokli & Rice Pudding' },
                    Saturday: { breakfast: 'Bread Butter & Jam', lunch: 'Roti, Bhindi Masala, Dal, Rice, Salad', tea: 'Hot Milk & Bornvita', dinner: 'Pav Bhaji & Pulav' },
                    Sunday: { breakfast: 'Chole Bhature', lunch: 'Special feast: Paneer, Shrikhand, Dal Bati', tea: 'Special High Tea', dinner: 'Kadhi Pulav & Sweet Sukhadi' }
                },
                attendance: [
                    { name: 'Vasant Patel', room: '101', status: 'Present' },
                    { name: 'Smit Dave', room: '101', status: 'Present' },
                    { name: 'Rahul Verma', room: '101', status: 'Late' },
                    { name: 'Priya Sharma', room: '102', status: 'Present' },
                    { name: 'Karan Shah', room: '103', status: 'Absent' },
                    { name: 'Gopal Vyas', room: '203', status: 'Present' }
                ],
                outpasses: [
                    { id: 'OUT-001', name: 'Vasant Patel', purpose: 'Family Wedding', from: '2026-06-15', to: '2026-06-18', status: 'Pending' },
                    { id: 'OUT-002', name: 'Smit Dave', purpose: 'Medical Checkup', from: '2026-06-14', to: '2026-06-14', status: 'Approved' },
                    { id: 'OUT-003', name: 'Rahul Verma', purpose: 'Weekend Home Visit', from: '2026-06-12', to: '2026-06-14', status: 'Completed' }
                ],
                laundry: [
                    { id: 'LND-701', name: 'Sanjay Amin', bag: 'Bag #B-45', items: 12, date: '2026-06-13', status: 'Collected' },
                    { id: 'LND-702', name: 'Nirav Patel', bag: 'Bag #B-12', items: 8, date: '2026-06-12', status: 'Ready' },
                    { id: 'LND-703', name: 'Gopal Vyas', bag: 'Bag #B-89', items: 15, date: '2026-06-14', status: 'Washing' }
                ],
                complaints: [
                    { id: 'CMP-501', name: 'Rahul Verma', room: '101', category: 'Electrical', desc: 'Ceiling fan regulator is broken and stuck at speed 5.', severity: 'Medium', status: 'Open' },
                    { id: 'CMP-502', name: 'Vasant Patel', room: '101', category: 'Plumbing', desc: 'Water leakage in bathroom washbasin tap.', severity: 'Low', status: 'In Progress' },
                    { id: 'CMP-503', name: 'Priya Sharma', room: '102', category: 'Internet', desc: 'Wi-Fi keeps disconnecting every few minutes.', severity: 'High', status: 'Resolved' }
                ],
                fees: [
                    { name: 'Vasant Patel', room: '101', amount: 45000, paid: 45000, status: 'Paid' },
                    { name: 'Smit Dave', room: '101', amount: 45000, paid: 30000, status: 'Partial' },
                    { name: 'Rahul Verma', room: '101', amount: 45000, paid: 0, status: 'Unpaid' },
                    { name: 'Priya Sharma', room: '102', amount: 45000, paid: 45000, status: 'Paid' }
                ],
                inventory: [
                    { code: 'INV-BED', name: 'Wooden Single Bed Frame', category: 'Furniture', total: 100, use: 92, damaged: 2 },
                    { code: 'INV-MAT', name: 'Foam Mattress (Single)', category: 'Bedding', total: 100, use: 92, damaged: 0 },
                    { code: 'INV-CHY', name: 'Ergonomic Plastic Study Chair', category: 'Furniture', total: 120, use: 110, damaged: 5 },
                    { code: 'INV-FAN', name: 'Ceiling Fan (High Speed)', category: 'Electrical', total: 50, use: 45, damaged: 1 }
                ],
                cleaning: [
                    { zone: 'Block A Floor 1 Common Washrooms', frequency: 'Daily', janitor: 'Ramesh Kumar', status: 'Completed', date: '2026-06-14 09:30' },
                    { zone: 'Block B Floor 2 Corridors', frequency: 'Daily', janitor: 'Suresh Patel', status: 'Pending', date: '2026-06-13 14:00' },
                    { zone: 'Common Mess Hall Eating Area', frequency: 'Twice Daily', janitor: 'Ramesh Kumar', status: 'Completed', date: '2026-06-14 13:30' }
                ],
                visitors: [
                    { id: 'VIS-901', name: 'Harish Patel', relation: 'Father', student: 'Vasant Patel', room: '101', in: '2026-06-14 15:30', out: '2026-06-14 17:00' },
                    { id: 'VIS-902', name: 'Mahendra Dave', relation: 'Uncle', student: 'Smit Dave', room: '101', in: '2026-06-14 18:15', out: null }
                ],
                discipline: [
                    { name: 'Rahul Verma', date: '2026-06-10', incident: 'Late entry past curfew (22:45 PM) without notice', severity: 'Minor', action: 'Written Warning', status: 'Active' },
                    { name: 'Karan Shah', date: '2026-06-08', incident: 'Playing loud music past midnight causing disturbance', severity: 'Minor', action: 'Verbal Warning', status: 'Resolved' },
                    { name: 'Sanjay Amin', date: '2026-06-05', incident: 'Allowed unauthorized guest to stay in room overnight', severity: 'Major', action: 'Fine of ₹ 1,000 imposed', status: 'Active' }
                ],
                events: [
                    { name: 'Hostellers Welcome Sabha & Dinner', date: '2026-06-20 18:30', venue: 'Hostel Prayer Hall & Lawn', coordinator: 'Sadhu Adbhutanand Das' },
                    { name: 'Monthly Cleanliness Drive & Seva', date: '2026-06-25 08:00', venue: 'All Blocks & Garden', coordinator: 'Sadhu Gyanprasad Das' }
                ],
                // 2 New Options Datasets
                feedback: [
                    { name: 'Vasant Patel', rating: 5, category: 'Taste', comment: 'Sunday Shrikhand and Paneer Tikka was absolute top tier!', date: '2026-06-14' },
                    { name: 'Smit Dave', rating: 3, category: 'Variety', comment: 'Monday dinner Kadhi-Khichdi is very repetitive. Suggest handvo instead.', date: '2026-06-13' },
                    { name: 'Priya Sharma', rating: 4, category: 'Hygiene', comment: 'Table cleaning standards inside the dining hall are very good.', date: '2026-06-12' }
                ],
                roster: [
                    { day: 'Monday', shift: 'Day Shift (8AM - 8PM)', staff: 'Ramesh Kumar', role: 'Head Cook & Mess Staff', assignedBy: 'Sadhu Adbhutanand Das', phone: '+91 98250 12345' },
                    { day: 'Monday', shift: 'Night Shift (8PM - 8AM)', staff: 'Kishore Singh', role: 'Gate Security Guard', assignedBy: 'Sadhu Gyanprasad Das', phone: '+91 98250 54321' },
                    { day: 'Tuesday', shift: 'Day Shift (8AM - 8PM)', staff: 'Suresh Patel', role: 'Janitor & Cleaner', assignedBy: 'Sadhu Gyanprasad Das', phone: '+91 98250 54321' },
                    { day: 'Tuesday', shift: 'Night Shift (8PM - 8AM)', staff: 'Kishore Singh', role: 'Gate Security Guard', assignedBy: 'Sadhu Adbhutanand Das', phone: '+91 98250 12345' },
                    { day: 'Wednesday', shift: 'Day Shift (8AM - 8PM)', staff: 'Ramesh Kumar', role: 'Head Cook & Mess Staff', assignedBy: 'Sadhu Adbhutanand Das', phone: '+91 98250 12345' },
                    { day: 'Wednesday', shift: 'Night Shift (8PM - 8AM)', staff: 'Mahesh Solanki', role: 'Assistant Janitor', assignedBy: 'Sadhu Gyanprasad Das', phone: '+91 98250 54321' }
                ]
            };
            this.save();
        },

        save: function() {
            localStorage.setItem('hostel_hub_records', JSON.stringify(this.data));
        },

        render: function(tabName) {
            this.activeTab = tabName;
            const container = document.getElementById('hostel-workspace-panel');
            
            switch (tabName) {
                case 'room-allocation':
                    this.renderRoomAllocation(container);
                    break;
                case 'mess-menu':
                    this.renderMessMenu(container);
                    break;
                case 'night-attendance':
                    this.renderNightAttendance(container);
                    break;
                case 'outpass-approvals':
                    this.renderOutpassApprovals(container);
                    break;
                case 'laundry-registry':
                    this.renderLaundryRegistry(container);
                    break;
                case 'complaints':
                    this.renderComplaints(container);
                    break;
                case 'fee-payments':
                    this.renderFeePayments(container);
                    break;
                case 'inventory':
                    this.renderInventory(container);
                    break;
                case 'cleaning-schedule':
                    this.renderCleaningSchedule(container);
                    break;
                case 'visitor-log':
                    this.renderVisitorLog(container);
                    break;
                case 'disciplinary-log':
                    this.renderDisciplinaryLog(container);
                    break;
                case 'event-calendar':
                    this.renderEventCalendar(container);
                    break;
                case 'mess-feedback':
                    this.renderMessFeedback(container);
                    break;
                case 'hostel-staff-roster':
                    this.renderHostelStaffRoster(container);
                    break;
            }
        },

        renderRoomAllocation: function(el) {
            let vacantCount = 0;
            let occupiedCount = 0;
            this.data.rooms.forEach(r => {
                if (r.occupants.length >= r.capacity) occupiedCount++;
                else vacantCount++;
            });

            let roomsHtml = '';
            this.data.rooms.forEach(r => {
                const isFull = r.occupants.length >= r.capacity;
                const statusClass = isFull ? 'room-full' : 'room-available';
                const occupantsText = r.occupants.length > 0 ? r.occupants.join(', ') : 'None';
                roomsHtml += `
                    <div class="col-6 col-md-4">
                        <div class="room-map-cell ${statusClass}" onclick="openRoomDetails('${r.id}')">
                            <div class="fs-4 mb-1"><i class="fas fa-bed"></i> Room ${r.id}</div>
                            <div class="small fw-semibold mb-1">${r.block} | ${r.floor}</div>
                            <div class="small opacity-75">${r.occupants.length} / ${r.capacity} Beds</div>
                        </div>
                    </div>
                `;
            });

            el.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-door-open text-info me-2"></i> Room Allocation Grid</h5>
                    <div>
                        <span class="badge bg-success px-3 py-2 rounded-pill me-2">Available Rooms: ${vacantCount}</span>
                        <span class="badge bg-danger px-3 py-2 rounded-pill">Fully Occupied: ${occupiedCount}</span>
                    </div>
                </div>
                <div class="row g-4 mb-4">
                    ${roomsHtml}
                </div>
            `;
        },

        renderMessMenu: function(el) {
            let rowsHtml = '';
            Object.keys(this.data.messMenu).forEach(day => {
                const m = this.data.messMenu[day];
                rowsHtml += `
                    <tr>
                        <td class="fw-bold text-dark ps-3 bg-light" style="width: 120px;">${day}</td>
                        <td><div class="editable-menu-cell" onclick="editMenuMeal('${day}', 'breakfast', this)">${m.breakfast}</div></td>
                        <td><div class="editable-menu-cell" onclick="editMenuMeal('${day}', 'lunch', this)">${m.lunch}</div></td>
                        <td><div class="editable-menu-cell" onclick="editMenuMeal('${day}', 'tea', this)">${m.tea}</div></td>
                        <td><div class="editable-menu-cell" onclick="editMenuMeal('${day}', 'dinner', this)">${m.dinner}</div></td>
                    </tr>
                `;
            });

            el.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-utensils text-warning me-2"></i> Weekly Mess Menu Planner</h5>
                    <button class="btn btn-sm btn-outline-warning fw-bold px-3 py-2 rounded-pill shadow-sm" onclick="resetMessMenuToDefault()">
                        <i class="fas fa-redo me-1"></i> Reset to Default
                    </button>
                </div>
                <div class="alert alert-info py-2 small shadow-sm mb-3">
                    <i class="fas fa-info-circle me-1"></i> <strong>Tip:</strong> Click on any meal entry in the table to edit it directly! Changes will persist.
                </div>
                <div class="table-responsive bg-white">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3">Day</th>
                                <th>Breakfast</th>
                                <th>Lunch</th>
                                <th>High Tea</th>
                                <th>Dinner</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHtml}
                        </tbody>
                    </table>
                </div>
            `;
        },

        renderNightAttendance: function(el) {
            let rowsHtml = '';
            this.data.attendance.forEach((a, index) => {
                let statusBadge = '';
                if (a.status === 'Present') statusBadge = 'bg-success';
                else if (a.status === 'Absent') statusBadge = 'bg-danger';
                else statusBadge = 'bg-warning text-dark';

                rowsHtml += `
                    <tr>
                        <td class="ps-3 fw-bold text-dark">${a.name}</td>
                        <td>Room ${a.room}</td>
                        <td>
                            <span class="badge ${statusBadge} px-3 py-1 rounded-pill fw-bold" id="attn-status-${index}">${a.status}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-success" onclick="updateAttendanceStatus(${index}, 'Present')">Present</button>
                                <button class="btn btn-outline-danger" onclick="updateAttendanceStatus(${index}, 'Absent')">Absent</button>
                                <button class="btn btn-outline-warning text-dark" onclick="updateAttendanceStatus(${index}, 'Late')">Late</button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            el.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-clipboard-check text-success me-2"></i> Night Attendance Sheet</h5>
                    <button class="btn btn-sm btn-success fw-bold px-4 py-2 rounded-pill shadow-sm" onclick="markAllAttendancePresent()">
                        <i class="fas fa-check-double me-1"></i> Mark All Present
                    </button>
                </div>
                <div class="table-responsive bg-white">
                    <table class="table table-hover border align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Student Name</th>
                                <th>Room Number</th>
                                <th>Status</th>
                                <th>Quick Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHtml}
                        </tbody>
                    </table>
                </div>
            `;
        },

        renderOutpassApprovals: function(el) {
            let rowsHtml = '';
            this.data.outpasses.forEach((o, index) => {
                let badgeClass = 'bg-warning text-dark';
                if (o.status === 'Approved') badgeClass = 'bg-success';
                if (o.status === 'Rejected') badgeClass = 'bg-danger';
                if (o.status === 'Completed') badgeClass = 'bg-secondary';

                let actionBtns = '';
                if (o.status === 'Pending') {
                    actionBtns = `
                        <button class="btn btn-sm btn-success rounded-pill px-3 py-1 me-1 shadow-sm" onclick="updateOutpassStatus(${index}, 'Approved')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                        <button class="btn btn-sm btn-danger rounded-pill px-3 py-1 shadow-sm" onclick="updateOutpassStatus(${index}, 'Rejected')">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    `;
                } else {
                    actionBtns = `<span class="text-muted small">Processed</span>`;
                }

                rowsHtml += `
                    <tr>
                        <td class="ps-3 fw-bold text-dark">${o.id}</td>
                        <td class="fw-bold text-dark">${o.name}</td>
                        <td>${o.purpose}</td>
                        <td>${o.from} to ${o.to}</td>
                        <td><span class="badge ${badgeClass} px-3 py-1 rounded-pill fw-bold">${o.status}</span></td>
                        <td>${actionBtns}</td>
                    </tr>
                `;
            });

            el.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-ticket-alt text-danger me-2"></i> Active Outpass Approvals</h5>
                    <button class="btn btn-sm btn-primary fw-bold px-3 py-2 rounded-pill shadow-sm" onclick="toggleNewOutpassForm()">
                        <i class="fas fa-plus me-1"></i> Issue Outpass
                    </button>
                </div>
                
                <!-- Hidden outpass form -->
                <div class="card border border-2 border-primary rounded-4 mb-4 p-4 shadow-sm d-none" id="newOutpassFormCard">
                    <h6 class="fw-bold text-primary mb-3"><i class="fas fa-file-signature me-1"></i> Issue Emergency Student Outpass</h6>
                    <form onsubmit="event.preventDefault(); submitNewOutpass();">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Student Name</label>
                                <input type="text" class="form-control" id="op_student_name" placeholder="e.g. Smit Dave" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Purpose of Leave</label>
                                <input type="text" class="form-control" id="op_purpose" placeholder="e.g. Urgent Home Visit" required>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Leave Date</label>
                                <input type="date" class="form-control" id="op_from_date" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Return Date</label>
                                <input type="date" class="form-control" id="op_to_date" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light border px-4 rounded-pill" onclick="toggleNewOutpassForm()">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">Submit Outpass</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive bg-white">
                    <table class="table table-hover border align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>Student</th>
                                <th>Purpose</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHtml}
                        </tbody>
                    </table>
                </div>
            `;
        },

        renderLaundryRegistry: function(el) {
            let rowsHtml = '';
            this.data.laundry.forEach((l, index) => {
                let badgeClass = 'bg-info text-white';
                if (l.status === 'Washing') badgeClass = 'bg-warning text-dark';
                if (l.status === 'Ready') badgeClass = 'bg-success';
                if (l.status === 'Delivered') badgeClass = 'bg-secondary';

                rowsHtml += `
                    <tr>
                        <td class="ps-3 fw-bold text-dark">${l.id}</td>
                        <td class="fw-bold text-dark">${l.name}</td>
                        <td class="fw-semibold text-secondary">${l.bag}</td>
                        <td>${l.items} Clothes</td>
                        <td>${l.date}</td>
                        <td><span class="badge ${badgeClass} px-3 py-1 rounded-pill fw-bold">${l.status}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-warning text-dark" onclick="updateLaundryStatus(${index}, 'Washing')">Wash</button>
                                <button class="btn btn-outline-success" onclick="updateLaundryStatus(${index}, 'Ready')">Ready</button>
                                <button class="btn btn-outline-secondary" onclick="updateLaundryStatus(${index}, 'Delivered')">Deliver</button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            el.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-tshirt text-primary me-2"></i> Laundry Registry & Tracker</h5>
                    <button class="btn btn-sm btn-primary fw-bold px-3 py-2 rounded-pill shadow-sm" onclick="toggleLaundryForm()">
                        <i class="fas fa-plus me-1"></i> Add Entry
                    </button>
                </div>

                <!-- Hidden laundry form -->
                <div class="card border border-2 border-primary rounded-4 mb-4 p-4 shadow-sm d-none" id="laundryFormCard">
                    <h6 class="fw-bold text-primary mb-3"><i class="fas fa-tshirt me-1"></i> Register New Laundry Bag</h6>
                    <form onsubmit="event.preventDefault(); submitLaundryBag();">
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Student Name</label>
                                <input type="text" class="form-control" id="lnd_student_name" placeholder="e.g. Vasant Patel" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Bag ID / Code</label>
                                <input type="text" class="form-control" id="lnd_bag_code" placeholder="e.g. Bag #B-99" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Number of Clothes</label>
                                <input type="number" class="form-control" id="lnd_clothes_count" placeholder="e.g. 10" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light border px-4 rounded-pill" onclick="toggleLaundryForm()">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">Register Bag</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive bg-white">
                    <table class="table table-hover border align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Token ID</th>
                                <th>Student</th>
                                <th>Bag Code</th>
                                <th>Quantity</th>
                                <th>Collected On</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHtml}
                        </tbody>
                    </table>
                </div>
            `;
        },

        renderComplaints: function(el) {
            let openCount = 0;
            let progressCount = 0;
            let resolvedCount = 0;
            this.data.complaints.forEach(c => {
                if (c.status === 'Open') openCount++;
                else if (c.status === 'In Progress') progressCount++;
                else resolvedCount++;
            });

            let rowsHtml = '';
            this.data.complaints.forEach((c, index) => {
                let badgeClass = 'bg-danger';
                if (c.status === 'In Progress') badgeClass = 'bg-warning text-dark';
                if (c.status === 'Resolved') badgeClass = 'bg-success';

                let sevClass = 'text-danger fw-bold';
                if (c.severity === 'Medium') sevClass = 'text-warning fw-bold';
                if (c.severity === 'Low') sevClass = 'text-secondary';

                let actionBtn = '';
                if (c.status !== 'Resolved') {
                    actionBtn = `
                        <button class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 shadow-sm fw-bold" onclick="resolveComplaint(${index})">
                            <i class="fas fa-check"></i> Mark Resolved
                        </button>
                    `;
                } else {
                    actionBtn = `<span class="text-success small fw-bold"><i class="fas fa-check-double"></i> Resolved</span>`;
                }

                rowsHtml += `
                    <tr>
                        <td class="ps-3 fw-bold text-dark">${c.id}</td>
                        <td class="fw-bold text-dark">${c.name} (Rm ${c.room})</td>
                        <td><span class="badge bg-light text-dark border px-2 py-1">${c.category}</span></td>
                        <td>${c.desc}</td>
                        <td><span class="${sevClass}">${c.severity}</span></td>
                        <td><span class="badge ${badgeClass} px-3 py-1 rounded-pill fw-bold">${c.status}</span></td>
                        <td>${actionBtn}</td>
                    </tr>
                `;
            });

            el.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-exclamation-triangle text-danger me-2"></i> Hostel Complaint Portal</h5>
                    <button class="btn btn-sm btn-danger fw-bold px-3 py-2 rounded-pill shadow-sm" onclick="toggleComplaintForm()">
                        <i class="fas fa-plus me-1"></i> Lodge Complaint
                    </button>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <div class="card border border-start-4 border-danger p-3 bg-light shadow-sm text-center">
                            <div class="fs-4 text-danger fw-bold">${openCount}</div>
                            <div class="small fw-semibold text-muted">Open Issues</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card border border-start-4 border-warning p-3 bg-light shadow-sm text-center">
                            <div class="fs-4 text-warning fw-bold">${progressCount}</div>
                            <div class="small fw-semibold text-muted">In Progress</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card border border-start-4 border-success p-3 bg-light shadow-sm text-center">
                            <div class="fs-4 text-success fw-bold">${resolvedCount}</div>
                            <div class="small fw-semibold text-muted">Resolved Issues</div>
                        </div>
                    </div>
                </div>

                <!-- Hidden complaint form -->
                <div class="card border border-2 border-danger rounded-4 mb-4 p-4 shadow-sm d-none" id="complaintFormCard">
                    <h6 class="fw-bold text-danger mb-3"><i class="fas fa-exclamation-circle me-1"></i> Lodge New Maintenance Complaint</h6>
                    <form onsubmit="event.preventDefault(); submitComplaint();">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Student Name</label>
                                <input type="text" class="form-control" id="cmp_student_name" placeholder="e.g. Vasant Patel" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Room Number</label>
                                <input type="text" class="form-control" id="cmp_room_no" placeholder="e.g. 101" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Category</label>
                                <select class="form-select" id="cmp_category" required>
                                    <option value="Electrical">Electrical / Light</option>
                                    <option value="Plumbing">Plumbing / Water</option>
                                    <option value="Internet">Internet / Wi-Fi</option>
                                    <option value="Furniture">Furniture / Bedding</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Severity</label>
                                <select class="form-select" id="cmp_severity" required>
                                    <option value="Low">Low (Routine)</option>
                                    <option value="Medium" selected>Medium (Standard)</option>
                                    <option value="High">High (Urgent)</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-8">
                                <label class="form-label small fw-bold">Description of Issue</label>
                                <input type="text" class="form-control" id="cmp_desc" placeholder="Details of the fault..." required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light border px-4 rounded-pill" onclick="toggleComplaintForm()">Cancel</button>
                            <button type="submit" class="btn btn-danger px-4 rounded-pill">File Complaint</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive bg-white">
                    <table class="table table-hover border align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>Student</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Severity</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHtml}
                        </tbody>
                    </table>
                </div>
            `;
        },

        renderFeePayments: function(el) {
            // Recalculate totals
            let totalBilled = 0;
            let totalPaid = 0;
            this.data.fees.forEach(f => {
                totalBilled += f.amount;
                totalPaid += f.paid;
            });
            const outstanding = totalBilled - totalPaid;

            // Ensure transactions and waivers exist in memory
            if (!this.data.feeTransactions) {
                this.data.feeTransactions = [
                    { name: 'Vasant Patel', enrollment: 'ENR-2026-001', room: 'Room 101, Block A', academicYear: '2026-27 Sem 3', category: 'Rent', period: 'Q1 - July to Sept', baseAmount: 38135.59, gst: 6864.41, penalty: 0, method: 'UPI/BHIM', reference: 'UTR99882211', assignedBy: 'Sadhu Adbhutanand Das', remarks: 'Paid on time via UPI QR', date: '2026-06-14' },
                    { name: 'Smit Dave', enrollment: 'ENR-2026-002', room: 'Room 101, Block A', academicYear: '2026-27 Sem 3', category: 'Mess', period: 'Q1 - July to Sept', baseAmount: 25423.73, gst: 4576.27, penalty: 0, method: 'Card', reference: 'TXN55443322', assignedBy: 'Sadhu Gyanprasad Das', remarks: 'Partial payment made', date: '2026-06-13' }
                ];
            }
            if (!this.data.waivers) {
                this.data.waivers = [
                    { name: 'Rahul Verma', enrollment: 'ENR-2026-003', originalAmount: 45000, discountPercent: 50, category: 'Seva Scholarship', status: 'Pending', requestedBy: 'Sadhu Adbhutanand Das' },
                    { name: 'Smit Dave', enrollment: 'ENR-2026-002', originalAmount: 15000, discountPercent: 20, category: 'Academic Excellence', status: 'Approved', requestedBy: 'Sadhu Gyanprasad Das' }
                ];
            }

            // Sub-tabs default state
            if (!this.currentFeeSubTab) {
                this.currentFeeSubTab = 'ledger';
            }

            // 1. Ledger Rows HTML
            let ledgerRows = '';
            this.data.fees.forEach((f, index) => {
                let badgeClass = 'bg-success';
                if (f.status === 'Partial') badgeClass = 'bg-warning text-dark';
                if (f.status === 'Unpaid') badgeClass = 'bg-danger';
                const balance = f.amount - f.paid;

                ledgerRows += `
                    <tr>
                        <td class="ps-3 fw-bold text-dark">${f.name}</td>
                        <td class="fw-semibold text-secondary">Room ${f.room}</td>
                        <td class="fw-bold">₹ ${f.amount.toLocaleString()}</td>
                        <td class="text-success fw-bold">₹ ${f.paid.toLocaleString()}</td>
                        <td class="text-danger fw-bold">₹ ${balance.toLocaleString()}</td>
                        <td><span class="badge ${badgeClass} px-3 py-1 rounded-pill fw-bold">${f.status}</span></td>
                        <td>
                            <button class="btn btn-sm btn-success rounded-pill px-3 py-1 me-1 shadow-sm fw-bold" onclick="selectStudentForPay(${index})">
                                <i class="fas fa-receipt me-1"></i> Record Pay
                            </button>
                        </td>
                    </tr>
                `;
            });

            // 2. Transaction Logs HTML
            let txnRows = '';
            this.data.feeTransactions.forEach((t, idx) => {
                const totalTxn = (parseFloat(t.baseAmount) + parseFloat(t.gst) + parseFloat(t.penalty)).toFixed(2);
                txnRows += `
                    <tr>
                        <td class="ps-3 fw-bold text-dark">${t.name}</td>
                        <td><span class="badge bg-light text-dark border">${t.category}</span></td>
                        <td>${t.period}</td>
                        <td class="fw-bold text-success">₹ ${parseFloat(totalTxn).toLocaleString()}</td>
                        <td><span class="badge bg-primary px-3 py-1 rounded-pill fw-bold text-uppercase" style="font-size:0.75rem;">${t.method}</span></td>
                        <td class="small text-muted font-monospace">${t.reference}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1 shadow-sm fw-bold" onclick="printInvoiceReceipt(${idx})">
                                <i class="fas fa-print me-1"></i> Print Receipt
                            </button>
                        </td>
                    </tr>
                `;
            });

            // 3. Waivers HTML
            let waiverRows = '';
            this.data.waivers.forEach((w, idx) => {
                let badge = w.status === 'Approved' ? 'bg-success' : 'bg-warning text-dark';
                let actionBtn = w.status === 'Pending' ? 
                    `<button class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-bold shadow-sm" onclick="approveWaiver(${idx})">Approve 🤝</button>` : 
                    `<span class="text-success small fw-bold"><i class="fas fa-check-double"></i> Signed Off</span>`;

                waiverRows += `
                    <tr>
                        <td class="ps-3 fw-bold text-dark">${w.name}</td>
                        <td>${w.enrollment}</td>
                        <td>₹ ${w.originalAmount.toLocaleString()}</td>
                        <td class="fw-bold text-danger">${w.discountPercent}% Discount</td>
                        <td><span class="badge bg-light text-dark border">${w.category}</span></td>
                        <td><span class="badge ${badge} px-2 py-1 rounded-pill">${w.status}</span></td>
                        <td>${actionBtn}</td>
                    </tr>
                `;
            });

            // Analytics calculations
            let upiCount = 0, cardCount = 0, cashCount = 0;
            this.data.feeTransactions.forEach(t => {
                if (t.method.includes('UPI')) upiCount++;
                else if (t.method.includes('Card')) cardCount++;
                else cashCount++;
            });
            const totalTxns = this.data.feeTransactions.length || 1;
            const upiPercent = Math.round((upiCount / totalTxns) * 100);
            const cardPercent = Math.round((cardCount / totalTxns) * 100);
            const cashPercent = Math.round((cashCount / totalTxns) * 100);

            // Constructing subtab container
            el.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-rupee-sign text-success me-2"></i> Hostel Fee Management Suite</h5>
                    <span class="badge bg-success px-3 py-2 rounded-pill fw-bold">Live Admin Ledger</span>
                </div>

                <!-- 7 Functions Subtab Menu -->
                <ul class="nav nav-tabs mb-4 border-0 bg-light p-2 rounded-pill shadow-sm" id="feeSubTabs" style="display: flex; flex-wrap: wrap; gap: 4px;">
                    <li class="nav-item"><button class="nav-link rounded-pill fw-bold border-0 px-3 py-2 ${this.currentFeeSubTab === 'ledger' ? 'active bg-success text-white' : 'text-secondary'}" onclick="switchFeeSubTab('ledger')">📊 1. Fee Ledger</button></li>
                    <li class="nav-item"><button class="nav-link rounded-pill fw-bold border-0 px-3 py-2 ${this.currentFeeSubTab === 'pay-form' ? 'active bg-success text-white' : 'text-secondary'}" onclick="switchFeeSubTab('pay-form')">✍️ 2. Record Payment (13 Details)</button></li>
                    <li class="nav-item"><button class="nav-link rounded-pill fw-bold border-0 px-3 py-2 ${this.currentFeeSubTab === 'ai-generator' ? 'active bg-success text-white' : 'text-secondary'}" onclick="switchFeeSubTab('ai-generator')">🤖 3. AI Bill Builder</button></li>
                    <li class="nav-item"><button class="nav-link rounded-pill fw-bold border-0 px-3 py-2 ${this.currentFeeSubTab === 'transactions' ? 'active bg-success text-white' : 'text-secondary'}" onclick="switchFeeSubTab('transactions')">📜 4. Transactions Log</button></li>
                    <li class="nav-item"><button class="nav-link rounded-pill fw-bold border-0 px-3 py-2 ${this.currentFeeSubTab === 'analytics' ? 'active bg-success text-white' : 'text-secondary'}" onclick="switchFeeSubTab('analytics')">📈 5. Collection Analytics</button></li>
                    <li class="nav-item"><button class="nav-link rounded-pill fw-bold border-0 px-3 py-2 ${this.currentFeeSubTab === 'fines' ? 'active bg-success text-white' : 'text-secondary'}" onclick="switchFeeSubTab('fines')">⚠️ 6. Apply Late Fines</button></li>
                    <li class="nav-item"><button class="nav-link rounded-pill fw-bold border-0 px-3 py-2 ${this.currentFeeSubTab === 'waivers' ? 'active bg-success text-white' : 'text-secondary'}" onclick="switchFeeSubTab('waivers')">🤝 7. Seva Waivers</button></li>
                </ul>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <div class="card border border-start-4 border-info p-3 bg-light shadow-sm text-center">
                            <div class="fs-4 text-info fw-bold">₹ ${totalBilled.toLocaleString()}</div>
                            <div class="small fw-semibold text-muted">Total Billed Fees</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card border border-start-4 border-success p-3 bg-light shadow-sm text-center">
                            <div class="fs-4 text-success fw-bold">₹ ${totalPaid.toLocaleString()}</div>
                            <div class="small fw-semibold text-muted">Total Collections</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card border border-start-4 border-danger p-3 bg-light shadow-sm text-center">
                            <div class="fs-4 text-danger fw-bold">₹ ${outstanding.toLocaleString()}</div>
                            <div class="small fw-semibold text-muted">Outstanding Balance</div>
                        </div>
                    </div>
                </div>

                <!-- Subtab Workspace Container -->
                <div class="fee-tab-content bg-white p-3 border rounded-4 shadow-sm" style="min-height: 350px;">
                    
                    <!-- 1. LEDGER TAB -->
                    <div class="${this.currentFeeSubTab === 'ledger' ? '' : 'd-none'}">
                        <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-list me-2 text-primary"></i> Student Outstanding Ledger</h6>
                        <div class="table-responsive">
                            <table class="table table-hover border align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Student Name</th>
                                        <th>Room</th>
                                        <th>Total Term Fee</th>
                                        <th>Amount Paid</th>
                                        <th>Balance Due</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${ledgerRows}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 2. RECORD PAYMENT FORM (13 DETAILS) -->
                    <div class="${this.currentFeeSubTab === 'pay-form' ? '' : 'd-none'}">
                        <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-file-invoice-dollar me-2 text-success"></i> Record New Payment / Bill Invoice (13 Details Required)</h6>
                        <form onsubmit="event.preventDefault(); submitRecordPaymentForm();">
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold">1. Student Name</label>
                                    <select class="form-select" id="pay_student_name" onchange="autoFillStudentDetails(this.value)" required>
                                        <option value="">-- Select Student --</option>
                                        <option value="Vasant Patel">Vasant Patel</option>
                                        <option value="Smit Dave">Smit Dave</option>
                                        <option value="Rahul Verma">Rahul Verma</option>
                                        <option value="Priya Sharma">Priya Sharma</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold">2. Enrollment Number</label>
                                    <input type="text" class="form-control font-monospace" id="pay_enrollment" placeholder="e.g. ENR-2026-001" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold">3. Room & Block</label>
                                    <input type="text" class="form-control" id="pay_room" placeholder="e.g. Room 101, Block A" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold">4. Academic Year & Semester</label>
                                    <select class="form-select" id="pay_academic" required>
                                        <option value="2026-27 Sem 3" selected>2026-27 Sem 3</option>
                                        <option value="2026-27 Sem 4">2026-27 Sem 4</option>
                                        <option value="2027-28 Sem 5">2027-28 Sem 5</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold">5. Bill Category</label>
                                    <select class="form-select" id="pay_category" required>
                                        <option value="Rent" selected>Hostel Room Rent</option>
                                        <option value="Mess">Mess Food Fee</option>
                                        <option value="Laundry">Laundry Services</option>
                                        <option value="Gym">Gym Facility</option>
                                        <option value="Electricity">Electricity Charges</option>
                                        <option value="Penalty">Fine / Penalty Charges</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold">6. Billing Period</label>
                                    <select class="form-select" id="pay_period" required>
                                        <option value="Q1 - July to Sept" selected>Q1 - July to Sept</option>
                                        <option value="Q2 - Oct to Dec">Q2 - Oct to Dec</option>
                                        <option value="Q3 - Jan to Mar">Q3 - Jan to Mar</option>
                                        <option value="Q4 - Apr to June">Q4 - Apr to June</option>
                                        <option value="Annual Fee">Annual Term</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold">7. Base Hostel Fee (₹)</label>
                                    <input type="number" step="0.01" class="form-control" id="pay_base" placeholder="Base fee amount" oninput="calculateTotalInvoiceAmount()" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold">8. Tax / GST Amount (18%) (₹)</label>
                                    <input type="number" step="0.01" class="form-control text-muted bg-light" id="pay_gst" placeholder="Automatic 18% GST" readonly>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold">9. Late Fee / Penalty (₹)</label>
                                    <input type="number" step="0.01" class="form-control" id="pay_penalty" value="0" oninput="calculateTotalInvoiceAmount()" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold">10. Payment Method (Card, Cash, UPI)</label>
                                    <select class="form-select" id="pay_method" onchange="togglePaymentWayFields(this.value)" required>
                                        <option value="UPI/BHIM" selected>BHIM / UPI QR Code</option>
                                        <option value="Card">Credit / Debit Card</option>
                                        <option value="Cash">Physical Cash Handover</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold">11. Reference / Reciept ID</label>
                                    <input type="text" class="form-control font-monospace" id="pay_reference" placeholder="e.g. UTR / TXN / Slip No" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold">12. Supervisor / Swami / Dean / Admin</label>
                                    <select class="form-select" id="pay_assigned_by" required>
                                        <option value="Sadhu Adbhutanand Das (Warden)">Sadhu Adbhutanand Das (Warden)</option>
                                        <option value="Sadhu Gyanprasad Das (Warden)">Sadhu Gyanprasad Das (Warden)</option>
                                        <option value="Bhavik Patel (Administrator)" selected>Bhavik Patel (Administrator)</option>
                                        <option value="Dr. Sadhu Gyaneswar Das (Dean & Swami)">Dr. Sadhu Gyaneswar Das (Dean & Swami)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <label class="form-label small fw-bold">13. Remarks / Special Instructions</label>
                                    <textarea class="form-control" id="pay_remarks" rows="2" placeholder="Scholarship status, balance notes, details..."></textarea>
                                </div>
                            </div>

                            <!-- Dynamic 3-Way Payment Details Section -->
                            <div class="card bg-light border-dashed rounded-4 p-3 mb-4">
                                <div id="pay_details_upi" class="text-center py-2">
                                    <h6 class="fw-bold mb-2 text-dark"><i class="fas fa-qrcode text-primary me-2"></i> Scan dynamic QR Code to pay with BHIM UPI</h6>
                                    <div class="mb-2">
                                        <img id="upi_qr_img" src="https://api.qrserver.com/v1/create-qr-code/?size=130x130&data=upi://pay?pa=www.bhavikpatel110@oksbi&pn=BAPS%20Hostel&am=0" class="border p-2 bg-white rounded-3 shadow-sm" alt="UPI QR">
                                    </div>
                                    <span class="small fw-semibold text-secondary">UPI Number: 9316945893 | UPI ID: www.bhavikpatel110@oksbi | Enter UTR Reference above after scan.</span>
                                </div>
                                <div id="pay_details_card" class="d-none">
                                    <h6 class="fw-bold mb-3 text-dark"><i class="far fa-credit-card text-success me-2"></i> Card Transaction Processing Fields</h6>
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6"><input type="text" class="form-control" id="card_holder" placeholder="Cardholder Full Name"></div>
                                        <div class="col-12 col-md-6"><input type="text" class="form-control" id="card_no" placeholder="Credit Card Number (16 Digits)"></div>
                                        <div class="col-6"><input type="text" class="form-control" id="card_expiry" placeholder="Expiry MM/YY"></div>
                                        <div class="col-6"><input type="password" class="form-control" id="card_cvv" placeholder="CVV"></div>
                                    </div>
                                </div>
                                <div id="pay_details_cash" class="d-none text-center py-2">
                                    <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-coins text-warning me-2"></i> Physical Cash Details</h6>
                                    <div class="row g-3 justify-content-center align-items-center mb-3">
                                        <div class="col-auto">
                                            <div class="input-group input-group-sm border rounded-pill overflow-hidden bg-white shadow-sm" style="width: 140px;">
                                                <span class="input-group-text bg-light border-0 fw-bold font-monospace">₹ 500</span>
                                                <input type="number" id="cash_500" value="0" class="form-control border-0 text-center fw-bold" style="outline: none;" oninput="updateCashDenomSum()">
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="input-group input-group-sm border rounded-pill overflow-hidden bg-white shadow-sm" style="width: 140px;">
                                                <span class="input-group-text bg-light border-0 fw-bold font-monospace">₹ 200</span>
                                                <input type="number" id="cash_200" value="0" class="form-control border-0 text-center fw-bold" style="outline: none;" oninput="updateCashDenomSum()">
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="input-group input-group-sm border rounded-pill overflow-hidden bg-white shadow-sm" style="width: 140px;">
                                                <span class="input-group-text bg-light border-0 fw-bold font-monospace">₹ 100</span>
                                                <input type="number" id="cash_100" value="0" class="form-control border-0 text-center fw-bold" style="outline: none;" oninput="updateCashDenomSum()">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
                                        <span class="fw-bold text-success border-end pe-3" id="cash_denom_sum">Total Breakdown: ₹ 0.00</span>
                                        <div class="d-flex align-items-center gap-2">
                                            <label class="small fw-bold text-secondary m-0">Or enter total cash manually:</label>
                                            <input type="number" step="0.01" id="cash_manual_total" class="form-control form-control-sm rounded-pill fw-bold text-success font-monospace" style="width: 120px;" placeholder="₹ 0.00" oninput="updateCashManualTotal()">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold text-success m-0" id="total_bill_display">Total Invoice Amount: ₹ 0.00</h5>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-light border px-4 rounded-pill" onclick="switchFeeSubTab('ledger')">Cancel</button>
                                    <button type="submit" class="btn btn-success px-5 py-2 rounded-pill fw-bold shadow-sm"><i class="fas fa-save me-1"></i> Record & Generate Bill</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- 3. AI BILL BUILDER TAB -->
                    <div class="${this.currentFeeSubTab === 'ai-generator' ? '' : 'd-none'}">
                        <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-robot text-primary me-2"></i> AI Bill Builder & Invoice Generator</h6>
                        <div class="alert alert-info py-2 small shadow-sm mb-3">
                            <i class="fas fa-magic me-1"></i> <strong>How it works:</strong> Type your billing requirements in plain English (e.g., <i>"Mess fee of 15000 for Smit Dave for Q2, add a late fine of 500 rupees since he paid past deadline"</i>) and the AI Copilot will automatically compile taxes, parse entities, and populate the 13 fields.
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" id="ai_billing_prompt" rows="4" placeholder="Explain the invoice in natural language..."></textarea>
                        </div>
                        <div class="progress mb-4 d-none" id="ai_billing_progress" style="height: 10px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%"></div>
                        </div>
                        <button class="btn btn-success fw-bold px-4 py-2 rounded-pill shadow-sm" onclick="runAiBillingBuilder()">
                            <i class="fas fa-cog fa-spin me-2" id="ai_spin_icon" style="display:none;"></i> <i class="fas fa-brain me-1" id="ai_brain_icon"></i> Compile Invoice Details
                        </button>
                    </div>

                    <!-- 4. TRANSACTIONS LOG TAB -->
                    <div class="${this.currentFeeSubTab === 'transactions' ? '' : 'd-none'}">
                        <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-history me-2 text-warning"></i> Fee Collections Transaction Logs</h6>
                        <div class="table-responsive">
                            <table class="table table-hover border align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Student</th>
                                        <th>Category</th>
                                        <th>Billing Period</th>
                                        <th>Total Paid</th>
                                        <th>Payment Mode</th>
                                        <th>Reference ID</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${txnRows}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 5. ANALYTICS TAB -->
                    <div class="${this.currentFeeSubTab === 'analytics' ? '' : 'd-none'}">
                        <h6 class="fw-bold mb-4 text-dark"><i class="fas fa-chart-bar me-2 text-info"></i> Hostel Revenue & Collection Analytics</h6>
                        <div class="row g-4">
                            <!-- Left: SVG Pie Chart -->
                            <div class="col-12 col-md-6 text-center">
                                <h6 class="fw-bold mb-3 text-secondary">Payment Method Share (%)</h6>
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <svg width="200" height="200" viewBox="0 0 36 36" class="circular-chart">
                                        <path class="circle-bg" stroke="#f1f5f9" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        <path class="circle" stroke="#22c55e" stroke-width="3" stroke-dasharray="${upiPercent}, 100" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        <path class="circle" stroke="#3b82f6" stroke-dasharray="${cardPercent}, 100" stroke-dashoffset="-${upiPercent}" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        <path class="circle" stroke="#f59e0b" stroke-dasharray="${cashPercent}, 100" stroke-dashoffset="-${upiPercent + cardPercent}" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        <text x="18" y="20.35" class="percentage fw-bold" font-size="7" text-anchor="middle" fill="#1e293b">Collections</text>
                                    </svg>
                                    <div class="d-flex justify-content-center gap-3 mt-3">
                                        <span class="small"><i class="fas fa-circle text-success me-1"></i> UPI (${upiPercent}%)</span>
                                        <span class="small"><i class="fas fa-circle text-primary me-1"></i> Card (${cardPercent}%)</span>
                                        <span class="small"><i class="fas fa-circle text-warning me-1"></i> Cash (${cashPercent}%)</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right: Category Collections bar chart -->
                            <div class="col-12 col-md-6">
                                <h6 class="fw-bold mb-3 text-secondary">Collections by Category (₹)</h6>
                                <div class="d-flex flex-column gap-3">
                                    <div>
                                        <div class="d-flex justify-content-between small fw-bold mb-1"><span>Hostel Rent</span><span>₹ 90,000</span></div>
                                        <div class="progress" style="height: 12px;"><div class="progress-bar bg-success" style="width: 75%"></div></div>
                                    </div>
                                    <div>
                                        <div class="d-flex justify-content-between small fw-bold mb-1"><span>Mess Dining</span><span>₹ 25,000</span></div>
                                        <div class="progress" style="height: 12px;"><div class="progress-bar bg-info" style="width: 45%"></div></div>
                                    </div>
                                    <div>
                                        <div class="d-flex justify-content-between small fw-bold mb-1"><span>Laundry</span><span>₹ 5,000</span></div>
                                        <div class="progress" style="height: 12px;"><div class="progress-bar bg-warning" style="width: 25%"></div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6. APPLY LATE FINES TAB -->
                    <div class="${this.currentFeeSubTab === 'fines' ? '' : 'd-none'}">
                        <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-gavel me-2 text-danger"></i> Late Fee Penalty Application Desk</h6>
                        <p class="small text-muted">Use this rule enforcement engine to automatically apply a penalty fine to all outstanding (Unpaid / Partially Paid) student accounts in the system.</p>
                        <div class="card p-4 border rounded-4 bg-light shadow-sm">
                            <div class="row g-3 align-items-end">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-bold">Flat Penalty Fine Amount (₹)</label>
                                    <input type="number" class="form-control" id="fine_sweep_amount" value="500" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <button class="btn btn-danger fw-bold w-100 py-2 rounded-pill shadow-sm" onclick="runPenaltyFineSweep()">
                                        <i class="fas fa-gavel me-1"></i> Apply Penalty Fine Sweep
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 7. SEVA WAIVERS TAB -->
                    <div class="${this.currentFeeSubTab === 'waivers' ? '' : 'd-none'}">
                        <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-handshake me-2 text-success"></i> Seva Volunteer Fee Waivers & Scholarships</h6>
                        <div class="table-responsive">
                            <table class="table table-hover border align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Student Name</th>
                                        <th>Enrollment</th>
                                        <th>Original Amount</th>
                                        <th>Discount Percent</th>
                                        <th>Waiver Category</th>
                                        <th>Status</th>
                                        <th>Action Approval</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${waiverRows}
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- Modals / Receipt Previews -->
                <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" id="receiptModalContent"></div>
                    </div>
                </div>
            `;
        },


        renderInventory: function(el) {
            let rowsHtml = '';
            this.data.inventory.forEach((i, index) => {
                const available = i.total - i.use - i.damaged;
                rowsHtml += `
                    <tr>
                        <td class="ps-3 fw-bold text-dark">${i.code}</td>
                        <td class="fw-bold text-dark">${i.name}</td>
                        <td>${i.category}</td>
                        <td class="fw-bold">${i.total}</td>
                        <td class="text-primary fw-bold">${i.use}</td>
                        <td class="text-danger fw-bold">${i.damaged}</td>
                        <td class="text-success fw-bold">${available}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-danger" onclick="adjustInventoryDamaged(${index}, 1)"><i class="fas fa-plus"></i> Damage</button>
                                <button class="btn btn-outline-success" onclick="adjustInventoryTotal(${index}, 5)"><i class="fas fa-plus"></i> Restock</button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            el.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-bed text-secondary me-2"></i> Hostel Assets Inventory</h5>
                    <button class="btn btn-sm btn-primary fw-bold px-3 py-2 rounded-pill shadow-sm" onclick="toggleInventoryForm()">
                        <i class="fas fa-plus me-1"></i> Add Asset Type
                    </button>
                </div>

                <!-- Hidden inventory form -->
                <div class="card border border-2 border-secondary rounded-4 mb-4 p-4 shadow-sm d-none" id="inventoryFormCard">
                    <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-plus-circle me-1"></i> Add New Inventory Item Code</h6>
                    <form onsubmit="event.preventDefault(); submitInventoryItem();">
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-bold">Item Code</label>
                                <input type="text" class="form-control" id="inv_code" placeholder="e.g. INV-FAN-02" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-bold">Item Name</label>
                                <input type="text" class="form-control" id="inv_name" placeholder="e.g. Stand Fan" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-bold">Category</label>
                                <input type="text" class="form-control" id="inv_category" placeholder="e.g. Electrical" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-bold">Initial Quantity</label>
                                <input type="number" class="form-control" id="inv_qty" placeholder="e.g. 50" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light border px-4 rounded-pill" onclick="toggleInventoryForm()">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">Create Entry</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive bg-white">
                    <table class="table table-hover border align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Asset Code</th>
                                <th>Item Description</th>
                                <th>Category</th>
                                <th>Total Stock</th>
                                <th>In Active Use</th>
                                <th>Damaged</th>
                                <th>Available (Stock Room)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHtml}
                        </tbody>
                    </table>
                </div>
            `;
        },

        renderCleaningSchedule: function(el) {
            let rowsHtml = '';
            this.data.cleaning.forEach((c, index) => {
                let badgeClass = 'bg-success';
                if (c.status === 'Pending') badgeClass = 'bg-warning text-dark';

                let actionBtn = '';
                if (c.status === 'Pending') {
                    actionBtn = `
                        <button class="btn btn-sm btn-success rounded-pill px-3 py-1 shadow-sm fw-bold" onclick="completeCleaning(${index})">
                            <i class="fas fa-check me-1"></i> Sign-Off Clean
                        </button>
                    `;
                } else {
                    actionBtn = `<span class="text-success small fw-bold"><i class="fas fa-check-double me-1"></i> Cleaned</span>`;
                }

                rowsHtml += `
                    <tr>
                        <td class="ps-3 fw-bold text-dark">${c.zone}</td>
                        <td>${c.frequency}</td>
                        <td class="fw-bold">${c.janitor}</td>
                        <td>${c.date}</td>
                        <td><span class="badge ${badgeClass} px-3 py-1 rounded-pill fw-bold">${c.status}</span></td>
                        <td>${actionBtn}</td>
                    </tr>
                `;
            });

            el.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-broom text-info me-2"></i> Janitorial Cleaning Schedule</h5>
                    <button class="btn btn-sm btn-info text-white fw-bold px-3 py-2 rounded-pill shadow-sm" onclick="toggleCleaningForm()">
                        <i class="fas fa-plus me-1"></i> Assign New Schedule
                    </button>
                </div>

                <!-- Hidden cleaning form -->
                <div class="card border border-2 border-info rounded-4 mb-4 p-4 shadow-sm d-none" id="cleaningFormCard">
                    <h6 class="fw-bold text-info mb-3"><i class="fas fa-broom me-1"></i> Assign Area Cleaning Directive</h6>
                    <form onsubmit="event.preventDefault(); submitCleaningTask();">
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Area / Zone</label>
                                <input type="text" class="form-control" id="cln_zone" placeholder="e.g. Block A Lobby" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Janitor Name</label>
                                <input type="text" class="form-control" id="cln_janitor" placeholder="e.g. Suresh Patel" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Frequency</label>
                                <select class="form-select" id="cln_freq" required>
                                    <option value="Daily">Daily</option>
                                    <option value="Twice Daily">Twice Daily</option>
                                    <option value="Weekly">Weekly</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light border px-4 rounded-pill" onclick="toggleCleaningForm()">Cancel</button>
                            <button type="submit" class="btn btn-info text-white px-4 rounded-pill">Dispatch Janitor</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive bg-white">
                    <table class="table table-hover border align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Area / Zone</th>
                                <th>Frequency</th>
                                <th>Assigned Janitor</th>
                                <th>Last Cleaned / Checked</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHtml}
                        </tbody>
                    </table>
                </div>
            `;
        },

        renderVisitorLog: function(el) {
            let rowsHtml = '';
            this.data.visitors.forEach((v, index) => {
                let statusBadge = '';
                let actionBtn = '';
                if (v.out) {
                    statusBadge = '<span class="badge bg-secondary px-3 py-1 rounded-pill">Checked Out</span>';
                    actionBtn = `<span class="text-muted small">Checked Out</span>`;
                } else {
                    statusBadge = '<span class="badge bg-success px-3 py-1 rounded-pill">Active Visit</span>';
                    actionBtn = `
                        <button class="btn btn-sm btn-danger rounded-pill px-3 py-1 shadow-sm fw-bold" onclick="checkoutVisitor(${index})">
                            <i class="fas fa-sign-out-alt me-1"></i> Check Out
                        </button>
                    `;
                }

                rowsHtml += `
                    <tr>
                        <td class="ps-3 fw-bold text-dark">${v.id}</td>
                        <td class="fw-bold text-dark">${v.name} (${v.relation})</td>
                        <td>${v.student} (Rm ${v.room})</td>
                        <td>${v.in}</td>
                        <td>${v.out || '--:--'}</td>
                        <td>${statusBadge}</td>
                        <td>${actionBtn}</td>
                    </tr>
                `;
            });

            el.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-user-clock text-warning me-2"></i> Hostel Visitors Logbook</h5>
                    <button class="btn btn-sm btn-primary fw-bold px-3 py-2 rounded-pill shadow-sm" onclick="toggleVisitorForm()">
                        <i class="fas fa-plus me-1"></i> Log Visitor Entry
                    </button>
                </div>

                <!-- Hidden visitor form -->
                <div class="card border border-2 border-primary rounded-4 mb-4 p-4 shadow-sm d-none" id="visitorFormCard">
                    <h6 class="fw-bold text-primary mb-3"><i class="fas fa-user-check me-1"></i> Log New Guest Entry</h6>
                    <form onsubmit="event.preventDefault(); submitVisitor();">
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-bold">Visitor Full Name</label>
                                <input type="text" class="form-control" id="vis_name" placeholder="e.g. Harish Patel" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-bold">Relation to Student</label>
                                <input type="text" class="form-control" id="vis_relation" placeholder="e.g. Father" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-bold">Student Visited</label>
                                <input type="text" class="form-control" id="vis_student" placeholder="e.g. Vasant Patel" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label small fw-bold">Room No</label>
                                <input type="text" class="form-control" id="vis_room" placeholder="e.g. 101" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light border px-4 rounded-pill" onclick="toggleVisitorForm()">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">Log Check-in</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive bg-white">
                    <table class="table table-hover border align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Pass ID</th>
                                <th>Visitor Details</th>
                                <th>Hosteller Contact</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHtml}
                        </tbody>
                    </table>
                </div>
            `;
        },

        renderDisciplinaryLog: function(el) {
            let rowsHtml = '';
            this.data.discipline.forEach((d, index) => {
                let badgeClass = 'bg-danger';
                if (d.status === 'Resolved') badgeClass = 'bg-success';
                
                let sevClass = 'text-danger fw-bold';
                if (d.severity === 'Minor') sevClass = 'text-warning fw-bold';

                let actionBtn = '';
                if (d.status === 'Active') {
                    actionBtn = `
                        <button class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 shadow-sm fw-bold" onclick="resolveDisciplinaryCase(${index})">
                            <i class="fas fa-check me-1"></i> Resolve Case
                        </button>
                    `;
                } else {
                    actionBtn = `<span class="text-success small fw-bold"><i class="fas fa-check-double me-1"></i> Case Closed</span>`;
                }

                rowsHtml += `
                    <tr>
                        <td class="ps-3 fw-bold text-dark">${d.name}</td>
                        <td>${d.date}</td>
                        <td>${d.incident}</td>
                        <td><span class="${sevClass}">${d.severity}</span></td>
                        <td class="fw-bold">${d.action}</td>
                        <td><span class="badge ${badgeClass} px-3 py-1 rounded-pill fw-bold">${d.status}</span></td>
                        <td>${actionBtn}</td>
                    </tr>
                `;
            });

            el.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-gavel text-danger me-2"></i> Hostel Disciplinary Logs</h5>
                    <button class="btn btn-sm btn-danger fw-bold px-3 py-2 rounded-pill shadow-sm" onclick="toggleDisciplineForm()">
                        <i class="fas fa-plus me-1"></i> Log Incident
                    </button>
                </div>

                <!-- Hidden discipline form -->
                <div class="card border border-2 border-danger rounded-4 mb-4 p-4 shadow-sm d-none" id="disciplineFormCard">
                    <h6 class="fw-bold text-danger mb-3"><i class="fas fa-gavel me-1"></i> Log New Curfew/Rules Offense</h6>
                    <form onsubmit="event.preventDefault(); submitDisciplinaryIncident();">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Student Name</label>
                                <input type="text" class="form-control" id="dsp_student_name" placeholder="e.g. Rahul Verma" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Severity</label>
                                <select class="form-select" id="dsp_severity" required>
                                    <option value="Minor">Minor Violation</option>
                                    <option value="Major">Major / Critical Offense</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-bold">Action Taken</label>
                                <input type="text" class="form-control" id="dsp_action" placeholder="e.g. Fine ₹ 500 / Warning" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Detailed Incident Description</label>
                            <textarea class="form-control" id="dsp_incident" rows="2" placeholder="Details of curfew violation or code of conduct breach..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light border px-4 rounded-pill" onclick="toggleDisciplineForm()">Cancel</button>
                            <button type="submit" class="btn btn-danger px-4 rounded-pill">Log Incident</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive bg-white">
                    <table class="table table-hover border align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Student Name</th>
                                <th>Incident Date</th>
                                <th>Offense Description</th>
                                <th>Severity</th>
                                <th>Action Taken</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rowsHtml}
                        </tbody>
                    </table>
                </div>
            `;
        },

        renderEventCalendar: function(el) {
            let cardsHtml = '';
            this.data.events.forEach((ev, index) => {
                cardsHtml += `
                    <div class="col-12 col-md-6">
                        <div class="card border border-light shadow-sm rounded-4 h-100 p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-primary px-3 py-1 rounded-pill fw-bold text-uppercase" style="font-size:0.75rem;">Hostel Event</span>
                                <button class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="deleteHostelEvent(${index})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">${ev.name}</h5>
                            <div class="small text-muted mb-2"><i class="fas fa-calendar-day text-secondary me-1"></i> <strong>Schedule:</strong> ${ev.date}</div>
                            <div class="small text-muted mb-2"><i class="fas fa-map-marker-alt text-secondary me-1"></i> <strong>Venue:</strong> ${ev.venue}</div>
                            <div class="small text-muted mb-0"><i class="fas fa-user text-secondary me-1"></i> <strong>Coordinator:</strong> ${ev.coordinator}</div>
                        </div>
                    </div>
                `;
            });

            el.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-calendar-alt text-primary me-2"></i> Upcoming Hostel Events Calendar</h5>
                    <button class="btn btn-sm btn-primary fw-bold px-3 py-2 rounded-pill shadow-sm" onclick="toggleEventForm()">
                        <i class="fas fa-plus me-1"></i> Schedule Event
                    </button>
                </div>

                <!-- Hidden event form -->
                <div class="card border border-2 border-primary rounded-4 mb-4 p-4 shadow-sm d-none" id="eventFormCard">
                    <h6 class="fw-bold text-primary mb-3"><i class="fas fa-calendar-plus me-1"></i> Schedule New Hostel Activity</h6>
                    <form onsubmit="event.preventDefault(); submitHostelEvent();">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Event Title</label>
                                <input type="text" class="form-control" id="ev_title" placeholder="e.g. Sports Meet" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Coordinator</label>
                                <input type="text" class="form-control" id="ev_coordinator" placeholder="e.g. Sadhu Gyanprasad Das" required>
                            </div>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Date & Time</label>
                                <input type="text" class="form-control" id="ev_date" placeholder="e.g. June 28, 2026 at 19:00 PM" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold">Venue</label>
                                <input type="text" class="form-control" id="ev_venue" placeholder="e.g. Assembly Ground" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light border px-4 rounded-pill" onclick="toggleEventForm()">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 rounded-pill">Create Event</button>
                        </div>
                    </form>
                </div>

                <div class="row g-4">
                    ${cardsHtml}
                </div>
            `;
        },

        // NEW Option 1: Mess Feedback & Ratings
        renderMessFeedback: function(el) {
            let totalRating = 0;
            this.data.feedback.forEach(f => totalRating += f.rating);
            const avgRating = (totalRating / this.data.feedback.length).toFixed(1);

            let feedbackList = '';
            this.data.feedback.forEach(f => {
                let stars = '';
                for (let idx = 1; idx <= 5; idx++) {
                    stars += `<i class="fas fa-star ${idx <= f.rating ? 'text-warning' : 'text-secondary'} small"></i>`;
                }
                feedbackList += `
                    <div class="list-group-item bg-white border rounded-4 mb-3 p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="fw-bold text-dark">${f.name}</div>
                            <div class="small text-muted">${f.date}</div>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-light text-dark border me-2">${f.category}</span>
                            ${stars}
                        </div>
                        <p class="text-secondary small mb-0">"${f.comment}"</p>
                    </div>
                `;
            });

            el.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-star text-warning me-2"></i> Mess Food Quality & Feedback</h5>
                    <button class="btn btn-sm btn-outline-warning fw-bold px-3 py-2 rounded-pill shadow-sm" onclick="toggleFeedbackForm()">
                        <i class="fas fa-comment-dots me-1"></i> Submit Review
                    </button>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-4">
                        <div class="card border border-2 border-warning rounded-4 p-4 h-100 text-center shadow-sm d-flex flex-column align-items-center justify-content-center">
                            <div class="display-3 text-warning fw-bold mb-1">${avgRating}</div>
                            <div class="mb-2">
                                <i class="fas fa-star text-warning fs-4"></i>
                                <i class="fas fa-star text-warning fs-4"></i>
                                <i class="fas fa-star text-warning fs-4"></i>
                                <i class="fas fa-star text-warning fs-4"></i>
                                <i class="fas fa-star-half-alt text-warning fs-4"></i>
                            </div>
                            <div class="small fw-semibold text-muted">Overall Student Rating (Out of 5)</div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-8">
                        <div class="card border border-2 border-primary rounded-4 mb-4 p-4 shadow-sm d-none" id="feedbackFormCard">
                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-pen-nib me-1"></i> Submit Student Dinner/Lunch Feedback</h6>
                            <form onsubmit="event.preventDefault(); submitFeedback();">
                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-bold">Student Name</label>
                                        <input type="text" class="form-control" id="fb_student_name" placeholder="e.g. Vasant Patel" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-bold">Feedback Category</label>
                                        <select class="form-select" id="fb_category" required>
                                            <option value="Taste">Taste / Flavor</option>
                                            <option value="Hygiene">Cleanliness & Hygiene</option>
                                            <option value="Variety">Menu Diversity</option>
                                            <option value="Service">Warden Service / Speed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-12">
                                        <label class="form-label small fw-bold d-block">Overall Rating Score</label>
                                        <div class="star-rating d-inline-flex gap-2">
                                            <i class="fas fa-star" onclick="setStarRating(1)" data-star="1"></i>
                                            <i class="fas fa-star" onclick="setStarRating(2)" data-star="2"></i>
                                            <i class="fas fa-star" onclick="setStarRating(3)" data-star="3"></i>
                                            <i class="fas fa-star" onclick="setStarRating(4)" data-star="4"></i>
                                            <i class="fas fa-star" onclick="setStarRating(5)" data-star="5"></i>
                                        </div>
                                        <input type="hidden" id="fb_rating_score" value="5">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small fw-bold">Comment Details</label>
                                    <textarea class="form-control" id="fb_comment" rows="2" placeholder="Tell us how the food was..." required></textarea>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-light border px-4 rounded-pill" onclick="toggleFeedbackForm()">Cancel</button>
                                    <button type="submit" class="btn btn-warning px-4 rounded-pill">Submit Review</button>
                                </div>
                            </form>
                        </div>

                        <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-comments text-secondary me-2"></i> Student Feedback Wall</h6>
                        <div class="feedback-list-container" style="max-height: 300px; overflow-y: auto;">
                            ${feedbackList}
                        </div>
                    </div>
                </div>
            `;
            // Call star init after render
            this.setStarState(5);
        },

        setStarState: function(score) {
            const stars = document.querySelectorAll('.star-rating .fa-star');
            stars.forEach((star, idx) => {
                if (idx < score) star.classList.add('active');
                else star.classList.remove('active');
            });
            const input = document.getElementById('fb_rating_score');
            if (input) input.value = score;
        },

        // NEW Option 2: Hostel Staff Roster
        renderHostelStaffRoster: function(el) {
            let rosterRows = '';
            this.data.roster.forEach((sh, index) => {
                rosterRows += `
                    <tr>
                        <td class="ps-3 fw-bold text-dark">${sh.day}</td>
                        <td class="fw-semibold text-secondary">${sh.shift}</td>
                        <td class="fw-bold text-dark">${sh.staff}</td>
                        <td><span class="badge bg-light text-dark border px-2 py-1">${sh.role}</span></td>
                        <td class="fw-semibold text-muted">${sh.assignedBy}</td>
                        <td class="fw-bold text-primary">${sh.phone}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 shadow-sm fw-bold" onclick="toggleEditShiftForm(${index})">
                                <i class="fas fa-edit me-1"></i> Edit
                            </button>
                        </td>
                    </tr>
                `;
            });

            el.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-users-cog text-success me-2"></i> Hostel Staff Roster & Assignments</h5>
                    <button class="btn btn-sm btn-success fw-bold px-3 py-2 rounded-pill shadow-sm" onclick="toggleRosterForm()">
                        <i class="fas fa-plus me-1"></i> Assign Staff Shift
                    </button>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-4">
                        <div class="card border border-2 border-success rounded-4 p-4 h-100 shadow-sm" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);">
                            <h6 class="fw-bold text-success mb-3"><i class="fas fa-broadcast-tower me-1"></i> Active Supervisor on Duty</h6>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px; font-size: 1.6rem;">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Sadhu Adbhutanand Das</h6>
                                    <div class="small text-success fw-bold text-uppercase mb-1" style="letter-spacing: 0.5px;">Warden Desk (Block A)</div>
                                    <div class="small fw-semibold text-secondary"><i class="fas fa-phone-alt me-1"></i> +91 98250 12345</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-8">
                        <!-- Hidden roster form -->
                        <div class="card border border-2 border-success rounded-4 mb-4 p-4 shadow-sm d-none" id="rosterFormCard">
                            <h6 class="fw-bold text-success mb-3" id="rosterFormTitle"><i class="fas fa-calendar-day me-1"></i> Assign Hostel Staff Shift</h6>
                            <form onsubmit="event.preventDefault(); submitRosterShift();">
                                <input type="hidden" id="roster_edit_index" value="-1">
                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-bold">Day of Week</label>
                                        <select class="form-select" id="rst_day" required>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                            <option value="Saturday">Saturday</option>
                                            <option value="Sunday">Sunday</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-bold">Shift Timing / Task</label>
                                        <input type="text" class="form-control" id="rst_shift" placeholder="e.g. Day Shift (8AM - 8PM)" required>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-bold">Assigned Staff Name</label>
                                        <input type="text" class="form-control" id="rst_staff" placeholder="e.g. Ramesh Kumar" required>
                                    </div>
                                </div>
                                <div class="row g-3 mb-4">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-bold">Staff Role</label>
                                        <input type="text" class="form-control" id="rst_role" placeholder="e.g. Head Cook / Janitor" required>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-bold">Assigned By (Swami/Warden/Admin/Dean)</label>
                                        <select class="form-select" id="rst_assigned_by" required>
                                            <option value="Sadhu Adbhutanand Das">Sadhu Adbhutanand Das (Warden)</option>
                                            <option value="Sadhu Gyanprasad Das">Sadhu Gyanprasad Das (Warden)</option>
                                            <option value="Bhavik Patel">Bhavik Patel (Administrator)</option>
                                            <option value="Dr. Sadhu Gyaneswar Das">Dr. Sadhu Gyaneswar Das (Dean & Swami)</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label small fw-bold">Staff Contact Number</label>
                                        <input type="text" class="form-control" id="rst_phone" placeholder="e.g. +91 98250 54321" required>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-light border px-4 rounded-pill" onclick="toggleRosterForm()">Cancel</button>
                                    <button type="submit" class="btn btn-success px-4 rounded-pill">Save Assignment</button>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive bg-white shadow-sm rounded-4">
                            <table class="table table-hover border align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Day</th>
                                        <th>Shift / Duty</th>
                                        <th>Staff Member</th>
                                        <th>Staff Role</th>
                                        <th>Assigned By</th>
                                        <th>Contact</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${rosterRows}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;
        }
    };

    // Tab switching engine
    function activateHostelTab(tabName, btn) {
        // Remove active class from all buttons
        const btns = document.querySelectorAll('.hostel-menu-grid .action-btn');
        btns.forEach(b => b.classList.remove('active-hostel-tab'));
        
        // Add active class to clicked button
        btn.classList.add('active-hostel-tab');
        
        // Render panel content
        HostelHub.render(tabName);
    }

    // Room Allocation details Modal Trigger
    function openRoomDetails(roomId) {
        const room = HostelHub.data.rooms.find(r => r.id === roomId);
        if (!room) return;

        document.getElementById('roomDetailsTitle').innerText = `${room.block} - Room ${room.id} Occupancy`;
        document.getElementById('assign_room_id').value = room.id;
        document.getElementById('assign_student_name').value = '';

        const list = document.getElementById('roomOccupantsList');
        list.innerHTML = '';
        if (room.occupants.length === 0) {
            list.innerHTML = '<li class="list-group-item text-muted py-3 text-center">Room is currently empty</li>';
        } else {
            room.occupants.forEach((name, index) => {
                list.innerHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="fw-bold text-dark"><i class="fas fa-user-graduate me-2 text-secondary"></i> ${name}</span>
                        <button class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="removeRoomOccupant('${roomId}', ${index})">
                            <i class="fas fa-user-minus"></i>
                        </button>
                    </li>
                `;
            });
        }

        const modal = new bootstrap.Modal(document.getElementById('hostelRoomDetailsModal'));
        modal.show();
    }

    function submitRoomAssignment() {
        const roomId = document.getElementById('assign_room_id').value;
        const name = document.getElementById('assign_student_name').value;
        const room = HostelHub.data.rooms.find(r => r.id === roomId);
        if (!room) return;

        if (room.occupants.length >= room.capacity) {
            alert('This room is already at full capacity!');
            return;
        }

        room.occupants.push(name);
        HostelHub.save();
        
        // Close modal
        const modalEl = document.getElementById('hostelRoomDetailsModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        // Rerender allocation view
        HostelHub.render('room-allocation');
        if (typeof showBapsToast === 'function') showBapsToast(`Student ${name} allocated to Room ${roomId}! 🚪`, 'success');
    }

    function removeRoomOccupant(roomId, index) {
        const room = HostelHub.data.rooms.find(r => r.id === roomId);
        if (!room) return;
        const removedStudent = room.occupants.splice(index, 1);
        HostelHub.save();

        const modalEl = document.getElementById('hostelRoomDetailsModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        HostelHub.render('room-allocation');
        if (typeof showBapsToast === 'function') showBapsToast(`Student ${removedStudent} removed from Room ${roomId}!`, 'info');
    }

    // Mess Menu edit inline
    function editMenuMeal(day, meal, cellEl) {
        const originalText = cellEl.innerText;
        const newText = prompt(`Edit ${day} - ${meal.toUpperCase()}:`, originalText);
        if (newText !== null && newText.trim() !== '') {
            HostelHub.data.messMenu[day][meal] = newText.trim();
            HostelHub.save();
            cellEl.innerText = newText.trim();
            if (typeof showBapsToast === 'function') showBapsToast('Mess Menu saved successfully! 🍲', 'success');
        }
    }

    function resetMessMenuToDefault() {
        if (confirm('Are you sure you want to reset the Mess Menu to defaults?')) {
            HostelHub.seedDefaults();
            HostelHub.render('mess-menu');
            if (typeof showBapsToast === 'function') showBapsToast('Mess Menu reset to defaults!', 'info');
        }
    }

    // Attendance Logic
    function updateAttendanceStatus(index, status) {
        HostelHub.data.attendance[index].status = status;
        HostelHub.save();
        
        const badge = document.getElementById(`attn-status-${index}`);
        if (badge) {
            badge.innerText = status;
            badge.className = 'badge px-3 py-1 rounded-pill fw-bold';
            if (status === 'Present') badge.classList.add('bg-success');
            else if (status === 'Absent') badge.classList.add('bg-danger');
            else badge.classList.add('bg-warning', 'text-dark');
        }
        if (typeof showBapsToast === 'function') {
            showBapsToast(`${HostelHub.data.attendance[index].name} marked as ${status}! ✅`, 'success');
        }
    }

    function markAllAttendancePresent() {
        HostelHub.data.attendance.forEach(a => a.status = 'Present');
        HostelHub.save();
        HostelHub.render('night-attendance');
        if (typeof showBapsToast === 'function') showBapsToast('All student attendance marked as Present! ✅', 'success');
    }

    // Outpass Logic
    function toggleNewOutpassForm() {
        const card = document.getElementById('newOutpassFormCard');
        card.classList.toggle('d-none');
    }

    function submitNewOutpass() {
        const name = document.getElementById('op_student_name').value;
        const purpose = document.getElementById('op_purpose').value;
        const from = document.getElementById('op_from_date').value;
        const to = document.getElementById('op_to_date').value;

        const id = 'OUT-' + Math.floor(100 + Math.random() * 900);
        HostelHub.data.outpasses.unshift({ id, name, purpose, from, to, status: 'Pending' });
        HostelHub.save();
        
        toggleNewOutpassForm();
        HostelHub.render('outpass-approvals');
        if (typeof showBapsToast === 'function') showBapsToast('Emergency outpass request logged! 🎟️', 'success');
    }

    function updateOutpassStatus(index, status) {
        HostelHub.data.outpasses[index].status = status;
        HostelHub.save();
        HostelHub.render('outpass-approvals');
        if (typeof showBapsToast === 'function') showBapsToast(`Outpass ${status}!`, status === 'Approved' ? 'success' : 'error');
    }

    // Laundry Logic
    function toggleLaundryForm() {
        const card = document.getElementById('laundryFormCard');
        card.classList.toggle('d-none');
    }

    function submitLaundryBag() {
        const name = document.getElementById('lnd_student_name').value;
        const bag = document.getElementById('lnd_bag_code').value;
        const items = parseInt(document.getElementById('lnd_clothes_count').value);

        const id = 'LND-' + Math.floor(700 + Math.random() * 100);
        const today = new Date().toISOString().split('T')[0];

        HostelHub.data.laundry.unshift({ id, name, bag, items, date: today, status: 'Collected' });
        HostelHub.save();

        toggleLaundryForm();
        HostelHub.render('laundry-registry');
        if (typeof showBapsToast === 'function') showBapsToast('Laundry bag entry registered!', 'success');
    }

    function updateLaundryStatus(index, status) {
        HostelHub.data.laundry[index].status = status;
        HostelHub.save();
        HostelHub.render('laundry-registry');
        if (typeof showBapsToast === 'function') showBapsToast(`Laundry status updated to ${status}! 👕`, 'success');
    }

    // Complaint Logic
    function toggleComplaintForm() {
        const card = document.getElementById('complaintFormCard');
        card.classList.toggle('d-none');
    }

    function submitComplaint() {
        const name = document.getElementById('cmp_student_name').value;
        const room = document.getElementById('cmp_room_no').value;
        const category = document.getElementById('cmp_category').value;
        const severity = document.getElementById('cmp_severity').value;
        const desc = document.getElementById('cmp_desc').value;

        const id = 'CMP-' + Math.floor(500 + Math.random() * 100);
        HostelHub.data.complaints.unshift({ id, name, room, category, desc, severity, status: 'Open' });
        HostelHub.save();

        toggleComplaintForm();
        HostelHub.render('complaints');
        if (typeof showBapsToast === 'function') showBapsToast('Maintenance ticket lodged successfully!', 'success');
    }

    function resolveComplaint(index) {
        HostelHub.data.complaints[index].status = 'Resolved';
        HostelHub.save();
        HostelHub.render('complaints');
        if (typeof showBapsToast === 'function') showBapsToast('Complaint ticket marked as Resolved! 🔧', 'success');
    }

    // Fee Payments Logic
    function switchFeeSubTab(tabName) {
        HostelHub.currentFeeSubTab = tabName;
        HostelHub.render('fee-payments');
    }

    function selectStudentForPay(index) {
        const f = HostelHub.data.fees[index];
        if (!f) return;

        switchFeeSubTab('pay-form');
        setTimeout(() => {
            document.getElementById('pay_student_name').value = f.name;
            autoFillStudentDetails(f.name);
        }, 100);
    }

    function autoFillStudentDetails(studentName) {
        if (!studentName) return;
        const detailsMap = {
            'Vasant Patel': { enrollment: 'ENR-2026-001', room: 'Room 101, Block A' },
            'Smit Dave': { enrollment: 'ENR-2026-002', room: 'Room 101, Block A' },
            'Rahul Verma': { enrollment: 'ENR-2026-003', room: 'Room 101, Block A' },
            'Priya Sharma': { enrollment: 'ENR-2026-004', room: 'Room 102, Block A' }
        };
        const det = detailsMap[studentName];
        if (det) {
            document.getElementById('pay_enrollment').value = det.enrollment;
            document.getElementById('pay_room').value = det.room;
            
            // Find outstanding balance
            const feeRecord = HostelHub.data.fees.find(f => f.name === studentName);
            if (feeRecord) {
                const bal = feeRecord.amount - feeRecord.paid;
                document.getElementById('pay_base').value = (bal / 1.18).toFixed(2);
                calculateTotalInvoiceAmount();
            }
        }
    }

    function calculateTotalInvoiceAmount() {
        const base = parseFloat(document.getElementById('pay_base').value) || 0;
        const gst = parseFloat((base * 0.18).toFixed(2));
        const penalty = parseFloat(document.getElementById('pay_penalty').value) || 0;
        
        document.getElementById('pay_gst').value = gst;
        const total = base + gst + penalty;
        document.getElementById('total_bill_display').innerText = 'Total Invoice Amount: ₹ ' + total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        
        // Update QR code data
        const qrImg = document.getElementById('upi_qr_img');
        if (qrImg) {
            qrImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=130x130&data=upi://pay?pa=www.bhavikpatel110@oksbi%26pn=BAPS%2520Hostel%26am=` + total.toFixed(2);
        }
    }

    function togglePaymentWayFields(method) {
        document.getElementById('pay_details_upi').classList.add('d-none');
        document.getElementById('pay_details_card').classList.add('d-none');
        document.getElementById('pay_details_cash').classList.add('d-none');
        
        if (method === 'UPI/BHIM') {
            document.getElementById('pay_details_upi').classList.remove('d-none');
        } else if (method === 'Card') {
            document.getElementById('pay_details_card').classList.remove('d-none');
        } else if (method === 'Cash') {
            document.getElementById('pay_details_cash').classList.remove('d-none');
        }
    }

    function updateCashDenomSum() {
        const c500 = parseInt(document.getElementById('cash_500').value) || 0;
        const c200 = parseInt(document.getElementById('cash_200').value) || 0;
        const c100 = parseInt(document.getElementById('cash_100').value) || 0;
        const total = (c500 * 500) + (c200 * 200) + (c100 * 100);
        document.getElementById('cash_denom_sum').innerText = 'Total Breakdown: ₹ ' + total.toLocaleString('en-IN', { minimumFractionDigits: 2 });
        document.getElementById('cash_manual_total').value = total > 0 ? total.toFixed(2) : '';
    }

    function updateCashManualTotal() {
        const manualTotal = parseFloat(document.getElementById('cash_manual_total').value) || 0;
        document.getElementById('cash_denom_sum').innerText = 'Total Breakdown: ₹ ' + manualTotal.toLocaleString('en-IN', { minimumFractionDigits: 2 });
        if (manualTotal > 0) {
            const c500 = parseInt(document.getElementById('cash_500').value) || 0;
            const c200 = parseInt(document.getElementById('cash_200').value) || 0;
            const c100 = parseInt(document.getElementById('cash_100').value) || 0;
            const total = (c500 * 500) + (c200 * 200) + (c100 * 100);
            if (total !== manualTotal) {
                document.getElementById('cash_500').value = 0;
                document.getElementById('cash_200').value = 0;
                document.getElementById('cash_100').value = 0;
            }
        }
    }

    function submitRecordPaymentForm() {
        const name = document.getElementById('pay_student_name').value;
        const enrollment = document.getElementById('pay_enrollment').value;
        const room = document.getElementById('pay_room').value;
        const academic = document.getElementById('pay_academic').value;
        const category = document.getElementById('pay_category').value;
        const period = document.getElementById('pay_period').value;
        const base = parseFloat(document.getElementById('pay_base').value) || 0;
        const gst = parseFloat(document.getElementById('pay_gst').value) || 0;
        const penalty = parseFloat(document.getElementById('pay_penalty').value) || 0;
        const method = document.getElementById('pay_method').value;
        const reference = document.getElementById('pay_reference').value;
        const assignedBy = document.getElementById('pay_assigned_by').value;
        const remarks = document.getElementById('pay_remarks').value;

        const totalPay = base + gst + penalty;

        let cashDetails = '';
        if (method === 'Cash') {
            const c500 = parseInt(document.getElementById('cash_500').value) || 0;
            const c200 = parseInt(document.getElementById('cash_200').value) || 0;
            const c100 = parseInt(document.getElementById('cash_100').value) || 0;
            const manualTotal = parseFloat(document.getElementById('cash_manual_total').value) || 0;
            
            if (manualTotal > 0 && (c500 === 0 && c200 === 0 && c100 === 0)) {
                cashDetails = `Manual cash entry: ₹ ${manualTotal.toFixed(2)}`;
            } else {
                const totalCalculated = (c500 * 500) + (c200 * 200) + (c100 * 100);
                cashDetails = `Denominations: ₹500x${c500}, ₹200x${c200}, ₹100x${c100} (Total: ₹ ${totalCalculated.toFixed(2)})`;
            }
        }

        // Log transaction
        const today = new Date().toISOString().split('T')[0];
        if (!HostelHub.data.feeTransactions) HostelHub.data.feeTransactions = [];
        HostelHub.data.feeTransactions.unshift({
            name, enrollment, room, academicYear: academic, category, period,
            baseAmount: base, gst, penalty, method, reference, assignedBy, remarks, date: today,
            cashDetails: cashDetails
        });

        // Update student due balances in ledger
        const feeRecord = HostelHub.data.fees.find(f => f.name === name);
        if (feeRecord) {
            feeRecord.paid += totalPay;
            if (feeRecord.paid >= feeRecord.amount) {
                feeRecord.status = 'Paid';
            } else {
                feeRecord.status = 'Partial';
            }
        }

        HostelHub.save();
        HostelHub.render('fee-payments');
        if (typeof showBapsToast === 'function') showBapsToast('Fee payment recorded and invoice compiled! 🧾', 'success');
    }

    // AI Bill Builder logic
    function runAiBillingBuilder() {
        const promptText = document.getElementById('ai_billing_prompt').value;
        if (!promptText.trim()) {
            alert('Please type billing instructions first.');
            return;
        }

        const spin = document.getElementById('ai_spin_icon');
        const brain = document.getElementById('ai_brain_icon');
        const progressCard = document.getElementById('ai_billing_progress');
        const progressBar = progressCard.querySelector('.progress-bar');

        spin.style.display = 'inline-block';
        brain.style.display = 'none';
        progressCard.classList.remove('d-none');
        progressBar.style.width = '0%';

        let width = 0;
        const interval = setInterval(() => {
            width += 20;
            progressBar.style.width = width + '%';
            if (width >= 100) {
                clearInterval(interval);
                
                // Parse prompt details
                let parsedName = '';
                if (/vasant/i.test(promptText)) parsedName = 'Vasant Patel';
                else if (/smit/i.test(promptText)) parsedName = 'Smit Dave';
                else if (/rahul/i.test(promptText)) parsedName = 'Rahul Verma';
                else if (/priya/i.test(promptText)) parsedName = 'Priya Sharma';

                let parsedCategory = 'Rent';
                if (/mess|food/i.test(promptText)) parsedCategory = 'Mess';
                else if (/laundry/i.test(promptText)) parsedCategory = 'Laundry';
                else if (/gym/i.test(promptText)) parsedCategory = 'Gym';
                else if (/electricity/i.test(promptText)) parsedCategory = 'Electricity';
                else if (/penalty|fine/i.test(promptText)) parsedCategory = 'Penalty';

                let parsedPeriod = 'Q1 - July to Sept';
                if (/q2/i.test(promptText)) parsedPeriod = 'Q2 - Oct to Dec';
                else if (/q3/i.test(promptText)) parsedPeriod = 'Q3 - Jan to Mar';
                else if (/q4/i.test(promptText)) parsedPeriod = 'Q4 - Apr to June';
                else if (/annual/i.test(promptText)) parsedPeriod = 'Annual Fee';

                let parsedBase = 15000;
                const baseMatch = promptText.match(/(?:rent|fee|amount|of|₹|\$)\s*(\d+)/i) || promptText.match(/(\d+)\s*(?:rupees|rs)/i);
                if (baseMatch) {
                    parsedBase = parseFloat(baseMatch[1]);
                }

                let parsedPenalty = 0;
                const penaltyMatch = promptText.match(/(?:fine|penalty|late)\s*(?:of|is)?\s*(\d+)/i);
                if (penaltyMatch) {
                    parsedPenalty = parseFloat(penaltyMatch[1]);
                }

                let parsedMethod = 'UPI/BHIM';
                if (/card/i.test(promptText)) parsedMethod = 'Card';
                else if (/cash/i.test(promptText)) parsedMethod = 'Cash';

                // Update form fields
                switchFeeSubTab('pay-form');
                
                if (parsedName) {
                    document.getElementById('pay_student_name').value = parsedName;
                    autoFillStudentDetails(parsedName);
                }
                
                document.getElementById('pay_category').value = parsedCategory;
                document.getElementById('pay_period').value = parsedPeriod;
                document.getElementById('pay_base').value = parsedBase.toFixed(2);
                document.getElementById('pay_penalty').value = parsedPenalty.toFixed(2);
                document.getElementById('pay_method').value = parsedMethod;
                document.getElementById('pay_reference').value = 'AI-' + Math.floor(100000 + Math.random() * 900000);
                
                togglePaymentWayFields(parsedMethod);
                calculateTotalInvoiceAmount();

                spin.style.display = 'none';
                brain.style.display = 'inline-block';
                progressCard.classList.add('d-none');
                document.getElementById('ai_billing_prompt').value = '';

                if (typeof showBapsToast === 'function') {
                    showBapsToast('AI Compiled: Form populated for ' + (parsedName || 'Selected Student') + '! 🤖', 'success');
                }
            }
        }, 300);
    }

    // Invoice receipt modal generator
    function printInvoiceReceipt(idx) {
        const t = HostelHub.data.feeTransactions[idx];
        if (!t) return;

        const base = parseFloat(t.baseAmount);
        const gst = parseFloat(t.gst);
        const penalty = parseFloat(t.penalty);
        const total = base + gst + penalty;

        let cashDetailsHtml = '';
        if (t.method === 'Cash' && t.cashDetails) {
            cashDetailsHtml = `<div class="col-12 mt-2 border-top pt-2"><strong>Cash Count Details:</strong><br><span class="small text-secondary font-monospace">${t.cashDetails}</span></div>`;
        }

        const modalContent = document.getElementById('receiptModalContent');
        modalContent.innerHTML = `
            <div class="modal-header bg-dark text-white border-0 p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-invoice-dollar me-2 text-warning"></i> Itemized Fee Invoice Receipt</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="printableReceiptArea">
                <div class="text-center mb-4">
                    <img src="/img/baps_logo.png" alt="BAPS" style="height: 60px;" onerror="this.src='https://placehold.co/80x80/f97316/white?text=BAPS'">
                    <h5 class="fw-bold text-dark mt-2 mb-1">BAPS SWAMINARAYAN HOSTEL</h5>
                    <div class="small text-muted font-monospace">receipt ID: ${t.reference} | Date: ${t.date}</div>
                </div>

                <div class="row g-2 mb-4 border-top border-bottom py-3">
                    <div class="col-6"><strong>Student Name:</strong><br>${t.name}</div>
                    <div class="col-6 text-end"><strong>Enrollment No:</strong><br><span class="font-monospace">${t.enrollment}</span></div>
                    <div class="col-6 mt-2"><strong>Room details:</strong><br>${t.room}</div>
                    <div class="col-6 text-end mt-2"><strong>Academic Term:</strong><br>${t.academicYear}</div>
                </div>

                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-list me-1"></i> Cost Breakdown</h6>
                <table class="table table-sm table-borderless mb-4">
                    <tbody>
                        <tr>
                            <td>${t.category} Fee Base (${t.period})</td>
                            <td class="text-end font-monospace">₹ ${base.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</td>
                        </tr>
                        <tr>
                            <td>GST / Taxes (18% Flat Rate)</td>
                            <td class="text-end font-monospace text-muted">₹ ${gst.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</td>
                        </tr>
                        <tr>
                            <td>Late fee Penalty Fine</td>
                            <td class="text-end font-monospace text-danger">₹ ${penalty.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</td>
                        </tr>
                        <tr class="border-top fw-bold text-dark fs-5">
                            <td>Grand Total Received</td>
                            <td class="text-end font-monospace text-success">₹ ${total.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="row g-2 mb-4 bg-light p-3 rounded-3 border">
                    <div class="col-6"><strong>Payment Mode:</strong><br><span class="badge bg-primary px-3 py-1 mt-1 text-uppercase">${t.method}</span></div>
                    <div class="col-6 text-end"><strong>Assigned / Approved By:</strong><br><span class="small text-muted fw-bold">${t.assignedBy}</span></div>
                    <div class="col-12 mt-2"><strong>Remarks:</strong><br><span class="small text-secondary">${t.remarks || 'None'}</span></div>
                    ${cashDetailsHtml}
                </div>

                <div class="row text-center mt-5 pt-3 border-top g-2">
                    <div class="col-3">
                        <div style="height: 40px; font-family: 'Brush Script MT', cursive, sans-serif; font-size: 15px; color: #3b82f6;" class="fw-bold pt-2">Bhavik Patel</div>
                        <div class="border-top small text-muted pt-1" style="font-size: 0.7rem; font-weight: 600;">Admin Sign</div>
                    </div>
                    <div class="col-3">
                        <div style="height: 40px; font-family: 'Brush Script MT', cursive, sans-serif; font-size: 15px; color: #10b981;" class="fw-bold pt-2">Sadhu Adb.</div>
                        <div class="border-top small text-muted pt-1" style="font-size: 0.7rem; font-weight: 600;">Warden Sign</div>
                    </div>
                    <div class="col-3">
                        <div style="height: 40px; font-family: 'Brush Script MT', cursive, sans-serif; font-size: 15px; color: #f97316;" class="fw-bold pt-2">S. Gyaneswar</div>
                        <div class="border-top small text-muted pt-1" style="font-size: 0.7rem; font-weight: 600;">Swami / Dean Sign</div>
                    </div>
                    <div class="col-3">
                        <div style="height: 40px; font-family: 'Brush Script MT', cursive, sans-serif; font-size: 15px; color: #64748b;" class="fw-bold pt-2">${t.name ? t.name.split(' ')[0] : ''}</div>
                        <div class="border-top small text-muted pt-1" style="font-size: 0.7rem; font-weight: 600;">Student Sign</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 bg-light p-3">
                <button type="button" class="btn btn-outline-dark px-4 rounded-pill shadow-sm" onclick="window.print()"><i class="fas fa-print me-1"></i> Print / Save PDF</button>
                <button type="button" class="btn btn-dark px-4 rounded-pill" data-bs-dismiss="modal">Close</button>
            </div>
        `;
        const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
        modal.show();
    }

    function runPenaltyFineSweep() {
        const amtInput = document.getElementById('fine_sweep_amount');
        if (!amtInput) return;
        const penalty = parseFloat(amtInput.value) || 0;
        if (penalty <= 0) {
            alert('Please enter a valid fine amount!');
            return;
        }

        if (confirm(`Are you sure you want to apply a flat late fee of ₹ ${penalty} to all Unpaid and Partially paid students?`)) {
            let count = 0;
            HostelHub.data.fees.forEach(f => {
                if (f.status === 'Unpaid' || f.status === 'Partial') {
                    f.amount += penalty;
                    f.status = 'Partial';
                    count++;
                }
            });

            if (count > 0) {
                HostelHub.save();
                HostelHub.render('fee-payments');
                if (typeof showBapsToast === 'function') {
                    showBapsToast(`Applied ₹ ${penalty} late fine to ${count} student accounts! ⚖️`, 'warning');
                }
            } else {
                alert('No outstanding accounts found to apply fines.');
            }
        }
    }

    function approveWaiver(index) {
        const w = HostelHub.data.waivers[index];
        if (!w) return;

        if (confirm(`Approve ${w.discountPercent}% fee waiver for ${w.name}?`)) {
            w.status = 'Approved';

            const feeRecord = HostelHub.data.fees.find(f => f.name === w.name);
            if (feeRecord) {
                const discount = (feeRecord.amount * w.discountPercent) / 100;
                feeRecord.amount -= discount;
                if (feeRecord.paid >= feeRecord.amount) feeRecord.status = 'Paid';
                else feeRecord.status = 'Partial';
            }

            HostelHub.save();
            HostelHub.render('fee-payments');
            if (typeof showBapsToast === 'function') {
                showBapsToast(`Approved ${w.discountPercent}% fee waiver for ${w.name}! 🤝`, 'success');
            }
        }
    }

    // Inventory Logic
    function toggleInventoryForm() {
        const card = document.getElementById('inventoryFormCard');
        card.classList.toggle('d-none');
    }

    function submitInventoryItem() {
        const code = document.getElementById('inv_code').value;
        const name = document.getElementById('inv_name').value;
        const category = document.getElementById('inv_category').value;
        const total = parseInt(document.getElementById('inv_qty').value);

        HostelHub.data.inventory.push({ code, name, category, total, use: 0, damaged: 0 });
        HostelHub.save();

        toggleInventoryForm();
        HostelHub.render('inventory');
        if (typeof showBapsToast === 'function') showBapsToast('New inventory item type logged!', 'success');
    }

    function adjustInventoryDamaged(index, amount) {
        const item = HostelHub.data.inventory[index];
        const available = item.total - item.use - item.damaged;
        if (available < amount) {
            alert('Not enough items in stockroom to mark as damaged!');
            return;
        }
        item.damaged += amount;
        HostelHub.save();
        HostelHub.render('inventory');
        if (typeof showBapsToast === 'function') showBapsToast('Inventory damage logged! ⚠️', 'warning');
    }

    function adjustInventoryTotal(index, amount) {
        HostelHub.data.inventory[index].total += amount;
        HostelHub.save();
        HostelHub.render('inventory');
        if (typeof showBapsToast === 'function') showBapsToast('Restock logs compiled! 📦', 'success');
    }

    // Cleaning Schedule Logic
    function toggleCleaningForm() {
        const card = document.getElementById('cleaningFormCard');
        card.classList.toggle('d-none');
    }

    function submitCleaningTask() {
        const zone = document.getElementById('cln_zone').value;
        const janitor = document.getElementById('cln_janitor').value;
        const frequency = document.getElementById('cln_freq').value;
        const now = new Date().toISOString().slice(0, 16).replace('T', ' ');

        HostelHub.data.cleaning.unshift({ zone, frequency, janitor, status: 'Pending', date: now });
        HostelHub.save();

        toggleCleaningForm();
        HostelHub.render('cleaning-schedule');
        if (typeof showBapsToast === 'function') showBapsToast('Cleaning shift directive dispatched!', 'success');
    }

    function completeCleaning(index) {
        const now = new Date().toISOString().slice(0, 16).replace('T', ' ');
        HostelHub.data.cleaning[index].status = 'Completed';
        HostelHub.data.cleaning[index].date = now;
        HostelHub.save();
        HostelHub.render('cleaning-schedule');
        if (typeof showBapsToast === 'function') showBapsToast('Janitor cleaning verified! 🧼', 'success');
    }

    // Visitor Logic
    function toggleVisitorForm() {
        const card = document.getElementById('visitorFormCard');
        card.classList.toggle('d-none');
    }

    function submitVisitor() {
        const name = document.getElementById('vis_name').value;
        const relation = document.getElementById('vis_relation').value;
        const student = document.getElementById('vis_student').value;
        const room = document.getElementById('vis_room').value;
        
        const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const dateStr = new Date().toISOString().split('T')[0] + ' ' + now;

        const id = 'VIS-' + Math.floor(900 + Math.random() * 99);
        HostelHub.data.visitors.unshift({ id, name, relation, student, room, in: dateStr, out: null });
        HostelHub.save();

        toggleVisitorForm();
        HostelHub.render('visitor-log');
        if (typeof showBapsToast === 'function') showBapsToast('Visitor entry logged! 🚪', 'success');
    }

    function checkoutVisitor(index) {
        const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const dateStr = new Date().toISOString().split('T')[0] + ' ' + now;

        HostelHub.data.visitors[index].out = dateStr;
        HostelHub.save();
        HostelHub.render('visitor-log');
        if (typeof showBapsToast === 'function') showBapsToast('Visitor checkout logged successfully!', 'info');
    }

    // Disciplinary Logic
    function toggleDisciplineForm() {
        const card = document.getElementById('disciplineFormCard');
        card.classList.toggle('d-none');
    }

    function submitDisciplinaryIncident() {
        const name = document.getElementById('dsp_student_name').value;
        const severity = document.getElementById('dsp_severity').value;
        const action = document.getElementById('dsp_action').value;
        const incident = document.getElementById('dsp_incident').value;
        const today = new Date().toISOString().split('T')[0];

        HostelHub.data.discipline.unshift({ name, date: today, incident, severity, action, status: 'Active' });
        HostelHub.save();

        toggleDisciplineForm();
        HostelHub.render('disciplinary-log');
        if (typeof showBapsToast === 'function') showBapsToast('Rules infraction recorded! ⚖️', 'warning');
    }

    function resolveDisciplinaryCase(index) {
        HostelHub.data.discipline[index].status = 'Resolved';
        HostelHub.save();
        HostelHub.render('disciplinary-log');
        if (typeof showBapsToast === 'function') showBapsToast('Disciplinary infraction signed off and closed.', 'success');
    }

    // Event Calendar Logic
    function toggleEventForm() {
        const card = document.getElementById('eventFormCard');
        card.classList.toggle('d-none');
    }

    function submitHostelEvent() {
        const name = document.getElementById('ev_title').value;
        const coordinator = document.getElementById('ev_coordinator').value;
        const date = document.getElementById('ev_date').value;
        const venue = document.getElementById('ev_venue').value;

        HostelHub.data.events.push({ name, date, venue, coordinator });
        HostelHub.save();

        toggleEventForm();
        HostelHub.render('event-calendar');
        if (typeof showBapsToast === 'function') showBapsToast('New event scheduled on hostel board! 📅', 'success');
    }

    function deleteHostelEvent(index) {
        if (confirm('Delete this event?')) {
            HostelHub.data.events.splice(index, 1);
            HostelHub.save();
            HostelHub.render('event-calendar');
            if (typeof showBapsToast === 'function') showBapsToast('Event cancelled.', 'info');
        }
    }

    // Mess Feedback Logic
    function toggleFeedbackForm() {
        const card = document.getElementById('feedbackFormCard');
        card.classList.toggle('d-none');
    }

    function setStarRating(score) {
        HostelHub.setStarState(score);
    }

    function submitFeedback() {
        const name = document.getElementById('fb_student_name').value;
        const category = document.getElementById('fb_category').value;
        const rating = parseInt(document.getElementById('fb_rating_score').value);
        const comment = document.getElementById('fb_comment').value;
        const today = new Date().toISOString().split('T')[0];

        HostelHub.data.feedback.unshift({ name, rating, category, comment, date: today });
        HostelHub.save();

        toggleFeedbackForm();
        HostelHub.render('mess-feedback');
        if (typeof showBapsToast === 'function') showBapsToast('Mess feedback registered! Thank you 🍛', 'success');
    }

    // Hostel Staff Roster Logic
    function toggleRosterForm() {
        const card = document.getElementById('rosterFormCard');
        if (card) {
            card.classList.toggle('d-none');
            document.getElementById('rosterFormTitle').innerHTML = '<i class="fas fa-calendar-day me-1"></i> Assign Hostel Staff Shift';
            document.getElementById('roster_edit_index').value = '-1';
        }
    }

    function submitRosterShift() {
        const day = document.getElementById('rst_day').value;
        const shift = document.getElementById('rst_shift').value;
        const staff = document.getElementById('rst_staff').value;
        const role = document.getElementById('rst_role').value;
        const assignedBy = document.getElementById('rst_assigned_by').value;
        const phone = document.getElementById('rst_phone').value;
        const editIndex = parseInt(document.getElementById('roster_edit_index').value);

        if (editIndex >= 0) {
            HostelHub.data.roster[editIndex] = { day, shift, staff, role, assignedBy, phone };
        } else {
            HostelHub.data.roster.push({ day, shift, staff, role, assignedBy, phone });
        }
        
        HostelHub.save();
        toggleRosterForm();
        HostelHub.render('hostel-staff-roster');
        if (typeof showBapsToast === 'function') showBapsToast('Staff Duty Roster saved successfully! 🗓️', 'success');
    }

    function toggleEditShiftForm(index) {
        toggleRosterForm();
        const sh = HostelHub.data.roster[index];
        document.getElementById('rosterFormTitle').innerHTML = '<i class="fas fa-edit me-1"></i> Edit Shift Assignment';
        document.getElementById('roster_edit_index').value = index;

        document.getElementById('rst_day').value = sh.day;
        document.getElementById('rst_shift').value = sh.shift;
        document.getElementById('rst_staff').value = sh.staff;
        document.getElementById('rst_role').value = sh.role;
        document.getElementById('rst_assigned_by').value = sh.assignedBy;
        document.getElementById('rst_phone').value = sh.phone;
    }

    // Initialize on document ready
    document.addEventListener('DOMContentLoaded', function() {
        HostelHub.init();
    });
</script>
