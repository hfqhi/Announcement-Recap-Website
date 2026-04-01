<?php
// public/index.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
$pageTitle = "Announcement Recap";

$announcements = getActiveAnnouncements($pdo);
$upcomingDeadlines = array_filter($announcements, fn($a) => !empty($a['due_date']));
$generalInfo = array_filter($announcements, fn($a) => empty($a['due_date']));

// Calendar & Semester Engine Logic
$month = date('m'); $year = date('Y');
$daysInMonth = date('t', mktime(0, 0, 0, $month, 1, $year));
$firstDayOfWeek = date('w', mktime(0, 0, 0, $month, 1, $year));

// Calculate the exact Sunday of the week the semester started
$semStart = new DateTime(SEMESTER_START);
$semDayOfWeek = (int)$semStart->format('w');
$semStartSunday = clone $semStart;
$semStartSunday->modify("-$semDayOfWeek days");

$calendarEvents = [];
foreach($announcements as $a) {
    if($a['due_date']) {
        $start = strtotime($a['due_date']);
        $end = $a['end_date'] ? strtotime($a['end_date']) : $start;
        for($t = $start; $t <= $end; $t += 86400) {
            if(date('m', $t) == $month && date('Y', $t) == $year) {
                $calendarEvents[(int)date('d', $t)][] = $a;
            }
        }
    }
}

// Build Calendar Grid Array
$weeks = []; $currentWeek = [];
for($i = 0; $i < $firstDayOfWeek; $i++) { $currentWeek[] = null; }
for($day = 1; $day <= $daysInMonth; $day++) {
    $currentWeek[] = $day;
    if(count($currentWeek) == 7) { $weeks[] = $currentWeek; $currentWeek = []; }
}
if(count($currentWeek) > 0) {
    while(count($currentWeek) < 7) { $currentWeek[] = null; }
    $weeks[] = $currentWeek;
}

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-end mb-4 mt-3 border-bottom pb-3">
    <div>
        <h1 class="display-6 fw-bold text-uppercase mb-0" style="letter-spacing: 2px;">Announcements Recap</h1>
        <h5 class="text-danger font-monospace mt-1">
            <?= date('M d, Y (l)') ?> | <span id="liveClock"><?= date('h:i:s A') ?></span>
        </h5>
    </div>
    <div class="btn-group" role="group">
        <button class="btn btn-outline-dark active" id="btn-card" onclick="toggleView('card')"><i class="bi bi-grid"></i> Cards</button>
        <button class="btn btn-outline-dark" id="btn-calendar" onclick="toggleView('calendar')"><i class="bi bi-calendar3"></i> Calendar</button>
    </div>
</div>

