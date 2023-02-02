<?php

namespace Up\Application\Email;

class MailTemplate
{
    public static function adminTemplate(
        int $applicationId,
        string $name,
        string $fromDate,
        string $toDate,
        string $reason
    ):string {
        $approveUrl = $_ENV['APP_UI_URL'] . $_ENV['APP_UI_ACCEPT_URL'] . $applicationId;
        $rejectUrl = $_ENV['APP_UI_URL'] . $_ENV['APP_UI_REJECT_URL'] . $applicationId;

        return printf(
            'Dear supervisor, employee %s requested for some time off,
        starting on %s and ending on %s, stating the reason: <div style"display:block; width:100%%;"> %s </div>
        Click on one of the below links to approve or reject the application: 
        <a href="%s">Approve</a> - <a href="%s">Reject</a>',
            $name,
            $fromDate,
            $toDate,
            $reason,
            $approveUrl,
            $rejectUrl
        );
    }

    public static function employeeTemplate(
        string $status,
        string $createdDatetime
    ):string {
        return printf(
            'Dear employee, your supervisor has %s your application submitted on %s.',
            $status,
            $createdDatetime
        );
    }
}
