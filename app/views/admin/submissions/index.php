<?php require_once '../app/views/layouts/header.php'; ?>

<?php
    $proofLabels = [
        'text' => 'Text',
        'image' => 'Single Image',
        'image_text' => 'Image + Text',
        'multi_image' => 'Multiple Images',
        'none' => 'No Proof',
        'qr' => 'QR Code'
    ];
?>

<!-- Breadcrumbs -->
<div class="mb-8 font-mono text-xs font-bold uppercase flex items-center gap-2 text-secondary">
    <span>ROOT</span>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-on-surface">SUBMISSIONS</span>
</div>

<div class="flex justify-between items-center mb-8">
    <h2 class="font-h1 text-h1 uppercase text-on-background">QUEST SUBMISSIONS</h2>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="bg-[#9aed83] border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        <?php 
            if ($_GET['success'] === 'approved') echo "Submission approved.";
            elseif ($_GET['success'] === 'rejected') echo "Submission rejected.";
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="bg-error-container border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        <?php 
            if ($_GET['error'] === 'not_pending') echo "This submission was already reviewed.";
            elseif ($_GET['error'] === 'invalid') echo "Invalid request.";
            else echo "Something went wrong. Please try again.";
        ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['qr'])): ?>
    <div class="bg-surface-container-low border-2 border-on-surface p-4 mb-8 font-mono font-bold uppercase pixel-shadow-sm text-on-surface">
        <?php
            if ($_GET['qr'] === 'redeemed') echo 'QR verified. Quest awarded.';
            elseif ($_GET['qr'] === 'missing') echo 'QR token is required.';
            elseif ($_GET['qr'] === 'invalid') echo 'Invalid QR token.';
            elseif ($_GET['qr'] === 'used') echo 'This QR token was already used.';
            elseif ($_GET['qr'] === 'expired') echo 'This QR token is expired or inactive.';
            elseif ($_GET['qr'] === 'not_accepted') echo 'Quest is not in accepted status.';
            elseif ($_GET['qr'] === 'already_awarded') echo 'Quest already awarded.';
            else echo 'QR verification failed.';
        ?>
    </div>
<?php endif; ?>

