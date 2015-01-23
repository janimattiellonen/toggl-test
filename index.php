<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'settings.php';
require_once 'date_functions.php';
require_once 'vendor/autoload.php';


$beginday = date("2015-01-01");
$lastday = date("2015-01-23");

$nr_work_days = getWorkingDays($beginday, $lastday);
echo $nr_work_days . "<br/><br/>";

foreach (range(1, 12) as $month) {
    echo get_weekdays($month, 2015) . "<br>";
}

use AJT\Toggl\TogglClient;
use AJT\Toggl\ReportsClient;

$toggl_api_version = 'v8';

$toggl = TogglClient::factory(array('api_key' => $togglKey, 'apiVersion' => 'v8'));

$togglReportsClient = ReportsClient::factory(array('api_key' => $togglKey, 'apiVersion' => 'v2'));

$request = $toggl->get('workspaces');
$workspace = $toggl->send($request)->json();

$request = $toggl->get("workspaces/$workspaceId/workspace_users");

$workspaceUsers = $toggl->send($request)->json();
echo "<pre>";

$acceptedGroups = [
    8235,
    8237,
];

$uids = array_map(function($user) { return $user['uid'];},array_filter($workspaceUsers, function($user) use($acceptedGroups) {
    if (!isset($user['group_ids'])) {
        return false;
    }

    foreach($user['group_ids'] as $groupId) {
        if (in_array($groupId, $acceptedGroups)) {
            return true;
        }
    }

    return false;

}));

$result = $togglReportsClient->summary([
    'subgrouping' => 'time_entries',
    'workspace_id' => $workspaceId,
    'user_agent' => 'Toggl+New+3.22.2',
    'user_ids' => implode(',', $uids),
    'billable' => 'both',
    'since' => '2015-01-01',
    'until' => '2015-01-23',
    'period' => 'today',
]);

echo "<pre>";
print_r($result);