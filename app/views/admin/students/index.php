<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <span>ROOT</span>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-on-surface">STUDENTS</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">STUDENT ACCOUNTS</h2>
    <button onclick="openModal('create-student-modal')" class="bg-primary text-on-surface border-2 border-on-surface px-4 py-2 font-button-text hover:bg-surface-container active:translate-y-1 pixel-shadow-sm uppercase flex items-center gap-2">
        <span class="material-symbols-outlined">person_add</span> ADD STUDENT
    </button>
</div>

<?php if (!empty($success)): ?>
    <div class="bg-[#9aed83] border-2 border-on-surface p-4 mb-8 font-mono font-bold pixel-shadow-sm text-on-surface">
        <div class="uppercase mb-2 text-lg"><?php echo htmlspecialchars($success); ?></div>
        <?php if (!empty($generated_password)): ?>
            <div class="bg-black text-primary p-4 text-center text-xl mt-4">
                PASSWORD: <?php echo htmlspecialchars($generated_password); ?>
            </div>
            <p class="text-xs text-[#1e6d12] mt-2 uppercase">Please copy this password now. It will not be shown again.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="bg-[#9aed83] border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        <?php
            if ($_GET['success'] === 'updated') echo "Student updated successfully.";
            elseif ($_GET['success'] === 'status') echo "Student status updated.";
            elseif ($_GET['success'] === 'points_added') echo "Points successfully added to student.";
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="bg-error-container border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        <?php
            if ($_GET['error'] === 'invalid') echo "Invalid request.";
            elseif ($_GET['error'] === 'invalid_points') echo "Points must be greater than zero.";
            else echo "Something went wrong. Please try again.";
        ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="bg-error-container border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<section class="bg-surface-container-lowest border-2 border-on-surface pixel-shadow">
    <div class="p-4 border-b-4 border-on-surface flex flex-wrap gap-4 justify-between items-center bg-surface-container-low">
        <h2 class="font-h2 text-on-surface uppercase font-black">ALL STUDENTS</h2>
        <div class="flex items-center gap-2">
            <label class="text-[10px] uppercase font-black text-on-surface">Search</label>
            <input id="student-search" type="text" placeholder="Name, ID, email" class="border-2 border-on-surface p-2 text-xs uppercase bg-white focus:outline-none focus:border-primary">
        </div>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($students)): ?>
            <div class="p-6 font-mono text-sm uppercase">No students found.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-surface-container border-b-4 border-on-surface">
                    <tr>
                        <th class="p-4 uppercase font-black">STUDENT</th>
                        <th class="p-4 uppercase font-black">STUDENT ID</th>
                        <th class="p-4 uppercase font-black">YEAR LEVEL</th>
                        <th class="p-4 uppercase font-black">EMAIL</th>
                        <th class="p-4 uppercase font-black">TOTAL POINTS</th>
                        <th class="p-4 uppercase font-black">STATUS</th>
                        <th class="p-4 uppercase font-black">CREATED</th>
                        <th class="p-4 uppercase font-black text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="students-table-body">
                    <?php foreach ($students as $student): ?>
                        <?php
                            $studentSearch = strtolower(
                                $student['full_name'] . ' ' .
                                ($student['student_id'] ?? '') . ' ' .
                                ($student['email'] ?? '') . ' ' .
                                ($student['year_level'] ?? '')
                            );
                        ?>
                        <tr class="border-b-2 border-outline-variant hover:bg-surface-container-low" data-student-row data-search="<?php echo htmlspecialchars($studentSearch); ?>">
                            <td class="p-4 font-bold text-on-surface uppercase"><?php echo htmlspecialchars($student['full_name']); ?></td>
                            <td class="p-4 uppercase font-bold text-secondary"><?php echo htmlspecialchars($student['student_id'] ?? '-'); ?></td>
                            <td class="p-4 uppercase font-bold text-secondary"><?php echo htmlspecialchars($student['year_level'] ?? '-'); ?></td>
                            <td class="p-4 lowercase"><?php echo htmlspecialchars($student['email']); ?></td>
                            <td class="p-4 text-primary font-black"><?php echo htmlspecialchars($student['total_points']); ?></td>
                            <td class="p-4">
                                <?php if ((int) $student['is_active'] === 0): ?>
                                    <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase bg-error-container text-error">DEACTIVATED</span>
                                <?php elseif ((int) $student['must_change_password'] === 1): ?>
                                    <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase bg-[#ffd54f] text-on-surface" title="Must change password">PENDING_PW</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase bg-[#9aed83] text-[#1e6d12]">ACTIVE</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 uppercase"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($student['created_at']))); ?></td>
                            <td class="p-4 text-right">
                                <button onclick="openAddPointsModal(<?php echo htmlspecialchars(json_encode($student)); ?>)" class="text-[#1e6d12] hover:text-on-surface mx-2" title="Add Points">
                                    <span class="material-symbols-outlined">stars</span>
                                </button>
                                <button onclick="openEditStudentModal(<?php echo htmlspecialchars(json_encode($student)); ?>)" class="text-primary hover:text-on-surface mx-2" title="Edit Student">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <?php
                                    $passwordAction = (int) $student['must_change_password'] === 1 ? 'reroll' : 'reset';
                                    $passwordTitle = $passwordAction === 'reroll' ? 'Reroll Temporary Password' : 'Reset Password';
                                ?>
                                <button type="button" onclick="openPasswordResetModal(<?php echo htmlspecialchars(json_encode($student)); ?>, '<?php echo htmlspecialchars($passwordAction); ?>')" class="text-[#b45309] hover:text-on-surface mx-2" title="<?php echo htmlspecialchars($passwordTitle); ?>">
                                    <span class="material-symbols-outlined">lock_reset</span>
                                </button>
                                <form method="post" action="<?php echo BASE_URL; ?>/admin/toggleStudentStatus" class="inline-block" onsubmit="return confirm('Update this student status?');">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($student['user_id']); ?>">
                                    <input type="hidden" name="is_active" value="<?php echo (int) $student['is_active'] === 1 ? 0 : 1; ?>">
                                    <button type="submit" class="text-error hover:text-on-surface mx-2" title="Toggle Status">
                                        <span class="material-symbols-outlined">power_settings_new</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr id="students-empty-row" class="hidden">
                        <td colspan="8" class="p-6 font-mono text-sm uppercase">No matching students.</td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<!-- Create Student Dialog -->
<dialog id="create-student-modal" class="bg-surface-container-lowest border-2 border-on-surface p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-lg w-full backdrop:bg-black/60">
    <div class="bg-[#1e6d12] border-b-4 border-on-surface p-4 flex justify-between items-center">
        <h2 class="font-h2 text-white uppercase">ADD NEW STUDENT</h2>
        <button onclick="closeModal('create-student-modal')" class="text-white hover:text-zinc-200"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="<?php echo BASE_URL; ?>/admin/createStudent" class="p-6 flex flex-col gap-4 font-mono">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">FULL NAME</label>
            <input type="text" name="full_name" class="border-2 border-on-surface p-2 focus:outline-none focus:border-[#1e6d12]" value="<?php echo htmlspecialchars($old['full_name'] ?? ''); ?>" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">STUDENT ID</label>
            <input type="text" name="student_id" placeholder="e.g. 241c-1234" class="border-2 border-on-surface p-2 focus:outline-none focus:border-[#1e6d12]" value="<?php echo htmlspecialchars($old['student_id'] ?? ''); ?>" required>
            <p class="text-[10px] text-on-surface-variant">Format: XXXX-XXXX (e.g. 241c-1234)</p>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">YEAR LEVEL</label>
            <select name="year_level" class="border-2 border-on-surface p-2 focus:outline-none focus:border-[#1e6d12]" required>
                <option value="">SELECT YEAR</option>
                <option value="1" <?php echo ($old['year_level'] ?? '') === '1' ? 'selected' : ''; ?>>1</option>
                <option value="2" <?php echo ($old['year_level'] ?? '') === '2' ? 'selected' : ''; ?>>2</option>
                <option value="3" <?php echo ($old['year_level'] ?? '') === '3' ? 'selected' : ''; ?>>3</option>
                <option value="4" <?php echo ($old['year_level'] ?? '') === '4' ? 'selected' : ''; ?>>4</option>
            </select>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">EMAIL</label>
            <input type="email" name="email" class="border-2 border-on-surface p-2 focus:outline-none focus:border-[#1e6d12]" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
        </div>
        <div class="p-4 bg-surface-container border-2 border-on-surface mt-2">
            <p class="text-[10px] uppercase text-center font-bold">A temporary password will be generated automatically upon account creation.</p>
        </div>
        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('create-student-modal')" class="flex-1 bg-zinc-200 border-2 border-on-surface p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-[#1e6d12] text-white border-2 border-on-surface p-3 font-button-text hover:bg-[#154d0d]">CREATE ACCOUNT</button>
        </div>
    </form>
</dialog>

<!-- Edit Student Dialog -->
<dialog id="edit-student-modal" class="bg-surface-container-lowest border-2 border-on-surface p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-lg w-full backdrop:bg-black/60">
    <div class="bg-primary border-b-4 border-on-surface p-4 flex justify-between items-center">
        <h2 class="font-h2 text-on-surface uppercase">EDIT STUDENT</h2>
        <button onclick="closeModal('edit-student-modal')" class="text-on-surface hover:text-white"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="<?php echo BASE_URL; ?>/admin/editStudent" class="p-6 flex flex-col gap-4 font-mono">
        <input type="hidden" name="user_id" id="edit-student-id">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">FULL NAME</label>
            <input type="text" name="full_name" id="edit-student-name" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">STUDENT ID</label>
            <input type="text" name="student_id" id="edit-student-id-value" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">YEAR LEVEL</label>
            <select name="year_level" id="edit-student-year" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">EMAIL</label>
            <input type="email" name="email" id="edit-student-email" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">STATUS</label>
            <select name="is_active" id="edit-student-status" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary">
                <option value="1">ACTIVE</option>
                <option value="0">DEACTIVATED</option>
            </select>
        </div>
        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('edit-student-modal')" class="flex-1 bg-zinc-200 border-2 border-on-surface p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-[#9aed83] border-2 border-on-surface p-3 font-button-text hover:bg-tertiary-fixed">UPDATE</button>
        </div>
    </form>
</dialog>

<!-- Add Points Dialog -->
<dialog id="add-points-modal" class="bg-surface-container-lowest border-2 border-on-surface p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-lg w-full backdrop:bg-black/60">
    <div class="bg-[#1e6d12] border-b-4 border-on-surface p-4 flex justify-between items-center">
        <h2 class="font-h2 text-white uppercase">ADD POINTS</h2>
        <button onclick="closeModal('add-points-modal')" class="text-white hover:text-zinc-200"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="<?php echo BASE_URL; ?>/admin/addPoints" class="p-6 flex flex-col gap-4 font-mono">
        <input type="hidden" name="user_id" id="add-points-user-id">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">STUDENT</label>
            <input type="text" id="add-points-student-name" class="border-2 border-on-surface p-2 bg-surface-container text-on-surface-variant font-bold" readonly>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">POINTS TO ADD</label>
            <input type="number" name="points" min="1" class="border-2 border-on-surface p-2 focus:outline-none focus:border-[#1e6d12] font-black text-primary text-xl" required>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">REASON / NOTE</label>
            <textarea name="reason" rows="2" class="border-2 border-on-surface p-2 focus:outline-none focus:border-[#1e6d12] resize-none" placeholder="e.g. Exceptional class participation..." required></textarea>
        </div>
        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('add-points-modal')" class="flex-1 bg-zinc-200 border-2 border-on-surface p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-[#1e6d12] text-white border-2 border-on-surface p-3 font-button-text hover:bg-[#154d0d]">GIVE POINTS</button>
        </div>
    </form>
</dialog>

<!-- Password Reset Dialog -->
<dialog id="password-reset-modal" class="bg-surface-container-lowest border-2 border-on-surface p-0 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] max-w-lg w-full backdrop:bg-black/60">
    <div class="bg-[#b45309] border-b-4 border-on-surface p-4 flex justify-between items-center">
        <h2 class="font-h2 text-white uppercase" id="password-reset-title">RESET PASSWORD</h2>
        <button onclick="closeModal('password-reset-modal')" class="text-white hover:text-zinc-200"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="<?php echo BASE_URL; ?>/admin/resetStudentPassword" class="p-6 flex flex-col gap-4 font-mono">
        <input type="hidden" name="user_id" id="password-reset-user-id">
        <input type="hidden" name="mode" id="password-reset-mode">
        <p class="text-xs uppercase font-bold" id="password-reset-message">Reset this student password and generate a temporary password?</p>
        <div class="border-2 border-on-surface bg-surface-container p-3 text-xs uppercase font-bold" id="password-reset-student"></div>
        <div class="flex gap-4 mt-2">
            <button type="button" onclick="closeModal('password-reset-modal')" class="flex-1 bg-zinc-200 border-2 border-on-surface p-3 font-button-text hover:bg-zinc-300">CANCEL</button>
            <button type="submit" class="flex-1 bg-[#b45309] text-white border-2 border-on-surface p-3 font-button-text hover:bg-[#9a4208]">CONFIRM</button>
        </div>
    </form>
</dialog>

<script>
    function openEditStudentModal(student) {
        document.getElementById('edit-student-id').value = student.user_id;
        document.getElementById('edit-student-name').value = student.full_name;
        document.getElementById('edit-student-id-value').value = student.student_id || '';
        document.getElementById('edit-student-email').value = student.email;
        document.getElementById('edit-student-year').value = student.year_level || '1';
        document.getElementById('edit-student-status').value = Number(student.is_active) === 1 ? '1' : '0';

        document.querySelector('#edit-student-modal form').action = "<?php echo BASE_URL; ?>/admin/editStudent/" + student.user_id;
        openModal('edit-student-modal');
    }

    function openAddPointsModal(student) {
        document.getElementById('add-points-user-id').value = student.user_id;
        document.getElementById('add-points-student-name').value = student.full_name + " (" + (student.student_id || "N/A") + ")";
        openModal('add-points-modal');
    }

    function openPasswordResetModal(student, mode) {
        var isReroll = mode === 'reroll';
        document.getElementById('password-reset-user-id').value = student.user_id;
        document.getElementById('password-reset-mode').value = mode;
        document.getElementById('password-reset-title').textContent = isReroll ? 'REROLL TEMP PASSWORD' : 'RESET PASSWORD';
        document.getElementById('password-reset-message').textContent = isReroll
            ? 'Generate a new temporary password? This replaces the previous one.'
            : 'Reset this student password and generate a temporary password?';
        document.getElementById('password-reset-student').textContent = student.full_name + " (" + (student.student_id || "N/A") + ")";
        openModal('password-reset-modal');
    }

    function applyStudentFilter() {
        var searchInput = document.getElementById('student-search');
        if (!searchInput) {
            return;
        }
        var query = searchInput.value.trim().toLowerCase();
        var rows = document.querySelectorAll('#students-table-body tr[data-student-row]');
        var visibleCount = 0;

        rows.forEach((row) => {
            var haystack = (row.dataset.search || '').toLowerCase();
            var shouldShow = query === '' || haystack.includes(query);
            row.classList.toggle('hidden', !shouldShow);
            if (shouldShow) {
                visibleCount += 1;
            }
        });

        var emptyRow = document.getElementById('students-empty-row');
        if (emptyRow) {
            emptyRow.classList.toggle('hidden', visibleCount !== 0);
        }
    }

    <?php if (!empty($open_create_modal)): ?>
        window.addEventListener('DOMContentLoaded', () => { openModal('create-student-modal'); });
    <?php endif; ?>

    <?php if (!empty($open_edit_modal)): ?>
        window.addEventListener('DOMContentLoaded', () => {
            <?php if (!empty($student)): ?>
                openEditStudentModal(<?php echo json_encode($student); ?>);
            <?php else: ?>
                openModal('edit-student-modal');
            <?php endif; ?>
        });
    <?php endif; ?>

    window.addEventListener('DOMContentLoaded', () => {
        var searchInput = document.getElementById('student-search');
        if (searchInput) {
            searchInput.addEventListener('input', applyStudentFilter);
        }
        applyStudentFilter();
    });
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
