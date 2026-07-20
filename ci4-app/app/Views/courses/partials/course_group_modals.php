<?php
$groupForm = static function (array $group, array $companies, string $action, string $buttonLabel): void {
    $isEdit = ! empty($group['cg_id']);
    ?>
    <form method="post" action="<?= esc($action) ?>">
        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Company</label>
                    <select class="form-select" name="com_id" required>
                        <option value="">Select company</option>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?= esc($company['com_id']) ?>" <?= (string) ($group['com_id'] ?? '') === (string) $company['com_id'] ? 'selected' : '' ?>>
                                <?= esc(($company['com_code'] ?: '-') . ' - ' . ($company['com_name_eng'] ?: $company['com_name_th'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Group Code</label>
                    <input class="form-control" name="cgcode" maxlength="100" required value="<?= esc($group['cgcode'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label>English Title</label>
                    <input class="form-control" name="cgtitle_en" required value="<?= esc($group['cgtitle_en'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label>Thai Title</label>
                    <input class="form-control" name="cgtitle_th" value="<?= esc($group['cgtitle_th'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label>Japanese Title</label>
                    <input class="form-control" name="cgtitle_jp" value="<?= esc($group['cgtitle_jp'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label>English Description</label>
                    <textarea class="form-control" name="cgdesc_en"><?= esc($group['cgdesc_en'] ?? '') ?></textarea>
                </div>
                <div class="col-md-6">
                    <label>Thai Description</label>
                    <textarea class="form-control" name="cgdesc_th"><?= esc($group['cgdesc_th'] ?? '') ?></textarea>
                </div>
                <div class="col-md-4">
                    <label>Status</label>
                    <select class="form-select" name="cg_status">
                        <option value="1" <?= (string) ($group['cg_status'] ?? '1') === '1' ? 'selected' : '' ?>>Open</option>
                        <option value="0" <?= (string) ($group['cg_status'] ?? '') === '0' ? 'selected' : '' ?>>Closed</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Approval</label>
                    <select class="form-select" name="cg_approve">
                        <option value="2" <?= (string) ($group['cg_approve'] ?? '2') === '2' ? 'selected' : '' ?>>Waiting</option>
                        <option value="1" <?= (string) ($group['cg_approve'] ?? '') === '1' ? 'selected' : '' ?>>Approved</option>
                        <option value="0" <?= (string) ($group['cg_approve'] ?? '') === '0' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Approver IDs</label>
                    <input class="form-control" name="cg_approve_by" value="<?= esc($group['cg_approve_by'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label>Reject Reason</label>
                    <input class="form-control" name="cg_reject" value="<?= esc($group['cg_reject'] ?? '') ?>">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn primary"><?= esc($buttonLabel) ?></button>
        </div>
    </form>
    <?php
};
?>

<div class="modal fade" id="courseGroupCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Course Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?php $groupForm([], $companies, site_url('managecourse/course_groups/create'), 'Create Group'); ?>
        </div>
    </div>
</div>

<?php foreach (($items ?? []) as $group): ?>
    <div class="modal fade" id="courseGroupEditModal<?= (int) $group['cg_id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Course Group: <?= esc($group['cgcode'] ?: ('#' . $group['cg_id'])) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php $groupForm($group, $companies, site_url('managecourse/course_groups/' . $group['cg_id'] . '/update'), 'Save Changes'); ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="courseGroupRejectModal<?= (int) $group['cg_id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="<?= site_url('managecourse/course_groups/' . $group['cg_id'] . '/approval') ?>">
                    <input type="hidden" name="approval" value="0">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Course Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">Reject <?= esc($group['title'] ?? $group['cgcode']) ?>?</p>
                        <label>Reject Reason</label>
                        <textarea class="form-control" name="cg_reject"><?= esc($group['cg_reject'] ?? '') ?></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn primary">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
