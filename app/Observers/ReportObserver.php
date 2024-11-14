<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Report;
use App\Notifications\CheckFailed;

use function in_array;

use Symfony\Component\HttpFoundation\Response;

final class ReportObserver
{
    public function created(Report $report): void
    {
        if ( !in_array($report->status, [Response::HTTP_OK, Response::HTTP_FOUND, Response::HTTP_SEE_OTHER], true)) {

            $report->check->service->user->notify(
                instance: new CheckFailed(
                    check: $report->check,
                ),
            );
        }

    }
}