<section class="bg-surface-container-lowest border-2 border-on-surface pixel-shadow">
    <div class="p-4 border-b-4 border-on-surface flex flex-wrap gap-4 justify-between items-center bg-surface-container-low">
        <h2 class="font-h2 text-on-surface uppercase font-black">ALL SUBMISSIONS</h2>
        <button onclick="openQrScanModal()" class="bg-primary text-white border-2 border-on-surface px-3 py-2 font-button-text hover:bg-on-primary-fixed-variant uppercase">
            SCAN QR
        </button>
    </div>
    <div class="overflow-x-auto">
        <?php if (empty($submissions)): ?>
            <div class="p-6 font-mono text-sm uppercase">No submissions found.</div>
        <?php else: ?>
            <table class="w-full text-left font-mono text-sm">
                <thead class="bg-surface-container border-b-4 border-on-surface">
                    <tr>
                        <th class="p-4 uppercase font-black">STUDENT</th>
                        <th class="p-4 uppercase font-black">QUEST</th>
                        <th class="p-4 uppercase font-black">PROOF</th>
                        <th class="p-4 uppercase font-black">STATUS</th>
                        <th class="p-4 uppercase font-black">SUBMITTED</th>
                        <th class="p-4 uppercase font-black text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $submission): ?>
                        <?php
                            $proofType = $submission['proof_type'] ?? ($submission['quest_proof_type'] ?? 'text');
                            $files = $submission_files[$submission['submission_id']] ?? [];
                        ?>
                        <tr class="border-b-2 border-outline-variant hover:bg-surface-container-low">
                            <td class="p-4">
                                <div class="font-bold text-on-surface uppercase"><?php echo htmlspecialchars($submission['full_name']); ?></div>
                                <div class="text-xs text-secondary mt-1 lowercase"><?php echo htmlspecialchars($submission['email']); ?></div>
                            </td>
                            <td class="p-4">
                                <div class="font-bold uppercase"><?php echo htmlspecialchars($submission['title']); ?></div>
                                <div class="text-primary font-black text-xs">+<?php echo htmlspecialchars($submission['points']); ?> XP</div>
                            </td>
                            <td class="p-4 text-xs">
                                <div class="text-[10px] uppercase font-bold text-zinc-600 mb-2">
                                    <?php echo htmlspecialchars($proofLabels[$proofType] ?? 'Text'); ?>
                                </div>
                                <?php if (!empty($submission['proof_text'])): ?>
                                    <div class="max-h-20 overflow-y-auto mb-2">
                                        <?php echo nl2br(htmlspecialchars($submission['proof_text'])); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($files)): ?>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($files as $filePath): ?>
                                            <a href="#" onclick="openImageModal('<?php echo BASE_URL . '/' . htmlspecialchars($filePath); ?>'); return false;" class="block cursor-pointer">
                                                <img src="<?php echo BASE_URL . '/' . htmlspecialchars($filePath); ?>" alt="Proof image" class="h-16 w-16 object-cover border border-on-surface hover:opacity-80 transition-opacity" />
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (empty($submission['proof_text']) && empty($files)): ?>
                                    <div class="text-[10px] uppercase text-outline">No proof provided.</div>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 text-[10px] border border-on-surface font-black uppercase 
                                    <?php echo $submission['status'] === 'approved' ? 'bg-[#9aed83] text-[#1e6d12]' : ($submission['status'] === 'rejected' ? 'bg-error-container text-error' : 'bg-[#ffd54f] text-on-surface'); ?>">
                                    <?php echo htmlspecialchars($submission['status']); ?>
                                </span>
                            </td>
                            <td class="p-4 uppercase text-xs"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($submission['submitted_at']))); ?></td>
                            <td class="p-4 text-right">
                                <?php if ($submission['status'] === 'pending'): ?>
                                    <button onclick="openReviewModal(<?php echo htmlspecialchars(json_encode($submission)); ?>)" class="bg-on-surface text-surface border-2 border-on-surface px-3 py-1 font-button-text hover:bg-surface-variant uppercase">
                                        REVIEW
                                    </button>
                                <?php else: ?>
                                    <div class="text-[10px] text-secondary uppercase">
                                        REVIEWED ON <?php echo htmlspecialchars($submission['reviewed_at'] ? date('Y-m-d', strtotime($submission['reviewed_at'])) : '-'); ?>
                                    </div>
                                    <?php if (!empty($submission['remarks'])): ?>
                                        <div class="text-[10px] text-on-surface-variant mt-1 max-w-[150px] truncate" title="<?php echo htmlspecialchars($submission['remarks']); ?>">
                                            "<?php echo htmlspecialchars($submission['remarks']); ?>"
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<!-- QR Scan Dialog -->
<dialog id="qr-scan-modal" class="bg-surface-container-lowest border-2 border-on-surface p-0 pixel-shadow max-w-md w-full backdrop:bg-black/60">
    <div class="bg-primary border-b-2 border-on-surface p-4 flex justify-between items-center">
        <h2 class="font-h2 text-white uppercase">QR SCAN</h2>
        <button onclick="closeQrScanModal()" class="text-white hover:text-on-surface-variant"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="<?php echo BASE_URL; ?>/admin/redeemQrToken" class="p-6 flex flex-col gap-4 font-mono">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">CAMERA</label>
            <div id="qr-reader" class="w-full h-48 border-2 border-on-surface bg-surface flex items-center justify-center overflow-hidden">
                <video id="qr-video" class="w-full h-full object-cover" playsinline muted></video>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="startQrScanner()" class="flex-1 bg-surface-container border-2 border-on-surface p-2 font-button-text hover:bg-surface-container-low uppercase">START</button>
                <button type="button" onclick="stopQrScanner()" class="flex-1 bg-zinc-200 border-2 border-on-surface p-2 font-button-text hover:bg-zinc-300 uppercase">STOP</button>
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">UPLOAD QR IMAGE</label>
            <input type="file" id="qr-file-input" accept="image/*" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary">
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">QR TOKEN</label>
            <input type="text" name="qr_token" id="qr-token-input" class="border-2 border-on-surface p-2 focus:outline-none focus:border-primary" placeholder="Scan or paste token" required>
        </div>
        <div id="qr-scan-status" class="text-[10px] uppercase text-on-surface-variant"></div>
        <div id="qr-scan-error" class="text-[10px] uppercase text-error hidden"></div>
        <div class="text-[10px] uppercase text-on-surface-variant">Once verified, the QR token becomes invalid.</div>
        <div class="flex gap-4 mt-2">
            <button type="button" onclick="closeQrScanModal()" class="flex-1 bg-surface-container border-2 border-on-surface p-3 font-button-text hover:bg-surface-container-low transition-colors">CANCEL</button>
            <button type="submit" class="flex-1 bg-primary text-white border-2 border-on-surface p-3 font-button-text hover:bg-on-primary-fixed-variant transition-colors">VERIFY</button>
        </div>
    </form>