<div id="view-card">
    <h4 class="text-danger mb-3 border-bottom pb-2"><i class="bi bi-clock-history"></i> Upcoming Deadlines</h4>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        <?php if(empty($upcomingDeadlines)): ?><div class="col-12 text-muted">No upcoming deadlines.</div><?php endif; ?>
        <?php foreach($upcomingDeadlines as $row): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0 announcement-card">
                    <div class="card-header border-0 text-center p-0">
                        <span class='badge w-100 py-2 fs-5 <?= e($row['color_theme']) ?>' style="border-radius: 6px 6px 0 0; letter-spacing: 2px;">
                            <?= e($row['code']) ?>
                        </span>
                    </div>
                    <div class="card-body pt-3 bg-light rounded-bottom d-flex flex-column">
                        <div class="text-muted small mb-2 border-bottom pb-2">
                            <i class="bi bi-person-video3"></i> <?= e($row['professor']) ?> <br>
                            <i class="bi bi-clock"></i> <?= e($row['schedule']) ?>
                        </div>
                        <h5 class="card-title fw-bold text-dark"><?= e($row['title']) ?></h5>
                        <div class="card-text mb-3 flex-grow-1"><?= nl2br(e($row['content'])) ?></div>
                        <div class="mt-auto pt-2 text-danger fw-bold small border-top">
                            <i class="bi bi-calendar-check"></i>
                            <?= $row['end_date'] ? date('M d', strtotime($row['due_date'])) . ' - ' . date('M d, Y', strtotime($row['end_date'])) : date('D, M d', strtotime($row['due_date'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h4 class="text-secondary mb-3 border-bottom pb-2"><i class="bi bi-info-circle"></i> General Information</h4>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach($generalInfo as $row): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0 announcement-card opacity-75">
                    <div class="card-header border-0 text-center p-0">
                        <span class='badge w-100 py-2 fs-5 <?= e($row['color_theme']) ?>' style="border-radius: 6px 6px 0 0; letter-spacing: 2px;"><?= e($row['code']) ?></span>
                    </div>
                    <div class="card-body pt-3 bg-light rounded-bottom d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark"><?= e($row['title']) ?></h5>
                        <div class="card-text mb-3 flex-grow-1"><?= nl2br(e($row['content'])) ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="view-calendar" style="display: none;">
    <div class="row">
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h3 class="mb-0 font-monospace"><?= date('F Y') ?></h3>
                <small class="text-muted fst-italic"><i class="bi bi-info-circle"></i> Semester Week 1 started on <?= date('M d, Y', strtotime(SEMESTER_START)) ?></small>
            </div>

            <div class="bg-white shadow-sm rounded border">
                <div class="cal-strict-grid header bg-light border-bottom">
                    <div class="text-center fw-bold py-2">Wk</div>
                    <div class="text-center fw-bold py-2">Sun</div><div class="text-center fw-bold py-2">Mon</div><div class="text-center fw-bold py-2">Tue</div>
                    <div class="text-center fw-bold py-2">Wed</div><div class="text-center fw-bold py-2">Thu</div><div class="text-center fw-bold py-2">Fri</div><div class="text-center fw-bold py-2">Sat</div>
                </div>

                <?php foreach($weeks as $weekDays):
                    // Pinpoint the exact Sunday of this specific calendar row
                    $firstValidDay = array_values(array_filter($weekDays))[0];
                    $firstValidDayIndex = array_search($firstValidDay, $weekDays);

                    $sundayOfRow = new DateTime("$year-$month-$firstValidDay");
                    $sundayOfRow->modify("-$firstValidDayIndex days");

                    // Calculate exact week difference between the two Sundays
                    $diff = $semStartSunday->diff($sundayOfRow);
                    $daysDiff = $diff->invert ? -$diff->days : $diff->days;
                    $weekNum = floor($daysDiff / 7) + 1;
                ?>
                <div class="cal-strict-grid row-group border-bottom">
                    <div class="d-flex align-items-center justify-content-center text-danger fw-bold border-end bg-light">
                        <?= $weekNum > 0 ? "W{$weekNum}" : "-" ?>
                    </div>
                    <?php foreach($weekDays as $day):
                        if($day === null): echo "<div class='cal-cell empty border-end'></div>"; continue; endif;
                        $isToday = ($day == date('d') && $month == date('m') && $year == date('Y'));
                    ?>
                    <div class='cal-cell border-end p-1 <?= $isToday ? 'bg-light' : '' ?>'>
                        <div class="d-flex justify-content-between align-items-center mb-1 px-1">
                            <span class='fw-bold <?= $isToday ? 'text-primary' : 'text-muted' ?>'><?= $day ?></span>
                            <?php if($isToday): ?><i class="bi bi-star-fill text-warning" style="font-size: 0.8rem;"></i><?php endif; ?>
                        </div>
                        <div class="event-bars-container">
                        <?php if(isset($calendarEvents[$day])): ?>
                            <?php foreach($calendarEvents[$day] as $evt): ?>
                                <div class="event-bar <?= e($evt['color_theme']) ?> text-truncate" title="<?= e($evt['title']) ?>"><?= e($evt['code']) ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-lg-3 mt-4 mt-lg-0">
            <h4 class="text-danger border-bottom pb-2">Upcoming</h4>
            <ul class="list-group list-group-flush shadow-sm rounded">
                <?php foreach(array_slice($upcomingDeadlines, 0, 8) as $a): ?>
                    <li class='list-group-item'>
                        <small class='d-block text-muted'><?= date('M d (D)', strtotime($a['due_date'])) ?></small>
                        <strong><?= e($a['title']) ?></strong><span class='badge <?= e($a['color_theme']) ?> float-end mt-1'><?= e($a['code']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>