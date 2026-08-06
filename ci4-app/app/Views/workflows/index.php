<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Workflow center</title>
    <style>
        body{font-family:Arial;background:#f4f6f8;color:#17202a;margin:0}main{max-width:1280px;margin:28px auto;padding:0 16px}
        section{background:#fff;border:1px solid #d9e0e7;border-radius:10px;padding:18px;margin:18px 0;overflow:auto}
        table{width:100%;border-collapse:collapse}th,td{padding:10px;border-bottom:1px solid #e5e7eb;text-align:left;vertical-align:top}
        form{display:flex;gap:6px;flex-wrap:wrap}input,select,button{padding:7px}.notice{padding:12px;background:#dcfce7}.error{padding:12px;background:#fee2e2}
        :focus-visible{outline:3px solid #2563eb;outline-offset:2px}.skip,.visually-hidden{position:absolute;left:-9999px}.skip:focus{left:12px;top:12px;background:#fff;padding:8px}
    </style>
</head>
<body>
<a class="skip" href="#content">Skip to content</a>
<main id="content">
    <h1>Workflow center</h1>
    <?php if($notice): ?><p class="notice" role="status"><?= esc($notice) ?></p><?php endif ?>
    <?php if($error): ?><p class="error" role="alert"><?= esc($error) ?></p><?php endif ?>
    <section aria-labelledby="courses-heading"><h2 id="courses-heading">Course lifecycle and enrollment policy</h2>
        <table><thead><tr><th>Course</th><th>Lifecycle</th><th>Transition</th><th>Enrollment policy</th></tr></thead><tbody>
        <?php foreach($courses as $course): ?><tr>
            <td><?= esc($course['ccode']) ?><br><?= esc($course['cname_eng'] ?: $course['cname_th']) ?></td>
            <td><?= esc($course['lifecycle_status'] ?: 'draft') ?> v<?= esc($course['version_no'] ?: 1) ?></td>
            <td><form method="post" action="<?= site_url('workflows/courses/'.$course['cos_id'].'/transition') ?>"><?= csrf_field() ?>
                <label><span class="visually-hidden">New status</span><select name="status" required><?php foreach(['submitted','reviewing','approved','rejected','scheduled','published','closed','archived','draft'] as $state): ?><option><?= $state ?></option><?php endforeach ?></select></label>
                <input name="reason" maxlength="500" placeholder="Reason"><button>Apply</button></form></td>
            <td><form method="post" action="<?= site_url('workflows/courses/'.$course['cos_id'].'/policy') ?>"><?= csrf_field() ?>
                <select name="enrollment_mode"><?php foreach(['open','approval','assigned','closed'] as $mode): ?><option <?= ($course['enrollment_mode']?:'approval')===$mode?'selected':'' ?>><?= $mode ?></option><?php endforeach ?></select>
                <input type="number" name="capacity" min="0" value="<?= esc($course['capacity']) ?>" placeholder="Capacity">
                <label><input type="checkbox" name="waitlist_enabled" value="1" <?= $course['waitlist_enabled']===null||(int)$course['waitlist_enabled']===1?'checked':'' ?>> Waitlist</label><button>Save</button></form></td>
        </tr><?php endforeach ?></tbody></table>
    </section>
    <section aria-labelledby="requests-heading"><h2 id="requests-heading">Pending enrollment requests</h2>
        <?php if($requests===[]): ?><p>No pending requests.</p><?php endif ?>
        <table><thead><tr><th>Learner</th><th>Course</th><th>Requested</th><th>Decision</th></tr></thead><tbody>
        <?php foreach($requests as $request): ?><tr><td><?= esc($request['emp_c'].' '.($request['fullname_en']?:$request['fullname_th'])) ?></td><td><?= esc($request['ccode']) ?></td><td><?= esc($request['requested_at']) ?></td>
            <td><form method="post" action="<?= site_url('workflows/requests/'.$request['request_id'].'/decision') ?>"><?= csrf_field() ?><select name="decision"><option value="approve">Approve</option><option value="reject">Reject</option></select><input name="reason" maxlength="500" placeholder="Reason required for rejection"><button>Save</button></form></td></tr><?php endforeach ?>
        </tbody></table>
    </section>
</main>
</body>
</html>