</dialog>

<!-- Review Dialog -->
<dialog id="review-modal" class="bg-surface-container-lowest border-2 border-on-surface p-0 pixel-shadow max-w-md w-full backdrop:bg-black/60">
    <div class="bg-on-surface border-b-2 border-on-surface p-4 flex justify-between items-center">
        <h2 class="font-h2 text-surface uppercase">REVIEW SUBMISSION</h2>
        <button onclick="closeModal('review-modal')" class="text-surface hover:text-error transition-colors"><span class="material-symbols-outlined">close</span></button>
    </div>
    <form method="post" action="<?php echo BASE_URL; ?>/admin/reviewSubmission" class="p-6 flex flex-col gap-4 font-mono">
        <input type="hidden" name="submission_id" id="review-submission-id">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">DECISION</label>
            <select name="status" class="border-2 border-on-surface p-2 focus:outline-none focus:border-on-surface font-bold">
                <option value="approved">APPROVE</option>
                <option value="rejected">REJECT</option>
            </select>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase">REMARKS (OPTIONAL)</label>
            <input type="text" name="remarks" class="border-2 border-on-surface p-2 focus:outline-none focus:border-on-surface">
        </div>
        <div class="flex gap-4 mt-4">
            <button type="button" onclick="closeModal('review-modal')" class="flex-1 bg-surface-container border-2 border-on-surface p-3 font-button-text hover:bg-surface-container-low transition-colors">CANCEL</button>
            <button type="submit" class="flex-1 bg-primary text-white border-2 border-on-surface p-3 font-button-text hover:bg-on-primary-fixed-variant transition-colors">SUBMIT</button>
        </div>
    </form>
</dialog>

<!-- Image Preview Dialog -->
<dialog id="image-modal" class="bg-surface border-2 border-on-surface p-2 pixel-shadow max-w-3xl w-full backdrop:bg-black/80">
    <div class="flex justify-end mb-2">
        <button onclick="closeModal('image-modal')" class="text-on-surface hover:text-error transition-colors"><span class="material-symbols-outlined">close</span></button>
    </div>
    <div class="flex justify-center items-center overflow-hidden pb-2">
        <img id="preview-image" src="" alt="Proof Preview" class="max-w-full max-h-[70vh] object-contain border-2 border-on-surface pixel-border">
    </div>
</dialog>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    var qrScanner = null;
    var qrScannerRunning = false;
    var qrVideo = null;
    var qrStream = null;
    var qrDetectInterval = null;
    var qrDetector = null;
    var qrScanMode = null;
    var html5QrcodeLoader = null;

    function openReviewModal(submission) {
        document.getElementById('review-submission-id').value = submission.submission_id;
        openModal('review-modal');
    }

    function openImageModal(src) {
        document.getElementById('preview-image').src = src;
        openModal('image-modal');
    }

    window.addEventListener('DOMContentLoaded', () => {
        var qrInput = document.getElementById('qr-token-input');
        var fileInput = document.getElementById('qr-file-input');
        var qrDialog = document.getElementById('qr-scan-modal');
        qrVideo = document.getElementById('qr-video');

        if (qrInput) {
            qrInput.value = '';
        }

        if (fileInput) {
            fileInput.addEventListener('change', (event) => {
                var file = event.target.files ? event.target.files[0] : null;
                if (!file) {
                    return;
                }
                scanQrFile(file);
            });
        }

        if (qrDialog) {
            qrDialog.addEventListener('close', () => {
                stopQrScanner();
            });
        }
    });

    function openQrScanModal() {
        clearQrScanMessages();
        var qrInput = document.getElementById('qr-token-input');
        if (qrInput) {
            qrInput.value = '';
            qrInput.focus();
        }
        openModal('qr-scan-modal');
    }

    function closeQrScanModal() {
        stopQrScanner();
        closeModal('qr-scan-modal');
    }

    function ensureQrScanner() {
        if (!qrScanner && typeof Html5Qrcode !== 'undefined') {
            qrScanner = new Html5Qrcode('qr-reader');
        }
    }

    function startQrScanner() {
        clearQrScanMessages();
        if (supportsBarcodeDetector()) {
            startBarcodeScanner();
            return;
        }
        loadHtml5Qrcode()
            .then(() => {
                startHtml5Scanner();
            })
            .catch(() => {
                setQrScanError('Camera scanning is not supported here. Upload a QR image or paste the token.');
            });
    }

    function stopQrScanner() {
        stopBarcodeScanner();
        stopHtml5Scanner();
    }

    function scanQrFile(file) {
        clearQrScanMessages();
        if (supportsBarcodeDetector()) {
            scanQrFileWithBarcodeDetector(file);
            return;
        }
        loadHtml5Qrcode()
            .then(() => {
                ensureQrScanner();
                qrScanner
                    .scanFile(file, true)
                    .then((decodedText) => {
                        handleQrResult(decodedText);
                    })
                    .catch(() => {
                        setQrScanError('Unable to read QR from image.');
                    });
            })
            .catch(() => {
                setQrScanError('QR scanning is not supported here. Paste the token instead.');
            });
    }

    function loadHtml5Qrcode() {
        if (typeof Html5Qrcode !== 'undefined') {
            return Promise.resolve();
        }
        if (html5QrcodeLoader) {
            return html5QrcodeLoader;
        }
        html5QrcodeLoader = new Promise((resolve, reject) => {
            var script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.10/minified/html5-qrcode.min.js';
            script.async = true;
            script.onload = () => resolve();
            script.onerror = () => reject();
            document.head.appendChild(script);
        });
        return html5QrcodeLoader;
    }

    function supportsBarcodeDetector() {
        return typeof window.BarcodeDetector !== 'undefined';
    }

    function startBarcodeScanner() {
        if (!qrVideo) {
            setQrScanError('Camera preview unavailable.');
            return;
        }

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            setQrScanError('Camera API is not available.');
            return;
        }

        if (qrScanMode === 'barcode') {
            return;
        }

        qrDetector = qrDetector || new BarcodeDetector({ formats: ['qr_code'] });
        qrScanMode = 'barcode';

        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then((stream) => {
                qrStream = stream;
                qrVideo.srcObject = stream;
                qrVideo.play();
                setQrScanStatus('Camera active. Hold a QR code in view.');

                qrDetectInterval = setInterval(async () => {
                    try {
                        if (!qrVideo || qrVideo.readyState < 2) {
                            return;
                        }
                        var codes = await qrDetector.detect(qrVideo);
                        if (codes && codes.length > 0) {
                            handleQrResult(codes[0].rawValue || codes[0].value || '');
                            stopBarcodeScanner();
                        }
                    } catch (error) {
                        // Ignore detection errors.
                    }
                }, 400);
            })
            .catch(() => {
                qrScanMode = null;
                setQrScanError('Camera permission denied or unavailable.');
            });
    }

    function stopBarcodeScanner() {
        if (qrScanMode !== 'barcode') {
            return;
        }
        qrScanMode = null;
        if (qrDetectInterval) {
            clearInterval(qrDetectInterval);
            qrDetectInterval = null;
        }
        if (qrVideo) {
            qrVideo.pause();
            qrVideo.srcObject = null;
        }
        if (qrStream) {
            qrStream.getTracks().forEach((track) => track.stop());
            qrStream = null;
        }
    }

    function startHtml5Scanner() {
        if (typeof Html5Qrcode === 'undefined') {
            setQrScanError('QR scanner library failed to load.');
            return;
        }
        ensureQrScanner();

        Html5Qrcode.getCameras().then((cameras) => {
            if (!cameras || cameras.length === 0) {
                setQrScanError('No camera detected.');
                return;
            }
            if (qrScannerRunning) {
                return;
            }
            qrScannerRunning = true;
            qrScanMode = 'html5';
            qrScanner
                .start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: 200 },
                    (decodedText) => {
                        handleQrResult(decodedText);
                        stopHtml5Scanner();
                    },
                    () => {}
                )
                .catch(() => {
                    qrScannerRunning = false;
                    qrScanMode = null;
                    setQrScanError('Unable to start camera scan.');
                });
        }).catch(() => {
            setQrScanError('Camera permission denied.');
        });
    }

    function stopHtml5Scanner() {
        if (!qrScanner || !qrScannerRunning) {
            return;
        }
        qrScanner
            .stop()
            .then(() => {
                qrScannerRunning = false;
                qrScanMode = null;
                qrScanner.clear();
            })
            .catch(() => {
                qrScannerRunning = false;
                qrScanMode = null;
            });
    }

    function scanQrFileWithBarcodeDetector(file) {
        if (!supportsBarcodeDetector()) {
            setQrScanError('QR scanning is not supported here.');
            return;
        }
        qrDetector = qrDetector || new BarcodeDetector({ formats: ['qr_code'] });
        createImageBitmap(file)
            .then((bitmap) => qrDetector.detect(bitmap).finally(() => {
                if (bitmap.close) {
                    bitmap.close();
                }
            }))
            .then((codes) => {
                if (codes && codes.length > 0) {
                    handleQrResult(codes[0].rawValue || codes[0].value || '');
                } else {
                    setQrScanError('Unable to read QR from image.');
                }
            })
            .catch(() => {
                setQrScanError('Unable to read QR from image.');
            });
    }

    function handleQrResult(decodedText) {
        var token = extractQrToken(decodedText);
        if (!token) {
            setQrScanError('QR code does not include a token.');
            return;
        }
        var qrInput = document.getElementById('qr-token-input');
        if (qrInput) {
            qrInput.value = token;
        }
        setQrScanStatus('Token captured. Click verify to redeem.');
    }

    function extractQrToken(decodedText) {
        var text = (decodedText || '').trim();
        if (text === '') {
            return '';
        }

        try {
            var url = new URL(text, window.location.origin);
            var parts = url.pathname.split('/').filter(Boolean);
            for (var i = 0; i < parts.length - 2; i++) {
                if (parts[i] === 'admin' && parts[i + 1] === 'qr' && parts[i + 2]) {
                    return parts[i + 2];
                }
            }
        } catch (error) {
            // Not a URL
        }

        return text;
    }

    function clearQrScanMessages() {
        setQrScanStatus('');
        setQrScanError('');
    }

    function setQrScanStatus(message) {
        var statusEl = document.getElementById('qr-scan-status');
        if (!statusEl) {
            return;
        }
        statusEl.textContent = message;
    }

    function setQrScanError(message) {
        var errorEl = document.getElementById('qr-scan-error');
        if (!errorEl) {
            return;
        }
        errorEl.textContent = message;
        errorEl.classList.toggle('hidden', message === '');
    }
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
